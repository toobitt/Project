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
		$sql = "SELECT * FROM ".DB_PREFIX."plan ".$condition." ORDER BY publish_time DESC LIMIT {$offset},{$count}";
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
	
	public function get_plan_set($ids)
	{
		$result = array();
		$sql = "SELECT * FROM ".DB_PREFIX."plan_set WHERE id in (".$ids.")";
		$info = $this->db->query($sql);
		while($row = $this->db->fetch_array($info))
		{
			$result[$row['id']] = $row;
		}
		return $result;
	}
	
	public function check_action_type($set_id,$from_id,$action_type)
	{
		if(!$set_id || !$from_id || !$action_type)
		{
			return false;
		}
		$sql = "SELECT id FROM ".DB_PREFIX."plan WHERE set_id=".$set_id." AND from_id=".$from_id." AND action_type='".$action_type."'";
		$info = $this->db->fetch_all($sql);
		if(empty($info))
		{
			return true;
		}
		$ids = '';
		foreach($info as $k=>$v)
		{
			$ids .= $v['id'].',';
		}
		$ids = trim($ids.',');
		if($ids)
		{
			$sql = "DELETE FROM ".DB_PREFIX."plan WHERE id in(".$ids.")";
			$this->db->query($sql);
		}
	}
	
}
?>