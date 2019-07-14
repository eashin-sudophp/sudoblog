<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/12
 * Time: 11:10
 */

namespace common;

class Redis
{
    private static $_instance = null;

    private function __construct()
    {

        $redis_ip = config('redis.host') ? :$_SERVER['REDIS_HOST'];
        $redis_port = config('redis.port')? :$_SERVER['REDIS_PORT'];
        $redis_auth = config('redis.auth')? :$_SERVER['REDIS_AUTH'];


        self::$_instance = new \Redis();
        self::$_instance->connect($redis_ip, $redis_port);
        self::$_instance->auth($redis_auth);
    }

    //获取静态实例
    public static function getRedis()
    {
        if (!self::$_instance) {
            new self;
        }

        return self::$_instance;
    }

    private function __clone()
    {
    }
}