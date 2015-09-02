<?php
require('global.php');
class movie_area extends BaseFrm
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$sql = $sql = "select * from " . DB_PREFIX . "movie_area where 1 " . $this->get_condition() ;
		//$this->errorOutput($this->input['keyword']);
		$rows = $this->db->fetch_all($sql);
		$this->addItem($rows);
		$this->output();
	}
	
	public function area_detail()
	{
		if($this->input['id'])
		{
			$sql = $sql = "select * from " . DB_PREFIX . "movie_area where id = " . $this->input['id'];
			$query = $this->db->query($sql);
			$row = $this->db->fetch_array($query);
			$this->addItem( $row );
		}
		$this->output();
	}
	
	public function get_condition()
	{
		$condition = '';
		if ( $this->input['keyword'] )
		{
			$keyword = urldecode( $this->input['keyword'] );
			$condition .= " and name like '%$keyword%' ";
		}
		return $condition;
	}
	
	
	public function unknow()
	{
		$this->output("没有这个方法");
	}
}

$out = new movie_area();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();