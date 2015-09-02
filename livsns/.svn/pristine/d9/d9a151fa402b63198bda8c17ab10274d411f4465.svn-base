<?php
require('global.php');
define('SCRIPT_NAME', 'carType');
class carType extends adminReadBase
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}
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
			$row['log'] = json_decode($row['log'],1);
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
	
	function detail()
	{
		$id = intval($this->input['id']);
		if($id)
		{
			$data_limit = " AND id = " . $id;
		}
		else
		{
			$data_limit = " LIMIT 1";
		}
		$sql = "SELECT * FROM ". DB_PREFIX ."cat_type WHERE 1 ". $data_limit;
		$ret = $this->db->query_first($sql);
		if($ret)
		{
			$ret['log_img'] = json_decode($ret['log'],1);
			$this->addItem($ret);
			$this->output();
		}
		else
		{
			$this->errorOutput('查询失败');
		}
	}
	
	function get_condition()
	{
		$condition = '';
		return $condition;	
	}
}
include(ROOT_PATH . 'excute.php');
?>
