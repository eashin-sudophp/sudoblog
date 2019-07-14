<?php

    /**
     * 读取配置表
     * @param string $module 模块
     * @param string $class 配置的分类
     * @param integer $return 返回方式 0转配置 1返回键值对 2返回完整信息
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     *
     */
    function fetch_setting($module = '', $class = '', $return = 0)
    {
        $map = [];
        $module && $map['module'] = $module;
        $class && $map['class'] = $class;
        $conf = db('setting')->field('title, key, value, type')->where($map)->select();

        if (!is_array($conf))
            return [];

        if ($return == 1)
            return array_column($conf, 'value', 'key');
        elseif ($return == 2)
            return $conf;

        // 转成配置
        foreach ($conf as $k => $v) {
            $value = $v['type'] == 1 ? json_decode($v['value'], true) : $v['value'];
            config($v['key'], $value);
        }
        return [];

    }

    /**
     * 读取配置表（by key）
     * @param string $key 配置的键
     * @param integer $return 返回方式 0转配置 1返回值 2返回完整信息
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException     */
    function fetch_setting_by_key($key = '', $return = 0)
    {
        $map = [];
        $key && $map['key'] = $key;
        $conf = db('setting')->field('title, key, value, type')->where($map)->find();

        if (!is_array($conf))
            return [];

        if ($return == 1)
            return $conf['type'] == 1 ? json_decode($conf['value'], true) : $conf['value'];
        elseif ($return == 2)
            return $conf;

        // 转成配置
        $value = $conf['type'] == 1 ? json_decode($conf['value'], true) : $conf['value'];
        config($conf['key'], $value);
        return [];

    }

    /**
     * 字符串截取
     * @param string $string 待截取的字符串
     * @param integer $charNums 待截取字数
     * @param integer $ellipsis 是否带省略号 0不带 1带
     * @param integer $chsThree 是否开启中文占三个字符数 0否 1是
     * @return string
     *
     */
    function mysubstr($string, $charNums = 100, $ellipsis = 1, $chsThree = 1)
    {
        $return = '';
        // 截取
        if (empty($chsThree)) {
            $return .= mb_substr($string, 0, $charNums);
        } else {
            $return .= mb_strcut($string, 0, $charNums);
        }
        // 加省略号
        $return .= !empty($ellipsis) && mb_strlen($return) < mb_strlen($string) ? '...' : '';
        // -------- mb_strlen一中文一字符 strlen一中文三字符
        return $return;
    }

    /**
     * 获取文字里的图片链接
     *
     * @param $content
     * @return mixed
     */
    function preg_match_img($content, $num = 1)
    {
        preg_match_all('/\<img .*src=["\'](.*\.(?:jpg|jpeg|png))["\'].*/Ui', $content, $matchs);
        return isset($matchs[1]) ? array_slice($matchs[1], 0, $num) : [];
    }

    /**
     * 生成图片缩略图并缓存
     *
     * @param $img_url
     * @param string $size
     * @return string
     */
    function image_thumbnail($img_url, $size="150x150")
    {
        $image = \think\Image::open('.' . $img_url);
        $thumb_path = "./_mystorage/thumbnail/";
        $storage = $thumb_path . str_replace(array('/', '\\'),'', str_replace('.', "_$size.", $img_url));
        if (!file_exists($storage)) {
            !file_exists($thumb_path) && !mkdir($thumb_path, 0777, true);
            $image->thumb((int)explode('x',$size)[0], (int)explode('x',$size)[1],\think\Image::THUMB_CENTER)->save($storage);
        }
        return Request()->domain() . ltrim($storage, '.');
    }

    /**
     * 清楚数组中空元素
     *
     * @param $data
     * @return mixed
     */
    function clear_empty($data)
    {
        foreach ($data as $key => $value) {
            if (empty($value))
                unset($data[$key]);
        }
        return $data;
    }