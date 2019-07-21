<?php

namespace app\admin\model;

use think\Model;

class ArticleCategory extends Model
{

    // 获取多级文章分类（二维数组）
    public function getParentCates($id)
    {
        $list = [];
        while (true) {
            $parent = $this->where('id', $id)->find()->toArray();
            $list[] = $parent;
            if ($parent['parent_id'] === 0) {
                break;
            }
            $id = $parent['parent_id'];
        };
        return array_reverse($list);
    }

}