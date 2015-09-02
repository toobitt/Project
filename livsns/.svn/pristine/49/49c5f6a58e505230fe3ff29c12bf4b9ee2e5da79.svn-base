<?php
define('MOD_UNIQUEID','city');
define('SCRIPT_NAME', 'cityapi');
define('ROOT_DIR', '../../');
require_once(ROOT_DIR.'global.php');
require_once(CUR_CONF_PATH . 'lib/fetch_city.php');
require_once(CUR_CONF_PATH . 'lib/functions.php');
class cityapi extends adminBase
{
	//是否需要更新本地缓存数据
	private $mUpdateCache;
	
	//
	private $fetch_city_object;
	
	function __construct()
	{
		parent::__construct();
		$this->mUpdateCache = $this->input['update_cache'] ? true : false;
		$this->init_fetch_env();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	protected function init_fetch_env()
	{
		$this->fetch_city_object = new fetchCity();
	}
	//默认方法获取城市aqi平均值
	public function show()
	{
		$city_type = $this->input['city_type'];
		if(!$city_type)
		{
			$this->errorOutput(CITY_TYPE_ERROR);
		}
		switch($city_type)
		{
			case 'trains':
				{
					$this->get_trains_city();
					break;
				}
			case 'airports':
				{
					$this->get_airports_city();
					break;
				}
			default:
				{
					$this->errorOutput(CITY_TYPE_ERROR);
				}
		}
	}
	public function get_trains_city()
	{
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'trains_city WHERE 1 ';
		$orderby = ' ORDER BY first_letter ASC';
		$query = $this->db->query($sql . $this->get_conditions() . $orderby);
		while($row = $this->db->fetch_array($query))
		{
			$cities[] = $row;
		}
		if(!$cities)
		{
			$cities = $this->fetch_city_object->fetch_trains_city();
		}
		if(!$cities)
		{
			$this->errorOutput(GET_TRAINS_CITY_ERROR);
		}
		foreach($cities as $city)
		{
			$this->addItem($city);
		}
		$this->output();
	}
	public function get_airports_city()
	{
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'airports_city WHERE 1 ';
		$orderby = ' ORDER BY flag DESC';
		$query = $this->db->query($sql . $this->get_conditions() . $orderby);
		while($row = $this->db->fetch_array($query))
		{
			$cities[] = $row;
		}
		if(!$cities)
		{
			$cities = $this->fetch_city_object->fetch_airports_city();
		}
		if(!$cities)
		{
			$this->errorOutput(GET_AIRPORTS_CITY_ERROR);
		}
		foreach($cities as $city)
		{
			$this->addItem($city);
		}
		$this->output();
	}
	public function get_started_city()
	{
		$type = $this->input['type'] ? $this->input['type'] : 'train'; //默认
		if(!in_array($type, array('train', 'airport')))
		{
			$type = 'train';
		}
		$city_name = $this->input['city_name'];
		switch($type)
		{
			case 'train' : $table = 'trains_city';break;
			case 'airport' : $table = 'airports_city';break;
		}
		$sql = 'SELECT * FROM ' . DB_PREFIX . $table . ' WHERE city_name="'.$city_name.'"';
		$citydata = $this->db->query_first($sql);
		$this->addItem($citydata);
		$this->output();
	}
	protected function get_conditions()
	{
		$conditions = '';
		if($keywords = $this->input['keywords'])
		{
			$conditions .= ' AND concat(city_name, city_name_en, city_name_jp) like "%'.$keywords.'%"';
		}
		return $conditions;
	}
}
include(ROOT_PATH . 'excute.php');