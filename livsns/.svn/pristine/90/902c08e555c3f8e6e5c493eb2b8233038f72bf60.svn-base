<?php

define('MOD_UNIQUEID','update_device');
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require(ROOT_PATH."global.php");
define('SCRIPT_NAME', 'updateDevice');
class updateDevice extends cronBase
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
			'name' => '更新设备',	 
			'brief' => '存在相同设备，根据配置更新',
			'space' => '2',	//运行时间间隔，单位秒
			'is_use' => 0,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	public function show()
	{
		$sql = "SELECT * FROM ".DB_PREFIX."certificate ";
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			if($r['link_appid'] && $r['appid'] != $r['link_appid'])
			{
				$data[$r['appid']] = $r['link_appid'];
				$link_appid[] = $r['link_appid'];
			}
		}
		if(empty($link_appid))
		{
			return false;
		}
		foreach ($data as $k => $v)
		{
			$sql = '';
			$device_token_arr = array();
			$sql = "SELECT appid,device_token,count(*) FROM ".DB_PREFIX."device WHERE appid IN (".$k.','.$v.") AND state = 1 GROUP BY device_token HAVING COUNT(device_token) > 1";
			$q = $this->db->query($sql);
			while ($r = $this->db->fetch_array($q))
			{
				$device_token_arr[] = "'".$r['device_token']."'";
			}
			
			$device_token_str = '';
			if($device_token_arr)
			{
				$device_token_str = implode(',', $device_token_arr);
			}
			if($device_token_str)
			{
				$sql = "UPDATE ".DB_PREFIX."device SET state = 3 WHERE appid = ".$v." AND device_token IN (".$device_token_str.")";
				$this->db->query($sql);
			}
		}
		$this->addItem('success');
		$this->output();
	}
}

include(ROOT_PATH . 'excute.php');
?>