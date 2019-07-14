<?php

namespace app\api\controller;

use think\Db;

class City extends ApiBase
{

    /**
     * 省市数据
     */
    public function index()
    {
        $list = Db::name('city')->select();
        $city = $this->get_tree($list);
        return $this->return_json(['list'=>$city]);
    }

    /**
     * 处理省市数据
     * @param string $data  省市数据集合
     * @param integer $pid  父id
     * @return array
     */
    private function get_tree($data, $pid = 0)
    {
        $arr = array();
        foreach ($data as $key => $value) {
            if ($value['parent_id'] == $pid) {
                $son = $this->get_tree($data, $value['id']);
                $v = array('id' => $value['id'], 'name' => $value['name']);
                if (!empty($son)) {
                    $v['son'] = $son;
                }
                $arr[] = $v;
            }
        }
        return $arr;
    }

}