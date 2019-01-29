import Map from './map.js';
import Button from './button.js';
import cax from 'cax';

export default class Site extends Map {


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
        this._bindEvent();
        this._drawButton();
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

    /**
     * 操作按钮
     * @private
     */
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

        backButton.y = 35;
        completeButton.y = 70;

        redrawButton.cursor = "pointer";
        backButton.cursor = "pointer";
        completeButton.cursor = "pointer";

        this.buttonsView.add(redrawButton);
        this.buttonsView.add(backButton);
        this.buttonsView.add(completeButton);

        redrawButton.on('click', () => this.reDraw());
        backButton.on('click', () => this.cancelDraw());
        completeButton.on('click', () => this.completeDraw());
    }

    setCurrentSite(id) {
        super.setCurrentSite(id);
        // console.log('setCurrentSite')
    }

    reDraw() {
        // console.log('reDraw this', this);
        if (!this.currentEditSite) {
            return;
        }

        let siteView, siteIndex;

        this.sites.forEach((site, index) => {
            if (site.id === this.currentEditSite) {
                siteView = site;
                siteIndex = index;
            }
        });

        if (!siteView) {
            return;
        }

        this.mapView.remove(siteView.view);
        this._sites.splice(siteIndex, 1);
    }

    cancelDraw() {
        this.setSites(this.sitesData);
        this.clearDots();
    }

    completeDraw() {

        if (this.inRequest) {
            return;
        }

        if (this.editPoints.length < 3) {
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
                points: data
            }
        }).then((res) => {
            this.getSitesFromServer();
            alert('操作成功');
            this.clearDots();
            this.inRequest = false;
        }).fail((err) => {
            this.inRequest = false;
            alert('出错啦');
        });
    }

}