<?php
namespace app\index\controller;

use think\Route;

class Page extends Base
{
    // 文章分类页
    public function tagCloud()
    {

        $tags = db('ArticleTag')->field('id, tag_name')->where('status', 1)->select();

        $this->assign('tags', $tags);
        return $this->fetch_temp('tagcloud');
    }

}
