<?php
/*******************************************************************
 * LivSNS 0.1
 * (C) 2004-2013 HOGE Software
 * 
 * filename :Core.php
 * package  :package_name
 * Created  :2013-7-23,Writen by scala yuanzhigang@scalachina.com
 * export_var(get_filename()."_b.txt",var,__LINE__,__FILE__);
 * 
 ******************************************************************/
class Core extends InitFrm{
	public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	public function insert($tbname,$data)
	{
		if(trim($tbname)==false)
			return false;
		$query = "insert into ".DB_PREFIX."$tbname set ";
		
		if(!is_array($data))
		{
			$this->db->query($query.$data);
			return $this->db->insert_id();
		}

		foreach ($data as $field => $val)
			$query .= "`$field` = '".$val."',";
        $query = substr($query,0,-1);
		$this->db->query($query);
		return $this->db->insert_id();
	}
	public function count($tbname,$cond='')
	{
		if(!trim($tbname))
			return false;
		$query = "select count(id) as total from ".DB_PREFIX."$tbname $cond";
		$result = $this->db->query($query);
		return $this->db->fetch_array($result);
	}
	public function update($tbname,$data,$cond='')
	{
		if(!trim($tbname)||!$cond)
			return false;
		
		$query = "update ".DB_PREFIX."$tbname set ";
		
		if(is_string($data))
		{
			$this->db->query($query.$data.$cond);
			return $this->db->affected_rows();
		}
		
		foreach ($data as $field => $val)
			$query .= "`$field` = '".$val."',";
		$query = substr($query,0,-1);	
		$this->db->query($query.$cond);
		return $this->db->affected_rows();
				
	}
	public function detail($tbname,$cond='')
	{
		if(!trim($tbname)||!$cond)
			return false;
		$query = "select * from ".DB_PREFIX."$tbname $cond";
		$result = $this->db->query($query);
		$row = $this->db->fetch_array($result);
		if(!$row)
			return false;
		$row['create_time'] = date("Y-m-d H:i",$row['create_time']);
		$row['update_time'] = date("Y-m-d H:i",$row['update_time']);
		return $row;	
	}
	public function delete($tbname,$cond='')
	{
		if(!trim($tbname)||!$cond)
			return false;
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
			$row['create_time'] = date("Y-m-d H:i",$row['create_time']);
			$row['update_time'] = date("Y-m-d H:i",$row['update_time']);
			$info[$row['id']] = $row;
		}
		return $info;			
	}

	//optb
	//显示字段的全部信息
	public function desc_tb($tbname)
	{
		$query = "SHOW FULL COLUMNS FROM ".DB_PREFIX."$tbname";
		$result = $this->db->query($query);
		$datas = array();
		while(($row = $this->db->fetch_array($result))!=false)
		{
			$datas[$row['Field']] = $row;
		}
		//unset($datas[0]);
		return $datas;
	}
	

}
?>
