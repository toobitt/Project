<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: grade_update.php 4728 2013-04-18 10:38:02Z yaojian $
***************************************************************************/
require_once './global.php';
include_once CUR_CONF_PATH . 'lib/grade.class.php';
define('MOD_UNIQUEID', 'grade');  //模块标识

class gradeUpdateApi extends adminUpdateBase
{
	private $grade;
	
	public function __construct()
	{
		parent::__construct();
		$this->grade = new grade();
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->grade);
	}
	
	/**
	 * 创建等级信息
	 */
	public function create()
	{
		//获取提交数据
		$data = $this->filter_data();
		//验证名称是否重复
		$checkResult = $this->grade->verify(array('name' => $data['name']));
		if ($checkResult) $this->errorOutput(NAME_EXISTS);
		//获取角色名称
		$role_info = $this->get_role_name($data['role_id']);
		if (!$role_info) $this->errorOutput(PARAM_WRONG);
		$data['role_name'] = $role_info['role']['name'];
		$result = $this->grade->create('grade', $data);
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 修改等级信息
	 */
	public function update()
	{
		$id = isset($this->input['id']) ? intval($this->input['id']) : 0;
		if ($id <= 0) $this->errorOutput(PARAM_WRONG);
		$info = $this->grade->detail($id);
		if (!$info) $this->errorOutput(PARAM_WRONG);
		$data = $this->filter_data();
		$validate = array();
		if ($data['name'] != $info['name'])
		{
			//验证名称是否重复
			$checkResult = $this->grade->verify(array('name' => $data['name']));
			if ($checkResult) $this->errorOutput(NAME_EXISTS);
			$validate['name'] = $data['name'];
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
			$result = $this->grade->update('grade', $validate, array('id' => $id));
		}
		else
		{
			$result = true;
		}
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 删除等级信息
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
		$result = $this->grade->delete('grade', array('id' => $ids));
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
		$roleId = isset($this->input['roleId']) ? intval($this->input['roleId']) : 0;
		if (empty($name) || $roleId < 0) $this->errorOutput(PARAM_WRONG);
		$data = array(
			'name' => $name,
			'role_id' => $roleId
		);
		return $data;
	}
	
	public function audit() {}
	public function sort() {}
	public function publish() {}
}

$out = new gradeUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'none';
}
$out->$action();
?>