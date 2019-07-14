<?php
namespace app\admin\lib;

use  app\admin\model\AdminMenu;

class AuthMenu {

	public $storage = []; // 用来存储

	/**
	 * 菜单列表
	 * @param int $parent_id
     * @return bool|null
	 */
	public function menuList($parent_id = 0)
	{
		$menuModel = new AdminMenu();
		$menus = $menuModel->getSonList($parent_id, false);
		$this->storage = []; // 清理储存器
		return $this->sortByTree($menus, $parent_id);
	}

	/**
	 * 所有权限
	 * 设置权限页面的所有权限
	 * @param int $parent
     * @param int $Level
     * @return bool|null
	 */
	public function allMenuAuth($parent_id = 0)
	{
		$menuModel = new AdminMenu();
		$menus = $menuModel->adminMenu($parent_id, false);
		foreach ($menus as $key => &$value) {
			$value['rule'] = strtolower($value['app'] . '/' . $value['controller'] . '/' . $value['action']);
			$son_menu = $this->menuList($value['id']);
			if (!empty($son_menu)) {
				$value['son_menu'] = $son_menu;
			}
		}
		return $menus;
	}

	/**
	 * 重新排序
	 */
	public function sortByTree($data, $parent_id = 0, $level = 1)
	{
		$arr = $this->storage;
		foreach ($data as $key => $value) {
			if ($value['parent_id'] == $parent_id) {
				$value['rule'] = strtolower($value['app'] . '/' . $value['controller'] . '/' . $value['action']);
				$value['level'] = $level-1;
				$this->storage[] = $value;
				$this->sortByTree($data, $value['id'], $level+1);
			}
		}
		return $this->storage;
	}
}