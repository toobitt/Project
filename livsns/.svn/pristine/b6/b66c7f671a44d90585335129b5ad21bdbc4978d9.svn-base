<?php

class publish extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function get_plan_set()
	{
		$sql = "SELECT * FROM ".DB_PREFIX."plan_set ";
		$info = $this->db->fetch_all($sql);
		return $info;
	}
	
	public function get_plan_by_status($con)
	{
		$sql = "SELECT * FROM ".DB_PREFIX."plan WHERE 1 ".$con;
		$info = $this->db->fetch_all($sql);
		return $info;
	}
	
	public function update_plan_status($pubdataids,$status)
	{
		$sql = "UPDATE ".DB_PREFIX."plan SET status=".$status." WHERE id in(".$pubdataids.")";
		$this->db->query($sql);
	}
	
}
?>