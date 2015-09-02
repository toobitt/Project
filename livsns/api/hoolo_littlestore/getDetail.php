<?php
define('MOD_UNIQUEID','littlestore');
require_once ('./global.php');
class getDetail extends outerReadBase
{
		
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function detail(){}
	public function count(){}
	public function show()
	{
		$id = intval($this->input['road_id']);
		if(!$id)
		{
			$this->errorOutput('NOID');
		}
		$sql = "SELECT r.*,g.title AS sort_name, g.log AS icon, g.color FROM " . DB_PREFIX ."road r " .
				"LEFT JOIN ".DB_PREFIX."group g " .
					"ON r.group_id = g.id " .
				"WHERE r.id = " . $id ." AND r.state = 1 ";
		$ret = $this->db->query_first($sql);
		if(!$ret)
		{
			$this->errorOutput('NOTEXISTS');
		}
		$ret['pic'] = json_decode($ret['pic'],1);			
		$ret['picsize'] = json_decode($ret['picsize'],1);
		$ret['icon'] = json_decode($ret['icon'],1);
		if ($ret['baidu_latitude'] != '0.00000000000000')
		{
			$ret['latitude'] = $ret['baidu_latitude'];
		}
		if ($ret['baidu_longitude'] != '0.00000000000000')
		{
			$ret['longitude'] = $ret['baidu_longitude'];
		}
		$this->addItem($ret);
		$this->output();
	}	
}
$out = new getDetail();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>