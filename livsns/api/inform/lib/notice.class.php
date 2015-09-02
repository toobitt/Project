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
class notice extends informcore
{

    public function __construct()
    {
    	parent::__construct();	
    }
	
	/*
	 * 获取视频
	 */
	public function get_notice($cond,$fields=" * ")
	{
		$query 		= "select $fields from ".DB_PREFIX."notice_content a,".DB_PREFIX."notice b  where 1 AND a.id=b.notice_id $cond";
		
		//export_var('query',$query);
		$result		= $this->db->query($query);
		$datas		= array();
		while($row = $this->db->fetch_array($result))
		{ 	
			$row['create_time'] = date("Y-m-d H:i",$row['create_time']);
			$row['update_time'] = date("Y-m-d H:i",$row['update_time']);
			$row['due_time']    = date("Y-m-d H:i",$row['due_time']);
			$datas[$row['id']] = $row;
		}
		return $datas;	
		
		
		
	}
	
	public function count($cond)
	{
		$query 		= "select count(*) as total from ".DB_PREFIX."notice_content a,".DB_PREFIX."notice b  where 1 AND a.id=b.notice_id $cond";
		
		$result 	= $this->db->query_first($query);
		return $result;
	}
	
	
	/*
	 * 删除视频
	 * @param:$ids string or array
	 * 如果$ids 是一个string 则类似 1,2,3
	 * 如果$ids 是一个array 则类似 array(1,2,3)
	 */
	 public function delete_notice($ids,$type)
	 {
	 	//删除内容
	 	$result = false;
	 	if($type=='via_content')
	 	{
	 		$result = $this->delete_data('notice','notice_id',$ids);
	 		if($result==true)
	 			$result = $this->delete_data('notice_content','id',$ids);
	 		
	 	}
	 	
	 	//删除某条公告
	 	if($type=='via_notice')
	 	{
	 		$result = $this->delete_data('notice','id',$ids);
	 		
	 	}
	 	return $result;
	 		
	 }
	 
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
	 

	
	public function __destruct()
	{
		parent::__destruct();
	}

}
?>
