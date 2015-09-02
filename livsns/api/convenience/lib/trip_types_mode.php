<?php
class trip_types_mode extends InitFrm
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
		$sql = "SELECT  t.id , t.en ,t.is_quick_search , t.zh ,t.logo as logoid, t.create_time , t.user_id , t.user_name , t.update_time , t.ip ,t.order_id ,m.img_info as logo FROM " . DB_PREFIX . "trip_types t LEFT JOIN " . DB_PREFIX . "material m  ON t.logo = m.id WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['logo'] = unserialize($r['logo']);
			$r['create_time'] = date('Y-m-d H:m:s',$r['create_time']);
			$r['update_time'] = date('Y-m-d H:m:s',$r['update_time']);
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
		
		$sql = " INSERT INTO " . DB_PREFIX . "trip_types SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."trip_types SET order_id = {$vid}  WHERE id = {$vid}";
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
		$sql = " SELECT * FROM " .DB_PREFIX. "trip_types WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "trip_types SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE id = '"  .$id. "'";
		$this->db->query($sql);
		return $pre_data;
	}
	
	public function detail($id = '')
	{
		if(!$id)
		{
			return false;
		}
		
		$sql = "SELECT t.id , t.en , t.zh ,t.logo as logoid, t.is_quick_search , t.create_time , t.user_id , t.user_name , t.update_time , t.ip ,t.order_id ,m.img_info as logo FROM " . DB_PREFIX . "trip_types t LEFT JOIN " . DB_PREFIX . "material m  ON t.logo = m.id WHERE t.id = '" .$id. "'";
		$q = $this->db->query($sql);
		$info = array();
		while ($r = $this->db->fetch_array($q))
		{
			$r['logo'] = unserialize($r['logo']);
			$r['create_time'] = date('Y-m-d H:m:s',$r['create_time']);
			$r['update_time'] = date('Y-m-d H:m:s',$r['update_time']);
			$info[] = $r;
		}
		return $info;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "trip_types WHERE 1 " . $condition;
		$total = $this->db->query_first($sql);
		return $total;
	}
	
	public function delete($id = '')
	{
		if(!$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "trip_types WHERE id IN (" . $id . ")";
		$q = $this->db->query($sql);
		$pre_data = array();
		while ($r = $this->db->fetch_array($q))
		{
			$pre_data[] 	= $r;
		}
		if(!$pre_data)
		{
			return false;
		}
		//删除主表
		$sql = " DELETE FROM " .DB_PREFIX. "trip_types WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		return $pre_data;
	}

}
?>