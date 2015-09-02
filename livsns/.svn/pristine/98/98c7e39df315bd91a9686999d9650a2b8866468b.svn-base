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
class mail extends informcore
{
	

     public function __construct()
    {
    	parent::__construct();	
    }
	
	/*
	 * 获取视频
	 */
	public function get_mail($cond,$fields=" * ")
	{
		
		//liv_send a  mail_id   liv_mail b id
		$query 		= "select $fields from ".DB_PREFIX."send a,".DB_PREFIX."mail b  where 1 AND a.mail_id=b.id $cond";
		
		export_var('query',$query);
		$result		= $this->db->query($query);
		$datas		= array();
		while($row = $this->db->fetch_array($result))
		{ 	
			$row['create_time'] = date("Y-m-d H:i",$row['create_time']);
			$row['update_time'] = date("Y-m-d H:i",$row['update_time']);
			$datas[$row['id']] = $row;
		}
		return $datas;	
		
	}
	
	public function count($cond)
	{
		$query 		= "select count(*) as total from ".DB_PREFIX."send a,".DB_PREFIX."mail b  where 1 AND a.mail_id=b.id $cond";
		
		$result 	= $this->db->query_first($query);
		return $result;
	}
	
	
	/*
	 * 删除视频
	 * @param:$ids string or array
	 * 如果$ids 是一个string 则类似 1,2,3
	 * 如果$ids 是一个array 则类似 array(1,2,3)
	 */
	 public function delete_mail($ids,$type)
	 {
	 	//只能通过send表删除
	 	$result = false;
	 	if($type=='via_send')
	 	{
	 		$result = $this->delete_data('send','id',$ids);
	 	}
	 	
	 	return $result;
	 		
	 }
	 
	/*
	 * @fuction:增加一条mail
	 */
	public function create_token($data)
	{
		$tbname = "token";
		return $this->insert_data($tbname,$data);
	}
	
	public function create_send($data)
	{
		$tbname = "send";
		return $this->insert_data($tbname,$data);
	}
	
	public function create_mail($data)
	{
		$tbname = "mail";
		return $this->insert_data($tbname,$data);
	}
	
	 

	
	public function __destruct()
	{
		parent::__destruct();
	}

}
?>
