<?php
class activate_code_mode extends InitFrm
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
		$sql = "SELECT * FROM " . DB_PREFIX . "activation_code  WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['guest_type_text'] = $this->settings['guest_type'][$r['guest_type']];
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
		
		$sql = " INSERT INTO " . DB_PREFIX . "activation_code SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		return $vid;
	}
	
	public function update($id,$data = array())
	{
		if(!$data || !$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "activation_code WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "activation_code SET ";
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
		
		$sql = "SELECT * FROM " . DB_PREFIX . "activation_code  WHERE id = '" .$id. "'";
		$info = $this->db->query_first($sql);
		$info['guest_type_text'] = $this->settings['guest_type'][$info['guest_type']];
		return $info;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "activation_code WHERE 1 " . $condition;
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
		$sql = " SELECT * FROM " .DB_PREFIX. "activation_code WHERE id IN (" . $id . ")";
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
		$sql = " DELETE FROM " .DB_PREFIX. "activation_code WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		return $pre_data;
	}
	
	public function audit($id = '')
	{
		if(!$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "activation_code WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		/**********************************以下状态只是示例，根据情况而定************************************/
		switch (intval($pre_data['status']))
		{
			case 1:$status = 2;break;//审核
			case 2:$status = 3;break;//打回
			case 3:$status = 2;break;//审核
		}
		
		$sql = " UPDATE " .DB_PREFIX. "activation_code SET status = '" .$status. "' WHERE id = '" .$id. "'";
		$this->db->query($sql);
		return array('status' => $status,'id' => $id);
	}
	
	//判断是否存在某个验证码，并且判断该验证码没有有被使用过
	public function isExistsActivateCode($code = '')
	{
		if(!$code)
		{
			return false;
		}
		
		$sql = "SELECT * FROM " .DB_PREFIX. "activation_code WHERE is_use = 0 AND activate_code = '" .$code. "'";
		$ret = $this->db->query_first($sql);
		if(!$ret)
		{
			return false;
		}
		return $ret;
	}
	
	//判断是否存在某个验证码
	public function isExistsCode($code = '',$id = '')
	{
		if(!$code)
		{
			return false;
		}
		
		$cond = '';
		if($id)
		{
			$cond = " AND id != '" . $id . "' ";
		}
		
		$sql = "SELECT * FROM " .DB_PREFIX. "activation_code WHERE activate_code = '" .$code. "' " . $cond;
		$ret = $this->db->query_first($sql);
		if(!$ret)
		{
			return false;
		}
		return $ret;
	}
}
?>