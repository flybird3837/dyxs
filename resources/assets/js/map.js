import cax from 'cax'
import Button from './button.js';

const mapUlr = 'http://smart-guide.test/images/map5.jpg';
const appUrl = $("meta[name=app-url]").attr('content');

export default class Map {

    appUrl = appUrl;
    /**
     * canvas 画布
     * @type {cax.Stage}
     */
    stage;

    /**
     * 舞台宽
     */
    stageWith;

    /**
     * 舞台高
     * @type {number}
     */
    stageHeight;

    /**
     * 地图层
     * @type {cax.Group}
     */
    mapView;

    mapImg;

    /**
     * 地图宽度
     * @type {number}
     */
    mapWith;

    /**
     * 地图高度
     * @type {number}
     */
    mapHeight;

    /**
     * 展点列表
     * @type {Array}
     * @private
     */
    _sites = [];

    /**
     * 从服务端获取的展点数据
     * @type {Array}
     */
    sitesData = [];

    /**
     * 地图是否在拖动
     * @type {boolean}
     */
    mapMoving = false;

    /**
     * 当前地图id
     * @types {number}
     */
    mapId;

    /**
     * 当前site
     * @type {number}
     */
    currentEditSite;

    /**
     * point 容器
     * @type {Array}
     */
    editPoints = [];

    /**
     * 按钮层
     */
    buttonsView;

    inRequest = false;

    constructor({width, height, container, map} = {width: null, height: null, container: 'body', map: null}) {
        this._init(width, height, container, map);
    }

    /**
     * 地图初始化
     * @param width
     * @param height
     * @param container
     * @param map
     * @private
     */
    _init(width, height, container, map) {
        let $container = $(container);
        this.mapId = $container.data('id');
        // console.log('map id', this.mapId);
        if (!$container.length) {
            throw new Error('container is not exist');
        }

        if (!map) {
            throw new Error('map url is not exist');
        }

        width = !width ? $container.width() : width;
        height = !height ? $container.height() : height;
        this.stageWith = width;
        this.stageHeight = height;

        this.stage = new cax.Stage(width, height, container);
        // 自动刷新
        cax.tick(this.stage.update.bind(this.stage));

        cax.loadImgs({
            imgs: [map],
            complete: (imgs) => {
                const img = imgs[0];
                this.mapWith = img.width;
                this.mapHeight = img.height;
                this.mapView = new cax.Group();
                const bitmap = new cax.Bitmap(img);
                this.mapImg = bitmap;

                this.mapView.add(bitmap);
                this.stage.add(this.mapView);
                this._bindMapDrag();
                this.getSitesFromServer();

                // 触发地图加载完成方法
                if (typeof this.mapLoaded === 'function') {
                    this.mapLoaded();
                }
            }
        })
    }

    /**
     * 地图拖动事件绑定
     * @private
     */
    _bindMapDrag() {
        this.mapView.on('drag', (e) => {
            // console.log(e)
            if (this.mapView.x + e.dx < 0 && this.mapView.x + e.dx > this.stageWith - this.mapWith) {
                this.mapView.x += e.dx;
            }
            if (this.mapView.y + e.dy < 0 && this.mapView.y + e.dy > this.stageHeight - this.mapHeight) {
                this.mapView.y += e.dy;
            }

            this.mapMoving = true;
        })
    }

    /**
     * 获取该地图的展点数据
     * @private
     */
    getSitesFromServer() {

        let url = `${appUrl}/map/${this.mapId}/sites`;

        $.ajax({
            url: url,
            method: 'GET',
        }).then(
            res => {
                //this.sites = res.data;
                this.sitesData = res.data;
                this.setSites(this.sitesData);
            }
        ).fail(err =>
            console.error('requrest fail', err)
        )
    }



