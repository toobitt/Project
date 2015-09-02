<?php
require_once('../admin/global.php');
class MessageGroup
{
	var $db;
	function __construct()
	{
		global $gDB,$gGlobalConfig;
		$this->db = $gDB;
		$this->settings = $gGlobalConfig;
	}
	function __destruct()
	{
	}
	
	/*
	*添加分组
	*
	*$group@array
	*
	*/
	public function add_group($data)
	{
		
		$sql = 'INSERT INTO '.DB_PREFIX.'message_group SET ';
		foreach($data as $k=>$v)
		{
			$sql .= '`'.$k . '`="' . $v . '",';
		}
		$sql = rtrim($sql,',');
		if($this->db->query($sql))
		{
			$data['gid'] = $this->db->insert_id();
			return $data;
		}
		else
		{
			return false;
		}
	}
}
?>