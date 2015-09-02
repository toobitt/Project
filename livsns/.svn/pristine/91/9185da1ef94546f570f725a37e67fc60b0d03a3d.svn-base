<?php
require_once 'global.php';
require_once CUR_CONF_PATH.'lib/city.php';
require_once CUR_CONF_PATH.'lib/weather_forcast.class.php';
require_once CUR_CONF_PATH.'lib/yesterday.class.php';
define('MOD_UNIQUEID','weatehr_wuxi');//模块标识
class weatherWuxiApi extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->city = new city();
		$this->forcast = new forcastWeather();
		$this->yesterday = new yesterdayWeather();
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
		//对城市名称进行查询，有返回天气信息
		$ret = $this->forcast->show($condition,$cityName);
		$y_weather = array();
		
		$y_weather = $this->yesterday->show($condition, $cityName,$this->user['appid']);
		$arr = array();
		$arr[] = $y_weather;
		foreach ($ret as $key =>$val)
		{
			$val['pm25'] = htmlspecialchars_decode($val['pm25']);
			$arr[] = $val;
		}
		foreach ($arr as $val)
		{
			$this->addItem($val);
		}
		//$this->addItem_withkey('weather', $ret);
		//$this->addItem_withkey('yesterday', $y_weather);
		$this->output();
	}
	
	public function count()
	{
	
	}
	
	public function detail()
	{
		
	}
}
$ouput= new weatherWuxiApi();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}else {
	$action = $_INPUT['a'];
}
$ouput->$action();