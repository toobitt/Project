<?php

class videopoint extends InitFrm{
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
	public function count()
	{

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
	
	//返回视频的points
	public function detail($tbname,$id,$type='videoid')
	{
		if(!trim($tbname)||!$id)
			return false;
			
		$query = "select * from ".DB_PREFIX."$tbname where $type=$id order by point asc";
		
		//export_var("1.txt",$query,__LINE__,__FILE__,$flag=false);
		$result = $this->db->query($query);
		$datas = array();
		while(($row = $this->db->fetch_array($result))!=false)
		{ 	
			$row['create_time'] = date("Y-m-d H:i",$row['create_time']);
			$row['update_time'] = date("Y-m-d H:i",$row['update_time']);
			$datas[] = $row;
		}
		return $datas;	
	}
    
    
    public function show($tbname,$id,$type='videoid')
    {
        if(!trim($tbname)||!$id)
            return false;
            
        $query = "select * from ".DB_PREFIX."$tbname where $type in ($id) order by point asc";
        $result = $this->db->query($query);
        $datas = array();
        while(($row = $this->db->fetch_array($result))!=false)
        {   
            $row['create_time'] = date("Y-m-d H:i",$row['create_time']);
            $row['update_time'] = date("Y-m-d H:i",$row['update_time']);
            $datas[$row['videoid']][] = $row;
        }
        return $datas;  
    }
	
	public function delete($tbname,$cond='')
	{
		if(!trim($tbname)||!$cond)
			return false;
		
		$query = "delete from ".DB_PREFIX."$tbname $cond";
		return $this->db->query($query);
	}
	
	public function count_points($videoid)
	{
		
		$query = "select count(id) as total from ".DB_PREFIX."point where videoid=$videoid";
		$result = $this->db->query($query);
		$datas = array();
		$row = $this->db->fetch_array($result);
		return $row['total'];
		
	}
	    
}
?>