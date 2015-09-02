<?php
class fetchCity extends InitFrm
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function fetch_trains_city()
	{
		if(!defined('TRAINS_CITY_API') || !TRAINS_CITY_API)
		{
			return '';
		}
		
		$city_json = curlRequest(TRAINS_CITY_API);
		if(!$city_json)
		{
			$city_json = curlRequest(TRAINS_CITY_API);
		}
		$city_data = json_decode($city_json, true);
		$cities = array();
		if(is_array($city_data['Data']))
		{
			unset($city_data['Data'][0]);//字段含义
			foreach($city_data['Data'] as $key=>$val)
			{
				$cities[] = array(
				'city_id'=>strval($val[0]),
				'city_name'=>strval($val[1]),
				'city_name_en'=>strval($val[2]),
				'city_name_jp'=>strval($val[3]),
				'first_letter'=>strval($val[4]),
				'flag'=>strval($val[5]),
				'hot_flag'=>strval($val[6]),
				);
			}
		}
		//hg_pre($cities);
		if($cities)
		{
			$fields = array('city_id', 'city_name','city_name_en', 'city_name_jp', 'first_letter', 'flag','hot_flag');
			$sql = 'REPLACE INTO ' . DB_PREFIX . 'trains_city('.implode(',', $fields).') values';
			foreach($cities as $key=>$val)
			{
				$sql .= '('.$val['city_id'].', 
				"'.addslashes($val['city_name']).'",
				"'.addslashes($val['city_name_en']).'",
				"'.addslashes($val['city_name_jp']).'",
				"'.addslashes($val['first_letter']).'",
				"'.addslashes($val['flag']).'",
				"'.addslashes($val['hot_flag']).'"
				),';
			}
			$sql = trim($sql, ',');
			$this->db->query($sql);
		}
		return $cities;
	}
	function fetch_airports_city()
	{
		if(!defined('AIRPORTS_CITY_API') || !AIRPORTS_CITY_API)
		{
			return '';
		}
		
		$city_json = curlRequest(AIRPORTS_CITY_API);
		if(!$city_json)
		{
			$city_json = curlRequest(AIRPORTS_CITY_API);
		}
		$city_data = json_decode($city_json, true);
		$cities = array();
		if(is_array($city_data['Data']))
		{
			unset($city_data['Data'][0]);//字段含义
			foreach($city_data['Data'] as $key=>$val)
			{
				$cities[] = array(
				'city_id'=>strval($val[0]),
				'city_name'=>strval($val[1]),
				'city_name_en'=>strval($val[2]),
				'city_name_jp'=>strval($val[3]),
				'city_code'=>strval($val[4]),
				'air_port_code'=>strval($val[5]),
				'air_port_name'=>strval($val[6]),
				'first_letter'=>strval($val[7]),
				'flag'=>strval($val[8]),
				);
			}
		}
		//hg_pre($cities);
		if($cities)
		{
			$fields = array('city_id', 'city_name','city_name_en', 'city_name_jp', 'city_code', 'air_port_code','air_port_name','first_letter','flag');
			$sql = 'REPLACE INTO ' . DB_PREFIX . 'airports_city('.implode(',', $fields).') values';
			foreach($cities as $key=>$val)
			{
				$sql .= '('.$val['city_id'].', 
				"'.addslashes($val['city_name']).'",
				"'.addslashes($val['city_name_en']).'",
				"'.addslashes($val['city_name_jp']).'",
				"'.addslashes($val['city_code']).'",
				"'.addslashes($val['air_port_code']).'",
				"'.addslashes($val['air_port_name']).'",
				"'.addslashes($val['first_letter']).'",
				"'.addslashes($val['flag']).'"
				),';
			}
			$sql = trim($sql, ',');
			$this->db->query($sql);
		}
		return $cities;
	}
}