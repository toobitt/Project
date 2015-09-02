<?php
class live_time_shift_mode extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition,$orderby,$limit)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "time_shift_log WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['status'] = $this->settings['status'][$r['status']];
			$r['create_time'] = date('Y-m-d H:i',$r['create_time']);
			$r['starttime'] = date('Y-m-d H:i:s',$r['starttime']);
			$r['endtime'] = date('Y-m-d H:i:s',$r['endtime']);
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

		$sql = " INSERT INTO ".DB_PREFIX."time_shift_log SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$insert_id = $this->db->insert_id();
		$sql = " UPDATE " .DB_PREFIX. "time_shift_log SET order_id = '" .$insert_id. "' WHERE id = '" .$insert_id. "'";
		$this->db->query($sql);
		$data['id'] = $insert_id;
		return $data;
	}
	
	public function update($id = '',$data = array())
	{
		if(!$id || !$data)
		{
			return false;
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "time_shift_log WHERE id=" . $id;
		$f = $this->db->query_first($sql);
		if(empty($f))
		{
			return false;
		}
		$sql = "UPDATE " . DB_PREFIX . "time_shift_log SET ";
		$space = "";
		foreach($data as $k => $v)
		{
			$sql .= $space . $k . "='" . $v . "'";
			$space = ',';
		}
		$sql .= " WHERE id=" . $id;
		$this->db->query($sql);
		$data['id'] = $id;
		return $data;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "time_shift_log WHERE 1" . $condition;
		$f = $this->db->query_first($sql);
		return $f;
	}
	
	public function delete($id = '')
	{
		if(!$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "time_shift_log WHERE id IN (" . $id . ")";
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
		$sql = " DELETE FROM " .DB_PREFIX. "time_shift_log WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		return $pre_data;
	}
}
?>