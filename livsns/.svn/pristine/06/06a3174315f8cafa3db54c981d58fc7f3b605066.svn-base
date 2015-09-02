<?php
define('MOD_UNIQUEID','dingdonestatistics');//模块标识
require_once ('./global.php');
require_once (CUR_CONF_PATH . 'lib/statistics.class.php');
class statisticsApi extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->statistics = new statistics();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}
	
	public function show()
	{
		$condition = $this->get_condition();
		$info = $this->statistics->show($condition);
		$this->addItem($info);
		$this->output();				
	}
	
	public function count()
	{
		
	}
	
	public function detail()
	{
		
	}
		
	private function get_condition()
	{
		$condition = array();
		if ($this->input['type'] && in_array($this->input['type'], array('hour','day','week','month','quarter','year')))
		{
			$condition['type'] = $this->input['type'];
		}
		else 
		{
			$condition['type'] = 'day';
		}
		if ($this->input['start_time'])
		{
			$condition['start_time'] = $this->input['start_time'];
		}
		else 
		{
			$condition['start_time'] = date('Y-m-d H:i:s',mktime(0,0,0, date('m')-1, date('d'), date('Y')));	
		}
		if ($this->input['end_time'])
		{
			$condition['end_time'] = $this->input['end_time'];
		}
		return $condition;	
	}
}

$out = new statisticsApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>
