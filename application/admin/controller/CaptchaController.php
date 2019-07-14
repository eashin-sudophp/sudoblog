<?php
namespace app\admin\controller;
use think\Request;
use think\captcha\Captcha;

class CaptchaController{
    public function index(Request $request)
    {
        $config = [
            // 验证码字体大小(px)
            'fontSize' => 25,
            // 验证码图片高度
            'imageH'   => 0,
            // 验证码图片宽度
            'imageW'   => 0,
            // 验证码位数
            'length'   => 4,
            // 背景颜色
            'bg'       => [243, 251, 254],
            // 设置验证码字符为纯数字
            'codeSet'  => '0123456789',
            // 是否画混淆曲线
            'useCurve' => false,
            // 是否添加杂点
            'useNoise' => true,
            // 验证成功后不重置
            'reset'    => false,
        ];

        $fontSize = $request->param('font_size', 25, 'intval');
        if ($fontSize > 8) {
            $config['fontSize'] = $fontSize;
        }

        $imageH = $request->param('height', '');
        if ($imageH != '') {
            $config['imageH'] = intval($imageH);
        }

        $imageW = $request->param('width', '');
        if ($imageW != '') {
            $config['imageW'] = intval($imageW);
        }

        $length = $request->param('length', 4, 'intval');
        if ($length > 2) {
            $config['length'] = $length;
        }

        $bg = $request->param('bg', '');

        if (!empty($bg)) {
            $bg = explode(',', $bg);
            array_walk($bg, 'intval');
            if (count($bg) > 2 && $bg[0] < 256 && $bg[1] < 256 && $bg[2] < 256) {
                $config['bg'] = $bg;
            }
        }

        $id = $request->param('id', 0, 'intval');
        if ($id > 5 || empty($id)) {
            $id = '';
        }

        $defaultCaptchaConfig = config('captcha');
        if($defaultCaptchaConfig && is_array($defaultCaptchaConfig)){
            $config = array_merge($defaultCaptchaConfig, $config);
        }

        $captcha = new Captcha($config);
        return $captcha->entry($id);
    }
}