<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:  $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
 
class updateLastApi extends BaseFrm
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	//更新 某条消息的最后阅读时间
	public function update_ltime()
	{
		if(!$this->user['user_id'])
		{
			$this->errorOutput(NEED_LOGIN);//用户未登录
		}
		
		$sessionID = urldecode($this->input['sessionid']);
		$pid = urldecode($this->input['ids']);
		
		if(!$sessionID || !$pid)
		{
			$this->errorOutput(PARAM_NO_FULL);
		}
		$time = urldecode($this->input['rtime']);
		$sql = 'select * from ' . DB_PREFIX . 'pm pm left join ' . DB_PREFIX . 's_pm s_pm on pm.sid = s_pm.sid where s_pm.sessionId="' . $sessionID . '"';
		$query = $this->db->query_first($sql);
		if(!$query)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$update_sql = 'update ' . DB_PREFIX . 'pm_user set rtime = "' . $time . '",new = 0 where uid=' . $this->user['user_id'] . ' and pid in(' . $pid . ')';
		$this->db->query($update_sql);
		$update_sql = 'update ' . DB_PREFIX . 'pm set rtime = "' . $time . '" where pid in(' . $pid . ')';
		$this->db->query($update_sql);
 		$this->setXmlNode("RTime","RTime");
		$this->addItem('');
		$this->output();
	}
}
$updateLastApi = new updateLastApi();
$updateLastApi->update_ltime();
?>