<?php


namespace common;

use think\Validate;

/**
 * 统一格式验证器
 * Class Format
 * @package common
 */
class Format
{

    private static $validata_ = null;

    /**
     * @param $name
     * @param $rule
     * @return null|Validate
     */
    private static function validata($name, $rule)
    {
        if (null === self::$validata_) {
            self::$validata_ = new Validate();
        }
        self::$validata_->rule($name, $rule);
        return self::$validata_;
    }

    /**
     * 手机格式验证
     * @param $value
     * @return bool
     */
    public static function mobile($value)
    {
        $validate = self::validata('mobile', '/^1[34578]\d{9}$/');
        return $validate->check(array('mobile' => $value));
    }

    /**
     * 邮箱格式验证
     * @param $value
     * @return bool
     */
    public static function email($value)
    {
        $validate = self::validata('email', 'email');
        return $validate->check(array('email' => $value));
    }

    /**
     * qq格式验证
     * @param $value
     * @return bool
     */
    public static function qq($value)
    {
        $validate = self::validata('qq', '/^\d{4,13}$/');
        return $validate->check(array('qq' => $value));
    }

    /**
     * weixin格式验证
     * @param $value
     * @return bool
     */
    public static function weixin($value)
    {
        $validate = self::validata('weixin', '/^\w{2,16}$/');
        return $validate->check(array('weixin' => $value));
    }

    /**
     * 数值格式
     * @param $value
     * @return bool
     */
    public static function number($value)
    {
        $validate = self::validata('number', 'number');
        return $validate->check(array('number' => $value));
    }

    /**
     * 字母格式
     * @param $value
     * @return bool
     */
    public static function alpha($value)
    {
        $validate = self::validata('alpha', 'alpha');
        return $validate->check(array('alpha' => $value));
    }

    /**
     * 字母加数字格式
     * @param $value
     * @return bool
     */
    public static function alphaNum($value)
    {
        $validate = self::validata('alphaNum', 'alphaNum');
        return $validate->check(array('alphaNum' => $value));
    }

    /**
     * 字母数字下划线格式
     * @param $value
     * @return bool
     */
    public static function alphaDash($value)
    {
        $validate = self::validata('alphaDash', 'alphaDash');
        return $validate->check(array('alphaDash' => $value));
    }

    /**
     * 汉字格式
     * @param $value
     * @return bool
     */
    public static function chs($value)
    {
        $validate = self::validata('chs', 'chs');
        return $validate->check(array('chs' => $value));
    }

    /**
     * 身份证校验
     * @param $value
     * @return bool
     */
    public static function idCard($value)
    {
        if (!preg_match('/^\d{17}[0-9xX]$/', $value)) { //基本格式校验
            return false;
        }

        $parsed = date_parse(substr($value, 6, 8));
        if (!(isset($parsed['warning_count'])
            && $parsed['warning_count'] == 0)) { //年月日位校验
            return false;
        }

        $base = substr($value, 0, 17);

        $factor = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];
        $tokens = ['1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'];

        $checkSum = 0;
        for ($i = 0; $i < 17; $i++) {
            $checkSum += intval(substr($base, $i, 1)) * $factor[$i];
        }

        $mod = $checkSum % 11;
        $token = $tokens[$mod];

        $lastChar = strtoupper(substr($value, 17, 1));

        return ($lastChar === $token); //最后一位校验位校验
    }


}