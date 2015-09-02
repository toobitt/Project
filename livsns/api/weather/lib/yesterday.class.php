<?php
require_once CUR_CONF_PATH.'core/get_weatherInfo.php';
require_once CUR_CONF_PATH.'core/get_cityCode.php';
require_once CUR_CONF_PATH.'lib/getPinyinByChinese.php';
class yesterdayWeather extends InitFrm
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
	public function show($condition,$cityName,$appid = '')
	{
		$sql = 'SELECT id FROM '.DB_PREFIX.'weather_city WHERE 1'.$condition;
		$ret = $this->db->query_first($sql);
		//取当日天气已经做过此操作，无需重复纪录
		if (!$ret['id'] || !$cityName)
		{
			$ret['id'] = $this->settings['default_city_id']['id'];
			$sql = 'SELECT name FROM '.DB_PREFIX.'weather_city WHERE id ='.$ret['id'];
			$city = $this->db->query_first($sql);
			if (!$city['name'])
			{
				return array();
			}
			$cityName = $city['name'];
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'weather WHERE id = '.$ret['id'];
		$res = $this->db->query_first($sql);
		if (!$res)
		{
			return array();
		}
		$date = date('Y-m-d', strtotime('-1 day'));
		$sql = 'SELECT * FROM '.DB_PREFIX.'weather_information 
				WHERE  id = '.$ret['id'].' AND w_date = "'.$date.'" AND w_time = "" AND source_id = 1  limit 1';//limit 1 防止异常产生的重复数据
		$y_infor = $this->db->query_first($sql);
		if (!$y_infor)
		{
			return array();
		}
		//暂不显示额外数据
		//$y_extra = $y_infor['extra'] ? unserialize($y_infor['extra']) : array();
		//$y_infor = array_merge($y_infor, $y_extra);
		
		//检索配置
		$configs = array();
		$sql = 'SELECT * FROM '.DB_PREFIX.'weather_user_define WHERE is_zs = 0 ORDER BY id ASC';
		$query = $this->db->query($sql);
		while ($row = $this->db->fetch_array($query))
		{
			$configs[] = $row; 
		}
		if (empty($configs))
		{
			return array();
		}
		$arr = array();
		$arr['id'] 					= $y_infor['id'];
		$arr['source_id'] 			= $y_infor['source_id'];
		$arr['w_date'] 				= $y_infor['w_date'];
		$arr['format_date'] 		= date('m/d', strtotime($y_infor['w_date'])); 
		foreach ($configs as $config)
		{
			$arr[$config['user_field']] = $y_infor[$config['source_field']];
			if ($config['source_field'] == 'temperature')
			{
				$temperature = str_replace('℃', '', $y_infor[$config['source_field']]);
				$temperature = explode('~', $temperature);
				if ($temperature[0]>=$temperature[1])
				{
					$arr['high'] = $temperature[0];
					$arr['low'] = $temperature[1];
				}
				else 
				{
					$arr['high'] = $temperature[1];
					$arr['low'] = $temperature[0];
				}
			}
		}
		$arr['icon'] = array();
		$arr['bg_image'] = array();
		if ($arr['img'])
		{
			$sql = 'SELECT * FROM '.DB_PREFIX.'weather_material WHERE id IN ('.$arr['img'].')';
			$query = $this->db->query($sql);
			$images = array();
			while ($row = $this->db->fetch_array($query))
			{
				$img = array();
				$bg_img = array();
				$system_img = $row['system_img'] ? unserialize($row['system_img']) : array();
				$user_img = $row['user_img'] ? unserialize($row['user_img']) : array();
				$bg_image = $row['bg_image'] ? unserialize($row['bg_image']) : array();
				$app_user_image = $row['app_user_image'] ? unserialize($row['app_user_image']) : array();
				$app_bg_image = $row['app_bg_image'] ? unserialize($row['app_bg_image']) : array();
				if ($app_user_image && $app_user_image[$appid])
				{
					$img = array(
								'host'		=> $app_user_image[$appid]['host'],
								'dir'		=> $app_user_image[$appid]['dir'],
								'filepath'	=> $app_user_image[$appid]['filepath'],
								'filename'	=> $app_user_image[$appid]['filename'],
							);
				}
				if (empty($img))
				{
					$img = $user_img;
				}
				if (empty($img))
				{
					$img = $system_img;
				}
				if ($app_bg_image && $app_bg_image[$appid])
				{
					$bg_img = array(
								'host'		=> $app_bg_image[$appid]['host'],
								'dir'		=> $app_bg_image[$appid]['dir'],
								'filepath'	=> $app_bg_image[$appid]['filepath'],
								'filename'	=> $app_bg_image[$appid]['filename'],
							);
				}
				if (empty($bg_img))
				{
					$bg_img = $bg_image;
				}
				$arr['icon'][] = $img;
				$arr['bg_image'][] = $bg_img;
			}
		}
		return $arr;
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
}