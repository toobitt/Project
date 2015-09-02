<?php
require_once ROOT_PATH . 'lib/class/curl.class.php';
require_once(ROOT_PATH . 'lib/class/material.class.php');
require_once CUR_CONF_PATH.'core/pub.class.php';
class extend extends InitFrm
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
		$sql = 'SELECT weather_api_url host,weather_api_dir dir FROM '.DB_PREFIX.'weather_source WHERE id = '.intval($weather_source_id);
		$curl_parameters = $this->db->query_first($sql);
		$this->curl = new curl($curl_parameters['host'], $curl_parameters['dir']);
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
				//图片处理
				$flag = 'img';
				$flag_t = 'img_title';
				$k = array();
				$id_title = array();
				$imgIds = array();
				for ($i=3;$i<13;$i++)
				{
					if (intval($weather[$flag.$i]) !=99)
					{
						$id_title[intval($weather[$flag.$i])] = $weather[$flag_t.$i];
					}
				}
				for ($i=3;$i<13;$i=$i+2)
				{
					$k[$i] = $weather[$flag.$i];
					if (intval($weather[$flag.($i+1)]) != 99)
					{
						$k[$i] = $weather[$flag.$i].','.$weather[$flag.($i+1)];
					}
				}
				if (!empty($k))
				{
					$icon = implode(',', $k);
					$icon = explode(',', $icon);
					$icon = array_unique($icon);
					foreach ($icon as $v)
					{
						$imgIds[$v] = $this->pubWeather->get_system_material_id($v, $id_title[$v], intval($weather_source_id),$userinfo);
					}
				}	
				foreach ($k as $key=>$val)
				{
					$val = explode(',', $val);
					$img = array();
					foreach ($val as $ico)
					{
						$img[] =  $imgIds[$ico]; 
					}
					$k[$key] = implode(',', $img);
				}
				//数据处理
				$data = array(
							'two'=>array(
							'id'=>$city_id,
							'source_id'=>intval($weather_source_id),
							'w_date'=>date('Y-m-d',strtotime("+1 days")),
							'img_id'=>$k[3],
							'weather_report'=>$weather['weather2'],
							'temperature'=>$weather['temp2'],
							'wind_direction'=>$weather['fx2'],
							'wind_level'=>$weather['wind2'],
							'user_id'=>$userinfo['user_id'],
							'user_name'=>$userinfo['user_name'],
							'ip'=>hg_getip(),
							),
							'three'=>array(
							'id'=>$city_id,
							'source_id'=>intval($weather_source_id),
							'w_date'=>date('Y-m-d',strtotime("+2 days")),
							'img_id'=>$k[5],
							'weather_report'=>$weather['weather3'],
							'temperature'=>$weather['temp3'],
							'wind_direction'=>$weather['fx3'],
							'wind_level'=>$weather['wind3'],
							'user_id'=>$userinfo['user_id'],
							'user_name'=>$userinfo['user_name'],
							'ip'=>hg_getip(),
							),
							'four'=>array(
							'id'=>$city_id,
							'source_id'=>intval($weather_source_id),
							'w_date'=>date('Y-m-d',strtotime("+3 days")),
							'img_id'=>$k[7],
							'weather_report'=>$weather['weather4'],
							'temperature'=>$weather['temp4'],
							'wind_direction'=>$weather['fx4'],
							'wind_level'=>$weather['wind4'],
							'user_id'=>$userinfo['user_id'],
							'user_name'=>$userinfo['user_name'],
							'ip'=>hg_getip(),
							),
							'five'=>array(
							'id'=>$city_id,
							'source_id'=>intval($weather_source_id),
							'w_date'=>date('Y-m-d',strtotime("+4 days")),
							'img_id'=>$k[9],
							'weather_report'=>$weather['weather5'],
							'temperature'=>$weather['temp5'],
							'wind_direction'=>$weather['fx5'],
							'wind_level'=>$weather['wind5'],
							'user_id'=>$userinfo['user_id'],
							'user_name'=>$userinfo['user_name'],
							'ip'=>hg_getip(),
							),
							'six'=> defined('COPY_SIX_DAY') && COPY_SIX_DAY ? array(
							'id'=>$city_id,
							'source_id'=>intval($weather_source_id),
							'w_date'=>date('Y-m-d',strtotime("+5 days")),
							'img_id'=>$k[9],
							'weather_report'=>$weather['weather5'],
							'temperature'=>$weather['temp5'],
							'wind_direction'=>$weather['fx5'],
							'wind_level'=>$weather['wind5'],
							'user_id'=>$userinfo['user_id'],
							'user_name'=>$userinfo['user_name'],
							'ip'=>hg_getip(),
							) :  array(
							'id'=>$city_id,
							'source_id'=>intval($weather_source_id),
							'w_date'=>date('Y-m-d',strtotime("+5 days")),
							'img_id'=>$k[11],
							'weather_report'=>$weather['weather6'],
							'temperature'=>$weather['temp6'],
							'wind_direction'=>$weather['fx6'],
							'wind_level'=>$weather['wind6'],
							'user_id'=>$userinfo['user_id'],
							'user_name'=>$userinfo['user_name'],
							'ip'=>hg_getip(),
							) ,
				);
				foreach ($data as $k=>$val)
				{
					$this->pubWeather->storedIntoDB($val, 'weather_information');
					$this->update_weather($val,$k);
				}	
			}	
		}
		return $data;
	}
	//更新天气信息表
	private function update_weather($data,$date)
	{
		if (!$data || !is_array($data) || empty($data))
		{
			return false;
		}
		$arr = array(
			'id'=>$data['id'],
			'w_date'=>$data['w_date'],
			'update_time'=>TIMENOW
		);
		//获取城市名称
		$sql  = 'SELECT name FROM '.DB_PREFIX.'weather_city WHERE id = '.$arr['id'];
		$q = $this->db->query_first($sql);
		$arr['name']  = $q['name'];
		$k = array();
		//处理匹配数据
		$sql = 'SELECT * FROM '.DB_PREFIX.'weather_user_define WHERE 1';
		$query = $this->db->query($sql);
		while ($row = $this->db->fetch_array($query))
		{
			$k[$row['user_field']] = $row['source_field'];
		}
		if (!empty($k))
		{
			foreach ($k as $key=>$val)
			{
				$arr[$key] = $data[$val];
			}
		}
		//处理自定义数据
		$sql = 'SELECT * FROM '.DB_PREFIX.'weather_user_define WHERE source_id = 0 AND city_id ='.$data['id'];
		$query = $this->db->query($sql);
		$user_field = array();
		$res = array();
		while ($row = $this->db->fetch_array($query))
		{
			$arr[$row['user_field']] = $row['user_data'];
		}
		$sql = 'SELECT id FROM '.DB_PREFIX.'weather WHERE id = '.$arr['id'];
		$ret = $this->db->query_first($sql);
		if ($ret['id'])
		{
			$sql = 'UPDATE '.DB_PREFIX.'weather SET '.$date.'="'.addslashes(serialize($arr)).'" WHERE id = '.$arr['id'];
		}else {
			$sql = 'INSERT INTO '.DB_PREFIX.'weather SET '.$date.' = "'.addslashes(serialize($arr)).'",id = '.$arr['id'];
		}
		$this->db->query($sql);
		return true;	
	}
}