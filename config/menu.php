<?php

return [
    [
        'name' => '控制台',
        'route_name' => 'dashboard.index',
        'icon' => 'fa-home',
        'bgcolor_class' => 'bg-danger',
        'active' => false,
        'childs' => null
    ],
    [
        'name' => '项目管理',
        'route_name' => 'projects.index',
        'icon' => 'fa-list-ul',
        'bgcolor_class' => 'bg-danger',
        'active' => false,
        'childs' => null
    ],
    [
        'name' => '设备管理',
        'route_name' => 'devices.index',
        'icon' => 'fa-cogs',
        'bgcolor_class' => 'bg-warning',
        'active' => false,
        'childs' => null
    ],

    [
        'name' => '展点管理',
        'route_name' => 'sites.index',
        'icon' => 'fa-map-marker',
        'bgcolor_class' => 'bg-success',
        'active' => false,
        'childs' => null
    ],
    [
        'name' => '地图管理',
        'route_name' => 'map.index',
        'icon' => 'fa-indent',
        'bgcolor_class' => 'bg-info',
        'active' => false,
        'childs' => null
    ],
    [
        'name' => '测试菜单',
        'route_name' => null,
        'icon' => null,
        'bgcolor_class' => 'bg-info',
        'active' => false,
        'childs' => [
            [
                'name' => '测试子菜单',
                'route_name' => null,
                'icon' => null,
                'bgcolor_class' => 'bg-info',
                'active' => false,
            ]
        ]
    ]
];