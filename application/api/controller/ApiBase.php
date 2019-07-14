<?php

namespace app\api\controller;


use common\Common;
use common\Errcode;
use think\Controller;


class ApiBase extends Controller
{
    protected $user = null;
    protected $token_debug = true;

    protected function _initialize()
    {
        $input = input();
        if (!$this->check_sign_($input)) {
            exit($this->return_json('', Errcode::SYS_SIGN_FAIL, Errcode::get_err_msg(Errcode::SYS_SIGN_FAIL))->send());
        }
        $this->init_check_token();
    }

    /**
     * 检查用户token
     * @param $user_id
     * @param $token
     */
    public function check_token($user_id, $token)
    {
        /*$return = \app\common\logic\User::check_token($user_id, $token);
        if ($return['errcode'] != Errcode::SUCCESS) {
            exit($this->return_json('', $return['errcode'], Errcode::get_err_msg($return['errcode']))->send());
        }
        $this->user = $return['datas'];*/
    }


    /**
     * 返回数据入口
     * @param $datas
     * @param int $errcode
     * @param string $message
     * @return \think\response\Json
     */
    protected function return_json($datas, $errcode = 0, $message = '')
    {
        empty($message) && $message = Errcode::get_err_msg($errcode);

        if(empty($datas)){
            $datas = "";
        }else{
            array_walk_recursive($datas, function(&$item, $index){
                $item = (string)$item;
            });
        }
        return json(array('errcode' => (int)$errcode, 'message' => (string)$message, 'datas' => $datas));
    }

    /**
     * 验证签名
     * @param $datas
     * @return bool
     */
    private function check_sign_(array $datas)
    {
    	//临时添加开发绕过sign
    	if($datas['sign']=='dev_sign')
    		return true;

        if (empty($datas['sign'])) {
            return false;
        }
        $sign = $datas['sign'];
        unset($datas['sign']);
        $str = '';
        ksort($datas);
        foreach ($datas as $k => $v) {
            if (is_null($v) || $v === "") {
                unset($datas[$k]);
            }
            $str .= $k.'='.$v.'&';
        }
        $key = config('config.sign_key');
        $str .= 'sign_key='.$key;
        $str =  urldecode($str);

        return md5($str) === $sign;

    }
    /**
     * 统一处理函数返回
     * @param mix
     * $return <boolean>true <array> <object>
     * */
    protected function callback_chk($arr , $cb = '')
    {
        if(is_string($arr)){
            exit($this->return_json('' , Errcode::SYS_ERRMSG_UNDEFINE , $arr)->send());
        }
        else if(isset($arr['errcode'])){
            if($arr['errcode'] !== Errcode::SUCCESS){
                exit($this->return_json(!empty($arr['datas'])?$arr['datas']:'',$arr['errcode'],Errcode::get_err_msg($arr['errcode']))->send());
            }
            else if($arr['errcode'] === Errcode::SUCCESS){
                exit($this->return_json(!empty($arr['datas'])?$arr['datas']:'',$arr['errcode'],Errcode::get_err_msg($arr['errcode']))->send());
            }
        }
        else if($arr !== true){
            if($this->VADT instanceof \think\Validate && $arr === false){
                $code = Errcode::SYS_PARAM_ERR;
                $message = $this->VADT->getError();
                exit($this->return_json('' , $code , $message)->send());
            }
            elseif(empty($arr)){
                exit($this->return_json('' , Errcode::SYS_FALSY , var_export($arr))->send());
            }
            elseif($arr instanceof \Exception){
                $code = Errcode::SYS_EXCEPTION;
                $message = $arr->getMessage() .' '. $arr->getCode();
                exit($this->return_json('' , $code , $message)->send());
            }

        }
        return $arr;
    }

    /**
     * 统一验证token
     * */
    protected function init_check_token(){
        if($this->token_debug){
            return;
        }
        if(!empty($this->ACTIONS_NEED_TOKEN) && !in_array(strtolower(ACTION) , $this->ACTIONS_NEED_TOKEN)){
            return;
        }
        $d = input();
        if(empty($d[Common::UID]) || empty($d['token'])){
            exit($this->return_json('' , Errcode::SYS_TOKEN_ERROR , Errcode::get_err_msg(Errcode::SYS_TOKEN_ERROR)));
        }
        $this->check_token($d[Common::UID] , $d['token']);
    }

    /**
     * 404
     * */
    public function _empty(){
        exit('404');
    }
}
