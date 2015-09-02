<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: user.php 7586 2013-04-19 09:40:56Z yaojian $
***************************************************************************/
require_once './global.php';
include_once ROOT_PATH . 'lib/class/members.class.php';
define('MOD_UNIQUEID', 'members');  //模块标识

class membersApi extends appCommonFrm
{
	private $members;
	
	public function __construct()
	{
		parent::__construct();
		$this->members = new members();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$data = array();
		$condition = $this->get_condition();
		$res = $this->members->get_members(0,'show','',$condition,true);
		if ($res && is_array($res) && !empty($res))
		{
			foreach ($res as $val)
			{
				$filter = array(
					'id'			=> $val['member_id'],
					'name'			=> $val['member_name'],
					'type'			=> $val['type'],
					'type_name'		=> $val['type_name'],
					'avater'		=> is_array($val['avatar']) ? $val['avatar'] : array(),
					'create_time'	=> strtotime($val['create_time']),
				);
				$this->addItem($filter);
			}
		}else {
			$this->addItem($data);
		}
		$this->output();
	}
	
	private function get_condition()
	{
		$condition = array();
		$application_id = intval($this->input['application_id']);
		if ($application_id)
		{
			$condition['identifier'] = $application_id;
		}else {
			$condition['identifier'] = '0';
		}
		if (trim($this->input['type']))
		{
			$condition['type']	= trim($this->input['type']);
		}
		return $condition;
	}
	
	public function count()
	{

	}
	
	
	
}

$out = new membersApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>