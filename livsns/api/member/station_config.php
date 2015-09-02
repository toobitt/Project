<?php
/***************************************************************************
* $Id: station_config.php 19006 2013-03-21 09:07:30Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','member');//模块标识
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_PATH."global.php");
require(CUR_CONF_PATH."lib/functions.php");
class stationConfigApi extends appCommonFrm
{
	private $mStationConfig;
	public function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/station_config.class.php';
		$this->mStationConfig = new stationConfig();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function get_station_config()
	{
		$condition = " AND status = 1 ";
		
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		
		$station_configs = $this->mStationConfig->_show($condition, $offset, $count);
		
		if (!empty($station_configs))
		{
			$station_config = array();
			foreach ($station_configs AS $v)
			{
				$station_config['id'] 		= $v['id'];
				$station_config['name'] 	= $v['name'];
				$station_config['platform'] = $v['platform'];
				$station_config['callback'] = $v['callback'];

				$this->addItem($station_config);
			}
		}
		$this->output();
	}

	public function unknow()
	{
		$this->errorOutput('此方法不存在！');
	}
	

}

$out = new stationConfigApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'get_station_config';
}
$out->$action();
?>