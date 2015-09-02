<?php
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require(ROOT_PATH.'global.php');
define('MOD_UNIQUEID','logs');
class logsApi extends cronBase
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/logs.class.php');
		$this->obj = new logs();
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
	
	public function delete_by_date()
	{
		//删除日志
		if(defined('DELETE_DATE') && DELETE_DATE )
		{
			$date = strtotime(DELETE_DATE);
			$sql = "DELETE FROM " . DB_PREFIX . "system_log  WHERE create_time <= ".$date;
			$this->db->query($sql);	
			
			$sql = "DELETE FROM " . DB_PREFIX . "system_log_content  WHERE  create_time <= ".$date;
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
