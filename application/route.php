<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;

// Route::get('new/:id','News/read',[],['__url__'=>'new\/\w+$']);
//Route::get(':cateAlias/:id','article/index',[],['__url__'=>'\w+\/\d+$']);

/*return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    'news/:id'    => ['index/news2', ['method' => 'get'], ['id' => '\d+']],

];*/



// 前台首页
Route::get('/[:page]','Index/index/', [], ['page'=>'\d+']);

// 前台文章列表-标签获取
Route::get('/tag/:tag_id','Article/tag/', [], ['tag_id'=>'\d+']);
// 标签云单页
Route::get('/tagcloud','Page/tagCloud/', [], []);

// 前台文章列表-分类获取
Route::get('/:cate/[:page]','Article/category/', [], ['cate'=>'((?!admin)(?!api).)+', 'page'=>'\d+']);

// 前台文章
Route::get('/:cate/:article','Article/index/', [], ['cate'=>'((?!admin)(?!api).)+', 'article'=>'.+']);
