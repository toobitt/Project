<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: trade_update.php 4728 2013-04-15 10:38:02Z yaojian $
***************************************************************************/
require_once './global.php';
include_once CUR_CONF_PATH . 'lib/trade.class.php';
define('MOD_UNIQUEID', 'trade');  //模块标识

class tradeUpdateApi extends adminUpdateBase
{
	private $trade;
	
	public function __construct()
	{
		parent::__construct();
		$this->trade = new trade();
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->trade);
	}
	
	/**
	 * 创建行业信息
	 */
	public function create()
	{
		//获取提交数据
		$data = $this->filter_data();
		//验证名称是否重复
		$checkResult = $this->trade->verify(array('name' => $data['name'], 'pid' => $data['pid']));
		if ($checkResult) $this->errorOutput(NAME_EXISTS);
		if ($data['pid'] === 0)
		{
			$data['path'] = '0';
		}
		else
		{
			$parent_info = $this->trade->detail($data['pid']);
			if (!$parent_info) $this->errorOutput(PARAM_WRONG);
			$data['path'] = $parent_info['path'] . '-' . $parent_info['id'];
			
			//获取角色名称
			$role_info = $this->get_role_name($data['role_id']);
			if (!$role_info) $this->errorOutput(PARAM_WRONG);
			$data['role_name'] = $role_info['role']['name'];
		}
		$result = $this->trade->create('trade', $data);
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 修改行业信息
	 */
	public function update()
	{
		$id = isset($this->input['id']) ? intval($this->input['id']) : 0;
		if ($id <= 0) $this->errorOutput(PARAM_WRONG);
		$info = $this->trade->detail($id);
		if (!$info) $this->errorOutput(PARAM_WRONG);
		$data = $this->filter_data();
		$validate = array();
		if ($data['pid'] != $info['pid'] || $data['name'] != $info['name'])
		{
			if ($data['pid'] != $info['pid'])
			{
				$pid = intval($data['pid']);
				if ($pid > 0)
				{
					//验证是否有这个上级
					$parent = $this->trade->detail($pid);
					if (!$parent) $this->errorOutput(PARAM_WRONG);
					$validate['path'] = $parent['path'] . '-' . $parent['id'];
				}
				elseif ($pid === 0)
				{
					$validate['path'] = '0';	
				}
				$validate['pid'] = $pid;
			}
			else
			{
				$pid = intval($info['pid']);
			}
			if ($data['name'] != $info['name'])
			{
				$name = $data['name'];
				$validate['name'] = $name;
			}
			else
			{
				$name = $info['name'];
			}
			//验证名称是否重复
			$checkResult = $this->trade->verify(array('name' => $name, 'pid' => $pid));
			if ($checkResult) $this->errorOutput(NAME_EXISTS);
		}
		//if ($data['role_id'] != $info['role_id'])
		//{
			$validate['role_id'] = intval($data['role_id']);
			//获取角色名称
			$role_info = $this->get_role_name($validate['role_id']);
			if (!$role_info) $this->errorOutput(PARAM_WRONG);
			$validate['role_name'] = $role_info['role']['name'];
		//}
		if ($validate)
		{
			$result = $this->trade->update('trade', $validate, array('id' => $id));
		}
		else
		{
			$result = true;
		}
		$this->addItem($result);
		$this->output();
	}

	/**
	 * 删除行业信息
	 */
	public function delete()
	{
		$ids = isset($this->input['id']) ? trim(urldecode($this->input['id'])) : '';
		if (empty($ids)) $this->errorOutput(PARAM_WRONG);
		$ids_arr = explode(',', $ids);
		$ids_arr = array_filter($ids_arr);
		if (!$ids_arr) $this->errorOutput(PARAM_WRONG);
		if (count($ids_arr) == 1)
		{
			$ids = intval(current($ids_arr));
		}
		else
		{
			$ids = implode(',', $ids_arr);
		}
		/*
		$info = $this->trade->show(array(
			'count' => -1,
			'condition' => array(
				'pid' => 0,
				'id' => $ids
			)
		));
		if ($info)
		{
			$hasSub = array();
			foreach ($info as $val)
			{
				$hasSub[$val['id']] = $val['id'];
			}
			if (count($hasSub) == 1)
			{
				$hasSub = intval(current($hasSub));
			}
			else
			{
				$hasSub = implode(',', $hasSub);
			}
			$this->trade->delete('trade', array('pid' => $hasSub));
		}
		*/
		$result = $this->trade->delete('trade', array('id' => $ids));
		$this->addItem($result);
		$this->output();
	}
	
	//获取角色名称
	private function get_role_name($role_id)
	{
		include_once ROOT_PATH . 'lib/class/auth.class.php';
		$auth = new Auth();
		return $auth->get_role($role_id);
	}
	
	/**
	 * 方法不存在的时候调用的方法
	 */
	public function none()
	{
		$this->errorOutput('调用的方法不存在');
	}
	
	/**
	 * 处理提交的数据
	 */
	private function filter_data()
	{
		$name = isset($this->input['name']) ? trim(urldecode($this->input['name'])) : '';
		$pid = isset($this->input['pid']) ? intval($this->input['pid']) : 0;
		$roleId = isset($this->input['roleId']) ? intval($this->input['roleId']) : 0;
		if (empty($name) || $pid < 0 || $roleId < 0) $this->errorOutput(PARAM_WRONG);
		if ($pid === 0) $roleId = 0;
		$data = array(
			'name' => $name,
			'role_id' => $roleId,
			'pid' => $pid
		);
		return $data;
	}
	
	public function audit() {}
	public function sort() {}
	public function publish() {}
}

$out = new tradeUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'none';
}
$out->$action();
?>