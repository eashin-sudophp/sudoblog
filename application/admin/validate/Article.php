<?php
namespace app\admin\validate;

use think\Validate;

class Article extends Validate{

    // 验证规则
    protected $rule = [
        'id|文章编号'                  => ['require', 'checkArticleId'],
        'title|文章标题'               => ['require', 'max' => 200],
        'cate_id|分类栏目'             => ['require', 'checkCateId'],
        'content_basic|文章内容'             => ['require', 'max' => 2000],
    ];

    // 错误消息
    protected $message = [
        'id.checkArticleId'           => 'ARTICLE_ID_NOT_EXISTS',
        'cate_id.checkCateId'         => 'CATE_ID_OUT_RANGE',
    ];

    // 验证场景
    protected $scene = [
        'add' => ['title','cate_id','content']
    ];

    // 正则
    protected $regex = [];

    // 自定义验证规则
    protected function checkCateId($cate_id)
    {
        return boolval(db('ArticleCategory')->where('id', $cate_id)->count());
    }

    protected function checkArticleId($article_id)
    {
        return boolval(db('Article')->where('id', $article_id)->count());
    }

}