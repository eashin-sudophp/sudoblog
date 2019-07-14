<?php

    /**
     * 无限极分类
     * @param array $data 二维数组，元素包含下标“parent_id”
     * @param integer $parent_id 父id
     * @param integer $level 级别
     * @return array
     */
    function beTree($data, $parent_id = 0)
    {
        $arr = array();
        foreach ($data as $key => $value) {
            if ($value['parent_id'] == $parent_id) {
                $child = beTree($data, $value['id']);
                $child && $value['child'] = $child;
                $arr[] = $value;
            }
        }
        return $arr;
    }