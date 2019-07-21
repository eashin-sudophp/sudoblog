<?php
namespace app\admin\controller;

class CategoryController extends AdminBaseController
{
	/**
	 * 文章分类管理
	 */
	public function index()
	{
        $list = db('ArticleCategory')->order('list_order')->select() ?: [];
        $cate = beTree($list);
        $this->assign('cate', $cate);
		return $this->fetch();
	}

    /**
     * 文章分类管理数据
     */
    public function paging()
    {
        $list = db('ArticleCategory')->order('list_order')->select() ?: [];
        $cate = beTree($list);
        $this->assign('cate', $cate);
        return $this->fetch();
    }

	/**
	 * 分类添加
	 */
	public function add()
	{
        $list = db('ArticleCategory')->select() ?: [];
        $cate = beTree($list);
        $this->assign('cate', $cate);
		return $this->fetch();
	}

	/**
	 * 分类添加提交
	 */
	public function addPost()
	{
        $param = input();
        $param['status'] = $status = input('status', 0, 'intval');
        $param['list_order'] = $list_order = input('list_order', 0, 'intval') ?: 10000;
        $param['seo_keyword'] = str_replace(',', ',', $param['seo_keyword']);
        $result = $this->validate($param, 'Category.add');
        if ($result !== true) {
            $this->error($result);
        }

        if (db('ArticleCategory')->insertGetId(clear_empty($param)) === false) {
            $this->error(lang('ADD_FAILED'));
        }

        $this->success(lang('ADD_SUCCESS'));
	}

	/**
	 * 分类编辑
	 */
	public function edit()
	{
        $cate_id = input('id', 0, 'intval');
        if (empty($cate_id)) {
            $this->error(lang('LACK_MUST_PARAM', ['param' => 'id']));
        }

        $row = db('ArticleCategory')->where('id', $cate_id)->find();
        if (empty($row)) {
            $this->error(lang('NOT_FOUND_RECORD', ['param' => 'id']));
        }

        $list = db('ArticleCategory')->select() ?: [];
        $cate = beTree($list);
        $this->assign('cate', $cate);
        $this->assign('row', $row);
        return $this->fetch('add');

	}

	/**
	 * 分类编辑提交
	 */
	public function editPost()
	{
	    $param = input();
        $param['status'] = $status = input('status', 0, 'intval');
        $param['list_order'] = $list_order = input('list_order', 0, 'intval') ?: 10000;
        $param['seo_keyword'] = str_replace(',', ',', $param['seo_keyword']);
        $result = $this->validate($param, 'Category.edit');
        if ($result !== true) {
            $this->error($result);
        }

        unset($param['parent_id']);
        if (db('ArticleCategory')->update(clear_empty($param)) === false) {
            $this->error(lang('EDIT_FAILED'));
        }

        $this->success(lang('EDIT_SUCCESS'));
	}

	/**
	 * 分类删除
	 */
	public function delete()
	{
        $id = input('id', 0, 'strval');
        if (empty($id))
            $this->error(lang('DELETE_FAILED_NOT_SELECTED'));

        $all_ids = db('ArticleCategory')->column('id');
        $after_id = array_diff($all_ids, explode(',', $id));
        $after_pid = db('ArticleCategory')->where(['id' => ['in', $after_id], 'parent_id' => ['neq', 0]])->column('parent_id');
        $exi = array_diff($after_pid, $after_id);
        if (!empty($exi))
            $this->error(lang('DELETE_FAILED_WITH_SON_CATE'));

        $hasArticle = db('Article')->where('cate_id', 'in', $id)->find();
        if (!empty($hasArticle))
            $this->error(lang('DELETE_FAILED_WITH_CATE_ARTICLE'));

        if (!empty($id) && !in_array(1, explode(',', $id)) && db('ArticleCategory')->where('id', 'in', $id)->delete() > 0) {
            $this->success(lang('DELETE_SUCCESS'));
        } else {
            $this->error(lang('DELETE_FAILED'));
        }
	}


}