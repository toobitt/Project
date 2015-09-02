<?php
/***************************************************************************
* $Id: channel_chg_plan_create.php 
***************************************************************************/
define('MOD_UNIQUEID','callback');
require('global.php');
class callbackApi extends cronBase
{
	private $mBackup;
	function __construct()
	{
		parent::__construct();
		
		require_once CUR_CONF_PATH . 'lib/backup.class.php';
		$this->mBackup = new backup();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function initcron()
	{
	}
	
	/**
	 * 提交备播文件流媒体执行的回调函数
	 * $id 备播文件id
	 * Enter description here ...
	 */
	public function backup_callback()
	{
		$token = $this->input['token'];
		if (!$token)
		{
			$this->errorOutput('未传入备播文件id');
		}
		
		$sql = 'UPDATE ' . DB_PREFIX . "stream_info SET update_time=" . TIMENOW . ", state=" . intval($this->input['result']) . " WHERE token='$token'";
		$this->db->query($sql);
		
		$this->addItem($token);
		$this->output();
	}
	
	function unknow()
	{
		$this->errorOutput('未定义的空方法');
	}
	protected function verifyToken()
	{
	}
	
}
$out = new callbackApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>