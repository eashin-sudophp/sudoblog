<?php

namespace app\admin\controller;

use think\Db;
use think\Controller;
use app\admin\lib\Auth;

class AdminBaseController extends Controller
{
    protected $web_version;

	public function __construct()
    {

        // 模板目录
    	config('template.view_path', '../template/admin/');

        // 标签替换
        foreach (config('view_replace_str') as $key => $value) {
            config('view_replace_str.' . $key, request()->domain() . $value);
        }

        parent::__construct();

        $web_version = array_keys($this->getDevLog());
        $this->web_version = end($web_version);
        $this->assign('web_title', 'sudoblog');
        $this->assign('web_version', $this->web_version);
        $this->assign('breadcrumb', breadcrumb());
        $this->assign('cur_page', request()->controller(). '/' . request()->action());
    }

    // 权限验证
    public function _initialize()
    {
        $this->notLogin(); // 未登录验证

        // 白名单控制器
        $controller = request()->controller();
        if (in_array(strtolower($controller), ['index']))
            return true;

        // 白名单方法
        $action = request()->action();
        if (in_array(strtolower($action), ['paging']))
            return true;

        $app = request()->module();
        $action = str_replace(['_paging', 'Paging', 'paging'], '', request()->action());
    	if (!Auth::check(session('ADMIN_ID'), $app .'/'. $controller .'/'. $action))
    		$this->error(lang('HASNOT_AUTHORITY'));

    }

    // 未登录验证
    public function notLogin()
    {
        !Auth::loginStatus() && $this->redirect('Public/login');
    }

    // 已登录验证
    public function hasLogin()
    {
        Auth::loginStatus() && $this->redirect('Index/index');
    }

    // 开发日志
    protected  function getDevLog()
    {
        $log = array(

        );
        return $log;
    }

}
