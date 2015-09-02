<?php
define('MOD_UNIQUEID','cityQueue');
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require_once(ROOT_PATH.'global.php');
define('SCRIPT_NAME', 'city_plan');
require_once ROOT_PATH.'lib/class/curl.class.php';
class city_plan extends cronBase
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
			'name' => '天气城市队列',	 
			'brief' => '天气城市队列',
			'space' => '30',	//运行时间间隔，单位秒
			'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	public function show()
	{
		$offset = $queue['offset']?$queue['offset']:0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):3;
		$order = ' ORDER BY id ASC';
		$limit = " limit {$offset}, {$count}";
		$sql = 'SELECT * FROM '.DB_PREFIX.'weather_city_queue WHERE 1 '.$order.$limit;
		$query = $this->db->query($sql);
		$k = array();
		while ($row = $this->db->fetch_array($query))
		{
			$k[$row['id']] = $row['city_name'];
		}
		if (!empty($k))
		{
			foreach ($k as $key=>$val)
			{
				$configs = $this->settings['App_weather'];
				$this->curl = new curl($configs['host'], $configs['dir'].'admin/');
				$this->curl->setSubmitType('get');
				$this->curl->setReturnFormat('json');
				$this->curl->initPostData();
				$this->curl->addRequestData('a','create');
				$this->curl->addRequestData('name',$val);
				$this->curl->addRequestData('userInfo',$this->user);
				$re = $this->curl->request('city_node_update.php');
			}
			if($this->input['debug'])
			{
				echo "<pre>";
				print_r($re);
			}
			$ids = implode(',', array_keys($k));
			$ret = $this->delete_plan($ids);
			$this->addItem($ret);
		}
		$this->output();
			
	}
	private function delete_plan($ids)
	{
		if (empty($ids))
		{
			return false;
		}
		$sql = 'DELETE FROM '.DB_PREFIX.'weather_city_queue WHERE id IN ('.$ids.')';
		$this->db->query($sql);
		return $ids;
	}
	
}
include(ROOT_PATH . 'excute.php');