<?php
namespace app\admin\lib;

class Auth extends \think\Controller{

    /**
     * 验证权限
     * @param int $uid
     * @param str $name 权限
     * @param bool $strict 精准验证
     * @return bool|null
     */
	public static function check($uid, $name, $strict = true)
	{
		if (empty($uid))
			return false;
		elseif ($uid == 1)
			return true;

		$role_id = db('admin_role_user')->where('user_id', $uid)->value('role_id');
		if (empty($role_id))
			return false;
		elseif ($role_id == 1)
			return true;

		$name = explode(',', strtolower($name));

		$rules = db('admin_auth_access')->where(['role_id' => $role_id, 'rule_name' => ['in', $name]])->column('rule_name');
		$rules = explode(',', strtolower(implode(',', $rules)));
		$diff = array_diff($rules, $name);
		if (empty($diff)) {
			return true;
		}
		if (!$strict && !empty($rules)) {
			return true;
		}
		return false;
	}

	/**
	 * 验证是否登录
	 * @return bool
	 */
	public static function loginStatus()
	{
		return boolval(session('ADMIN_ID'));
	}
}