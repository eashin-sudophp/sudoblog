<?php
namespace app\admin\validate;

use think\Validate;

class Menu extends Validate{

	// 验证规则
    protected $rule = [
        'id|菜单编号'         => ['require'],
        'name|菜单名称'       => ['require', 'max' => 30],
        'app|模块名'          => ['require', 'alphaDash', 'max' => 30, 'unique' => 'AdminMenu,app^controller^action'],
        'controller|控制器名' => ['require', 'alphaDash', 'max' => 30],
        'action|方法名'       => ['require', 'alphaDash', 'max' => 30],
        'list_order|排序'     => ['integer', 'elt' => 10000, 'gt' => 0],
    ];

    // 错误消息
    protected $message = [
        'app.unique'           => 'RULES_EXISTS',
        'app.alphaDash'        => 'APP_FORMAT_ERROR',
        'controller.alphaDash' => 'CONTROLLER_FORMAT_ERROR',
    	'action.alphaDash'     => 'ACTION_FORMAT_ERROR',
    ];

    // 验证场景
    protected $scene = [
        'add' => [
            'name' => 'require|max:30|chs|unique:AdminMenu',
            'app',
            'controller',
            'action',
            'list_order',
        ],
        'edit' => [
            'id',
            'name',
            'app',
            'controller',
            'action',
            'list_order',
        ],
    ];
}