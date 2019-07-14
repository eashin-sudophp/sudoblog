<?php
namespace app\admin\validate;

use think\Validate;

class Category extends Validate{

	// 验证规则
    protected $rule = [
        'id|栏目分类ID'           => ['require'],
        'cate_name|栏目名称'      => ['require', 'chsAlphaNum', 'max' => 30],
        'cate_alias|栏目别名'     => ['require', 'alphaDash', 'max' => 30],
        'seo_title|SEO标题'       => ['max' => 100],
        'seo_keyword|SEO关键字'   => ['max' => 200],
        'seo_description|SEO描述' => ['max' => 200],
        'list_order|排序'         => ['integer', 'elt' => 10000, 'gt' => 0],
    ];

    // 错误消息
    protected $message = [
    ];

    // 验证场景
    protected $scene = [
        'add' => [
            'cate_name',
            'cate_alias',
            'seo_title',
            'seo_keyword',
            'seo_description',
            'list_order',
        ],
        'edit' => [
            'id',
            'cate_name',
            'cate_alias',
            'seo_title',
            'seo_keyword',
            'seo_description',
            'list_order',
        ],
    ];
}