<?php

class get_content extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function check_field($check_field,$field)
	{
		$f = explode(',',$check_field);
		$fall = explode(',',$field);
		foreach($f as $k=>$v)
		{
			if(!in_array($v,$fall))
			{
				return false;
			}
		}
		return true;
	}	
	
	public function get_content($field,$tablename,$offset,$count)
	{
		$sql = "SELECT ".$field." FROM ".$tablename." LIMIT " . $offset . " , " . $count;
		return $this->db->fetch_all($sql);
	}
	
	public function get_content_detail($field,$tablename,$id)
	{
		$sql = "SELECT ".$field." FROM ".$tablename." WHERE id=".$id;
		return $this->db->query_first($sql);
	}
	
}
?>