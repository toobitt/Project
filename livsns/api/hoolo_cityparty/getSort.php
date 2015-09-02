<?php
define('MOD_UNIQUEID','activity');
require_once ('./global.php');
class getSort extends outerReadBase
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
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$data_limit = ' LIMIT ' . $offset . ',' . $count;
		$sql  = "SELECT * FROM " . DB_PREFIX ."group  WHERE 1 " . $condition . $data_limit;
		$info = $this->db->query($sql);	
		$ret = array();
		while ($row = $this->db->fetch_array($info) ) 
		{
			$row['icon'] = json_decode($row['log'], true);
			
			unset($row['log'], $row['ip'], $row['user_name']);
			$ret[] = $row;
		}
		foreach($ret as $k => $v)
		{
			$this->addItem($v);
		}
		$this->output();
	}
	
	public function get_condition()
	{
		$condition = '';
		return $condition ;
	}
}
$out = new getSort();
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