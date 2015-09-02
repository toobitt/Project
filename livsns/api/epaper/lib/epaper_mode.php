<?php
class epaper_mode extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition = '',$orderby = '',$limit = '')
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "epaper  WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$info[] = $r;
		}
		return $info;
	}
	
	public function create($data = array())
	{
		if(!$data)
		{
			return false;
		}
		
		$sql = " INSERT INTO " . DB_PREFIX . "epaper SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."epaper SET order_id = {$vid}  WHERE id = {$vid}";
		$this->db->query($sql);
		$data['id'] = $vid;
		return $data;
	}
	
	public function update($id,$data = array())
	{
		if(!$data || !$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "epaper WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "epaper SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE id = '"  .$id. "'";
		$this->db->query($sql);
		$row = $this->db->affected_rows();
		return $row;
	}
	
	public function detail($id = '')
	{
		if(!$id)
		{
			return false;
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "epaper  WHERE id = '" .$id. "'";
		$q = $this->db->query($sql);
		$info = array();
		while ($r = $this->db->fetch_array($q))
		{
			$info[] = $r;
		}
		return $info;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "epaper WHERE 1 " . $condition;
		$total = $this->db->query_first($sql);
		return $total;
	}
	
	public function delete($id = '')
	{
		if(!$id)
		{
			return false;
		}
		//查询下面是否有子期
		$sql = " SELECT id FROM " .DB_PREFIX. "period WHERE epaper_id IN (" . $id . ")";
		$query = $this->db->query($sql);
		$pre_data = array();
		while ($re = $this->db->fetch_array($query))
		{
			$pre_data[] = $re['id'];
		}

		$sql = " SELECT * FROM " .DB_PREFIX. "epaper WHERE id IN (" . $id . ")";
		$query = $this->db->query($sql);
		$paper_data = array();
		while ($re = $this->db->fetch_array($query))
		{
			$paper_data[] = $re;
		}
		if(!$pre_data && $paper_data)
		{
			//删除主表
			/*
			$sql = " DELETE FROM " .DB_PREFIX. "epaper WHERE id IN (" . $id . ")";
			$this->db->query($sql);
			return $paper_data;
			*/
			return $paper_data;
		}
		else
		{
			return false;
		}
	}
	
	public function audit($id = '',$period_id = '')
	{
		if(!$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "period WHERE epaper_id = " . $id . " AND id = " . $period_id;
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		switch (intval($pre_data['status']))
		{
			case 0:$status = 1;break;//审核
			case 1:$status = 2;break;//打回
			case 2:$status = 1;break;//审核
		}
		
		$sql = " UPDATE " .DB_PREFIX. "period SET status = '" .$status. "' WHERE epaper_id = " . $id . " AND id = " . $period_id;
		$this->db->query($sql);
		return array('status' => $status,'id' => $id);
	}
}
?>