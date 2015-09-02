<?php
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require_once(ROOT_PATH.'global.php');
define(MOD_UNIQUEID,'clearData');
class clearData extends cronBase
{
	function __construct()
	{
		parent::__construct();
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	public function initcron()
	{
		$array = array(
				'mod_uniqueid' => MOD_UNIQUEID,
				'name' => '清理路况队列',
				'brief' => '清理路况数据队列',
				'space' => '86400',	//运行时间间隔，单位秒
				'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	function clear()
	{
		$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."road";
		$total_num = $this->db->query_first($sql);
		$limit_num = $total_num['total'] - MAX_RECORD_NUM;
		if($limit_num > 0)
		{
			$sql = "DELETE FROM ".DB_PREFIX."road WHERE create_time+effect_time*60 <".TIMENOW." ORDER BY create_time ASC LIMIT " . $limit_num;
			$this->db->query($sql);
		}
		echo '清理完成';
		exit(); 	
	}
}
$out = new clearData();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'clear';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>
