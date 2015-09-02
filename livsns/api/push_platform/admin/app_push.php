<?php
define('SCRIPT_NAME', 'AddApp');
define('MOD_UNIQUEID','add_app');
require_once('./global.php');
class AddApp extends adminReadBase
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function index()
	{
	}
	function show()
	{
		$order = ' ORDER BY id DESC';
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";

		$sql = 'SELECT * FROM '.DB_PREFIX.'app_info  WHERE 1';
		$condition = $this->get_condition();		
		$sql = $sql . $condition . $order . $limit;
		
		
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$this->addItem($row);
		}
		$this->output();
	}
	private function get_condition()
	{
		$condition = '';
		if($this->input['k'])
		{
			$condition .= ' AND name LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		$id = intval($this->input['id']);
		if($id)
		{
			$condition .= ' AND id = '.$id;
		}
		return $condition;
	}
	function count()
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'app_info  WHERE 1'.$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}
	
	function detail()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput('未找到应用id');
		}
		$condition = $this->get_condition();
		$sql = "SELECT * FROM ".DB_PREFIX."app_info WHERE 1 ".$condition;
		
		$q = $this->db->query_first($sql);
		$this->addItem($q);
		$this->output();
	}
	
	function unknown()
	{
		$this->erroroutput(NO_METHOD);
	}
}
include(ROOT_PATH . 'excute.php');