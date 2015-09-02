<?php
require('./global.php');
define('SCRIPT_NAME', 'catType');
class catType extends outReadBase
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	public function detail(){}
	public function count(){}
	function show()
	{
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;
		$count = $this->input['count'] ? $this->input['count'] : 1000;
		$limit = " LIMIT {$offset},{$count}";
		$sql = "SELECT * FROM ".DB_PREFIX."cat_type WHERE 1 ". $condition . $limit;
		$info = $this->db->query($sql);
		$ret = array();
		while($row = $this->db->fetch_array($info))
		{
			$row['icon'] = json_decode($row['log'],1);
			unset($row['log']);
			$ret[] = $row;	
		}
		if($ret && is_array($ret))
		{
			foreach ($ret as $k => $v)
			{
				$this->addItem($v);
			}
		}
		$this->output();
	}
	
	function get_condition()
	{
		$condition = '';
		return $condition;	
	}
}
include(ROOT_PATH . 'excute.php');
?>
