<?php
namespace app\api\controller;

use common\Common;
use common\Errcode;
use Qiniu\Qiniu;
use common\Uploader;
use think\Db;

/**
 * 统一上传接口
 * Class Upload
 * @package app\api\controller
 */
class Upload extends ApiBase
{
    public function image()
    {

        $width = input('width');
        $height = input('height');

        if (!isset($_FILES['file'])) {
            return $this->return_json(array(),Errcode::SYS_UPLOAD_FILE_EMPTY);
        }

        if ($_FILES["file"]["error"] > 0) {
            return $this->return_json("", Errcode::SYS_UPLOAD_FILE_ERR);
        }

        $uploader_config = array(
            'filePath' => '',
            'pathFormat'=>'/upload/image/{yyyy}{mm}{dd}/{hh}{ii}{ss}{time}',
            'maxSize'=>2*1024*1024,
            'allowFiles'=>array('.jpg', '.png')
        );
        $uploader = new Uploader('file', $uploader_config);
        $uploader_info = $uploader->getFileInfo();

        if($uploader_info['state'] != 'SUCCESS'){
            return $this->return_json("", Errcode::SYS_UPLOAD_FILE_FAIL);
        }

        return $this->return_json(array(
            'image'=>$uploader_info['url'],
            'thumb_image'=>$uploader_info['url'],
            'domain'=> 'http://' . $_SERVER['HTTP_HOST'],
        ));

    }

    // 百度编辑器上传图片
    public function ue_image()
    {

        $width = input('width');
        $height = input('height');

        if (!isset($_FILES['file'])) {
            return $this->return_json(array(),Errcode::SYS_UPLOAD_FILE_EMPTY);
        }

        if ($_FILES["file"]["error"] > 0) {
            return $this->return_json("", Errcode::SYS_UPLOAD_FILE_ERR);
        }

        $uploader_config = array(
            'filePath' => '',
            'pathFormat'=>'upload/image/{yyyy}{mm}{dd}/{hh}{ii}{ss}{time}',
            'maxSize'=>2*1024*1024,
            'allowFiles'=>array('.jpg', '.png')
        );
        $uploader = new Uploader('file', $uploader_config);
        $uploader_info = $uploader->getFileInfo();

        if($uploader_info['state'] != 'SUCCESS'){
            return $this->return_json("", Errcode::SYS_UPLOAD_FILE_FAIL);
        }

        return $this->return_json($uploader_info);

    }


    //小视频上传
    public function video()
    {

        if (!isset($_FILES['file'])) {
            return $this->return_json(array(),Errcode::SYS_UPLOAD_FILE_EMPTY);
        }

        if ($_FILES["file"]["error"] > 0) {
            return $this->return_json("", Errcode::SYS_UPLOAD_FILE_ERR);
        }

        $uploader_config = array(
            'filePath' => '',
            'pathFormat'=>'upload/video/{yyyy}{mm}{dd}/{hh}{ii}{ss}{time}',
            'maxSize'=>2*1024*1024,
            'allowFiles'=>array('.mp4', '.avi')
        );
        $uploader = new Uploader('file', $uploader_config);
        $uploader_info = $uploader->getFileInfo();

        if($uploader_info['state'] != 'SUCCESS'){
            return $this->return_json("", Errcode::SYS_UPLOAD_FILE_FAIL);
        }

        return $this->return_json(array(
            'video'=>$uploader_info['url'],
            'domain'=>'http://' . $_SERVER['HTTP_HOST'] . DS,
        ));

    }
}
