<?php
namespace app\admin\controller;

use think\Db;

class UserController extends AdminBaseController
{

    public function _initialize()
    {
        fetch_setting_by_key('page_per_count');
        parent::_initialize();
    }

	/**
	 * 管理员管理
	 */
	public function index()
	{
		$count = db('AdminUser')->alias('u')->count();
		$list = db('AdminUser')
			->alias('u')
			->field('u.*, r.name role_name')
			->join('AdminRoleUser ru', 'u.id = ru.user_id','LEFT')
			->join('AdminRole r', 'r.id = ru.role_id','LEFT')
			->order('id')
			->paginate(config('page_per_count')?:10)
			->each(function ($item, $key) {
				$item['create_time'] = get_timestamp($item['create_time'], 'Y-m-d');
				$item['last_login_time'] = get_timestamp($item['last_login_time']);
				$item['last_login_add'] = $item['last_login_add']?:$item['last_login_ip'];
				return $item;
			});

		$lists = $list->toArray();

		$this->assign('query', '');
		$this->assign('count', $count); //右上角总记录数
		$this->assign('list', $lists['data']); // table列表
		$this->assign('desc', $lists); // 左下角分页描述
		$this->assign('page', $list->render()); // 右下角分页框
		return $this->fetch();
	}

	/**
	 * 管理员管理_paging
	 */
	public function paging()
	{
		$get = input();
		$page = input('page/d', 1, 'intval');

		$map = [];
		if (!empty($get['start'])) {
			$map['u.create_time'] = array('gt', strtotime($get['start']));
		}
		if (!empty($get['end'])) {
			$map['u.create_time'] = array('lt', strtotime($get['end']) + 86400);
		}
		if (!empty($get['start']) && !empty($get['end'])) {
			$map['u.create_time'] = array('between', [strtotime($get['start']),  strtotime($get['end']) + 86400]);
		}
		if (!empty($get['username'])) {
			$map['u.user_login|u.user_nickname'] = array('like', '%' . $get['username'] . '%');
		}

		$listRows = config('page_per_count')?:10;
		$count = db('AdminUser')->alias('u')->where($map)->count();
        $page > 1 && ceil($count/$listRows) < $page && $page = ceil($count/$listRows); // 整一页数据删完的情况
		$list = db('AdminUser')
			->alias('u')
			->where($map)
			->field('u.*, r.name role_name')
			->join('AdminRoleUser ru', 'u.id = ru.user_id','LEFT')
			->join('AdminRole r', 'r.id = ru.role_id','LEFT')
			->order('id')
			->paginate(config('page_per_count'), $count, ['page' => $page])
			->each(function ($item, $key) {
				$item['create_time'] = get_timestamp($item['create_time'], 'Y-m-d');
				$item['last_login_time'] = get_timestamp($item['last_login_time']);
				$item['last_login_add'] = $item['last_login_add']?:$item['last_login_ip'];
				return $item;
			});

		$lists = $list->toArray();

		$this->assign('count', $count); //右上角总记录数
		$this->assign('list', $lists['data']); // table列表
		$this->assign('desc', $lists); // 左下角分页描述
		$this->assign('page', $list->render()); // 右下角分页框
		$this->view->engine->layout(false);
		return $this->fetch();
	}

	/**
	 * 管理员添加
	 */
	public function add()
	{
		$roles = db('AdminRole')->field('id, name')->where('id', 'gt', 1)->select();
		$this->assign('roles', $roles);
		return $this->fetch();
	}

	/**
	 * 管理员添加提交
	 */
	public function addPost()
	{
		$param = input();
		$result = $this->validate($param, 'User.add');
		if ($result !== true) {
			$this->error($result);
		}

		db()->startTrans();
		try {

			if ($param['role_id'] > 1 && db('AdminUser')->insert([
				'user_login' => $param['username'],
				'user_pass' => password_encrypt($param['password']),
				'role_id' => $param['role_id'],
				'create_time' => time(),
			]))
				$res = db('AdminRoleUser')->insert(['role_id' => $param['role_id'], 'user_id' => db('AdminUser')->getLastInsId()]);

			if (empty($res))
				exception(lang('FAILED'));

		} catch (\Exception $e) {
			db()->rollback();
			$this->error(lang('ADD_FAILED'), '', $e->getMessage());
		}
		db()->commit();
		$this->success(lang('ADD_SUCCESS'));

	}

