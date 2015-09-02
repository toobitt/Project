<?php
define('MOD_UNIQUEID','pm25');
define('SCRIPT_NAME', 'pm25');
define('ROOT_DIR', '../../');
require_once(ROOT_DIR.'global.php');
require_once(CUR_CONF_PATH . 'lib/pm25.class.php');
class pm25 extends outerReadBase
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
	
	function __construct()
	{
		parent::__construct();
		$this->pm2token = PM25TOKEN;
		$this->mUpdateCache = $this->input['update_cache'] ? true : false;
		$this->initCity();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	protected function initCity()
	{
		$this->cities = $this->pm25cities();
		$this->mCity = urldecode($this->input['city']);
		if(!$this->mCity || !in_array($this->mCity, $this->cities))
		{
			$this->addItem(array('error'=>"城市暂不提供PM2.5数据"));
			$this->output();
		}
		$this->pm25core = new pm25core($this->mCity);
	}
	//默认方法获取城市aqi平均值
	public function show()
	{
		$parameters = array(
		'city'		=> $this->mCity, 
		'avg'		=> 'true',
		'stations'	=> 'yes',
		);
		$cache_lock = CACHE_DIR . 'AQI.lock';
		if(!file_exists($cache_lock) || TIMENOW - fileatime($cache_lock) > CACHE_TIME)
		{
			$this->mUpdateCache = 1;
		}
		if((!$aqidata = $this->pm25core->get_aqi_data()) || $this->mUpdateCache)
		{
			$this->setUrl($this->settings['pm25api']['aqidata']);
			$this->setRequestParameters($parameters);
			$data = $this->request();
			$avg = end($data);
			unset($data[array_search($avg, $data)]);
			$this->pm25core->update_aqi_data($avg, $data,$avg['time_point']);
			foreach($data as $val)
			{
				$_data[$val['station_code']] = $val;
			}
			$aqidata = array();
			$aqidata['avg'] = $avg;
			$aqidata['stations'] = $_data;
			hg_file_write($cache_lock, $avg['time_point']);
		}
		foreach($aqidata as $k=>$val)
		{
			$this->addItem_withkey($k, $val);
		}
		$this->output();
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
			$this->pm25core->update_city_stations($ret);
			foreach($ret['stations'] as $val)
			{
				$stations[$val['station_code']] = $val['station_name'];
			}
		}
		$this->addItem($stations);
		$this->output();
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
			$avg = end($data);
			unset($data[array_search($avg, $data)]);
			$this->pm25core->update_pm25_data($avg, $data,$avg['time_point']);
			foreach($data as $val)
			{
				$_data[$val['station_code']] = $val;
			}
			$pmdata = array();
			$pmdata['avg'] = $avg;
			$pmdata['stations'] = $_data;
			hg_file_write($cache_lock, $avg['time_point']);
		}
		foreach($pmdata as $key=>$val)
		{
			$this->addItem_withkey($key, $val);
		}
		$this->output();
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
		foreach($data as $val)
		{
			$this->addItem($val);
		}
		$this->output();
	}
	/**
	 * 获取一个监测点的AQI数据（含详情）
	 */
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
		//curl_setopt($ch, CURLOPT_POSTFIELDS, $this->getRequestParameter());
        curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
		curl_close($ch);
		$decoded = json_decode($data, true);
		if(!is_array($decoded) || !$decoded)
		{
			echo $decoded;exit;
		}
		if($decoded['error'])
		{
			$this->addItem($decoded);
			$this->output();
		}
		return $decoded;
	}
	//抽象方法
	function index(){}
	function count(){}
	function get_condition(){}
	function detail(){}
}
include(ROOT_PATH . 'excute.php');