    /**
     * 设置当前操作的 site id
     * @param id
     */
    setCurrentSite(id) {
        this.currentEditSite = id;

        let site = this.sitesData.find(item => item.id===id);

        let point = site.points[0];
        this.setMapCentre(point.x, point.y);

        this.setSites(this.sitesData);
    }

    /**
     * 通过 site 对象画出 site 区域
     * @param site
     * @param active {boolean}
     * @returns {cax.Graphics}
     * @private
     */
    drowSiteBySiteData(site, active = false) {
        if (Map._siteHasPoinst(site)) {
            return this.drowSiteByPoints(site.points, active);
        }

        throw new Error('site has no points');
    }

    /**
     * 判断site是够有点
     * @param site
     * @returns {boolean|*}
     * @private
     */
    static _siteHasPoinst(site) {
        return typeof site === 'object' && site.points && site.points.length;
    }

    /**
     * 使用 points 数组画出 site 区域
     * @param points
     * @param active {boolean}
     * @returns {cax.Graphics}
     * @private
     */
    drowSiteByPoints(points, active = false) {
        let ctx = new cax.Graphics();
        ctx.lineWidth(3);
        ctx.beginPath();
        points.forEach((point, index, data) => {
            if (index === 0) {
                ctx.moveTo(parseInt(point.x), parseInt(point.y));
            } else {
                ctx.lineTo(parseInt(point.x), parseInt(point.y));
            }
        });

        if (active) {
            ctx.strokeStyle('rgba(209, 71, 72, 0.5)');
            ctx.fillStyle('rgba(209, 71, 72, 0.2)');
        } else {
            ctx.strokeStyle('rgba(176, 224, 230, 0.5)');
            ctx.fillStyle('rgba(135, 206, 235, 0.3)');
        }
        ctx.closePath().fill().stroke();
        return ctx;
    }

    /**
     * 设置展点区域
     * @param data
     */
    setSites(data) {

        if (this.sites) {
            this.sites.forEach(site => this.mapView.remove(site.view));
            this._sites = [];
        }

        data.forEach(item => {
            if (!Map._siteHasPoinst(item)) {
                return;
            }
            let active = false;
            if (item.id === this.currentEditSite) {
                active = true;
            }
            let siteView = this.drowSiteBySiteData(item, active);
            this.mapView.add(siteView);
            this._sites.push({
                id: item.id,
                view: siteView
            });
        });
        if (typeof this.sitesSetted === 'function') {
            this.sitesSetted();
        }
    }

    /**
     * 获取绘制展点
     * @returns {Array}
     */
    get sites() {
        return this._sites;
    }


    drawDot(x, y) {
        let ctx = new cax.Graphics();
        ctx.beginPath().arc(x, y, 5, 0, Math.PI * 2).fillStyle('red').fill();
        this.mapView.add(ctx);
        this.editPoints.push(ctx);
        return ctx;
    }

    clearDots() {
        this.editPoints.forEach(dot => this.mapView.remove(dot));
        this.editPoints = [];
    }

    setMapCentre(x, y) {
        //想去的中心点可能超过边界了，测量出位置再移动
        let p = this.mapLimit(-x + this.stageWith / 2, -y + this.stageHeight / 2);
        // console.log(p)
        var dx = Math.abs(x - p.x);
        var dy = Math.abs(y - p.y);
        var dis = Math.sqrt(Math.pow(dx, 2) + Math.pow(dy, 2));
        cax.To.get(this.mapView).to({
            x: p.x,
            y: p.y
        }, dis * 0.4).end().start()
        //this.mapView.x = p.x
        //this.mapView.y = p.y
    }

    mapLimit(ox, oy) {
        let x = ox,
            y = oy;
        if (ox > 0) x = 0;
        if (ox < -this.mapW + this.w) x = -this.mapW + this.w;
        if (oy > 0) y = 0;
        if (oy < -this.mapH + this.h) y = -this.mapH + this.h;
        return {
            x: x,
            y: y
        }
    }

}


