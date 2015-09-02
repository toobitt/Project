<?php
class opinion extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	public function detail($data,  $flag)
	{
		if (!is_array($data) || !$data)
		{
			return false;
		}
		$condition = '';
		foreach ($data as $key=>$val)
		{
			$condition .= ' AND o.'.$key.'="'.$val.'"';
		}
		if ($flag)
		{
			$sql = 'SELECT o.*,c.* FROM '.DB_PREFIX.'opinion o
					LEFT JOIN '.DB_PREFIX.'content c ON o.rid=c.rid 
					WHERE 1 '.$condition;
		}else {
			$sql = 'SELECT o.*,c.*  FROM '.DB_PREFIX.'opinion o
				LEFT JOIN '.DB_PREFIX.'content c ON o.cid =c.content_id
				WHERE 1'.$condition;
		}		
		$query = $this->db->query($sql);
		$k = array();
		while (!false == ($row = $this->db->fetch_array($query)))
		{
			$k[] = $row;
		}
		return $k;
				
	}
	public function delete($ids)
	{
		$sql = 'DELETE FROM '.DB_PREFIX.'opinion WHERE id IN('.$ids.')';
		$this->db->query($sql);
		$sql = 'DELETE FROM '.DB_PREFIX.'content WHERE id IN('.$ids.')';
		$this->db->query($sql);
		return $ids;
	}
	//插入opinion 表
	public function add_opinion($data)
	{
		if (!is_array($data) || !$data)
		{
			return false;
		}
		$sql = 'REPLACE INTO '.DB_PREFIX.'opinion SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		$id = $this->db->insert_id();
		return $id;
		
	}
	public function add_content($data)
	{
		if (!is_array($data) || !$data)
		{
			return false;
		}
		$sql = 'INSERT INTO '.DB_PREFIX.'content SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		$id = $this->db->insert_id();
		return $id;
	}
	
	public function get_content_id($data)
	{
		if (!is_array($data) || !$data)
		{
			return false;
		}
		$condition = '';
		foreach ($data as $key=>$val)
		{
			$condition .= ' AND '.$key.'="'.$val.'"';
		}
		$sql = 'SELECT cid FROM '.DB_PREFIX.'opinion WHERE 1'.$condition;
		$ret = $this->db->query_first($sql);
		return $ret['cid'];
	}
	public function update($data,$id)
	{
		if (!is_array($data) || !$data || !$id)
		{
			return false;
		}
		$sql = ' UPDATE '.DB_PREFIX.'content SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		return true;
	}
	public function deleteOpinion($data,$ids)
	{
		if (!$data || !is_array($data) || !$ids)
		{
			return false;
		}
		$condition = '';
		foreach ($data as $key=>$val)
		{
			$condition .= ' AND '.$key.'="'.$val.'"';
		}
		$sql = ' DELETE FROM '.DB_PREFIX.'opinion WHERE 1 AND rid IN ('.$ids.')'.$condition;
		$this->db->query($sql);
		return true;
	}
	public function deleteContent($ids)
	{
		if (!$ids)
		{
			return false;
		}
		$sql = 'DELETE FROM '.DB_PREFIX.'content WHERE rid IN ('.$ids.')';
		$this->db->query($sql);
		return true;
	}
	
	
	
	
}