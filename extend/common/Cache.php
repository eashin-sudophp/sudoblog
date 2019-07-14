<?php


namespace common;

/**
 * 缓存抽象统一管理类库  get set del 为前缀
 * Class Cache
 * @package common
 */
class Cache
{

    /**
     * 通过Token获取用户信息
     * @param $token
     * @return array|mixed
     */
    public static function get_user_by_token($token)
    {
        $cache_key = 'user_by_token:' . $token;
        $str = Redis::getRedis()->get($cache_key);
        return $str ? json_decode($str, true) : array();
    }

    /**
     * 通过Token保存用户信息
     * @param string $token 用户token
     * @param array $user 用户信息
     * @param int $expire 有效期 秒
     * @return bool
     */
    public static function set_user_by_token($token, array $user, $expire = 0)
    {
        $cache_key = 'user_by_token:' . $token;
        if ($expire > 0)
            return Redis::getRedis()->setex($cache_key, $expire, json_encode($user));
        else
            return Redis::getRedis()->set($cache_key, json_encode($user));
    }

    /**
     * 通过Token删除用户信息
     * @param $token
     */
    public static function del_user_by_token($token)
    {
        $cache_key = 'user_by_token:' . $token;
        Redis::getRedis()->delete($cache_key);
    }


    /**
     * 设置男用户权重
     * @param int $token 用户id
     * @param int $weight 用户权重呢
     * @return bool
     */
    public static function set_man_weight($user_id, $weight)
    {
        return Redis::getRedis()->zAdd('man_weight', $weight, $user_id);
    }

    /**
     * 获取男用户权重列表
     * @param int $token 用户id
     * @param int $weight 用户权重呢
     * @return bool
     */
    public static function get_man_weight_list($user_id, $p, $p_size = 20)
    {
        return Redis::getRedis()->zRevRange('man_weight', ($p - 1) * $p_size, $p * $p_size - 1);
    }

    /**
     * 设置女用户权重
     * @param int $token 用户id
     * @param int $weight 用户权重呢
     * @return bool
     */
    public static function set_woman_weight($user_id, $weight)
    {
        return Redis::getRedis()->zAdd('woman_weight', $weight, $user_id);
    }

    /**
     * 清空男用户权重
     * @return bool
     */
    public static function clean_user_weight()
    {
        Redis::getRedis()->delete('man_weight');
        return Redis::getRedis()->delete('woman_weight');
    }

    /**
     * 获取女用户权重列表
     * @param int $token 用户id
     * @param int $weight 用户权重呢
     * @return bool
     */
    public static function get_woman_weight_list($user_id, $p, $p_size = 20)
    {
        return Redis::getRedis()->zRevRange('woman_weight', ($p - 1) * $p_size, $p * $p_size - 1);
    }

    /**
     * 获取短信验证码
     * @param int $phone 手机号码
     * @return bool
     */
    public static function get_sms_code($phone)
    {
        return Redis::getRedis()->get($phone);
    }

    /**
     * 设置短信验证码
     * @param int $phone 手机号码
     * @return bool
     */
    public static function set_sms_code($phone, $code)
    {
        $code_time = json_encode(array('create_time' => time(), 'code' => $code));
        return Redis::getRedis()->setex($phone, 300, $code_time);
    }

    /**
     * 获取短信验证码次数
     * @param int $phone 手机号码
     * @return bool
     */
    public static function get_sms_count($phone)
    {
        return Redis::getRedis()->get($phone);
    }

    /**
     * 设置短信验证码次数
     * @param int $phone 手机号码
     * @return bool
     */
    public static function set_sms_count($phone, $count)
    {
        return Redis::getRedis()->setex($phone, 86400, $count);
    }

    /**
     * 清空女性打招呼权重列表
     * @return bool
     */
    public static function clean_hello_weight()
    {
        return Redis::getRedis()->delete('hello_weight');
    }

    /**
     * 设置女用户打招呼权重
     * @param int $token 用户id
     * @param int $weight 用户权重呢
     * @return bool
     */
    public static function set_hello_weight($user_id, $weight)
    {
        return Redis::getRedis()->zAdd('hello_weight', $weight, $user_id);
    }


    /**
     * 获取所有女用户打招呼冷却集合
     * @param int $token 用户id
     * @param int $weight 用户权重呢
     * @return bool
     */
    public static function get_all_hello_hold()
    {
        return Redis::getRedis()->zRevRange('hello_hold', 0, -1, true);
    }


