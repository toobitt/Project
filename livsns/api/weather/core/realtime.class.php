<?php
require_once ROOT_PATH . 'lib/class/curl.class.php';
require_once(ROOT_PATH . 'lib/class/material.class.php');
require_once CUR_CONF_PATH.'core/pub.class.php';
class realtime extends InitFrm
{
	private $curl;
	public function __construct()
	{	
		parent::__construct();
		$this->material = new material();
		$this->pubWeather = new common_Weather();		
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	//中国天气网天气更新
	function cn_com_weather_update($city= array(), $weather_source_id = 0, $userinfo=array(),$update=true)
	{
		$weather_info = array();
		if(!$weather_source_id || empty($city))
		{
			return $weather_info;
		}
        //取实时天气
		$this->curl = new curl($this->settings['weather_realtime']['host'], $this->settings['weather_realtime']['dir']);
		$this->curl->initPostData();
		$this->curl->setSubmitType('get');
		$sql = 'SELECT id, code FROM '.DB_PREFIX.'weather_city_source WHERE source_id = '.intval($weather_source_id).' AND id in ('.implode(',', $city).')';
		$query = $this->db->query($sql);
		while($city = $this->db->fetch_array($query))
		{
			$file = $city['code'] . '.html';
			//效率存在问题 循环请求接口
			$weather_info[$city['id']] = $this->curl->request($file);	
		}
		$return = array();
		if($weather_info)
		{
			foreach ($weather_info as $city_id=>$weathers)
			{
				$weather = $weathers['weatherinfo'];
				$extra = array(
					'humidity'=>$weather['SD'],   //湿度
				);
				
				//数据处理
				$data = array(
				'id'=>$city_id,
				'source_id'=>intval($weather_source_id),
				'w_date'=>date('Y-m-d',TIMENOW),	
				'w_time'=>$weather['time'],			
				'temperature'=>$weather['temp'],			
				'wind_direction'=>$weather['WD'],
				'wind_level'=>$weather['WS'],
				'extra'=>addslashes(serialize($extra)),
				'user_id'=>$userinfo['user_id'],
				'user_name'=>$userinfo['user_name'],	
				'ip'=>hg_getip(),
				);
				$ret = $this->pubWeather->storedIntoDB($data, 'weather_information');
				$this->update_day_weather($city_id,$data);
				$return[$city_id] = $data;
				
			}
		}
		return $return;
	}
	//更新当日天气
	private function update_day_weather($id,$data)
	{
		if (!$data || !$id)
		{
			return false;
		}
		$extra = unserialize(stripslashes($data['extra']));
		$data = array_merge($data,$extra);
		$sql  = 'SELECT name FROM '.DB_PREFIX.'weather_city WHERE id = '.$id;
		$q = $this->db->query_first($sql);
		$data['name']  = $q['name'];	
		unset($data['extra']);
		unset($data['user_id']);
		unset($data['user_name']);
		unset($data['ip']);
		$sql = 'UPDATE '.DB_PREFIX.'weather SET realtime = "'.addslashes(serialize($data)).'" WHERE id = '.$id;
		$this->db->query($sql);		
		return true;	
	}	
}