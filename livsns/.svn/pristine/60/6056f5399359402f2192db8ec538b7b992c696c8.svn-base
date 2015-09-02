<?php
//模板的数据库操作

class specialQueue extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	//新增模板
	/*public function create($info)
	{	
		//插入数据操作
		$sql = "INSERT INTO " . DB_PREFIX ."special_queue SET ";
		$sql_extra = $space ='';
		foreach($info as $k=>$v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$this->db->query($sql);
		return $this->db->insert_id();
	}
	
	//更新模板相关信息
	public function update($info)
	{	
		//更新数据操作
		$sql = "UPDATE " . DB_PREFIX ."special_queue SET ";
		$sql_extra = $space ='';
		foreach($info as $k=>$v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$sql .= " WHERE id =".$info['id'];
		$this->db->query($sql);		
	}*/
	
	//根据查询队列
	public function query($condition,$limit)	
	{		
		$sql = "SELECT id
				FROM  " . DB_PREFIX ."special_queue 
				WHERE 1";
		$q = $this->db->query($sql);
		
		while($row = $this->db->fetch_array($q))
		{	
			$row['create_time'] = date("Y-m-d H:i:s",$row['create_time']);			
			$ret[] = $row;
		}
		return $ret;
	}
	//删除主题
	public function delete($id)
	{			
		$sql = "DELETE FROM " . DB_PREFIX . "special_queue WHERE id =".$id;
		$this->db->query($sql);
		return ($this->db->affected_rows() > 0) ? true : false;
	}	
	
	//根据条件查询专题
	public function show($condition,$limit)	
	{	
		$str = " ORDER BY id DESC";
		$sql = "SELECT *
				FROM  " . DB_PREFIX ."special_queue 
				WHERE 1".$condition.$str.$limit;
		$q = $this->db->query($sql);
		
		while($row = $this->db->fetch_array($q))
		{
			$row['create_time'] = date("Y-m-d H:i:s",$row['create_time']);			
			$ret[] = $row;
		}
		return $ret;
	}
}


?>