<?php
class pm25 extends InitFrm
{
	//申请token
	private $pm2token;
	
	//GET请求url
	private $url;
	
	//是否需要更新本地缓存数据
	private $mUpdateCache;
	
	//设定城市
	private $mCity;
	
	//
	private $pm25core;
	
	function __construct($city = '')
	{
		parent::__construct();
		$this->pm2token = PM25TOKEN;
		$this->mUpdateCache = $this->input['update_cache'] ? true : false;
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	public function initCity($city = '')
	{
		$this->cities = $this->pm25cities();
		$this->mCity = $city;
		if(!$this->mCity || !in_array($this->mCity, $this->cities))
		{
			if ($this->settings['default_pm25_city'])
			{
				$this->mCity = $this->settings['default_pm25_city'];
			}
			else
			{
				//return array('error'=>"城市暂不提供PM2.5数据");
				return false;
			}
		}
		$this->pm25core = new pm25core($this->mCity);
		return true;
	}
	//默认方法获取城市aqi平均值
	public function show()
	{
		$parameters = array(
		'city'		=> $this->mCity, 
		'avg'		=> 'true',
		'stations'	=> 'yes',
		);
		$cache_lock = CACHE_DIR . md5($this->mCity) . '.lock';
		if(!file_exists($cache_lock) || TIMENOW - fileatime($cache_lock) > CACHE_TIME)
		{
			$this->mUpdateCache = 1;
		}
		if((!$aqidata = $this->pm25core->get_aqi_data()) || $this->mUpdateCache)
		{
			$this->setUrl($this->settings['pm25api']['aqidata']);
			$this->setRequestParameters($parameters);
			$data = $this->request();
			if($data === false)
			{
				hg_file_write($cache_lock, TIMENOW);
				//远程接口有调整或报错 取最近一次更新的数据
				 return $aqidata ? $aqidata : $this->pm25core->get_aqi_data();
			}
			$avg = end($data);
			unset($data[array_search($avg, $data)]);
			$avg['time_point'] = $this->format_time($avg['time_point']);
			$avg['update_time'] = date('Y/m/d H:i', $avg['time_point']);
			$this->pm25core->update_aqi_data($avg, $data,$avg['time_point']);
			foreach($data as $val)
			{
				$val['time_point'] = $this->format_time($val['time_point']);
				$val['update_time'] = date('Y/m/d H:i', $val['time_point']);
				$_data[$val['station_code']] = $val;
			}
			$aqidata = array();
			$aqidata['avg'] = $avg;
			$aqidata['stations'] = $_data;
			hg_file_write($cache_lock, $avg['time_point']);
		}
		return $aqidata;
	}
	/**
	 * 地址	http://www.pm25.in/api/querys.json
	 * 方法	GET
	 * 参数	* 无
	 * 返回	* cities：值是一个数组
	 */
	function pm25cities()
	{
		$cities_cache = CACHE_DIR . 'cities.php';
		if(file_exists($cities_cache) && !$this->mUpdateCache)
		{
			return $cities = include($cities_cache);
		}
		$this->setUrl($this->settings['pm25api']['cities']);
		$this->setRequestParameters();
		$cities = $this->request();
		if($cities === false)
		{
			//
			$cities['cities'] = array();
		}
		hg_file_write($cities_cache, "<?php\nreturn ".var_export($cities['cities'],1).";\n?>");
		return $cities['cities'];
	}
	/**
	 * 获取一个城市的监测点列表
	 * 地址	http://www.pm25.in/api/querys/station_names.json
	 * 方法	GET
	 * 参数	* city：必选
	 * 返回	
	 * city
	 * stations：值是一个数组，里面的一个数组又包含了station_name和station_code     
	 */
	function city_stations()
	{
		//
		if((!$stations = $this->pm25core->get_city_stations()) || $this->mUpdateCache)
		{
			$parameters = array(
			'city'	=> $this->mCity,
			);
			$this->setUrl($this->settings['pm25api']['stations']);
			$this->setRequestParameters($parameters);
			$ret = $this->request();
			if($ret === false)
			{
				return $stations ? $stations : $this->pm25core->get_city_stations();
			}
			$this->pm25core->update_city_stations($ret);
			foreach($ret['stations'] as $val)
			{
				$stations[$val['station_code']] = $val['station_name'];
			}
		}
		return $stations;
	}
	/**
	 * 获取一个城市所有监测点的PM2.5数据
	 * city：城市名称，必选参数
	 * avg：是否返回一个城市所有监测点数据均值的标识，可选参数，默认是true，不需要均值时传这个参数并设置为false
	 * stations：是否只返回一个城市均值的标识，可选参数，默认是yes，不需要监测点信息时传这个参数并设置为no
	 */
	function pm25data()
	{
		$cache_lock = CACHE_DIR . 'PM2.5.lock';
		if(!file_exists($cache_lock) || TIMENOW - fileatime($cache_lock) > CACHE_TIME)
		{
			$this->mUpdateCache = 1;
		}
		if((!$pmdata = $this->pm25core->get_pm25_data()) || $this->mUpdateCache)
		{
			$parameters = array(
			'city'		=> $this->mCity, 
			'avg'		=> 'true',
			'stations'	=> 'yes',
			);
			$this->setUrl($this->settings['pm25api']['pm25data']);
			$this->setRequestParameters($parameters);
			$data = $this->request();
			if($data === false)
			{
				//请求接口出错
				return $pmdata ? $pmdata : $this->pm25core->get_pm25_data();
			}
			$avg = end($data);
			unset($data[array_search($avg, $data)]);
			$avg['time_point'] = $this->format_time($avg['time_point']);
			$avg['update_time'] = date('Y/m/d H:i', $avg['time_point']);
			$this->pm25core->update_pm25_data($avg, $data,$avg['time_point']);
			foreach($data as $val)
			{
				$val['time_point'] = $this->format_time($val['time_point']);
				$val['update_time'] = date('Y/m/d H:i', $val['time_point']);
				$_data[$val['station_code']] = $val;
			}
			$pmdata = array();
			$pmdata['avg'] = $avg;
			$pmdata['stations'] = $_data;
			hg_file_write($cache_lock, $avg['time_point']);
		}
		return $pmdata;
	}
	/**
	 * 获取一个城市所有监测点的AQI数据（含详情）
	 * city：必选
	 * avg：可选
	 * stations：可选
	 */
	function aqidata()
	{
		$parameters = array(
		'city'		=> $this->mCity, 
		'avg'		=> $this->input['avg'] ? 'true' : 'false',
		'stations'	=> $this->input['stations'] ? 'yes' : 'no',
		);
		$this->setUrl($this->settings['pm25api']['aqidata']);
		$this->setRequestParameters($parameters);
		$data = $this->request();
		if($data === false)
		{
			return array();
		}
		return $data;
	}
	function setUrl($url = '')
	{
		$this->url = $url;
	}
	protected function setRequestParameters($data = array())
	{
		$parameters = '?token=' . $this->pm2token;
		if($data)
		{
			foreach($data as $key=>$val)
			{
				$parameters .= '&' . $key . '=' . urlencode($val);
			}
		}
		$this->url .= $parameters;
	}
	public function request()
	{
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_HTTPHEADER,array("Content-Type: application/x-www-form-urlencoded; charset=UTF-8"));
		//curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 15);
		//curl_setopt($ch, CURLOPT_POSTFIELDS, $this->getRequestParameter());
        curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
		curl_close($ch);
		$decoded = json_decode($data, true);
		if(!is_array($decoded) || !$decoded || $decoded['error'])
		{
			return false;
		}
		return $decoded;
	}
	protected function format_time($time = '')
	{
		return $time = strtotime(trim(str_replace(array('T','Z'), ' ', $time)));
	}
}