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
		$sql = "SELECT * FROM " . DB_PREFIX . "baidu_zb";
		$q = $this->db->query($sql);
		$baidu_zb = array();
		while($r = $this->db->fetch_array($q))
		{
			if(!$r['baidu_x'] || !$r['baidu_y'])
			{
				continue;
			}
			$baidu_zb[] = $r;
		}
		if(!empty($baidu_zb))
		{
			foreach ($baidu_zb as $val)
			{
				$sql = "UPDATE " . DB_PREFIX . "station SET baidu_x = '{$val['baidu_x']}', baidu_y = '{$val['baidu_y']}' WHERE station_id = {$val['station_id']}";
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