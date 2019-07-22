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

    	// 404
    	if (empty($cate_detail) || empty($article_detail)) {
    	    return $this->fetch_temp('404');
        }

        // 作者
        $article_detail['author'] = Db('AdminUser')->where(['id' => $article_detail['author']])->value('user_nickname');

    	// 文章分类
        $article_detail['category_level'] = model('app\admin\model\ArticleCategory')->getParentCates($cate_detail['id']);

        // 文章标签
        $article_detail['tag'] = model('app\admin\model\ArticleTagRecord')
            ->alias('record')
            ->field('tag.tag_name, tag.id')
            ->join('article_tag tag', 'tag.id=record.tid')
            ->where('aid', $article_detail['id'])
            ->select();

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
            ->order('create_time desc')
            ->paginate(10, true);

    	// 当前分类详情
        $cate_row = Db::name('ArticleCategory')->where('cate_alias', $cate)->find();

        $this->assign('cate_row', $cate_row);
        $this->assign('articles', $articles->toArray()['data']);
        $this->assign('pagination', preg_replace("/\/?\d?(\.html)?\?page=/", '/', $articles->render()));
    	return $this->fetch_temp('index');
    }

    // 文章分类页
    public function tag($tag_id)
    {

        // 文章列表
        $articles = Db::name('Article')
            ->alias('Article')
            ->field('Article.*, cate.cate_alias')
            ->join('ArticleCategory cate', 'Article.cate_id = cate.id', 'LEFT')
            ->join('ArticleTagRecord Record', 'Article.id = Record.aid', 'LEFT')
            ->where('Record.tid', $tag_id)
            ->order('create_time desc')
            ->paginate(2, true);

        // 当前标签详情
        $tag_row = Db::name('ArticleTag')->where('id', $tag_id)->find();

        $this->assign('tag_row', $tag_row);
        $this->assign('articles', $articles->toArray()['data']);
//        $this->assign('pagination', preg_replace("/\/?\d?(\.html)?\?page=/", '/', $articles->render()));
        $this->assign('pagination', $articles->render());
        return $this->fetch_temp('index');
    }


}
