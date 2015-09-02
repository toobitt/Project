<?php

class publish_plan extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function get_plan($offset,$count,$condition)
	{
		$sql = "SELECT * FROM ".DB_PREFIX."plan ".$condition." ORDER BY id DESC LIMIT {$offset},{$count}";
		$info = $this->db->fetch_all($sql);
		return $info;
	}
	
	public function insert_queue($data)
	{
		$sql="INSERT INTO " . DB_PREFIX . "plan SET";
		
		$sql_extra=$space=' ';
		foreach($data as $k => $v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$this->db->query($sql);
	}
	
}
?>