<?php
define('MOD_UNIQUEID','insert_bicycle_station');
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require_once(ROOT_PATH.'global.php');
require_once CUR_CONF_PATH.'lib/functions.php';
define('SCRIPT_NAME', 'insert_station_plan');
class insert_station_plan extends cronBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		//遍历运营单位取api
		$sql = "SELECT * FROM " .DB_PREFIX. "company";
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			if(!$r['api_url'])
			{
				continue;
			}
			
			$station = file_get_contents($r['api_url']);
			$station = str_replace('var ibike = ','',$station);
			$station_arr = json_decode($station,1);
			if(!$station_arr || !$station_arr['station'])
			{
				continue;
			}

			$user_name 	= $this->user['user_name'];
			$appid		= $this->user['appid'];
			$appname	= $this->user['display_name'];
			foreach($station_arr['station'] AS $k => $v)
			{
				$data = array(
					'name' 				=> $v['name'],
					'station_id' 		=> $v['id'],
					'address' 			=> $v['address'],
					'baidu_latitude' 	=> $v['lat'],
					'baidu_longitude' 	=> $v['lng'],
					'company_id'		=> $r['id'],
					'create_time'		=> TIMENOW,
					'user_name'			=> $user_name,
					'appid'				=> $appid,
					'appname'			=> $appname,
				);
				
				$sql = " INSERT INTO " . DB_PREFIX . "station SET ";
				foreach ($data AS $k => $v)
				{
					$sql .= " {$k} = '{$v}',";
				}
				$sql = trim($sql,',');
				$this->db->query($sql);
				$vid = $this->db->insert_id();
				$sql = " UPDATE ".DB_PREFIX."station SET order_id = {$vid}  WHERE id = {$vid}";
				$this->db->query($sql);
			}
		}
		$this->addItem('success');
		$this->output();
	}
	
	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	
			'name' => '自行车站点数据插入',	 
			'brief' => '插入站点可借车数',
			'space' => '60',	//运行时间间隔，单位秒
			'is_use' => 0,		//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
}
include(ROOT_PATH . 'excute.php');