<?php
define('MOD_UNIQUEID','weatherCache');
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require_once(ROOT_PATH.'global.php');
define('SCRIPT_NAME', 'weatherCache');
require_once ROOT_PATH.'lib/class/curl.class.php';
class weatherCache extends cronBase
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
			'name' => '天气预报数据缓存',	 
			'brief' => '天气预报数据缓存',
			'space' => '30',	//运行时间间隔，单位秒
			'is_use' => 0,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	public function show()
	{
		//队列执行开始
		$sql = 'SELECT offset,locked FROM '.DB_PREFIX.'cache_queue where locked=1';
		$queue = $this->db->query_first($sql);
		if($queue['locked'])
		{
			$offset = $queue['offset']?$queue['offset']:0;
			$count = $this->input['count']?intval(urldecode($this->input['count'])):3;
			$order = ' ORDER BY id ASC';
			$limit = " limit {$offset}, {$count}";
			$sql = 'SELECT cq.*,c.code FROM '.DB_PREFIX.'weather_city cq LEFT JOIN '.DB_PREFIX.'weather_city_code c ON c.name=cq.name  WHERE 1 '.$order.$limit;
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$city_code[$row['name']] = $row['code'];
				$city[] = $row['name'];
			}
			$configs = $this->settings['App_weather'];
			if($configs)
			{
				$this->curl = new curl($configs['host'], $configs['dir']);
				$this->curl->setSubmitType('get');
				$this->curl->setReturnFormat('json');
				$this->curl->initPostData();
				$this->curl->addRequestData('a','show');
				$this->curl->addRequestData('userInfo',$this->user);
				if($city)
				{
					$this->update_queue($offset+$count);
					foreach ($city as $name)
					{
						$this->curl->addRequestData('name',$name);
						$re = $this->curl->request('weather.php');
						$this->addItem($re);
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
	}
	
	public function reset_queue()
	{
		$this->db->query('UPDATE '.DB_PREFIX.'cache_queue SET offset = 0, locked=0');
	}
	public function update_queue($offset=0)
	{
		//锁定队列准备开始执行
		$this->db->query('UPDATE '.DB_PREFIX.'cache_queue SET offset = '.intval($offset));
	}
	
}
include(ROOT_PATH . 'excute.php');