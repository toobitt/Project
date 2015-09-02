<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: grade.php 7586 2013-04-18 09:40:56Z yaojian $
***************************************************************************/
require_once './global.php';
include_once CUR_CONF_PATH . 'lib/grade.class.php';
define('MOD_UNIQUEID', 'grade');  //模块标识

class gradeApi extends adminReadBase
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
	
	public function index() {}
	
	/**
	 * 获取等级信息
	 */
	public function show()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 20;
		$condition = $this->filter_data();
		$data = array(
			'offset' => $offset,
			'count' => $count,
			'condition' => $condition
		);
		$grade_info = $this->grade->show($data);
		$this->setXmlNode('grade_info', 'grade');
		if ($grade_info) {
			foreach ($grade_info as $grade)
			{
				$this->addItem($grade);
			}
		}
		$this->output();
	}
	
	/**
	 * 获取等级总数
	 */
	public function count()
	{
		$condition = $this->filter_data();
		$info = $this->grade->count($condition);
		echo json_encode($info);
	}
	
	/**
	 * 获取单个等级信息
	 */
	public function detail()
	{
		$id = isset($this->input['id']) ? intval($this->input['id']) : 0;
		if ($id <= 0) $this->errorOutput(PARAM_WRONG);
		$grade_info = $this->grade->detail($id);
		$this->addItem($grade_info);
		$this->output();
	}
	
	/**
	 * 获取角色信息
	 */
	public function getAllRole()
	{
		$role_info = $this->grade->get_role();
		$this->setXmlNode('role_info', 'role');
		if ($role_info)
		{
			foreach ($role_info as $role)
			{
				$this->addItem($role);
			}
		}
		$this->output();
	}
	
	/**
	 * 过滤数据
	 */
	private function filter_data()
	{
		$name = isset($this->input['k']) ? trim(urldecode($this->input['k'])) : '';
		$role_id = isset($this->input['roleId']) ? intval($this->input['roleId']) : '';
		$data = array();
		if (!empty($name)) $data['keyword'] = $name;
		if ($role_id > -1) $data['role_id'] = $role_id;
		return $data;
	}
}

$out = new gradeApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>