<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 小夏 < 449134904@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;

use think\Db;
use think\captcha\Captcha;
use app\admin\lib\Auth;

class PublicController extends AdminBaseController
{
    public function _initialize()
    {
        // 重写：不验权限
    }

    /**
     * 后台登陆界面
     */
    public function login()
    {
        $this->hasLogin();
        return $this->fetch(":login");
    }

    /**
     * 登录验证
     */
    public function doLogin()
    {
        $this->hasLogin();

        $datas = input();
        // 验证器
        $validator = $this->validate($datas, 'User.login');
        if($validator !== true) {
            $this->error($validator);
        }

        $vcode = input('captcha');
        $captcha = new Captcha;
        if(!$captcha -> check($vcode)){
            $this->error(lang('CAPTCHA_NOT_RIGHT'));
        }

        if (strpos($datas['username'], "@") > 0) {//邮箱登陆
            $where['user_email'] = $datas['username'];
        } else {
            $where['user_login'] = $datas['username'];
        }

        $result = Db::name('AdminUser')->where($where)->find();

        if (!empty($result)) {
            if (compare_password($datas['password'], $result['user_pass'])) {
                $role_id = db('AdminRoleUser')
                    ->alias('a')
                    ->join('__ADMIN_ROLE__ b', 'a.role_id = b.id')
                    ->where(['status' => 1, 'user_id' => $result['id']])
                    ->value('role_id');
                if ($result['id'] != 1 && (empty($role_id) || empty($result['status' ]))) {
                    $this->error(lang('USER_DISABLED'));
                }

                $login_info = [
                    'last_login_ip' => $_SERVER['REMOTE_ADDR'],
                    //'last_login_add' => ip2address($_SERVER['REMOTE_ADDR']),
                    'last_login_add' => '127.0.0.1',
                    'last_login_time' => time()
                ];
                db('AdminUser')->where($where)->update($login_info);
                session('ADMIN_ID', $result['id']);
                $this->success(lang('LOGIN_SUCCESS'), url("admin/Index/index"));
            } else {
                $this->error(lang('PASSWORD_NOT_RIGHT'));
            }
        } else {
            $this->error(lang('USERNAME_NOT_EXIST'));
        }
    }

    /**
     * 后台管理员退出
     */
    public function logout()
    {
        session('ADMIN_ID', null);
        return $this->redirect('Public/login');
    }
}