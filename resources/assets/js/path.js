import Map from './map.js';
import Button from './button.js';
import cax from 'cax';
import arrowTo from './arrow.js';

export default class Path extends Map {

    pathData = [];

    ctx;


    constructor({width, height, container, map} = {width: null, height: null, container: 'body', map: null}) {
        super({width: width, height: height, container: container, map: map});
    }

    mapLoaded() {

        this.mapView.on('click', (e) => {
            if (this.currentEditSite) {
                let x = e.stageX - this.mapView.x;
                let y = e.stageY - this.mapView.y;
                this.drawDot(x, y);
            }
        });
        this._drawButton();
        this._bindEvent();

    }

    sitesSetted() {
        this.pathData = [];
        //console.log(this.sitesData);
        this.sitesData.forEach(site => {
            if (site.path) {
                this.pathData.push({id: site.id, path: site.path});
            }
        });
        // console.log(this.pathData);
        this._drawArrow();
    }

    /**
     * 监听web页面点击事件
     * @private
     */
    _bindEvent() {

        let $siteList = $('.site-list');
        let $this = this;

        $siteList.find('.list-group-item').on('click', function (e) {
            let self = $(this);
            let siteId = self.data('id');
            self.addClass('active').siblings().removeClass('active');

            if (siteId !== $this.currentEditSite) {
                $this.setCurrentSite(self.data('id'));
            }
        });
    }

    _drawButton() {
        this.buttonsView = new cax.Group();
        this.buttonsView.x = this.stageWith - 80;
        this.buttonsView.y = 20;
        this.stage.add(this.buttonsView);

        const redrawButton = new Button({
            fillStyle: "#ffffff",
            strokeStyle: '#888888',
            width: 60,
            height: 26,
            color: "#140D14",
            r: 4,
            text: "重绘",
            font: '16px Arial'
        });

        const backButton = new Button({
            fillStyle: "#ffffff",
            strokeStyle: '#888888',
            width: 60,
            height: 26,
            color: "#140D14",
            r: 4,
            text: "撤销",
            font: '16px Arial'
        });

        const completeButton = new Button({
            fillStyle: "#ffffff",
            strokeStyle: '#888888',
            width: 60,
            height: 26,
            color: "#140D14",
            r: 4,
            text: "完成",
            font: '16px Arial'
        });

        const deleteButton = new Button({
            fillStyle: "#ffffff",
            strokeStyle: '#888888',
            width: 60,
            height: 26,
            color: "#140D14",
            r: 4,
            text: "删除",
            font: '16px Arial'
        });

        completeButton.y = 35;
        deleteButton.y = 70;

        redrawButton.cursor = "pointer";
        backButton.cursor = "pointer";
        completeButton.cursor = "pointer";
        deleteButton.cursor = "pointer";

        // this.buttonsView.add(redrawButton);
        this.buttonsView.add(backButton);
        this.buttonsView.add(completeButton);
        this.buttonsView.add(deleteButton);

        // redrawButton.on('click', () => this.reDraw());
        backButton.on('click', () => this.cancelDraw());
        completeButton.on('click', () => this.completeDraw());
        deleteButton.on('click', () => this.deletePath());
    }

    _drawArrow() {

        if (this.ctx) {
            this.mapView.remove(this.ctx);
        }
        this.ctx = new cax.Graphics();
        this.mapView.add(this.ctx);

        if (this.pathData.length > 0) {
            this.pathData.forEach(item => {
                const path = item.path;
                // console.log('path', path);
                for (let i = 0; i < path.length - 1; i++) {
                    //console.log(path[i], path[i + 1]);
                    arrowTo(this.ctx, {
                        x: parseInt(path[i]['x']),
                        y: parseInt(path[i]['y'])
                    },{
                        x: parseInt(path[i+1]['x']),
                        y: parseInt(path[i+1]['y'])
                    }, {color: '#ce3922'});

                }
            })
        }
    }

    cancelDraw() {
        this.editPoints.forEach(point => this.mapView.remove(point));
        this.editPoints = [];

    }

    completeDraw() {
        if (!this.currentEditSite || this.inRequest === true || this.editPoints.length < 2) {
            return;
        }

        let data = [];
        this.editPoints.forEach(point => {
            let x = point.cmds[1][1][0];
            let y = point.cmds[1][1][1];
            data.push({x: x, y: y});
        });


        let url = `${this.appUrl}/map/${this.mapId}/sites/${this.currentEditSite}`;
        this.inRequest = true;
        $.ajax({
            method: 'PUT',
            url: url,
            data: {
                path: data
            }
        }).then((res) => {
            this.getSitesFromServer();
            this.clearDots();
            //console.log('complete', res);
            this.inRequest = false;
            alert('操作成功');
        }).fail((err) => {
            this.inRequest = false;
            alert('出错啦');
        });

    }

    deletePath() {
        if (!this.currentEditSite || this.inRequest) {
            return ;
        }
        let url = `${this.appUrl}/map/${this.mapId}/sites/${this.currentEditSite}`;
        $.ajax({
            method: 'PUT',
            url: url,
            data: {
                path: null
            }
        }).then((res) => {
            this.getSitesFromServer();
            // this.clearDots();
            //console.log('delete complete', res);
            this.inRequest = false;
            alert('删除成功');
        }).fail((err) => {
            this.inRequest = false;
            alert('出错啦');
        });
    }
}

