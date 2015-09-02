<?php
//WEB视频的数据库操作
require_once(ROOT_PATH . 'frm/node_frm.php');
class webvodSort extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	/*public function create($info)
	{	
		//插入数据操作
		$sql = "INSERT INTO " . DB_PREFIX ."webvod SET ";
		$sql_extra = $space ='';
		foreach($info as $k=>$v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$this->db->query($sql);
		
		$_id = $this->db->insert_id();
	}*/
	
	//更新WEB视频相关信息
	public function update($info)
	{	
		//更新数据操作
		$sql = "UPDATE " . DB_PREFIX ."webvod SET ";
		$sql_extra = $space ='';
		foreach($info as $k=>$v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$sql .= " WHERE id =".$info['id'];
		$this->db->query($sql);		
	}
	
	//根据条件查询电视台
	public function show($condition,$limit)	
	{		
		$sql = "SELECT *
				FROM  " . DB_PREFIX ."categorys_tv 
				WHERE 1".$condition.$limit;
		$q = $this->db->query($sql);
		
		$sql_ = 'SELECT count(*) as total from '.DB_PREFIX.'categorys_tv WHERE 1 '.$condition.$limit;
		$re = $this->db->query_first($sql_);
		
		while($row = $this->db->fetch_array($q))
		{
			$row['name'] = $row['cp_name'];
			$row['is_last'] = 0;
			$row['fid'] = 0;
			$row['id'] = $row['cpid'];
			$row['input_k'] = "_id";	
			$row['total'] = $re['total'];	
			$ret[] = $row;
		}
		
		return $ret;
	}
	
	//根据条件查询频道
	public function show_webchannel($condition,$limit)	
	{		
		$sql = "SELECT *
				FROM  " . DB_PREFIX ."categorys
				WHERE 1 ".$condition.$limit;
		$q = $this->db->query($sql);
		
		$sql_ = 'SELECT count(*) as total from '.DB_PREFIX.'categorys WHERE 1 '.$condition.$limit;
		$re = $this->db->query_first($sql_);
		
		while($row = $this->db->fetch_array($q))
		{		
			$row['name'] = $row['title'];
			$row['is_last'] = 1;
			$row['fid'] = $row['cpid'];
			$row['para'] = $row['category_id'];
			$row['input_k'] = "_id";
			$row['total'] = $re['total']; 				
			$ret[] = $row;
		}
		
		return $ret;
	}
	
	//根据条件查询WEB视频内容
	public function show_webvod($condition,$limit)	
	{		
		$sql = "SELECT *
				FROM  " . DB_PREFIX ."webvod 
				WHERE 1".$condition.$limit;
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{				
			$ret[] = $row;
		}
		return $ret;
	}
}


?>