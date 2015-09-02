<?php
require_once 'global.php';
require_once CUR_CONF_PATH.'lib/city.php';
require_once CUR_CONF_PATH.'lib/weather_forcast.class.php';
define('MOD_UNIQUEID','weatehr_forcast');//模块标识
class weatherApi extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->city = new city();
		$this->forcast = new forcastWeather();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function show()
	{
		$cityName = urldecode($this->input['name']);
		//对城市名称进行处理，有省，市的进行过滤
		if ($cityName)
		{
			$cityName = $this->city->filter($cityName);
			$condition = ' AND name="'.$cityName.'"';
		}	
		$days = $this->input['count'] && $this->input['count'] < 7 ? $this->input['count'] : 6;
		//对城市名称进行查询，有返回天气信息
		$ret = $this->forcast->show($condition,$cityName,$days);
		foreach ($ret as $key =>$val)
		{
			$val['pm25'] = htmlspecialchars_decode($val['pm25']);
			if ($this->settings['replaceHost'] && is_array($this->settings['replaceHost']) && !empty($this->settings['replaceHost']))
			{
				array_walk_recursive($val, 'replaceHost');	
			}
			$this->addItem($val);
		} 
		$this->output();
		
	}
	
	//相关城市天气
	public function related_city()
	{
		$city_ids = $this->input['city_id'];
		$city_nums = $this->input['city_nums'] ? intval($this->input['city_nums']) : 8;
		$ret = $this->forcast->getRelatedCity($city_ids,$city_nums);
		if(is_array($ret))
		{
			foreach ($ret as $key =>$val)
			{
				$this->addItem($val);
			}
		}
		$this->output();
	}
	
	public function count()
	{
	
	}
	
	public function detail()
	{
		
	}
}
function replaceHost(&$value,$key)
{
	global $gGlobalConfig;
	$add_domains = $gGlobalConfig['replaceHost'];
  	$value = str_replace(array_keys($add_domains), $add_domains, $value);
}
$ouput= new weatherApi();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}else {
	$action = $_INPUT['a'];
}
$ouput->$action();