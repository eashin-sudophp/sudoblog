<?php
namespace app\index\controller;

use think\Db;
use think\Route;

class Article extends Base
{
	// 文章详情页
    public function index($cate = '', $article = '')
    {
        $cate_detail = Db::name('ArticleCategory')->where('cate_alias', $cate)->field(['id', 'cate_name'])->find();

    	$article_detail = Db::name('Article')->where(['brief_title' => $article, 'cate_id' => $cate_detail['id'] ?? ''])->find();

    	if (empty($cate_detail) || empty($article_detail)) {
    	    return $this->fetch_temp('404');
        }

        $article_detail['author'] = Db('AdminUser')->where(['id' => $article_detail['author']])->value('user_nickname');
        $this->assign('article', $article_detail);
        return $this->fetch_temp('detail');
    }

    // 文章分类页
    public function category($cate, $page = 1)
    {

        // 文章列表
    	$articles = Db::name('Article')
            ->alias('Article')
            ->field('Article.*, cate.cate_alias')
            ->join('ArticleCategory cate', 'Article.cate_id = cate.id', 'LEFT')
            ->where('cate.cate_alias', $cate)
            ->paginate(10, true);

    	// 当前分类详情
        $cate_row = Db::name('ArticleCategory')->where('cate_alias', $cate)->find();

        $this->assign('cate_row', $cate_row);
        $this->assign('articles', $articles->toArray()['data']);
        $this->assign('pagination', preg_replace("/\/?\d?(\.html)?\?page=/", '/', $articles->render()));
    	return $this->fetch_temp('index');
    }


}