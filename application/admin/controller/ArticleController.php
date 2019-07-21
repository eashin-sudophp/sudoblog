<?php
namespace app\admin\controller;

use app\admin\model\Article;
use app\admin\model\ArticleTagRecord;

class ArticleController extends AdminBaseController
{
    public function _initialize()
    {
        fetch_setting('', 'article');
        parent::_initialize();
    }

	/**
	 * 文章管理
	 */
	public function index()
	{

		$cateList = db('ArticleCategory')->select();

		$count = db('Article')->where(1)->count();
		$list = db('Article')
			->alias('a')
			->join('ArticleCategory cate', 'a.cate_id = cate.id', 'LEFT')
			->field('a.*, cate.cate_name, cate.cate_alias')
			->order('auto_hold desc, id asc')
			->paginate(config('article_per_count')?:10)
			->each(function($item){
				$item['create_time'] = get_timestamp($item['create_time'], 'Y-m-d');
				$item['update_time'] = get_timestamp($item['update_time']);
				$item['article_status'] = $this->judge_status($item);
				return $item;
			});

		$lists = $list->toArray();

		$this->assign('count', $count);
		$this->assign('list', $lists['data']);
		$this->assign('desc', $lists);
		$this->assign('page', $list->render());
		$this->assign('cate', $cateList);
		return $this->fetch();
	}

	/**
	 * 文章管理_paging
	 */
	public function paging()
	{

		$get = input();
        $cur = input('page/d', 1, 'intval');

		$map = [];
		if (!empty($get['start'])) {
			$map['a.create_time'] = array('gt', strtotime($get['start']));
		}
		if (!empty($get['end'])) {
			$map['a.create_time'] = array('lt', strtotime($get['end']) + 86400);
		}
		if (!empty($get['start']) && !empty($get['end'])) {
			$map['a.create_time'] = array('between', [strtotime($get['start']),  strtotime($get['end']) + 86400]);
		}
		if (!empty($get['title'])) {
			$map['a.title'] = array('like', '%' . $get['title'] . '%');
		}
		if (!empty($get['cate_id'])) {
			$map['a.cate_id'] = $get['cate_id'];
		}

		$listRows = config('article_per_count')?:10;
		$count = db('Article')->alias('a')->where($map)->count();
        $cur > 1 && ceil($count/$listRows) < $cur && $cur = ceil($count/$listRows); // 整一页数据删完的情况
		$list = db('Article')
			->alias('a')
			->join('ArticleCategory cate', 'a.cate_id = cate.id', 'LEFT')
			->field('a.*, cate.cate_name, cate.cate_alias')
			->order('auto_hold desc, id asc')
			->where($map)
			->paginate($listRows, $count, ['page' => $cur])
			->each(function($item){
				$item['create_time'] = get_timestamp($item['create_time'], 'Y-m-d');
				$item['update_time'] = get_timestamp($item['update_time']);
				$item['article_status'] = $this->judge_status($item);
				return $item;
			});

		$lists = $list->toArray();

		$this->assign('count', $count);
		$this->assign('list', $lists['data']);
		$this->assign('desc', $lists); // 分页详情，用于翻页板块左边的分页介绍
		$this->assign('page', $list->render());
		$this->view->engine->layout(false);
		return $this->fetch();
	}

	/**
	 * 文章添加
	 */
	public function add()
	{
        $list = db('ArticleCategory')->select() ?: [];
        $cate = beTree($list);

        $tags = model('ArticleTag')->select() ?: [];

        $this->assign('cates', $cate);
        $this->assign('tags', $tags);
		return $this->fetch();
	}

	/**
	 * 文章添加提交
	 */
	public function addPost()
	{

        // 系统自动存档
        $auto_hold = input('auto_hold', 0, 'intval');
        // 存草稿
        $draft = $auto_hold ? 1 : input('draft', 0, 'intval');

		// 验证器
		$param = input();
		$param['content_basic'] = input('content', 0, 'strip_tags');
		$result = $this->validate($param, 'Article.add');
		if (!$draft && $result !== true) {
			$this->error($result);
		}

		// 关键词
        $param['keyword'] = input('keyword', '', function($v){
            return strval(str_replace('，', ',', $v));
        });
        // 摘要是否显示文章之前
        $param['summary_show'] = input('summary_show', 0, 'intval');
        // 开放评论
        $param['comment_auth'] = input('comment_auth', 0, 'intval');
        // 开放评论起止时间
        $param['comment_start'] = input('comment_start', 0, 'strtotime');
        $param['comment_end'] = input('comment_end', 0, 'strtotime');
        // 开放文章起止时间
        $param['open_time'] = input('open_time', 0, 'strtotime');
        $param['close_time'] = input('close_time', 0, 'strtotime');
		// 浏览次数
        $param['view_times'] = input('view_times', 0, 'intval');
        // 文章发布更新时间
		$param['create_time'] = $param['update_time'] = time();
		// 作者
        $param['author'] = session('ADMIN_ID');
        // 文章状态 0草稿1普通2私密
        $private = input('private', 0, 'intval');
        $param['status'] = $draft ? 0 : ($private ? 2 : 1);
        $param['auto_hold'] = $auto_hold;

		$msg = $draft ? lang('CONSERVATION') : lang('PUBLISH');

        $articleModel = new Article($param);
        if ($articleModel->allowField(true)->save()) {
            // 增加文章标签
            $article_tag = input('article_tag/a', []);
            foreach ($article_tag as $item) {
                ArticleTagRecord::create(['aid'=>$id, 'tid'=>$item]);
            }
            $this->success($msg.lang('SUCCESS'), '', ['id' => $articleModel->id]);
        }  else {
            $this->error($msg.lang('FAILED'));
        }
	}

