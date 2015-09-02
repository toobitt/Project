<?php
/*******************************************************************
 * LivSNS 0.1
 * (C) 2004-2013 HOGE Software
 * 
 * filename :notice.class.php
 * package  :package_name
 * Created  :2013-5-21,Writen by scala
 * 
 ******************************************************************/
class notice extends InitFrm
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
	 * @fuction:增加一条公告
	 */
	public function create_notice($data)
	{
		$tbname = "notice";
		return $this->insert_data($tbname,$data);
	}
	
	/*
	 * @fuction:增加一条公告
	 */
	public function create_notice_content($data)
	{
		$tbname = "notice_content";
		return $this->insert_data($tbname,$data);
	}
	
		
	
	/*
	 * @function:删除公告数据
	 */
	public function delete_notice($cond)
	{
		$tbname = "notice";
		return $this->delete_data($tbname,$cond);
	}
	public function delect_notice_content($cond)
	{
		$tbname = "notice_content";
		return $this->delete_data($tbname,$cond);
	}
	
	/*
	 * @function:更新公告数据
	 */
	public function update_notice($data,$cond)
	{
		$tbname = "notice_content";
		return $this->update_data($tbname,$data,$cond);
	}
	/*
	 * @function:获取一条公告数据
	 */
	public function get_notice_detail($cond,$fields= ' * ')
	{
		$query = "select $fields from ".DB_PREFIX."notice_content a where 1  $cond";
		
		return $this->db->query_first($query);
	}
	
	public function get_notice_all($cond,$fields=" * ")
	{
		$query = "select $fields from ".DB_PREFIX."notice_content a where 1  $cond";
		export_var('sql1',$query);
		
		$q = $this->db->query($query);
		$info = array();
		while($row = $this->db->fetch_array($q))
		{ 	
			$row['create_time'] = date("Y-m-d H:i",$row['create_time']);
			$row['update_time'] = date("Y-m-d H:i",$row['update_time']);
			$row['due_time']    = date("Y-m-d H:i",$row['due_time']);
			$info[$row['id']] = $row;
		}
		return $info;	
		
	}
	
    /*
     * @function:获取已经阅读过的公告条数
     * @params:$user_id int
     * @return:$nums int
     */
	public function readnums($user_id)
	{
		
		$tbname = 'readnotice';
		$cond =  " and user_id=".$user_id;
		$nums = $this->count($tbname,$cond);
		return $nums;
		
	}
	/*
	 * @function:获取未读过的公告的条数
	 * @params:$user_id int
	 * @return:$total-$readnums   type int
	 */
	public function noreadnums($user_id)
	{
		$readnums = $this->readnums($user_id);
		$tbname = 'notice';
		$cond = ' and user_id='.$user_id.' and type ='.NOTICE_VERIFIED_TYPE;
		$total = $this->count($tbname,$cond);
		return $total - $readnums;
		
	}
	/*******************************************************/
	
	
	public function __destruct()
	{
		parent::__destruct();
	}

}
?>
