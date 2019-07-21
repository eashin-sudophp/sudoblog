<?php
namespace app\admin\validate;

use think\Validate;

class Tag extends Validate{

	// 验证规则
    protected $rule = [
        'id|标签分类ID'           => ['require'],
        'tag_name|标签名称'      => ['require', 'chsAlphaNum', 'max' => 30],
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
            'tag_name',
            'seo_title',
            'seo_keyword',
            'seo_description',
            'list_order',
        ],
        'edit' => [
            'id',
            'tag_name',
            'seo_title',
            'seo_keyword',
            'seo_description',
            'list_order',
        ],
    ];
}