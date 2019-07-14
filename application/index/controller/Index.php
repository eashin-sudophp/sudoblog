<?php
namespace app\index\controller;

use think\Db;

class Index extends Base
{
    public function index($page=1)
    {
    	$articles = Db::name('Article')
    		->alias('Article')
            ->field('Article.*, cate.cate_alias')
            ->join('ArticleCategory cate', 'Article.cate_id = cate.id', 'LEFT')
            ->paginate(10, true);

        $this->assign('articles', $articles->toArray()['data']);
        $this->assign('pagination', preg_replace("/(\d+)\?page=/", '', $articles->render()));
        return $this->fetch_temp('index');
    }


}
