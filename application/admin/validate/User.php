<?php
namespace app\admin\validate;

use think\Validate;

class User extends Validate{

    // 验证规则
    protected $rule = [
        'id'                     => ['require'],
        'username|用户名'        => ['require', 'max' => 30, 'alphaDash'],
        'password|密码'          => ['require', 'max' => 30, 'alphaDash'],
        'repassword|确认密码'    => ['require', 'confirm' => 'password'],
        'nickname|昵称'          => ['chs'],
        'mobile|手机号'          => ['mobile'],
        'email|邮箱'             => ['email'],
        'captcha|验证码'         => '',
        'role_id|角色'           => '',
    ];

    // 错误消息
    protected $message = [
        'username.require'       => 'USERNAME_EMPTY',
        'username.max'           => 'USERNAME_TOO_LANG',
        'password.require'       => 'PASSWORD_REQUIRED',
        'password.max'           => 'PASSWORD_TOO_LANG',
        'captcha.require'        => 'ENTER_VERIFY_CODE',
        'username.alphaDash'     => 'USERNAME_ERROR_FORMAT',
        'password.alphaDash'     => 'PASSWORD_ERROR_FORMAT',
    ];

    // 验证场景
    protected $scene = [
        'login' => [
            'username' => 'require|max:30|alphaDash',
            'password' => 'require|max:30|alphaDash',
            'captcha' => 'require|number'
        ],
        'add' => [
            'username',
            'password',
            'repassword',
            'role_id' => 'require|number|gt:1',
        ],
        'edit' => [
            'id',
            'password' => 'max:30|alphaDash',
            'repassword' => 'confirm:password',
            'role_id' => 'require|number|gt:1',
        ],
        'info' => [
            'id',
            'nickname',
            'mobile',
            'email',
        ],
    ];

    // 正则
    protected $regex = [
        'mobile'    => '/^1[3|4|5|7|8]\d{9}$/',
        'password'  => '/^[\w]{6,15}$/'
    ];

}