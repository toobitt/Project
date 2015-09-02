<?php

class plan_log extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function get_log($offset,$count,$condition)
	{
		$sql = "SELECT * FROM ".DB_PREFIX."plan_log ".$condition." LIMIT {$offset},{$count}";
		$info = $this->db->fetch_all($sql);
		return $info;
	}
	
}
?>