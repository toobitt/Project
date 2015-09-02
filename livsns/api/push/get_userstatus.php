<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: get_userstatus.php 1217 2010-12-25 03:26:25Z wangxin $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class get_userstatus extends BaseFrm
{	
	private $user;
	function __construct()
	{
		parent::__construct();
		include_once(ROOT_DIR . 'lib/user/user.class.php');
		$this->user = new user();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function getfirst()
	{
		$user_id = intval($this->input['user_id']);
		$sql = "SELECT status_id FROM ".DB_PREFIX."status_push WHERE member_id = $user_id ORDER BY status_id ASC";
		$result = $this->db->query_first($sql);		
		echo intval($result['status_id']);
	}

	public function getuserinbox()
	{
		$user_id = intval($this->input['user_id']);
		$count = intval($this->input['count']);
		$page = intval($this->input['page']);
		$gettotal = trim($this->input['gettotal']);
		$offset = $page * $count;
		//取总数或取数据
		if($gettotal == 'gettotal')
		{
			
			$sql = "SELECT count(status_id) as total FROM ".DB_PREFIX."status_push WHERE member_id = $user_id ";
		}
		else 
		{
			$sql = "SELECT status_id FROM ".DB_PREFIX."status_push WHERE member_id = $user_id ORDER BY status_id DESC limit $offset , $count";
		}
		$result = $this->db->query($sql);			
		while($row = $this->db->fetch_array($result))
		{		
			$this->addItem($row);	
		}
		return $this->output();	
	}
	public function delete()
	{
		$statusid =$this->input['statusid'];
		$sql = "delete FROM ".DB_PREFIX."status_push where status_id in (".$statusid.")";
		$row = $this->db->query($sql);				
		$this->addItem($row);	
		return $this->output();	
	}
}

$out = new get_userstatus();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>