	/**
	 * 管理员编辑
	 */
	public function edit()
	{
		$id = input('id', 0, 'intval');
		if ($id>1) {
			$roles = db('AdminRole')->where('id', 'gt', 1)->select();
			$row = db('AdminUser')
			->alias('u')
			->field('u.*, ru.role_id')
			->join('AdminRoleUser ru', 'u.id = ru.user_id', 'LEFT')
			->where('u.id', $id)
			->find();
			$this->assign('roles', $roles);
			$this->assign('row', $row);
			return $this->fetch('add');
		}
	}

	/**
	 * 管理员编辑提交
	 */
	public function editPost()
	{
		$param = input();
		$result = $this->validate($param, 'User.edit');
		if ($result !== true) {
			$this->error($result);
		}

		db()->startTrans();
		try {
			$data = ['id' => $param['id'], 'status' => $param['status']];
			!empty($param['password']) && $data['user_pass'] = password_encrypt($param['password']);
			if ($param['role_id'] > 1 && db('AdminUser')->update($data) !== false) {
				$row_id = db('AdminRoleUser')->where('user_id', $param['id'])->value('id');
				if (!empty($row_id)) {
					$res = db('AdminRoleUser')->update(['id' => $row_id, 'role_id' => $param['role_id']]) !== false;
				} else {
					$res = db('AdminRoleUser')->insert(['user_id' => $param['id'], 'role_id' => $param['role_id']]) > 0;
				}
			}

			if (empty($res))
				exception(lang('FAILED'));

		} catch (\Exception $e) {
			db()->rollback();
			$this->error(lang('EDIT_FAILED'), '', $e->getMessage());
		}
		db()->commit();
		$this->success(lang('EDIT_SUCCESS'));
	}

	/**
	 * 管理员个人信息修改
	 */
	public function userInfo()
	{
		$id = input('id', 0, 'intval');
		if ($id>1) {
			$row = db('AdminUser')
				->field('id, user_nickname, user_mobile, user_email')
				->where('id', $id)
				->find();
			$this->assign('row', $row);
			return $this->fetch();
		}
	}

	/**
	 * 管理员个人信息修改提交
	 */
	public function userInfoPost()
	{
		$param = input();
		$result = $this->validate($param, 'User.info');
		if ($result !== true) {
			$this->error($result);
		}

		if ($param['id'] > 1 && db('AdminUser')->update([
			'id' => $param['id'],
			'user_nickname' => $param['nickname'],
			'user_mobile' => $param['mobile'],
			'user_email' => $param['email'],
		]) !== false) {
			$this->success(lang('EDIT_SUCCESS'));
		} else {
			$this->error(lang('EDIT_FAILED'));
		}
	}

	/**
	 * 管理员删除
	 */
	public function delete()
	{
		$id = input('id', 0, 'strval');
		if (!empty($id) && !in_array(1, explode(',', $id)) && db('AdminUser')->where('id', 'in', $id)->delete() >0) {
			$this->success(lang('DELETE_SUCCESS'));
		} else {
			$this->error(lang('DELETE_FAILED'));
		}
	}

	/**
	 * 停用管理员
	 */
	public function ban()
	{
		$id = input('id', 0, 'intval');
		if (!empty($id)) {
			$res = db('AdminUser')->where('id', $id)->setField('status', 0);
			if ($res) {
				$this->success(lang('SUCCESS'));
			} else {
				$this->error(lang('FAILED'));
			}
		}
	}

	/**
	 * 启用管理员
	 */
	public function cancelBan()
	{
		$id = input('id', 0, 'intval');
		if (!empty($id)) {
			$res = db('AdminUser')->where('id', $id)->setField('status', 1);
			if ($res) {
				$this->success(lang('SUCCESS'));
			} else {
				$this->success(lang('FAILED'));
			}
		}
	}

}