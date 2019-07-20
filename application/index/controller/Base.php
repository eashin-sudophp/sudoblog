<?php
namespace app\index\controller;

use \think\Controller;
use think\Db;

class Base extends Controller
{

    public $template_name;

    public function __construct()
    {

        // 模板目录
        $this->template_name = "breath";

        // 标签替换
        foreach (config('view_replace_str') as $key => $value) {
            config('view_replace_str.' . $key, 'http://' . $_SERVER['HTTP_HOST'] . $value);
        }

        parent::__construct();

        // 菜单
        $nav = Db::name('ArticleCategory')->select();

        $this->assign('nav', beTree($nav));
        $this->assign('domain', request()->domain());
        $this->assign('cdn_domain', config('cdn_domain'));
        $this->assign('template_header', $this->template_name . '/header');
        $this->assign('template_footer', $this->template_name . '/footer');

    }

    public function fetch_temp($path)
    {
        return $this->fetch("/{$this->template_name}/$path");
    }

}
