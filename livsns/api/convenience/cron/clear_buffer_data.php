<?php
require('global.php');
define('MOD_UNIQUEID','clear_buffer_data');//模块标识
class clear_train_cache extends cronBase
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function run()
	{
		$sql = 'SELECT `key` FROM ' . DB_PREFIX . 'buffer_data WHERE create_time < ' . intval(TIMENOW - EXPIRE_TIME);
		$query = $this->db->query($sql);
		$delete = array();
		while($row = $this->db->fetch_array($query))
		{
			$delete[]  = $row['key'];
		}
		if($delete)
		{
			$sql = 'DELETE FROM ' . DB_PREFIX . 'buffer_data WHERE `key` IN("'.implode('","', $delete).'")';
			$this->db->query($sql);
		}
		$this->addItem($delete);
		$this->output();
	}
	
	public function initcron()
	{
		$array = array(
			'mod_uniqueid' 	=> MOD_UNIQUEID,	 
			'name' 			=> '定时清理缓冲数据',	 
			'brief' 		=> '清理buffer表中的过期数据，过期时间设定常量EXPIRE_TIME',
			'space' 		=> '3600',//运行时间间隔，单位秒
			'is_use' 		=> 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
}
$out = new clear_train_cache();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'run';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();
?>