	/**
	 * 文章编辑
	 */
	public function edit()
	{
	    $id = input('id', 0, 'intval');
	    $row = db('Article')->where('id', $id)->find();
	    if (!empty($id) && !empty($row)) {
            $tags = model('ArticleTag')->select() ?: [];
            $article_tags = model('ArticleTagRecord')->where('aid', $id)->column('tid') ?: [];
            $list = db('ArticleCategory')->select() ?: [];
	        $cate = beTree($list);
            $this->assign('cates', $cate);
            $this->assign('tags', $tags);
            $this->assign('article_tags', $article_tags);
            $this->assign('row', $row);
            return $this->fetch('add');
        }
	}

	/**
	 * 文章编辑提交
	 */
	public function editPost()
	{
        // 文章编号
        $id = input('id', 0, 'intval');
        $articleModel = Article::get($id);

        // 系统自动存档
        $auto_hold = $articleModel->auto_hold == 1 ? input('auto_hold', 0, 'intval') : 0;
        // 存草稿
        $draft = $auto_hold ? 1 : input('draft', 0, 'intval');

        // 已发布无法存草稿
        if ($articleModel->status != 0 && $draft == 1) {
        	$this->error(lang('ARTICLE_HAS_PUBLISH'));
        }

        // 验证器
        $param = input();
        $param['content_basic'] = input('content', 0, 'strip_tags');
        $result = $this->validate($param, 'Article.edit');
        if (!$draft && $result !== true) {
            $this->error($result);
        }

        // 关键词
        $param['keyword'] = input('keyword', '', function($v){
            return strval(str_replace('，', ',', $v));
        });
        // 摘要是否显示文章之前
        $param['summary_show'] = input('summary_show', 0, 'intval');
        // 开放评论
        $param['comment_auth'] = input('comment_auth', 0, 'intval');
        // 开放评论起止时间
        $param['comment_start'] = input('comment_start', 0, 'strtotime');
        $param['comment_end'] = input('comment_end', 0, 'strtotime');
        // 开放文章起止时间
        $param['open_time'] = input('open_time', 0, 'strtotime');
        $param['close_time'] = input('close_time', 0, 'strtotime');
        // 浏览次数
        $param['view_times'] = input('view_times', 0, 'intval');
        // 文章发布更新时间
        $param['update_time'] = time();
        // 作者
        $param['author'] = session('ADMIN_ID');
        // 文章状态 0草稿1普通2私密
        $private = input('private', 0, 'intval');
        $param['status'] = $draft ? 0 : ($private ? 2 : 1);
        $param['auto_hold'] = $auto_hold;

        $msg = $draft ? lang('CONSERVATION') : lang('PUBLISH');
        if ($articleModel->allowField(true)->save($param) !== false) {
            // 修改文章标签
            $article_tag = input('article_tag/a', []);
            ArticleTagRecord::where('aid', $id)->delete();
            foreach ($article_tag as $item) {
                ArticleTagRecord::create(['aid'=>$id, 'tid'=>$item]);
            }
            $this->success($msg.lang('SUCCESS'));
        } else {
            $this->error($msg.lang('FAILED'));
        }

	}

	/**
	 * 文章操作
	 */
	public function operate()
	{
		$this->success(lang('SUCCESS'));
	}


	/**
	 * 文章删除
	 */
	public function delete()
	{
        $id = input('id', 0, 'strval');
        if (!empty($id) && db('Article')->where('id', 'in', $id)->delete() >0) {
            $this->success(lang('DELETE_SUCCESS'));
        } else {
            $this->error(lang('DELETE_FAILED'));
        }
	}

	/**
	 * 文章相关配置
	 */
	public function config()
	{
		$confList = db('setting')->where('class', 'article')->select();
		$this->assign('config', $confList);
		return $this->fetch();
	}

	/**
	 * 文章相关配置提交
	 */
	public function configPost()
	{
		$data = input();
		if (!empty($data)) {
			db()->startTrans();
			try {
			    $list = db('setting')->where('class', 'article')->column('key');
				foreach ($list as $key => $value) {
					$rs = db('setting')->where('key', $value)->setField('value', input($value, ''));
					$rs === false && exception('');
				}
			} catch (\Exception $e) {
				db()->rollback();
				$this->error(lang('FAILED'));
			}
			db()->commit();
			$this->success(lang('SUCCESS'));
		}
	}

	/**
	 * 文章状态区分
	 */
	private function judge_status($item)
	{
		//系统保存
		if ($item['auto_hold'] == 1)
			return 3;

		// 草稿|私密
		if (in_array($item['status'], [0, 2]))
			return $item['status'];

		// 普通
		if (!empty($item['close_time']) && time() >= $item['close_time'])
			return 4; //已结束
		elseif (!empty($item['open_time']) && time() < $item['open_time'])
			return 5; //发布中
		else
			return 1; //已发布

	}


}