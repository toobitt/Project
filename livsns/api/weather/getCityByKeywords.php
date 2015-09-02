<?php
require_once './global.php';
define('SCRIPT_NAME', 'get_weather_city');
require_once CUR_CONF_PATH.'lib/city.php';
define('MOD_UNIQUEID','get_weather_city');//模块标识
class get_weather_city extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->city = new city();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	function show()
	{
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";
		$condition = $this->get_condition();
		$cities = $this->city->cityShow($condition, $limit);
		if($cities)
		{
			foreach ($cities as $k=>$v)
			{
				$this->addItem($v);
			}
		}
		$this->output();
	}
	function get_condition()
	{
		$conditions = '';
		$keywords = (trim($this->input['keywords']));
		if($keywords)
		{
			$conditions .= ' AND (name like "%'.$keywords.'%" or en_name like "%'.$keywords.'%" or abbr_name like "%'.$keywords.'%")';
		}
		return $conditions;
	}
	
	public function detail()
	{
	
	}
	
	public function count()
	{
		
	}
}
include(ROOT_PATH . 'excute.php');