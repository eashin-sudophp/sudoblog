<?php
namespace app\admin\controller;

use think\Db;
use app\admin\validate\Menu;
use app\admin\lib\AuthMenu;
use app\admin\model\AdminMenu;

class MenuController extends AdminBaseController
{
	/**
	 * 所有菜单
	 */
	public function lists()
	{
		$count = db('AdminMenu')->count();

		$menuLib = new AuthMenu();
		$menus = $menuLib->menuList();
		$this->assign('menus', $menus);
		$this->assign('count', $count);
		return $this->fetch();
	}

	/**
	 * 所有菜单_paging
	 */
	public function paging()
	{
		$count = db('AdminMenu')->count();

		$menuLib = new AuthMenu();
		$menus = $menuLib->menuList();
		$this->assign('menus', $menus);
		$this->assign('count', $count);

		$this->view->engine->layout(false);

		return $this->fetch();
	}

	/**
	 * 菜单添加
	 */
	public function add()
	{
		$menuLib = new AuthMenu();
		$menus = $menuLib->menuList();
		$this->assign('menus', $menus);
		return $this->fetch();
	}

	/**
	 * 菜单添加提交
	 */
	public function addPost()
	{

		$param = input();
		$param['list_order'] = $list_order = input('list_order', 10000, 'intval')?:10000;
		// 验证器
		$validator = $this->validate($param, 'Menu.add');
        if($validator !== true) {
            $this->error($validator);
        }

        if ($param['parent_id'] != 0) {
        	$parent = AdminMenu::get($param['parent_id']);
        	$level_path = $parent->level_path . '-' . $parent->id;
        } else {
        	$level_path = '0';
        }

        db()->startTrans();
        try {
        	// 写入菜单
	        $res = AdminMenu::create([
	        	'parent_id'   => $param['parent_id'],
	        	'name'        => $param['name'],
	        	'app'         => $param['app'],
	        	'controller'  => $param['controller'],
	        	'action'      => $param['action'],
	        	'list_order'  => $list_order,
	        	'type'        => input('type', 1, 'intval'),
	        	'status'      => input('status', 0, 'intval'),
	        	'icon'        => 'home',
	        	'remark'      => !empty($param['remark']) ? $param['remark'] : $param['name'],
	        	'level_path'  => $level_path,
	        ]);
	        // 写入权限表
	        $name = strtolower($param['app'] . '/' . $param['controller'] . '/' . $param['action']);
	        $exi = db('AdminAuthRule')->where('name', $name)->count();
	        if (empty($exi)) {
	        	$res = $res && db('AdminAuthRule')->insertGetId([
	        		'status' => 1,
	        		'app' => 'admin',
	        		'name' => $name,
	        		'type' => 'admin_url',
	        		'title' => $param['name'],
	        		]) > 0;
	        }

	        if (!$res)
	        	exception(lang('ADD_FAILED'));

        } catch (\Exception $e) {
        	db()->rollback();
        	mk_log(__CLASS__ .'::'. __FUNCTION__ .'-'. __LINE__, $e->getMessage());
        	$this->error(lang('ADD_FAILED'), '', $e->getMessage());
        }

        db()->commit();
        $this->success(lang('ADD_SUCCESS'), url('Menu/lists'));

	}

	/**
	 * 菜单编辑
	 */
	public function edit()
	{
		$menu_id = input('id', 0, 'intval');
		if (!$menu_id) {
			$this->error(lang('MENU_NOT_EXISTS'));
		}

		$row = db('AdminMenu')->where('id', $menu_id)->find();
		if (!$row) {
			$this->error(lang('MENU_NOT_EXISTS'));
		}

		$menuLib = new AuthMenu();
		$menus = $menuLib->menuList();
		$this->assign('menus', $menus);
		$this->assign('row', $row);
		return $this->fetch('add');
	}

	/**
	 * 菜单编辑提交
	 */
	public function editPost()
	{
		$param = input();
		$param['list_order'] = $list_order = input('list_order', 0, 'intval')?:10000;

		$id = !empty($param['id']) ? $param['id'] : 0;
		$rule = ['name|菜单名称' => "require|max:30|chs|unique:AdminMenu,name,$id|max:30"];

		// 验证器
		$validate = new Menu($rule);
		$result = $validate->scene('edit')->check($param);
		if(!$result){
		    $this->error($validate->getError());
		}

		db()->startTrans();
        try {

        	$admin = AdminMenu::get($id);
        	$old_rule = strtolower($admin->app . '/' . $admin->controller . '/' . $admin->action);

        	// 更新菜单
	        $admin->name        = $param['name'];
            $admin->app         = $param['app'];
            $admin->controller  = $param['controller'];
            $admin->action      = $param['action'];
            $admin->list_order  = $list_order;
            $admin->type        = input('type', 1, 'intval');
            $admin->status      = input('status', 0, 'intval');
            $admin->remark      = !empty($param['remark']) ? $param['remark'] : $param['name'];
            $res = $admin->save() !== false;

	        if ($res) {
	        	// 更新权限表
		        $name = strtolower($param['app'] . '/' . $param['controller'] . '/' . $param['action']);
		        $exi = db('AdminAuthRule')->where('name', $old_rule)->find();
		        if (empty($exi)) {
		        	$res = db('AdminAuthRule')->insertGetId([
		        		'status' => 1,
		        		'app' => 'admin',
		        		'name' => $name,
		        		'type' => 'admin_url',
		        		'title' => $param['name'],
		        		]) > 0;
		        } else {
		        	$res = db('AdminAuthRule')->update([
		        		'id' => $exi['id'],
		        		'name' => $name,
		        		'title' => $param['name'],
		        		]) !== false;
		        }
	        }

	        if (!$res)
	        	exception(lang('EDIT_FAILED'));

        } catch (\Exception $e) {
        	db()->rollback();
        	mk_log(__CLASS__ .'::'. __FUNCTION__ .'-'. __LINE__, $e->getMessage());
        	$this->error(lang('EDIT_FAILED'), '', $e->getMessage());
        }

        db()->commit();
        $this->success(lang('EDIT_SUCCESS'), url('Menu/lists'));
	}

	/**
	 * 菜单删除
	 */
	public function delete()
	{
		$id = input('id', '', 'strval');
		if (empty($id))
			$this->error(lang('DELETE_FAILED_NOT_SELECTED'));

		$all_ids = db('AdminMenu')->column('id');
        $after_id = array_diff($all_ids, explode(',', $id));
        $after_pid = db('AdminMenu')->where(['id' => ['in', $after_id], 'parent_id' => ['neq', 0]])->column('parent_id');
        $exi = array_diff($after_pid, $after_id);
        if ($exi)
            $this->error(lang('DELETE_FAILED_WITH_SON_ROW'));

		// 权限
		db()->startTrans();
		$rules = db('AdminMenu')
			->field('concat(app,"/",controller," ",action) rule')
			->where('id', 'in', $id)
			->select();
		$rules = array_column($rules, 'rule');

		// 删除菜单和权限
		$res = db('AdminMenu')->where('id', 'in', $id)->delete();
		foreach ($rules as $key => $value) {
			$rule = strtolower(str_replace(' ', '/', $value));
			$res = $res && db('AdminAuthRule')->where('name', $rule)->delete();
			if (!$res) {
				db()->rollback();
				$this->error(lang('DELETE_FAILED'));
			}
		}

		db()->commit();
		$this->success(lang('DELETE_SUCCESS'));
	}
}