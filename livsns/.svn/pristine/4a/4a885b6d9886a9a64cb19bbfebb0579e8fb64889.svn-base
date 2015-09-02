<?php
//模板的数据库操作

class scenicSpots extends InitFrm
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
	public function create($info)
	{	
		//插入数据操作
		$sql = "INSERT INTO " . DB_PREFIX ."scenic_spots SET ";
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
		$sql = "UPDATE " . DB_PREFIX ."scenic_spots SET ";
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
	
	//删除主题
	public function delete($id)
	{			
		$sql = "DELETE FROM " . DB_PREFIX . "scenic_spots WHERE id =".$id;
		$this->db->query($sql);
		return ($this->db->affected_rows() > 0) ? true : false;
	}	
	
	//根据条件查询专题
	public function show($condition,$limit)	
	{		
		$sql = "SELECT *
				FROM  " . DB_PREFIX ."scenic_spots 
				WHERE 1".$condition.' ORDER BY id DESC '.$limit;
		$q = $this->db->query($sql);
		$sql_ = "select name,id from " . DB_PREFIX . "scenic_sort where 1";
		$sorts = $this->db->fetch_all($sql_);
		
		while($row = $this->db->fetch_array($q))
		{				
			foreach ($sorts as $k=>$v){			
				if( $v['id']== $row['sort_id']){
					$row['sort_name'] = $v['name'];
				}
				$row['cre_time'] = date("Y-m-d H:i",$row['create_time']);
			}	
			$ret[] = $row;
		}
		//file_put_contents('00',var_export($ret,1));
		return $ret;
	}
	
	//新增介绍
	public function insert_content($scenic_spots_id,$introduce)
	{	
		//插入数据操作
		$sql = "INSERT INTO " . DB_PREFIX ."scenic_spots_introduce SET introduce = ". "'".$introduce."'" .'AND scenic_spots_id = '.$scenic_spots_id;
		$this->db->query($sql);
		return $this->db->insert_id();
		
	}
	
	//更新介绍
	public function update_content($id,$introduce)
	{	
		//插入数据操作
		$sql = "UPDATE " . DB_PREFIX ."scenic_spots_introduce SET introduce = ". "'".$introduce."'"." WHERE id =".$id;
		$this->db->query($sql);
		return $id;
	}
}


?>