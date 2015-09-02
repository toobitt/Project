<?php
require ('./global.php');
define('MOD_UNIQUEID','gongjiao');
define('SCRIPT_NAME', 'BaiduZb');
class BaiduZb extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	public function count(){}	
	
	public function show()
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "station_qs";
		$q = $this->db->query($sql);
		$station = array();
		while($r = $this->db->fetch_array($q))
		{
			if(!$r['start_station'] || !$r['end_station'])
			{
				continue;
			}
			$station[] = $r;
		}
		
		//hg_pre($station,0);
		if(!empty($station))
		{
			foreach ($station as $val)
			{
				$sql = "UPDATE " . DB_PREFIX . "line SET start_station = '{$val['start_station']}', end_station = '{$val['end_station']}' WHERE line_no = '{$val['line_no']}'";
				$this->db->query($sql);
			}
		}
	}
	
	
	public function update_ok()
	{
		
	}
	public function get_condition()
	{
		$condition = '';
		return $condition ;
	}
	public function detail()
	{
	}
}
include(ROOT_PATH . 'excute.php');
?>