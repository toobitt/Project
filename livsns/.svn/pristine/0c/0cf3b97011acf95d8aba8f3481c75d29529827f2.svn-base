<?php
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require_once(ROOT_PATH . 'global.php');
define('MOD_UNIQUEID', 'cdn_logs'); //模块标识
class logsApi extends cronBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '清除日志',	 
			'brief' => '清除日志',
			'space' => '86400',	//运行时间间隔，单位秒
			'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	//删除推送日志
	public function delete_by_date()
	{
		if(defined('DELETE_DATA') && DELETE_DATA )
		{
			$days = DELETE_DATA;
			$date =  TIMENOW - $days*24*60*60;
			$sql = "DELETE FROM " . DB_PREFIX . "cdn_log  WHERE create_time <= ".$date;
			$this->db->query($sql);	
			
			$this->addItem(DELETE_DATA);
			$this->output();
		}
	}
}

$out = new logsApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'delete_by_date';
}
$out->$action();

?>
