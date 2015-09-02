<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: group_apply.php 4658 2011-10-10 01:35:46Z repheal $
***************************************************************************/
define('MOD_UNIQUEID','cp_group_m');//模块标识
require('./global.php');
require('../lib/group.class.php');

class groupApplyShowApi extends BaseFrm
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
	
	//获取所有申请圈子数据
	public function show()
	{
		$page = isset($this->input['pp']) ? intval($this->input['pp']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 20;
		$groups = $this->group->show_apply($page, $count);
		$this->setXmlNode('group_info' , 'group');
		$this->addItem($groups);
		$this->output();
	}
	
	//获取申请圈子总数
	public function count()
	{
		$info = $this->group->count_apply();
		echo json_encode($info);
	}
	
	/**
	 * 审核申请地主
	 */
	public function check()
	{
		$group_id = isset($this->input['group_id']) ? intval($this->input['group_id']) : -1;
		$user_id = isset($this->input['user_id']) ? intval($this->input['user_id']) : -1;
		$type = isset($this->input['type']) ? intval($this->input['type']) : -1;
		if ($group_id < 0 || $user_id < 0 || $type < 0) $this->errorOutput(PARAM_WRONG);
		$info = $this->group->check_creater($group_id, $user_id, $type);
		$this->addItem($info);
		$this->output();
	}
}

/**
 *  程序入口
 */
$out = new groupApplyShowApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>



	