<?php
//模板标签的数据库操作
class templateTag extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	//新增样式
	public function create($data)
	{	
		//插入数据操作
		$sql = "INSERT INTO " . DB_PREFIX ."template_tag SET ";
		$sql_extra = $space ='';
		foreach($data as $k=>$v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$this->db->query($sql);
		return $this->db->insert_id();
	}
	
	//更新模板标签参数相关信息
	public function update($data,$table_name)
	{	
		$sql = "UPDATE " . DB_PREFIX .$table_name." SET ";
		$sql_extra = $space ='';
		foreach($data as $k=>$v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$sql .= " WHERE id =".$data['id'];
		$this->db->query($sql);	
		return $this->db->affected_rows();
	}
	
	//删除模板标签
	public function delete($ids)
	{	
		$sq = "DELETE FROM " . DB_PREFIX . "template_tag  WHERE id IN(" . $ids . ")";
		$this->db->query($sq);
		
		return ($this->db->affected_rows() > 0) ? true : false;
	}	
	
	
	//根据条件查询模板标签
	public function show($condition,$limit)	
	{		
		$sql = "SELECT *
				FROM  " . DB_PREFIX ."template_tag 
				WHERE 1".$condition.' ORDER BY id DESC'.$limit;
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$rett[] = $r;
		}
		return $rett;
	}
	
}


?>