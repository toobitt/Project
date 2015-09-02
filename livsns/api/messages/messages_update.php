<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:  $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class messagesUpdateApi extends BaseFrm
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	public function delete_message()
	{
		if(!$this->user['user_id'])
		{
			$this->errorOutput(NEED_LOGIN); //用户未登录
		}
		$pid = $this->input['pid'] ? $this->input['pid'] : 0;
		if(empty($pid))
		{
			$this->errorOutput('未传入私信ID'); 
		}
		$sql = "DELETE FROM " . DB_PREFIX . "pm WHERE pid=" . $pid;
		$this->db->query($sql);
		$this->addItem($pid);
		$this->output();
	}
	
	public function delete_session()
	{
		if(!$this->user['user_id'])
		{
			$this->errorOutput(NEED_LOGIN); //用户未登录
		}
		$sid = $this->input['sid'] ? $this->input['sid'] : 0;
		if(empty($sid))
		{
			$this->errorOutput('未传入会话ID'); 
		}
		$sql = "DELETE FROM " . DB_PREFIX . "pm WHERE sid=" . $sid;
		$this->db->query($sql);
		$sql = "DELETE FROM " . DB_PREFIX . "pm_session WHERE sid=" . $sid;
		$this->db->query($sql);
		$sql = "DELETE FROM " . DB_PREFIX . "pm_user WHERE sid=" . $sid;
		$this->db->query($sql);
		$sql = "DELETE FROM " . DB_PREFIX . "s_pm WHERE sid=" . $sid;
		$this->db->query($sql);
		$this->addItem($sid);
		$this->output();
	}
}

$out = new messagesUpdateApi();
$action = $_INPUT['a'];

if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>