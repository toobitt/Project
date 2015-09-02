<?php
require_once CUR_CONF_PATH.'core/get_weatherInfo.php';
require_once CUR_CONF_PATH.'core/get_cityCode.php';
require_once CUR_CONF_PATH.'lib/getPinyinByChinese.php';
require_once(CUR_CONF_PATH.'lib/pm25.api.php');
require_once(CUR_CONF_PATH.'lib/pm25.class.php');
class forcastWeather extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->weatherInfo = new get_weatherInfo();
		$this->cityCode = new get_cityCode();
		$this->pinyin = new getPinyinByChineseApi();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	//外部接口输出
	public function show($condition,$cityName,$days =6)
	{
		$dayk = array(
			0 => 'one',
			1 => 'two',
			2 => 'three',
			3 => 'four',
			4 => 'five',
			5 => 'six',
			6 => 'seven',
		);
		$sql = 'SELECT id FROM '.DB_PREFIX.'weather_city WHERE 1'.$condition;
		$ret = $this->db->query_first($sql);
		if (!$cityName || !$ret['id'])
		{
			$ret['id'] = $this->settings['default_city_id']['id'];
			//将不存在的城市插入队列表
			$sql = 'REPLACE INTO '.DB_PREFIX.'weather_city_queue SET city_name = "'.$cityName.'"';
			$this->db->query($sql);
			if(defined('IS_DEFAULT_CITY') && IS_DEFAULT_CITY )
			{
				//查询默认城市的名称
				$sql = 'SELECT name FROM '.DB_PREFIX.'weather_city WHERE id ='.$ret['id'];
				$city = $this->db->query_first($sql);
				if (!$city['name'])
				{
					return array();
				}
				$cityName = $city['name'];
			}
			else 
			{
				return array();
			}
		}		
		$sql = 'SELECT * FROM '.DB_PREFIX.'weather WHERE id = '.$ret['id'];
		$res = $this->db->query_first($sql);
		if (!$res)
		{
			return array();
		}
		$imgs = array();
		$tmp = array();
		//检索天气配置
		//$sql = 'SELECT user_field FROM '.DB_PREFIX.'weather_user_define WHERE source_field = "temperature"';
		//$c_temp = $this->db->query_first($sql);
		$sql = 'SELECT * FROM '.DB_PREFIX.'weather_user_define WHERE 1 ORDER BY id ASC';
		$query = $this->db->query($sql);
		$zs = array();
		$c_temp = array();
		while ($row = $this->db->fetch_array($query))
		{
			if ($row['source_field'] == 'temperature')
			{
				$c_temp['user_field'] = $row['user_field'];
			}
			if ($row['is_zs'])
			{
				$zs[] = $row['user_field'];
			}
		}
		for($i = 0; $i < $days;$i++)
		{
			$k = unserialize($res[$dayk[$i]]);
			$k['format_update_time'] = date('Y/m/d H:i', $k['update_time']);
			$k['format_date'] = date('m/d', strtotime($k['w_date']));
			if ($c_temp['user_field'])
			{
				if ($k[$c_temp['user_field']])
				{
					$temperature = str_replace('℃', '', $k[$c_temp['user_field']]);
					$temperature = explode('~', $temperature);
					if ($temperature[0]>=$temperature[1])
					{
						$k['high'] = $temperature[0];
						$k['low'] = $temperature[1];
					}
					else 
					{
						$k['high'] = $temperature[1];
						$k['low'] = $temperature[0];
					}
				}
			}
			
			if ($i ==0)
			{
				if (!empty($zs))
				{
					foreach ($zs as $field)
					{
						$k['zs'][$field] = $k[$field];
						if ($this->settings['zs_image'])
						{
							$k['zs'][$field]['img'] = $this->settings['zs_image'][$field];
						}
						
						unset($k[$field]);
					}
					$k['zs'] = array_values($k['zs']);
				}
				//调用pm25
				$k['pm25_data'] = array();
				if ($this->settings['pm25api'] && defined('PM25TOKEN') && defined('CACHE_TIME'))
				{
					$this->mP25 = new pm25($cityName);
					if($this->mP25->initCity($cityName))
					{
						$k['pm25_data'] = $this->mP25->show();
					}
				}
				$realtime = unserialize($res['realtime']);
				$realtime['format_date'] = date('m/d', strtotime($realtime['w_date']));
				$k['realtime'] = $realtime;
			}
			$imgs[$dayk[$i]] = $k['img'];
			$tmp[] = $k;
		}
		if ($imgs)
		{
			$imgs = @array_filter($imgs);
			$sql = 'SELECT * FROM '.DB_PREFIX.'weather_material WHERE id IN ('.implode(',', $imgs).')';
			$query = $this->db->query($sql);
			$img = array();
			$bg_img = array();
			$app_user_image = array();
			$app_bg_image = array();
			while ($row = $this->db->fetch_array($query))
			{
				
				if ($row['is_update'])
				{
					$material = unserialize($row['user_img']);
				}
				else
				{
					$material = unserialize($row['system_img']);			
				}
				if (!$material)
				{
					$material = '';
				}			
				$bg_img[$row['id']] = unserialize($row['bg_image']) ? unserialize($row['bg_image']) : array();
				$img[$row['id']] = $material;
			
				$app_user_image[$row['id']] = unserialize($row['app_user_image']) ? unserialize($row['app_user_image']) : array();
				$app_bg_image[$row['id']] = unserialize($row['app_bg_image']) ? unserialize($row['app_bg_image']) : array();
			}
			if (!empty($app_user_image))
			{
				foreach ($app_user_image as $key=>$val)
				{
					if (is_array($val) && !empty($val))
					{
						foreach ($val as $kk=>$vv)
						{
							if ($this->input['appid'] ==$vv['appid'])
							{
								unset($app_user_image[$key][$kk]['appid']);
								unset($app_user_image[$key][$kk]['custom_name']);
								$img[$key] = $app_user_image[$key][$kk];
							}
						}
					}
				}
			}
			if (!empty($app_bg_image))
			{
				foreach ($app_bg_image as $key=>$val)
				{
					if (is_array($val) && !empty($val))
					{
						foreach ($val as $kk=>$vv)
						{
							if ($this->input['appid'] ==$vv['appid'])
							{
								unset($app_bg_image[$key][$kk]['appid']);
								unset($app_bg_image[$key][$kk]['custom_name']);
								$bg_img[$key] = $app_bg_image[$key][$kk];
							}
						}
					}
				}
			}
		}
		$ret = array();
		foreach ($tmp AS $v)
		{
			$weather_ico = explode(',', $v['img']);
			foreach ($weather_ico AS $iid)
			{
				if (!$img[$iid])
				{
					continue;
				}
				$v['icon'][] = $img[$iid];
				$v['bg_image'][] = $bg_img[$iid];
			}
			$ret[] = $v;
		}
		return $ret;
	}
	//外部接口输出
	public function create($cityName)
	{
		if (!$cityName)
		{
			return false;
		}
		return true;
	}
	
	public function getWeather($cityName)
	{
		if(!$cityName)
		{
			return array();
		}
		$dayk = array(
			0 => 'one',
			1 => 'two',
			2 => 'three',
			3 => 'four',
			4 => 'five',
			5 => 'six',
			6 => 'seven',
		);
		$sql = 'SELECT id,en_name,name FROM '.DB_PREFIX.'weather_city WHERE name = "'.$cityName.'"';
		$ret = $this->db->query_first($sql);
		if (!$ret['id'])
		{
			$sql = 'SELECT code FROM '.DB_PREFIX.'weather_city_code WHERE name = "'.$cityName.'"';
			$code = $this->db->query_first($sql);
			if($code['code'])
			{
				$configs = $this->settings['App_weather'];
				$this->curl = new curl($configs['host'], $configs['dir'].'admin/');
				$this->curl->setSubmitType('get');
				$this->curl->setReturnFormat('json');
				$this->curl->initPostData();
				$this->curl->addRequestData('a','create');
				$this->curl->addRequestData('name',$cityName);
				$re = $this->curl->request('city_node_update.php');
				$re = $re[0];
			}
			if($re['id'])
			{
				$ret['id'] = $re['id'];
				$ret['en_name'] = $re['en_name'];
				$ret['name'] = $re['name'];
			}
			else 
			{
				return array();
			}
		}	
		if(!isset($code))
		{
			$sql = 'SELECT code FROM '.DB_PREFIX.'weather_city_code WHERE name = "'.$cityName.'"';
			$code = $this->db->query_first($sql);
		}	
		if($ret['id'])
		{
			$sql = 'SELECT * FROM '.DB_PREFIX.'weather WHERE id = '.$ret['id'];
			$res = $this->db->query_first($sql);
			if (!$res)
			{
				return array();
			}
			for($i = 0; $i < 7;$i++)
			{
				$weather_info[$i] =$res[$dayk[$i]] ? unserialize($res[$dayk[$i]]) : array();
			}
			$weather_info[0]['cityid'] = $code['code'];
			$weather_info[0]['city_en'] = $ret['en_name'];
			$weather_info[0]['name'] = $ret['name'];
			return $weather_info;
		}
	}
	
	public function getRelatedCity($city_ids = 17,$city_nums = 8)
	{
		if($city_ids)
		{
			$condition = 'AND id IN ('.$city_ids.')';
		}
		$sql = 'SELECT one,id FROM '.DB_PREFIX .'weather WHERE 1 ' . $condition .' LIMIT '. $city_nums;
		$query = $this->db->query($sql);
		while($r = $this->db->fetch_array($query))
		{
			$r['one'] = unserialize($r['one']);
			$images[] = $r['one']['img'];
			$ret[$r['id']] = $r['one'];
		}
		if($images)
		{
			$images = array_unique(array_filter($images));
			$sql = 'SELECT * FROM '.DB_PREFIX.'weather_material WHERE id IN ('.implode(',', $images).')';
			$query = $this->db->query($sql);
			$img = array();
			$bg_img = array();
			$app_user_image = array();
			$app_bg_image = array();
			while ($row = $this->db->fetch_array($query))
			{
				
				if ($row['is_update'])
				{
					$material = unserialize($row['user_img']);
				}
				else
				{
					$material = unserialize($row['system_img']);			
				}
				if (!$material)
				{
					$material = '';
				}			
				$bg_img[$row['id']] = unserialize($row['bg_image']) ? unserialize($row['bg_image']) : array();
				$img[$row['id']] = $material;
			
				$app_user_image[$row['id']] = unserialize($row['app_user_image']) ? unserialize($row['app_user_image']) : array();
				$app_bg_image[$row['id']] = unserialize($row['app_bg_image']) ? unserialize($row['app_bg_image']) : array();
			}
			if (!empty($app_user_image))
			{
				foreach ($app_user_image as $key=>$val)
				{
					if (is_array($val) && !empty($val))
					{
						foreach ($val as $kk=>$vv)
						{
							if ($this->input['appid'] ==$vv['appid'])
							{
								unset($app_user_image[$key][$kk]['appid']);
								unset($app_user_image[$key][$kk]['custom_name']);
								$img[$key] = $app_user_image[$key][$kk];
							}
						}
					}
				}
			}
			if (!empty($app_bg_image))
			{
				foreach ($app_bg_image as $key=>$val)
				{
					if (is_array($val) && !empty($val))
					{
						foreach ($val as $kk=>$vv)
						{
							if ($this->input['appid'] ==$vv['appid'])
							{
								unset($app_bg_image[$key][$kk]['appid']);
								unset($app_bg_image[$key][$kk]['custom_name']);
								$bg_img[$key] = $app_bg_image[$key][$kk];
							}
						}
					}
				}
			}
		}
		if(!$ret) 
		{
			return false;
		}
		foreach ($ret AS $v)
		{
			$weather_ico = explode(',', $v['img']);
			foreach ($weather_ico AS $iid)
			{
				if (!$img[$iid])
				{
					continue;
				}
				$v['icon'][] = $img[$iid];
				$v['bg_image'][] = $bg_img[$iid];
			}
			$back[] = $v;
		}
		return $back;
	}
}