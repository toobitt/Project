<?php
require_once ROOT_PATH . 'lib/class/curl.class.php';
require_once(ROOT_PATH . 'lib/class/material.class.php');
require_once CUR_CONF_PATH.'core/pub.class.php';
class newRealtime extends InitFrm
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
	function cn_com_weather_update($city= array(), $weather_source_id = 0, $userinfo=array(),$cityEngName =array(), $cityCode =array(),$update=true)
	{
	
		$weather_info = array();
		if(!$weather_source_id || empty($city) || empty($cityEngName))
		{
			return $weather_info;
		}
		//hg_pre($cityCode);exit();		

		foreach ($city as $key=>$val)
		{
			$url = 'http://flash.weather.com.cn/wmaps/xml/'.$cityEngName[$val].'.xml';
			$rss =  @simplexml_load_file($url);			
			if (!$rss)
			{
				continue;
			}
			$temp[$val] = xml2Array($url);
		}

		//hg_pre($temp);exit();
		$return = array();		
		if (!empty($temp) && $temp && is_array($temp))
		{
			foreach ($temp as $city_id=>$val)
			{
				//hg_pre($val['city']);exit();
				$data = array();
				$forcast = array();
				foreach ($val['city'] as $kk=>$vv)
				{
					if ($vv['url'] == $cityCode[$city_id])
					{
						$extra = array(
							'humidity'=>$vv['humidity'],   //湿度
						);
						$data = array(
							'id'=>$city_id,
							'source_id'=>intval($weather_source_id),
							'w_date'=>date('Y-m-d',TIMENOW),	
							'w_time'=>$vv['time'],			
							'temperature'=>$vv['temNow'],			
							'wind_direction'=>$vv['windDir'],
							'wind_level'=>$vv['windPower'],
							'extra'=>addslashes(serialize($extra)),
							'user_id'=>$userinfo['user_id'],
							'user_name'=>$userinfo['user_name'],	
							'ip'=>hg_getip(),
						);
						$forcast = array(
							'report'=>$vv['stateDetailed'],
							'fx'=>$vv['windDir'],
							'fl'=>$vv['windState'],
							'temp'=>$vv['tem1'].'℃~'.$vv['tem2'].'℃',
							'sd'=>$vv['humidity'],
							'time'=>$vv['time'],
						);
					}
				}
				if (empty($data))
				{
					//hg_pre($val['city'][0]);exit();
				}
				if (empty($data))
				{
					$extra = array(
							'humidity'=>$val['city'][0]['humidity'],   //湿度
						);
						$data = array(
							'id'=>$city_id,
							'source_id'=>intval($weather_source_id),
							'w_date'=>date('Y-m-d',TIMENOW),	
							'w_time'=>$val['city'][0]['time'],			
							'temperature'=>$val['city'][0]['temNow'],			
							'wind_direction'=>$val['city'][0]['windDir'],
							'wind_level'=>$val['city'][0]['windPower'],
							'extra'=>addslashes(serialize($extra)),
							'user_id'=>$userinfo['user_id'],
							'user_name'=>$userinfo['user_name'],	
							'ip'=>hg_getip(),
						);
						$forcast = array(
							'report'=>$val['city'][0]['stateDetailed'],
							'fx'=>$val['city'][0]['windDir'],
							'fl'=>$val['city'][0]['windState'],
							'temp'=>$val['city'][0]['tem1'].'℃~'.$val['city'][0]['tem2'].'℃',
							'sd'=>$val['city'][0]['humidity'],
							'time'=>$val['city'][0]['time'],
						);
				}
				$return[$city_id] = $data;
				$ret = $this->pubWeather->storedIntoDB($data, 'weather_information');
				$this->update_day_weather($city_id,$data);
				$this->update_forcast_weather($city_id,$forcast);
				
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

	
	//更新当天天气
	private function update_forcast_weather($id,$data)
	{
		if (!$data || !$id || empty($data))
		{
			return false;
		}
		//临时解决方案
		$sql = 'SELECT id,one FROM '.DB_PREFIX.'weather WHERE id = '.$id;
		$res = $this->db->query_first($sql);
		$one = @unserialize($res['one']);
		//hg_pre($one);exit();
		if ($one)
		{
			$report = $data['report'];
			$temp = explode('转', $report);
			//匹配图片，临时做法
			if (!empty($temp))
			{
				$img_ids = '';
				if ($temp[0])
				{
					$sql = 'SELECT id,img_title FROM '.DB_PREFIX.'weather_material_buffer WHERE img_title like "'.addslashes($temp[0]).'"';
					$res1 = $this->db->query_first($sql);
					$img_ids .= $res1['id'];
				}
				if ($temp[1])
				{
					$sql = 'SELECT id,img_title FROM '.DB_PREFIX.'weather_material_buffer WHERE img_title like "'.addslashes($temp[1]).'"';
					$res2 = $this->db->query_first($sql);
					$img_ids .= ','.$res2['id'];
				}
				$one['img'] = $img_ids;
			}
			$one['w_date'] = date('Y-m-d', strtotime($data['time']));	
			$one['update_time'] = strtotime($data['time']);		
			$one['report'] = $data['report'];
			$one['fx'] = $data['fx'];
			$one['fl'] = $data['fl'];
			$one['temp'] = $data['temp'];
			$one['sd'] = $data['sd'];
			$one = serialize($one);
			$sql = 'UPDATE '.DB_PREFIX.'weather SET one = "'.addslashes($one).'" WHERE id =' .$id;
			$this->db->query($sql);
		} 
		return true;	
	}
}
function xml2Array($xml) 
{
	$xmlObj = simplexml_load_file($xml);
	if(!$xmlObj)
	{
		return false;
	}
	normalizeSimpleXML($xmlObj,$result);
	return $result;
}

function normalizeSimpleXML($obj, &$result)
{
	$data = $obj;
	if (is_object($data)) 
	{
		$data = get_object_vars($data);
	}
	if (is_array($data)) 
	{
		foreach ($data as $key => $value) 
		{
			$res = null;
			normalizeSimpleXML($value, $res);
			if (($key == '@attributes') && ($key)) 
			{
				$result = $res;
			}
			else 
			{
				$result[$key] = $res;
			}
		}
	}
	else
	{
		$result = $data;
	}
}
