<?php

class plan_set extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function insert_set($data)
	{
		$sql="INSERT INTO " . DB_PREFIX . "plan_set SET";
		
		$sql_extra=$space=' ';
		foreach($data as $k => $v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$this->db->query($sql);
		return $this->db->insert_id();
	}
	
	public function get_all_set($field = ' * ')
	{
		$sql = "SELECT ".$field." FROM " . DB_PREFIX . "plan_set";
		return $this->db->fetch_all($sql);
	}
	
	public function get_set($id)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "plan_set WHERE id=".$id;
		return $this->db->query_first($sql);
	}
	
	public function get_set_by_fid($field,$condition,$offset,$count)
	{
		$sql = "SELECT ".$field." FROM " . DB_PREFIX . "plan_set WHERE 1 ".$condition." LIMIT {$offset},{$count} ";
		$q = $this->db->query($sql);
		$result = array();
		while ($row = $this->db->fetch_array($q))
		{
			$sql2 = "SELECT * FROM " . DB_PREFIX . "plan_set WHERE fid=".$row['id'];
			$q2 = $this->db->fetch_all($sql2);
			$row['have_child'] = empty($q2)?0:1;
			$result[] = $row;
		}
		return $result;
	}
	
	public function get_set_node($field,$fid)
	{
		$sql = "SELECT ".$field." FROM " . DB_PREFIX . "plan_set WHERE 1 AND fid=".$fid;
		return $this->db->fetch_all($sql);
	}
	
	public function update_set($data,$id)
	{
		$sql="UPDATE " . DB_PREFIX . "plan_set SET";
		
		$sql_extra=$space=' ';
		foreach($data as $k => $v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$sql .= " WHERE id=".$id;
		$this->db->query($sql);
	}
	
	public function get_father_set($set_id , &$data = array())
	{
		$set_detail =  $this->get_set($set_id);
		$set_all    =  $this->get_set_node(' id,name,fid ',$set_detail['fid']);
		$set_all['select_column'] = $set_id;
		if(!empty($set_all))
		{
			array_unshift($data,$set_all);
		}
		if($set_detail['fid'])
		{
			$this->get_father_set($set_detail['fid'] , $data);
		}
	}
	
}
?>