    /**
     * 设置女用户打招呼冷却用户
     * @param int $token 用户id
     * @param int $weight 用户权重呢
     * @return bool
     */
    public static function add_hello_hold($user_id, $weight)
    {
        $redis = Redis::getRedis();
        return;//添加定时任务再开启插入冷却集合
        return $redis->zAdd('hello_hold', $weight, $user_id);
    }

    /**
     * 设置女用户打招呼冷却集合
     * @param int $token 用户id
     * @param int $weight 用户权重呢
     * @return bool
     */
    public static function rem_hello_hold($user_id)
    {
        return Redis::getRedis()->zRem('hello_hold', $user_id);
    }

    /**
     * 设置获取用户好友集合
     * @param int $token 用户id
     * @param int $weight 用户权重呢
     * @return bool
     */
    public static function set_friend_set($user_id)
    {
        $redis = Redis::getRedis();
        $friend_list = Db::name('user_friend')->field('to_user_id  as user_id')->where(array('to_user_id' => $user_id))->select();
        foreach ($friend_list as $value) {
            $redis->sAdd('friend_set_' . $user_id, $value['user_id']);
        }
    }


    /**
     * 设置女用户打招呼权重
     * @param int $token 用户id
     * @param int $weight 用户权重呢
     * @return bool
     */
    public static function get_hello_list($user_id, $count, $level2_count = 2)
    {

        $redis = Redis::getRedis();

        $level2 = $redis->zRangeByScore('hello_weight', 5000, 10000000);
        $level1 = $redis->zRangeByScore('hello_weight', 0, 5000);

        $now1_count = $now2_count = 0;
        $level1_arr = $level2_arr = array();
        while (!empty($level2) && $now2_count < $level2) {
            $tmp_item = array_pop($level2);
            //去掉冷却中的元素
            $score = \common\Cache::get_hello_hold($tmp_item);
            if (!empty($score) || $user_id == $tmp_item)
                continue;

            $level2_arr[] = $tmp_item;
            $now2_count++;
        }

        $level1_count = $count - $now2_count;
        while (!empty($level1) && $now1_count < $level1_count) {
            $tmp_item = array_pop($level1);
            //去掉冷却中的元素
            $score = \common\Cache::get_hello_hold($tmp_item);
            if (!empty($score) || $user_id == $tmp_item)
                continue;
            $level1_arr[] = $tmp_item;
            $now1_count++;
        }

        $user_arr = array_merge($level1_arr, $level2_arr);
        return $user_arr;
    }


    /**
     * 设置女用户打招呼是否冷却中
     * @param int $token 用户id
     * @param int $weight 用户权重呢
     * @return bool
     */
    public static function get_hello_hold($user_id)
    {
        return Redis::getRedis()->zScore('hello_hold', $user_id);
    }


    /**
     * 获取用户基础信息缓存
     */
    public static function get_user_base_cache($user_id)
    {

        $str = Redis::getRedis()->hGet('user_base', $user_id);
        return $str ? json_decode($str, true) : array();
    }

    /**
     * 设置用户基础信息缓存
     */
    public static function set_user_base_cache($user_info)
    {
        return Redis::getRedis()->hSet('user_base', $user_info['uid'], json_encode($user_info));
    }

    /**
     * 清除用户基础信息缓存
     */
    public static function clean_user_base_cache($user_id)
    {
        return Redis::getRedis()->hDel('user_base', $user_id);
    }

    /**
     * 上锁
     * @param string $lock_key 锁的键值
     * @param int $lock 锁的内容
     * @param int $expire 有效期 秒
     * @return bool | array
     */
    public static function set_lock_by_key($lock_key, $lock, $expire=0)
    {
        static $num;
        empty($num) AND $num = 1;
        try{
            for(;;){
                if (Redis::getRedis()->setnx($lock_key, $lock) || $num > 10) {
                    Redis::getRedis()->expire($lock_key, $expire);
                    $num = 0;
                    return true;
                }
                usleep(300000); //0.3 sec
                $num ++;
            }
        }
        catch(\Exception $e){
            Common::db_log(__METHOD__, $e->getMessage());
            return Common::return_array(Errcode::SYS_EXCEPTION);
        }
    }

    /**
     * 删除锁
     * @param string $lock_key 锁的键值
     */
    public static function del_lock_by_key($lock_key)
    {
        Redis::getRedis()->delete($lock_key);
    }
}