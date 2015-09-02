<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: group.php 8313 2012-07-24 06:47:25Z daixin $
***************************************************************************/
define('MOD_UNIQUEID','cp_group_m');//模块标识
require('./global.php');
require('../lib/group.class.php');

class groupApi extends BaseFrm
{
	var $group;
	
	public function __construct()
	{
		parent::__construct();
		$this->group = new group();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	//获取所有圈子数据
	public function show()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 20;
		$data = $this->get_con();
		$groups = $this->group->show($offset, $count, $data);
		$this->setXmlNode('group_info', 'group');
		foreach($groups as $group)
		{
			$this->addItem($group);
		}
		$this->output();
	}
	
	//获取圈子总数
	public function count()
	{
		$data = $this->get_con();
		$info = $this->group->count($data);
		echo json_encode($info);
	}
	
	private function get_con()
	{
		return array(
			'key' => trim(urldecode($this->input['k'])),
			'start_time' => strtotime(trim(urldecode($this->input['start_time']))),
			'end_time' => strtotime(trim(urldecode($this->input['end_time']))),
			'date_search' => $this->input['date_search'],
			'state' => $this->input['state'],
			'group_type' => $this->input['group_type'],
			'fatherid' => $this->input['fatherid'],
			'hgupdn' => trim(urldecode($this->input['hgupdn'])),
			'hgorder' => trim(urldecode($this->input['hgorder'])),
			'_type' => $this->input['_type'],
		);
	}
	
	//获取单个圈子信息
	public function detail()
	{
		if (isset($this->input['id']))
		{
			$group_id = intval($this->input['id']);
		}
		elseif (isset($this->input['group_id']))
		{
			$group_id = intval($this->input['group_id']);
		}
		else
		{
			$group_id = -1;
		}
		if ($group_id < 0) $this->errorOutput(PARAM_WRONG);
		$group = $this->group->detail($group_id, true);
		include_once ROOT_PATH . 'lib/class/mark.class.php';
		$mark = new mark();
		$tags = $mark->getInfoByType($group_id, 1);
		$group['tag'] = $tags;
		$this->setXmlNode('group_info', 'group');
		$this->addItem($group);
		$this->output();
	}
	
	//获取上级圈子
	public function father_group()
	{
		$groups = $this->group->top_group();
		$this->setXmlNode('group_info', 'group');
		foreach($groups as $group)
		{
			$this->addItem($group);
		}
		$this->output();
	}
	
	//获取所有圈子的类型
	public function group_type()
	{
		$types = $this->group->group_type();
		$this->setXmlNode('group_info', 'group');
		foreach($types as $type)
		{
			$this->addItem($type);
		}
		$this->output();
	}
}

/**
 *  程序入口
 */
$out = new groupApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>



