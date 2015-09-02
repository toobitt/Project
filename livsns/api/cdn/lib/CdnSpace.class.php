<?php

class CdnSpace extends InitFrm{
	public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	public function create($tbname,$data)
	{
		if(!trim($tbname))
		{
			return false;
		}
		$query = "insert into ".DB_PREFIX."$tbname set ";

		if(!is_array($data))
		{
			$this->db->query($query.$data);
			return $this->db->insert_id();
		}

		foreach ($data as $field => $val)
		{
			$query .= "`$field` = '".$val."',";
		}
		$query = substr($query,0,-1);
		$this->db->query($query);
		return $this->db->insert_id();
	}
	
	
	public function update($table,$data)
	{
		
		$sql = "UPDATE " . DB_PREFIX ."$table SET ";
		$sql_extra = $space ='';
		foreach($data as $k=>$v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$sql .= " WHERE id =".$data['id'];
		//file_put_contents('0s',$sql);
		$this->db->query($sql);		
		return $this->db->affected_rows();

	}
	
	public function delete($tbname,$cond='')
	{
		if(!trim($tbname)||!$cond)
		{
			return false;
		}
		$query = "delete from ".DB_PREFIX."$tbname $cond";
		return $this->db->query($query);
	}
	
	public function show($tbname,$cond='',$fields='*')
	{
		$query = "select $fields from ".DB_PREFIX."$tbname $cond";
		
		$q = $this->db->query($query);
		$info = array();
		while(($row = $this->db->fetch_array($q))!=false)
		{
			$row['create_time'] = date("Y-m-d H:i:s",$row['create_time']);
			$row['update_time'] = date("Y-m-d H:i:s",$row['update_time']);
			$row['type'] = $this->settings['cdn']['space_type'][$row['type']];
			
			$info[$row['id']] = $row;
		}
		return $info;
	}


}
?>
