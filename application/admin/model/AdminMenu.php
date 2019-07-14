<?php
namespace app\admin\model;

use think\Model;
use app\admin\lib\Auth;

class AdminMenu extends Model {

	/**
	 * 树状菜单
	 * 父菜单id获取下属树状菜单
	 * @param int $parent
     * @param int $Level
     * @param bool $check_auth
     * @return bool|null
	 */
	public function menuTree($parent_id = 0, $level = 1, $check_auth = true)
	{
		$menu = $this->adminMenu($parent_id);

		$ret = [];
		foreach ($menu as $key => $value) {
			if ($level > config('admin_menu_max_level'))
				return false;

			// 验证权限
			if ($check_auth) {
				$rule_name = $value['app'].'/'.$value['controller'].'/'.$value['action'];
				if (!Auth::check(session('ADMIN_ID'), $rule_name))
					continue;
			}

			$value['rule'] = strtolower($value['app'] . '/' . $value['controller'] . '/' . $value['action']);
			$value['level'] = $level;
			$son_menu = $this->menuTree($value['id'], $level+1, $check_auth);
			$son_menu && $value['son_menu'] = $son_menu;
			$ret[] = $value;
		}
		return $ret;
	}

	/**
	 * 所有权限
	 * 设置权限页面的所有权限
	 * @param int $parent
     * @param int $Level
     * @return bool|null
	 */
	public function allMenuAuth($parent_id = 0, $level = 1)
	{
		$menu = $this->adminMenu($parent_id, false);

		$ret = [];
		foreach ($menu as $key => $value) {
			$value['rule'] = strtolower($value['app'] . '/' . $value['controller'] . '/' . $value['action']);
			$value['level'] = $level;
			$son_menu = $this->allMenuAuth($value['id'], $level+1);
			$son_menu && $value['son_menu'] = $son_menu;
			$ret[] = $value;
		}
		return $ret;
	}

	/**
	 * 获取子菜单
	 * @param int $parent
	 * @param bool $type 是否只需要id
     * @return bool|null
	 */
	public function getSonList($parent_id = 0, $type = true)
	{
		$path = empty($parent_id) ? '0' :db('admin_menu')->where('id', $parent_id)->value('level_path');
		if ($type) {
			return db('admin_menu')->where('level_path', 'like', $path . '%')->order('list_order')->column('id');
		} else {
			return db('admin_menu')->where('level_path', 'like', $path . '%')->order('list_order')->select();
		}
	}

	/**
	 * 通过父级id获取子菜单
	 * @param int $menu_id
     * @param bool $status_show
     * @return bool|null
	 */
	public function adminMenu($menu_id, $status_show = true)
	{
		$map = [
			'parent_id' => $menu_id,
			];
		$status_show && $map['status'] = 1;
		return db('admin_menu')->where($map)->order('list_order')->select();
	}

}