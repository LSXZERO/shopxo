<?php

namespace app\admin\controller;

/**
 * 生活号分类管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class AlipayLifeCategory extends Common
{
	/**
	 * 构造方法
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-03T12:39:08+0800
	 */
	public function __construct()
	{
		// 调用父类前置方法
		parent::__construct();

		// 登录校验
		$this->Is_Login();

		// 权限校验
		$this->Is_Power();
	}

	/**
     * [Index 生活号分类列表]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
	public function Index()
	{
		$this->assign('common_is_enable_list', lang('common_is_enable_list'));
		$this->display('Index');
	}

	/**
	 * [GetNodeSon 获取节点子列表]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-25T15:19:45+0800
	 */
	public function GetNodeSon()
	{
		// 是否ajax请求
		if(!IS_AJAX)
		{
			$this->error(lang('common_unauthorized_access'));
		}

		// 获取数据
		$field = array('id', 'name', 'sort', 'is_enable');
		$data = db('AlipayLifeCategory')->field($field)->where(array('pid'=>intval(I('id', 0))))->select();
		if(!empty($data))
		{
			foreach($data as $k=>$v)
			{
				$data[$k]['is_son']		=	$this->IsExistSon($v['id']);
				$data[$k]['ajax_url']	=	url('Admin/AlipayLifeCategory/GetNodeSon', array('id'=>$v['id']));
				$data[$k]['delete_url']	=	url('Admin/AlipayLifeCategory/Delete');
				$data[$k]['json']		=	json_encode($v);
			}
		}
		$msg = empty($data) ? lang('common_not_data_tips') : lang('common_operation_success');
		$this->ajaxReturn($msg, 0, $data);
	}

	/**
	 * [IsExistSon 节点是否存在子数据]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-25T15:22:47+0800
	 * @param    [int]    $id [节点id]
	 * @return   [string]     [有数据ok, 则no]
	 */
	private function IsExistSon($id)
	{
		if(!empty($id))
		{
			return (db('AlipayLifeCategory')->where(array('pid'=>$id))->count() > 0) ? 'ok' : 'no';
		}
		return 'no';
	}

	/**
	 * [Save 生活号分类保存]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-25T22:36:12+0800
	 */
	public function Save()
	{
		// 是否ajax请求
		if(!IS_AJAX)
		{
			$this->error(lang('common_unauthorized_access'));
		}

		// id为空则表示是新增
		$m = D('AlipayLifeCategory');

		// 公共额外数据处理
		$m->sort 	=	intval(I('sort'));

		// 添加
		if(empty($_POST['id']))
		{
			if($m->create($_POST, 1))
			{
				// 额外数据处理
				$m->add_time	=	time();
				$m->name 		=	I('name');
				
				// 写入数据库
				if($m->add())
				{
					$this->ajaxReturn(lang('common_operation_add_success'));
				} else {
					$this->ajaxReturn(lang('common_operation_add_error'), -100);
				}
			}
		} else {
			// 编辑
			if($m->create($_POST, 2))
			{
				// 额外数据处理
				$m->name 		=	I('name');
				$m->upd_time	=	time();

				// 移除 id
				unset($m->id);

				// 更新数据库
				if($m->where(array('id'=>I('id')))->save())
				{
					$this->ajaxReturn(lang('common_operation_edit_success'));
				} else {
					$this->ajaxReturn(lang('common_operation_edit_error'), -100);
				}
			}
		}
		$this->ajaxReturn($m->getError(), -1);
	}

	/**
	 * [Delete 生活号分类删除]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-25T22:36:12+0800
	 */
	public function Delete()
	{
		if(!IS_AJAX)
		{
			$this->error(lang('common_unauthorized_access'));
		}

		$m = D('AlipayLifeCategory');
		if($m->create($_POST, 5))
		{
			if($m->delete(I('id')))
			{
				$this->ajaxReturn(lang('common_operation_delete_success'));
			} else {
				$this->ajaxReturn(lang('common_operation_delete_error'), -100);
			}
		} else {
			$this->ajaxReturn($m->getError(), -1);
		}
	}
}
?>