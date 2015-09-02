<?php
/*******************************************************************
 * LivSNS 0.1
 * (C) 2004-2013 HOGE Software
 * 
 * filename :message.class.php
 * package  :package_name
 * Created  :2013-5-21,Writen by scala
 * 
 ******************************************************************/
class message extends InitFrm
{
	

    public function __construct()
    {
    	parent::__construct();	
    }

	/*
	 * @function:count
	 * @params:
	 * $tbname:type string
	 * $cond:type string
	 * @resturn :$f int
	 */
    public function count($tbname,$cond)
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX  . "$tbname a 
				WHERE 1 " . $cond;
		$f = $this->db->query_first($sql);
		return $f;
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
		{
			$query .= "`$field` = '".$val."',";
		}
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
	
	public function delete_data($tbname,$cond='')
	{
		if(!trim($tbname)||!$cond)
			return false;
		
		$query = "delete from ".DB_PREFIX."$tbname $cond";
		return $this->db->query($query);
	}
	/*******************************************************/
	
	
	
	
	
	
	/*******************************************************/
	/*
	 * @fuction:增加一条消息
	 */
	public function create_message($data)
	{
		$tbname = "message";
		return $this->insert_data($tbname,$data);
	}
	/*
	 * @function:删除消息数据
	 */
	public function delete_message($cond)
	{
		$tbname = "message";
		return $this->delete_data($tbname,$cond);
	}
	/*
	 * @function:更新消息数据
	 */
	public function update_message($data,$cond)
	{
		$tbname = "message";
		return $this->update_data($tbname,$data,$cond);
	}
	/*
	 * @function:获取一条消息数据
	 */
	public function get_message_detail($cond,$fields= ' * ')
	{
		$query = "select $fields from ".DB_PREFIX."message a where 1  $cond";
		$row = $this->db->query_first($query);
		$row['create_time'] = date("Y-m-d H:i",$row['create_time']);
		$row['update_time'] = date("Y-m-d H:i",$row['update_time']);
		return $row;
		
		
	}
	
	public function get_message_all($cond,$fields=" * ")
	{
		$query = "select $fields from ".DB_PREFIX."message a where 1  $cond";
		
		$q = $this->db->query($query);
		$info = array();
		while($row = $this->db->fetch_array($q))
		{ 	
			$row['create_time'] = date("Y-m-d H:i",$row['create_time']);
			$info[$row['id']] = $row;
		}
		return $info;	
	}
	
	
	//通过用户id获取收到的信息
	public function get_rmessages_by_userid($userid,$fields= ' * ')
	{
		$query = "select $fields from ".DB_PREFIX."message a,".DB_PREFIX."session b where a.id=b.message_id and b.to_user_id=".$userid;
		$q = $this->db->query($query);
		$info = array();
		while($row = $this->db->fetch_array($q))
		{ 	
			$row['create_time'] = date("Y-m-d H:i",$row['create_time']);
			$info[$row['id']] = $row;
		}
		return $info;	
	}
	
	public function get_rmessages_num($userid)
	{
			$query = "select count(*) as total from ".DB_PREFIX."message a,".DB_PREFIX."session b where a.id=b.message_id and b.to_user_id=".$userid." and b.state=0";
			
			export_var('query',$query);
			$q = $this->db->query_first($query);
			
			return $q;
	}
	
	
	/*******************************************************/
	
	
	
	/*******************************************************/
	public function create_session($data)
	{
		$tbname = "session";
		return $this->insert_data($tbname,$data);
	}
	
	
	public function delete_session($cond)
	{
		$tbname = "session";
		return $this->delete_data($tbname,$cond);
	}
	/*
	 * @function:更新消息数据
	 */
	public function update_session($data,$cond)
	{
		$tbname = "session";
		return $this->update_data($tbname,$data,$cond);
	}
	/*
	 * @function:获取一条消息数据
	 */
	public function get_session_detail($cond,$fields= ' * ')
	{
		$query = "select $fields from ".DB_PREFIX."session a where 1  $cond";
		return $this->db->query_first($query);
	}
	
	public function get_session_all($cond,$fields=" * ")
	{
		$query = "select $fields from ".DB_PREFIX."session a where 1  $cond";
		$q     = $this->db->query($query);
		$info = array();
		while(($row = $this->db->fetch_array($q))!=false)
		{ 	
			$row['create_time'] = date("Y-m-d H:i",$row['create_time']);
			$info[$row['id']] = $row;
		}
		return $info;	
		
	}
	 
	
	/*******************************************************/
	

	
	public function __destruct()
	{
		parent::__destruct();
	}

}
?>
