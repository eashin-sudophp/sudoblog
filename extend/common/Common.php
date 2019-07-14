<?php
namespace common;

use app\common\model\user\UserPoolMa;
use hx\HxApi;

class Common
{

    const UID = 'uid';
    const USER_ID = 'user_id';
    /**
     * 生成图片url
     * @param $uri
     * @return string
     */
    public static function image_url($uri){
        if(empty($uri)){
            return '';
        }
        if(stristr($uri , 'http')){
            return $uri;
        }
        $domain = config('qiniu.cdn_domain');
        return rtrim($domain,'/').'/'.$uri;
    }

    public static function return_json($errcode = 0, $datas='',  $message = '')
    {
        empty($message) && $message = Errcode::get_err_msg($errcode);
        empty($datas) && $datas = '';
        return json(array('errcode' => (int)$errcode, 'message' => (string)$message, 'datas' => $datas));
    }

    public static function return_array($errcode = 0 , $datas = '' , $message = '')
    {
        empty($message) && $message = Errcode::get_err_msg($errcode);
        empty($datas) && $datas = '';
        return array('errcode' => (int)$errcode, 'message' => (string)$message, 'datas' => $datas);
    }

    public static function return_array_chkerr($d){
        return $d === false || is_null($d) || (isset($d['errcode']) && $d['errcode'] !== Errcode::SUCCESS);
    }

    public static function db_log($from , $msg){
        \think\Db::name('log')->insert(['from' => $from , 'msg'=>$msg , 'create_time' => time()]);
        trace($from . $msg);
    }

    public static function getcfg($ukey , $field , $default = false){
        return \think\Db::name('app_config')->where('key' , $ukey)->value($field , $default);
    }

    public static function limit_back($v , $act , $boolv){
        switch ($act){
            case UserPoolMa::ACT_VIDEO:
            case UserPoolMa::ACT_VOICE:
            case UserPoolMa::ACT_HELLO:
                if($boolv){
                    return self::return_json(Errcode::SUCCESS , ['seconds'=>0 , 'sendable' => intval($boolv)]);
                }
                return self::return_json(Errcode::SUCCESS, ['seconds' => intval($v) , 'sendable' => intval($boolv), 'msg'=>$v]);
            case UserPoolMa::ACT_PRIVATE:
                if($boolv){
                    return self::return_json(Errcode::SUCCESS , ['surplus'=> intval($v) , 'sendable' => intval($boolv)]);
                }
                return self::return_json(Errcode::SUCCESS , ['surplus'=> intval($v) , 'sendable' => intval($boolv), 'msg'=>$v]);
        }
    }
    
    public static function callback_chk($arr , $cb =''){
        if(is_string($arr)){
            exit(self::return_json('' , Errcode::SYS_ERRMSG_UNDEFINE , $arr)->send());
        }
        else if(isset($arr['errcode'])){
            if($arr['errcode'] !== Errcode::SUCCESS){
                exit(self::return_json(!empty($arr['datas'])?$arr['datas']:'',$arr['errcode'],Errcode::get_err_msg($arr['errcode']))->send());
            }
            else if($arr['errcode'] === Errcode::SUCCESS){
                exit(self::return_json(!empty($arr['datas'])?$arr['datas']:'',$arr['errcode'],Errcode::get_err_msg($arr['errcode']))->send());
            }
        }
        else if($arr !== true){
            if($arr === false){
                exit(self::return_json(Errcode::SYS_FALSY ,'', '')->send());
            }
            elseif(empty($arr)){
                exit(self::return_json(Errcode::SYS_FALSY ,['msg'=>var_export($arr , true)])->send());
            }
            elseif($arr instanceof \Exception){
                $message = $arr->getMessage() .' '. $arr->getCode();
                exit(self::return_json(Errcode::SYS_EXCEPTION , ['msg'=>$message])->send());
            }

        }
        return $arr;
    }

    public static function admin_send_msg($evt , $username){
        if(!is_array($username)){
            $username = [$username];
        }
        $msgs = db('auto_send_event')->where(['event' => $evt])->select();
        if(!empty($msgs)){
            $HX = HxApi::ins();
            foreach ($msgs as $row){
                if($msg = $row['msg']){
                    try{
                        if($res = $HX->customer_send_msg($username , $msg)){
                            self::db_log(__METHOD__.':'.__LINE__ , var_export($res , true));
                        }
                    }
                    catch (\Exception $e){
                        self::db_log(__METHOD__.':'.__LINE__ , $e->getMessage());
                    }
                }
            }
        }
        return true;
    }
}