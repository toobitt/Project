<?php
require('global.php');
define('MOD_UNIQUEID','layout_node');//模块标识
class layoutNode extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	function show()
	{	
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? intval(urldecode($this->input['offset'])) : 0;
		$count = $this->input['count'] ? intval(urldecode($this->input['count'])) : 20;
		$limit = " LIMIT {$offset}, {$count}";
		$sql = "SELECT * FROM " .DB_PREFIX. "layout_node WHERE 1 " . $condition . $limit;
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$row['create_time_show'] = date('Y-m-d H:i:s', $row['create_time']);
			$row['update_time_show'] = date('Y-m-d H:i:s', $row['update_time']);
			$this->addItem($row);
		}
		$this->output();	
	}
	
	function show_node()
	{
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? intval(urldecode($this->input['offset'])) : 0;
		$count = $this->input['count'] ? intval(urldecode($this->input['count'])) : 200;
		$limit = " LIMIT {$offset}, {$count}";
		$sql = "SELECT * FROM " .DB_PREFIX. "layout_node WHERE 1 " . $condition . $limit;
		$q = $this->db->query($sql);
		$ret = array();
		while($row = $this->db->fetch_array($q))
		{
			$row['create_time_show'] = date('Y-m-d H:i:s', $row['create_time']);
			$row['update_time_show'] = date('Y-m-d H:i:s', $row['update_time']);
			$ret[] = $row;
		}
		$this->addItem($ret);
		$this->output();	
	}
	
	function detail()
	{	
		$id = intval($this->input['id']);
		$data_limit = $id ? " LIMIT 1 " : " AND id = " . $id;
		$sql = "SELECT * FROM " . DB_PREFIX . "layout_node WHERE 1 " . $data_limit;
		$info = $this->db->query_first($sql);
		if ($info) {
			$info['create_time_show'] = date('Y-m-d H:i', $info['create_time']);
			$info['update_time_show'] = date('Y-m-d H:i', $info['update_time']);
		}
		$this->addItem($info);
		$this->output();		
	}
		
	
	public function count()
	{	
		$sql = 'SELECT count(*) as total from '.DB_PREFIX.'layout_node WHERE 1 '.$this->get_condition();
		$total = $this->db->query_first($sql);
		echo json_encode($total);	
	}
	
    function get_condition()
	{		
		$condition = '';
		return $condition;
	}
	
	function index()
	{	
	}
}

$out = new layoutNode();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>
