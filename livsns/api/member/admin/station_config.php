<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function show|detail|count
* @private function get_condition
* 
* $Id: station_config.php 17814 2013-02-25 01:47:55Z repheal $
***************************************************************************/
define('MOD_UNIQUEID','station_config');
require('global.php');
class stationConfigApi extends adminReadBase
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

	public function show()
	{	
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		
		$station_configs = $this->mStationConfig->_show($condition, $offset, $count);
		
		if (!empty($station_configs))
		{
			foreach ($station_configs AS $station_config)
			{
				$this->addItem($station_config);
			}
		}
	
		$this->output();
	}
	
	public function detail()
	{
		$id = urldecode($this->input['id']);
		$info = $this->mStationConfig->_detail($id);
		$this->addItem($info);
		$this->output();
	}

	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->mStationConfig->_count($condition);
		echo json_encode($info);
	}
	
	public function index()
	{
		
	}
	
	private function get_condition()
	{
		$condition = $this->mStationConfig->_get_condition();
		return $condition;
	}

}

$out = new stationConfigApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>