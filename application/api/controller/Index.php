<?php

namespace app\api\controller;

use common\Errcode;

class Index extends ApiBase
{

    public function _initialize()
    {
        fetch_setting('', 'article');
        parent::_initialize();
    }

    /**
     * 获取js的全局参数
     */
    public function article_param()
    {
        $data = [
            'intervals' => (config('duration_auto_hold') ?: 3) * 60 * 1000,
            'auto_txt' => lang('AUTO_HOLD_EVERY', ['minute' => config('duration_auto_hold') ?: 3]),
            'auto_hold_auth' => config('is_auto_hold') ?: 1,
        ];
        return $this->return_json($data, Errcode::SUCCESS);
    }
}