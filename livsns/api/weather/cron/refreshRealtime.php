<?php
define('MOD_UNIQUEID','refreshRealtime');
define('SCRIPT_NAME', 'refreshWeather');
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require_once(ROOT_PATH.'global.php');
require_once CUR_CONF_PATH.'lib/city.php';
require_once CUR_CONF_PATH.'core/get_weatherInfo.php';
include_once CUR_CONF_PATH.'core/realtime.class.php';
class refreshWeather extends cronBase
{
	public function __construct()
	{
		parent::__construct();
		$this->city = new city();
		$this->weatherInfo = new get_weatherInfo();
		$this->realtimeCore = new realtime();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '获取实时天气',	 
			'brief' => '获取实时天气',
			'space' => '10',	//运行时间间隔，单位秒
			'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	public function show()
	{
		//队列执行开始
		$sql = 'SELECT offset,locked FROM '.DB_PREFIX.'weather_realtime_refresh_queue where locked=1';
		$queue = $this->db->query_first($sql);
		if($queue['locked'])
		{
			$offset = $queue['offset']?$queue['offset']:0;
			$count = $this->input['count']?intval(urldecode($this->input['count'])):3;
			$limit = " limit {$offset}, {$count}";
			$condition = '';
			$group_city = array();
			$inner_func = array();
			$cities = $this->city->show($condition, '', $limit);
			if(!empty($cities))
			{
				$this->update_queue($offset+$count);
				foreach ($cities as $city_id=>$row)
				{
					$group_city[$row['source_id']][] = $row['id'];
					$inner_func[$row['source_id']]=$row['inner_func'];
				}
				if($group_city)
				{
					foreach ($group_city as $source_id=>$city_array_id)
					{
						$weather_api_func = $inner_func[$source_id] . '_update';
						//实时天气
						$this->realtimeCore->$weather_api_func($city_array_id, $source_id, $this->user);				
						$this->addItem(true);
					}
				}			
			}
			else
			{
				$this->reset_queue();
				$this->addItem(QUEUE_HAS_FINISHED);
			}
		}
		$this->output();
	}
	public function reset_queue()
	{
		$this->db->query('UPDATE '.DB_PREFIX.'weather_realtime_refresh_queue SET offset = 0, locked=0');
	}
	public function update_queue($offset=0)
	{
		//锁定队列准备开始执行
		$this->db->query('UPDATE '.DB_PREFIX.'weather_realtime_refresh_queue SET offset = '.intval($offset));
	}
}
include(ROOT_PATH . 'excute.php');