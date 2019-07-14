<?php

namespace app\api\controller;


use common\Cache;
use Think\Exception;
use common\Errcode;

class Sms extends ApiBase
{

    const EXP_TIME = 3 * 60; // 验证码过期时间
    const SEND_ONLY_ONCE = 60; // 60秒内发送最多一次验证码

    /**
     * 发送短信验证码接口
     */
    public function send_msg()
    {
        $phone = input('phone_num');
        if (!$phone) {
            return $this->return_json(array(), Errcode::SYS_MOBILE_EMPTY);
        }
        $code = mt_rand(100000, 999999);
        $msg = '[群欢部落]您的短信验证码是：' . $code . '。请不要把验证码泄露给其他人。';
        return $this->exec($phone, $msg, $code);

    }


    /**
     *  发送  绑定手机号码  短信验证码接口
     */
    public function bind_send_msg()
    {
        $phone = input('phone_num');

        $user_logic = new logic\User();
        $return = $user_logic->bind_send_msg($phone);

        if($return['errcode']!=Errcode::SUCCESS)
            return $this->return_json($return['datas'], $return['errcode']);


        $code = mt_rand(100000, 999999);
        $msg = '[群欢部落]您的短信验证码是：' . $code . '。请不要把验证码泄露给其他人。';
        return $this->exec($phone, $msg, $code);
    }


    private function exec($to_phone, $message, $code)
    {
        try {
            // 清掉redis原有验证码
            $redis_phone = Cache::get_sms_code($to_phone);

            if (!empty($redis_phone)) {
                $redis_phone = json_decode($redis_phone, 1);
                $create_time = $redis_phone['create_time'];
                if ($create_time > time() - self::SEND_ONLY_ONCE) {
                    return $this->return_json(array(), Errcode::SYS_SMS_SEND_ONE_MINUTE);
                }
            }
            $statusStr = array(
                "0" => "短信发送成功",
                "-1" => "参数不全",
                "-2" => "服务器空间不支持,请确认支持curl或者fsocket，联系您的空间商解决或者更换空间！",
                "30" => "密码错误",
                "40" => "账号不存在",
                "41" => "余额不足",
                "42" => "帐户已过期",
                "43" => "IP地址限制",
                "50" => "内容含有敏感词"
            );

            $smsapi = "http://api.smsbao.com/";
            $user = config('sms_user');
            $pass = md5(config('sms_pass'));

            $content = $message;
            $phone = $to_phone;
            $sendurl = $smsapi . "sms?u=" . $user . "&p=" . $pass . "&m=" . $phone . "&c=" . urlencode($content);
            $result = file_get_contents($sendurl);

            $rs['code'] = (int) $result;
            $rs['message'] = $statusStr[$result];
            if ($result == 0) {
                Cache::set_sms_code($phone, $code);
                // 当日发送短信数量
                $cache_key = 'sms_count_' . date('Ymd') . '_' . $phone;
                $mycount = Cache::get_sms_count($phone);
                if ($mycount === false) {
                    $mycount = 1;
                    Cache::set_sms_count($phone, $mycount);
                } else {
                    $mycount++;
                    Cache::set_sms_count($phone, $mycount);
                }
                if ($mycount >= 4) {
                    $rs['code'] = Errcode::SYS_SMS_SEND_MAX_TIME;
                }
            }
            return $this->return_json(array(), $rs['code'], $rs['message']);
        } catch (Exception $exception) {
            $rs['code'] = Errcode::SYS_SMS_SEND_ERROR;
            return $this->return_json(array(), $rs['code']);
        }


    }
}