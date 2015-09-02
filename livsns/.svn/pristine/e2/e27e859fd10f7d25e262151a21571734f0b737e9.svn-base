<?php
/*******************************************************************
 * LivSNS 0.1
 * (C) 2004-2013 HOGE Software
 * 
 * filename :noticecore.class.php
 * package  :package_name
 * Created  :2013-5-21,Writen by scala
 * 
 ******************************************************************/
class informcore  extends InitFrm
{
	
	public $auth;
    public function __construct()
    {
    	parent::__construct();
    	include_once ROOT_PATH."lib/class/auth.class.php";
		$this->auth = new auth();	
    }

	/*
	 * @function:count
	 * @params:
	 * $tbname:type string
	 * $cond:type string
	 * @resturn :$f int
	 */
    public function count($dbname,$cond)
	{
		$query 	= "SELECT COUNT(*) AS total FROM " . DB_PREFIX  . "$dbname a 
				WHERE 1 " . $cond;
		$result = $this->db->query_first($query);
		return $result;
	}	
	
	
	/*******************************************************/
	/*
	 * @function:insert a data向某张表插入数据
	 * @params:
	 * $tbname:type string
	 * $data:type array with key which match the field 
	 * @return:insert_id:type int
	 */
	public function insert_data($tbname,$data)
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
	/*
	 * @function:update a data
	 * @params:
	 * $tbname:type string
	 * $data:type array with key which match the field 
	 * @return:affected_rows:type int
	 */	
	public function update_data($tbname,$data,$cond='')
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
	
	public function delete_data($tbname,$index,$ids='')
	{
		if(!trim($tbname)||!$ids)
			return false;
		if(is_array($ids))
			$cond = implode(',',$ids);
		
		$query = "delete from ".DB_PREFIX."$tbname where $index in ($ids)";
		
		return $this->db->query($query);
	}

	

	
	public function __destruct()
	{
		parent::__destruct();
	}

}
?>
