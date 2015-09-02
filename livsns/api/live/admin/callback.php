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
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入备播文件id');
		}
		
		$update_data = array(
			'id'		=> $id,
			'status'	=> !$this->input['result'] ? 2 : 1,
		);
		
		$info = $this->mBackup->update($update_data);
		
		$backup_info = $this->mBackup->get_backup_by_id($id);
		
		if ($backup_info['url'] && $backup_info['type'] == 2)
		{
			$path = DATA_DIR . $backup_info['name'];
			if (file_exists($path))
			{
				@unlink($path);
			}
		}
		
		$this->addItem($info);
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