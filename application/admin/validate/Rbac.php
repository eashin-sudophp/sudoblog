<?php
namespace app\admin\validate;

use think\Validate;

class Rbac extends Validate{

	// 验证规则
    protected $rule = [
        'id|角色编号'         => ['require'],
        'name|角色名'         => ['require', 'max' => 30],
        'remark|备注'         => [],
    ];

    // 错误消息
    protected $message = [
    ];

    // 验证场景
    protected $scene = [
        'add' => ['name'],
        'edit' => ['id', 'name'],
    ];
}