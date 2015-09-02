<?php
define('MOD_UNIQUEID','value');
define('SCRIPT_NAME', 'value');
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require_once(ROOT_PATH.'global.php');
require_once(ROOT_PATH.'lib/class/curl.class.php');
class value extends InitFrm
{
	function __construct()
	{
		parent::__construct();
		$this->curl = new curl();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$code = trim($this->input['code']);
		if($code)
		{
			$sql = 'SELECT name FROM '.DB_PREFIX.'weather_city_code WHERE code = "'.$code.'"';
			$city_code = $this->db->query_first($sql);
			if($city_code['name'])
			{
				if(!file_exists($code.'.html'))
				{
					$configs = $this->settings['App_weather'];
					if($configs)
					{
						$this->curl = new curl($configs['host'], $configs['dir']);
						$this->curl->setSubmitType('get');
						$this->curl->setReturnFormat('json');
						$this->curl->initPostData();
						$this->curl->addRequestData('a','show');
						$this->curl->addRequestData('userInfo',$this->user);
						$this->curl->addRequestData('name',$city_code['name']);
						$re = $this->curl->request('weather.php');
					}
				}
				$info = @file_get_contents($code.'.html');
				echo $info;
			}
		}
	}
}
include(ROOT_PATH . 'excute.php');