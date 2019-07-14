<?php
namespace app\admin\lib;
use think\template\TagLib;

class Tagadmin extends TagLib{
    protected $tags = [
        // 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
        'page'                => ['attr' => '', 'close' => 0],//非必须属性name
        'widget'              => ['attr' => 'name', 'close' => 1],
        'navigation'          => ['attr' => '', 'close' => 1],//非必须属性nav-id,root,id,class
        'navigationmenu'      => ['attr' => '', 'close' => 1],//root,class
        'navigationfolder'    => ['attr' => '', 'close' => 1],//root,class,dropdown,dropdown-class
        'subnavigation'       => ['attr' => 'parent,root,id,class', 'close' => 1],
        'subnavigationmenu'   => ['attr' => '', 'close' => 1],//root,class
        'subnavigationfolder' => ['attr' => '', 'close' => 1],//root,class,dropdown,dropdown-class
        'links'               => ['attr' => '', 'close' => 1],//非必须属性item
        'slides'              => ['attr' => 'id', 'close' => 1],//非必须属性item
        'noslides'            => ['attr' => 'id', 'close' => 1],
        'captcha'             => ['attr' => 'height,width', 'close' => 0],//非必须属性font-size,length,bg,id
        'hook'                => ['attr' => 'name,param', 'close' => 0]
    ];

    public function tagCaptcha($tag, $content)
    {
        //height,width,font-size,length,bg,id
        $id       = empty($tag['id']) ? '' : '&id=' . $tag['id'];
        $height   = empty($tag['height']) ? '' : '&height=' . $tag['height'];
        $width    = empty($tag['width']) ? '' : '&width=' . $tag['width'];
        $fontSize = empty($tag['font-size']) ? '' : '&font_size=' . $tag['font-size'];
        $length   = empty($tag['length']) ? '' : '&length=' . $tag['length'];
        $bg       = empty($tag['bg']) ? '' : '&bg=' . $tag['bg'];
        $title    = empty($tag['title']) ? '换一张' : $tag['title'];
        $style    = empty($tag['style']) ? 'cursor: pointer;' : $tag['style'];
        $params   = ltrim("{$id}{$height}{$width}{$fontSize}{$length}{$bg}", '&');
        $parse    = <<<parse
        <?php \$__CAPTCHA_SRC=url('admin/captcha/index').'?{$params}'; ?>
    <img src="{\$__CAPTCHA_SRC}" onclick="this.src='{\$__CAPTCHA_SRC}&time='+Math.random();" title="{$title}" class="captcha captcha-img verify_img" style="{$style}"/>{$content}
parse;
        return $parse;
    }
}
