<?php

return [
    // 控制器类后缀
    'controller_suffix'       => true,
    // 后台菜单最大级别
    'admin_menu_max_level'    => 4,

    // 模板设置
    'template'                => [
        // 模板引擎类型 支持 php think 支持扩展
        'type'         => 'Think',
        // 模板路径
        'view_path'    => '',
        // 模板后缀
        'view_suffix'  => 'html',
        // 模板文件名分隔符
        'view_depr'    => DS,
        // 模板引擎普通标签开始标记
        'tpl_begin'    => '{',
        // 模板引擎普通标签结束标记
        'tpl_end'      => '}',
        // 标签库标签开始标记
        'taglib_begin' => '<',
        // 标签库标签结束标记
        'taglib_end'   => '>',
        // 模板布局
        'layout_on' => true,
        'layout_name' => 'layout',
        'taglib_build_in' => 'cx,app\admin\lib\Tagadmin',
        'tpl_cache'       => APP_DEBUG ? false : true,
        'tpl_deny_php'    => false
    ],

    // 模板替换（相对于/public的路径）
    'view_replace_str'  =>  [
	    '__STATIC__'=>'/static',
        '__ROOT__' => '/',
	    '__LIB__' => '/lib',
	    '__ADMIN__' => '/static/admin',
	],

];