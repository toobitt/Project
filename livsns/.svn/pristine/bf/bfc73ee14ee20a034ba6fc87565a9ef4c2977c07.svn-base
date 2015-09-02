<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id$
***************************************************************************/
require_once('global.php');
define('MOD_UNIQUEID', 'getArea');
require_once(CUR_CONF_PATH . 'lib/area_mode.php');
define('MOD_UNIQUEID', 'get_area');

class getArea extends outerReadBase
{
	private $mode;
	public function __construct()
	{
		parent::__construct();
		$this->mode = new area_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

    public function detail(){}
    public function count(){}
    
    //默认获取省
	public function show()
	{
		$ret = $this->mode->show();
		if(!empty($ret))
		{
			foreach($ret as $k => $v)
			{
				$this->addItem($v);
			}
			$this->output();
		}
	}
	
	//获取城市
	public function getCity()
	{
		$province_code = $this->input['province_code'];
		if(!$province_code)
		{
			$this->errorOutput(NO_PROVINCE_CODE);
		}
		
		$city = $this->mode->getCity($province_code);
		if(!empty($city))
		{
			foreach($city as $k => $v)
			{
				$this->addItem($v);
			}
			$this->output();
		}
	}
	
	//获取区县
	public function getDistrict()
	{
		$city_code = $this->input['city_code'];
		if(!$city_code)
		{
			$this->errorOutput(NO_CITY_CODE);
		}
		
		$district = $this->mode->getDistrict($city_code);
		if(!empty($district))
		{
			foreach($district as $k => $v)
			{
				$this->addItem($v);
			}
			$this->output();
		}
	}
}

$out = new getArea();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'show';
}
$out->$action();
?>