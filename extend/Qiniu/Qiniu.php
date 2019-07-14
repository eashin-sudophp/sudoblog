<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/29
 * Time: 17:01
 */

namespace Qiniu;

 
use Qiniu\Storage\UploadManager;

require_once __DIR__ . '/autoload.php';
class Qiniu
{
    public static function upload_file($file_path, $filename){
        try{
            $accessKey =config('qiniu.access_key');
            $secretKey = config('qiniu.secret_key');
            $bucket =  config('qiniu.bucket');
            $auth = new Auth($accessKey, $secretKey);
            $token = $auth->uploadToken($bucket);
            // 初始化 UploadManager 对象并进行文件的上传。
            $uploadMgr = new UploadManager();
            // 调用 UploadManager 的 putFile 方法进行文件的上传。
            list($ret, $err) = $uploadMgr->putFile($token, $filename, $file_path);
            if ($err !== null) {
                return false;
            } else {
                return true;
            }
        }catch (\Exception $e){
            return false;
        }
    }
}