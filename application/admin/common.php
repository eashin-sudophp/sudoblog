<?php

    /**
     * 面包屑导航
     * @return array
     */
    function breadcrumb()
    {
        $map = [
            'app' => request()->module(),
            'controller' => request()->controller(),
            'action' => request()->action(),
        ];
        $row = db('AdminMenu')->where($map)->find();
        $parents_id = str_replace('-', ',', $row['level_path']);
        $parents = db('AdminMenu')->where('id', 'in', $parents_id)->column('name');
        $menus = array_merge($parents, [$row['name']]);
        return $menus;
    }

    /**
     * 密码比较方法
     * @param string $password 要比较的密码
     * @param string $passwordInDb 数据库保存的已经加密过的密码
     * @return boolean 密码相同，返回true
     */
    function compare_password($password, $passwordInDb)
    {
        return password_encrypt($password) == $passwordInDb;
    }

    /**
     * CMF密码加密方法
     * @param string $pw 要加密的原始密码
     * @param string $authCode 加密字符串
     * @return string
     */
    function password_encrypt($pw, $authCode = '')
    {
        if (empty($authCode)) {
            $authCode = config('database.authcode');
        }
        $result = "###" . md5(md5($authCode . $pw));
        return $result;
    }

    /**
     * 记录错误日志
     * @param string $from 标题
     * @param string $msg 错误信息
     * @param integer $type 1数据库 0日志文件
     * @return int
     */
    function mk_log($from = '', $msg = '', $type=1)
    {
        if ($type) {
            return db('log')->insertGetId([
                'from'    => $from . ' ' . date('Y-m-d H:i:s'),
                'msg'     => $msg,
                'addtime' => time()
                ]);
        }

        trace(date('Y-m-d H:i:s') . '--' . $from . '--' . $msg, 'error');
        return false;
    }

    /**
     * 默认的缺失提示
     * @return string
     */
    function get_empty()
    {
        return '<span class="cont-empty"><i class="Hui-iconfont">&#xe633;</i></span>';
    }

    /**
     * ip获取地址
     * @param string $ip 地址
     * @return string
     */
    function ip2address($ip)
    {

        if (empty($ip)) {
            return '';
        }

        // $juhe_url =  'http://apis.juhe.cn/ip/ip2addr?key=feeefbaaeaaa425c0fa8c73bddae175a&ip=' . $ip;
        $api_url = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=' . $ip;
        $ret_json = file_get_contents($api_url);
        $ret = json_decode($ret_json, true);

        if ($ret['ret'] != 1) {
            return '';
        }

        if (!empty($ret['city']) && $ret['province'] == $ret['city']) {
            return $ret['city'] . '市';
        } elseif (empty($ret['province']) || empty($ret['city'])) {
            return $ret['country'] . $ret['province'] . $ret['city'];
        } else {
            return $ret['province'] . '省' . $ret['city'] . '市';
        }
    }

    /**
     * 时间戳转时间格式
     * @param int $unixtime 时间戳
     * @param string $format 格式
     * @return string
     */
    function get_timestamp($unixtime, $format='Y-m-d H:i')
    {
        return !empty($unixtime) ? date($format, $unixtime) : '';
    }

    /**
     * 无限极分类
     * @param array $data 二维数组，元素包含下标“parent_id”
     * @param integer $parent_id 父id
     * @param integer $level 级别
     * @return array
     */
    function beTree($data, $parent_id = 0, $level = 0)
    {
        static $arr;
        foreach ($data as $key => $value) {
            if ($value['parent_id'] == $parent_id) {
                $value['level'] = $level;
                $arr[] = $value;
                beTree($data, $value['id'], $level+1);
            }
        }
        return $arr;
    }