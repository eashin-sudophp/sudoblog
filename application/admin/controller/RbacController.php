<?php
namespace app\admin\controller;

use think\Db;
use app\admin\lib\AuthMenu;

class RbacController extends AdminBaseController
{
	/**
	 * 角色列表
	 */
	public function role()
	{
		$count = db('AdminRole')->count();

		$roles = db('AdminRole')->select();
		$this->assign('roles', $roles);
		$this->assign('count', $count);
		return $this->fetch();
	}

	/**
	 * 角色列表_paging
	 */
	public function paging()
	{
		$count = db('AdminRole')->count();

		$roles = db('AdminRole')->select();
		$this->assign('roles', $roles);
		$this->assign('count', $count);

		$this->view->engine->layout(false);

		return $this->fetch();
	}

	/**
	 * 角色添加
	 */
	public function roleAdd()
	{
		return $this->fetch();
	}

	/**
	 * 角色添加提交
	 */
	public function roleAddPost()
	{
		$param = input();
		// 验证器
		$validator = $this->validate($param, 'Rbac.add');
		if ($validator !== true)
			$this->error($validator);

		if (db('AdminRole')->insertGetId($param) > 0)
			$this->success(lang('ADD_SUCCESS'));
		else
			$this->error(lang('ADD_FAILED'));

	}

	/**
	 * 角色编辑
	 */
	public function roleEdit()
	{
		$role_id = input('id', 0, 'intval');
		if (!empty($role_id) && $role_id != 1) {
			$row = db('AdminRole')->find($role_id);
			if ($row)
				$this->assign('row', $row);
		}
		return $this->fetch('roleadd');
	}

	/**
	 * 角色编辑提交
	 */
	public function roleEditPost()
	{
		$param = input();
		// 验证器
		$validator = $this->validate($param, 'Rbac.edit');
		if ($validator !== true)
			$this->error($validator);


		if ($param > 1 && db('AdminRole')->update($param) !== false)
			$this->success(lang('EDIT_SUCCESS'));
		else
			$this->error(lang('EDIT_FAILED'));

	}

	/**
	 * 角色删除
	 */
	public function delete()
	{
		$id = input('id', 0, 'strval');
		if (!empty($id) && !in_array(1, explode(',', $id)) && db('AdminRole')->where('id', 'in', $id)->delete() > 0) {
			$this->success(lang('DELETE_SUCCESS'));
		} else {
			$this->error(lang('DELETE_FAILED'));
		}

	}

	/**
	 * 权限设置
	 */
	public function authorize()
	{
		$id = input('id', 0, 'intval');
		if ($id > 1) {
			$menuModel = new AuthMenu();
	    	$menus = $menuModel->allMenuAuth(); // 获取所有权限
	    	$rules = db('AdminAuthAccess')->where('role_id', $id)->where('type', 'admin_')->column('rule_name');
	        $this->assign('menus', $menus);
	        $this->assign('rules', $rules);
	        $this->assign('id', $id);
			return $this->fetch();
		}
	}

	/**
	 * 权限设置提交
	 */
	public function authorizePost()
	{
		$id = input('id', 0, 'intval');
		$rules = input('rules/a', []);
		if ($id > 0) {
			db()->startTrans();
			try {
				$old_rules = db('AdminAuthAccess')->where('role_id', $id)->where('type', 'admin_')->column('rule_name');
				$del_rules = array_diff($old_rules, $rules);
				$rs_del = db('AdminAuthAccess')->where('role_id', $id)->where('rule_name', 'in', $del_rules)->where('type', 'admin_')->delete();
				if (!empty($del_rules) && empty($rs_del))
					exception(lang('EDIT_FAILED') . ' deleting');

				$add_rules = array_diff($rules, $old_rules);
				if (!empty($add_rules)) {
					foreach ($add_rules as $key => $value) {
						$rs_add = db('AdminAuthAccess')->insertGetId(['role_id' => $id, 'rule_name' => $value, 'type' => 'admin_']);
						if (empty($rs_add))
							exception(lang('EDIT_FAILED') . ' adding');
					}
				}

			} catch (\Exception $e) {
				db()->rollback();
				$this->error(lang('EDIT_FAILED'), '', $e->getMessage());
			}
			db()->commit();
			$this->success(lang('EDIT_SUCCESS'));
		}
	}





}