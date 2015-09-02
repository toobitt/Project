<?php
class get_cityCode extends InitFrm
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	/**
	 * 
	 * 更新城市代码 ...
	 * @param int $cityId  城市id
	 * @param int $flag    是否强制更新,默认空，当$flag为true时，执行强制更新
	 */
	public function cityCode($cityId = array(),$userInfo = array(),$flag='')
	{
		if (!$cityId || !is_array($cityId) || empty($cityId))
		{
			return false;
		}
		//获取所有的天气源
		$sql = 'SELECT * FROM '.DB_PREFIX.'weather_source';
		$query = $this->db->query($sql);
		$sourceId = array();
		while ($row = $this->db->fetch_array($query))
		{
			if ($row['inner_func'])
			{
				$sourceId[] = $row['id'];
			}
		}
		
		$func = array();
		if (!empty($sourceId))
		{
			foreach ($cityId as $key=>$val)
			{
				foreach ($sourceId as $k=>$v)
				{
					$func[][$val]=$v;
				}
			}
		}
		$k = array();
		if (!empty($func))
		{
			foreach ($func as $key=>$val)
			{
				$ret = $this->get_city_code(key($val), $val[key($val)]);
				
				if ($ret && is_array($ret))
				{
					//城市代码入库
					 $return = $this->city_source(key($val),$val[key($val)],$userInfo,$ret);
					if ($return)
					{
						$k[] = array('id'=>$return['id'],'source_id'=>$return['source_id'],'code'=>$return['code']);	
					}
				}
			}
		}
		return $k;
	}
	
	//获取城市天气接口
	private function get_city_code($cityId,$sourceId)
	{
		$sql = 'SELECT name FROM '.DB_PREFIX.'weather_city WHERE id = '.$cityId;
		$ret = $this->db->query_first($sql);
		$cityName = $ret['name'];
		$sql = 'SELECT * FROM '.DB_PREFIX.'weather_source WHERE id = '.$sourceId;
		
		$weather_source_info = $this->db->query_first($sql);
		if(!$weather_source_info)
		{
			$this->errorOutput(WEATHER_SOUCE_NOT_EXISTS);
		}
		$api_func = $weather_source_info['inner_func'] . '_citycode';
		//验证该天气源的获取城市代码是否实现
		if(!method_exists($this, $api_func))
		{
			$this->errorOutput(NOT_IMPLEMENT);
		}	
		$return = $this->$api_func($weather_source_info,$cityName);
		return $return;
	}
	private function cn_com_weather_citycode($weather_source_info,$cityName)
	{
		if (!$cityName)
		{
			return false;
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'weather_city_code WHERE name = "'.$cityName.'"';
		$ret = $this->db->query_first($sql);
		if (!$ret)
		{
			return false;
		}
		return $ret;
		/*
		$language = $this->input['language'] ? $this->input['language'] : 'zh';
		$curl = new curl($weather_source_info['city_api_url'], $weather_source_info['city_api_dir']);
		$curl->addRequestData('language', $language);
		$curl->addRequestData('keyword', $cityName);
		$ret = $curl->request('searchBox');
		
	    if (!$ret)
	    {
	    	return false;
	    }else {
	    	if ($ret['i'][0]['n']==$cityName)
	    	{
	    		return $ret['i'][0];
	    	}else {
	    		return false;
	    	}
	    }
	    */
		
	}
	private function city_source($cityId,$sourceId,$userInfo,$ret)
	{
		if (!$cityId || !$sourceId || !$userInfo || !$ret)
		{
			return false;
		}
		$data = array(
			'id'=>$cityId,
			'source_id'=>$sourceId,
			'code'=>$ret['code'],
			'create_time'=>TIMENOW,
			'user_id'=>$userInfo['user_id'],
			'user_name'=>$userInfo['user_name'],
			'ip'=>$userInfo['ip'],
		);
		$sql = 'REPLACE INTO '.DB_PREFIX.'weather_city_source SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		
		return $data; 
	}
}