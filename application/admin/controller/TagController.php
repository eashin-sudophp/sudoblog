<?php
namespace app\admin\controller;

class TagController extends AdminBaseController
{
	/**
	 * 文章标签管理
	 */
	public function index()
	{
        $list = model('ArticleTag')->select() ?: [];
        $this->assign('tag', $list);
		return $this->fetch();
	}

    /**
     * 文章标签管理数据（用于ajax刷新数据）
     */
    public function paging()
    {
        $list = model('ArticleTag')->select() ?: [];
        $this->assign('tag', $list);
        return $this->fetch();
    }

	/**
	 * 文章标签添加
	 */
	public function add()
	{
		return $this->fetch();
	}

	/**
	 * 文章标签添加提交
	 */
	public function addPost()
	{
        $param = input();
        $param['status'] = $status = input('status', 0, 'intval');
        $param['seo_keyword'] = str_replace('，', ',', $param['seo_keyword']);
        $result = $this->validate($param, 'Tag.add');
        if ($result !== true) {
            $this->error($result);
        }

        $id = model('ArticleTag')->insertGetId(clear_empty($param));
        if ($id === false) {
            $this->error(lang('ADD_FAILED'));
        }

        $this->success(lang('ADD_SUCCESS'), '', ['id'=>$id, 'tag_name'=>$param['tag_name']]);
	}

	/**
	 * 文章标签编辑
	 */
	public function edit()
	{
        $tag_id = input('id', 0, 'intval');
        if (empty($tag_id)) {
            $this->error(lang('LACK_MUST_PARAM', ['param' => 'id']));
        }

        $row = model('ArticleTag')->where('id', $tag_id)->find();
        if (empty($row)) {
            $this->error(lang('NOT_FOUND_RECORD', ['param' => 'id']));
        }

        $list = model('ArticleTag')->select() ?: [];
        $this->assign('tag', $list);
        $this->assign('row', $row);
        return $this->fetch('add');

	}

	/**
	 * 文章标签编辑提交
	 */
	public function editPost()
	{
	    $param = input();
        $param['status'] = $status = input('status', 0, 'intval');
        $param['seo_keyword'] = str_replace(',', ',', $param['seo_keyword']);
        $result = $this->validate($param, 'Tag.edit');
        if ($result !== true) {
            $this->error($result);
        }

        if (model('ArticleTag')->update(clear_empty($param)) === false) {
            $this->error(lang('EDIT_FAILED'));
        }

        $this->success(lang('EDIT_SUCCESS'));
	}

	/**
	 * 文章标签删除
	 */
	public function delete()
	{
        $id = input('id', 0, 'strval');
        if (empty($id))
            $this->error(lang('DELETE_FAILED_NOT_SELECTED'));

        $hasArticle = model('Article')->where('tag_id', 'in', $id)->find();
        if (!empty($hasArticle))
            $this->error(lang('DELETE_FAILED_WITH_CATE_ARTICLE'));

        if (!empty($id) && model('ArticleTag')->where('id', 'in', $id)->delete() > 0) {
            $this->success(lang('DELETE_SUCCESS'));
        } else {
            $this->error(lang('DELETE_FAILED'));
        }
	}


}