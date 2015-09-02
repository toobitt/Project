<?php
class manage_unit_mode extends InitFrm
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
		$sql = "SELECT * FROM " . DB_PREFIX . "manage_unit WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['logo'] =  hg_fetchimgurl(unserialize($r['logo']), 40,30);
			$r['create_time'] = date('Y-m-d H:i',$r['create_time']);
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
		
		$sql = " INSERT INTO " . DB_PREFIX . "manage_unit SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."manage_unit SET order_id = {$vid}  WHERE id = {$vid}";
		$this->db->query($sql);
		return true;
	}
	
	public function update($data = array(),$id)
	{
		if(!$data || !$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "manage_unit WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		$sql = " UPDATE " . DB_PREFIX . "manage_unit SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE id = '"  .$id. "'";
		$this->db->query($sql);
		if(!$this->db->affected_rows())
		{
			return false;
		}
		return $pre_data;
	}
	
	public function detail($condition = '')
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "manage_unit WHERE 1 " . $condition;
		$info = $this->db->query_first($sql);
		if(!$info)
		{
			return false;
		}
		$info['logo'] = hg_fetchimgurl(unserialize($info['logo']), 80,60);
		return $info;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "manage_unit WHERE 1 " . $condition;
		$total = $this->db->query_first($sql);
		return $total;
	}
	
	public function delete($condition = '')
	{
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "manage_unit WHERE 1 " . $condition;
		$q = $this->db->query($sql);
		$pre_data = array();
		$ids = array();
		while ($r = $this->db->fetch_array($q))
		{
			$pre_data[] = $r;
			$ids[] = $r['id'];
		}
		if(!$pre_data)
		{
			return false;
		}
		$sql = " DELETE FROM " .DB_PREFIX. "manage_unit WHERE 1 " . $condition;
		$this->db->query($sql);
		return $pre_data;
	}
}
?>