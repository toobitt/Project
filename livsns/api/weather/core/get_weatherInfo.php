<?php
/**
 * 
 * 入口类，根据城市id获取各个天气源的天气信息
 *
 */
require_once CUR_CONF_PATH.'core/forcast.class.php';
require_once CUR_CONF_PATH.'core/realtime.class.php';
require_once CUR_CONF_PATH.'core/extend.class.php';
class get_weatherInfo extends InitFrm
{
	function __construct()
	{
		parent::__construct();
		$this->forcast = new forcast();
		$this->realtime = new realtime();
		$this->extend = new extend();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	public function getWeather($id = array(),$userInfo=array())
	{
		if (!$id || !is_array($id))
		{
			return false;
		}
		//查看有多个开启的城市源
		$sql = 'SELECT * FROM '.DB_PREFIX.'weather_source';
		$query =$this->db->query($sql);
		$k = array();
		while ($row = $this->db->fetch_array($query))
		{
			if ($row['inner_func'] && $row['is_open'])
			{
				$k['id'] = $row;
			}
		}
		if (!$k || empty($k))
		{
			return false;
		}
		foreach ($k as $key=>$val)
		{
			$func = $val['inner_func'].'_update';
			//更新当天天气
			$this->forcast->$func($id,$val['id'],$userInfo);
			$this->realtime->$func($id,$val['id'],$userInfo);
			$this->extend->$func($id,$val['id'],$userInfo);
		}
	}
}