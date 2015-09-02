<?php
class identity_auth_mode extends InitFrm
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
		$sql = "SELECT * FROM " . DB_PREFIX . "identity_auth  WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['status_text'] 	= $this->settings['identity_auth_status'][$r['status']];
			$r['type_text'] 	= $this->settings['identity_auth_type'][$r['type']];
			$r['create_time']   = date('Y-m-d H:i',$r['create_time']);
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
		
		$sql = " INSERT INTO " . DB_PREFIX . "identity_auth SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."identity_auth SET order_id = {$vid}  WHERE id = {$vid}";
		$this->db->query($sql);
		return $vid;
	}
	
	public function update($id,$data = array())
	{
		if(!$data || !$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "identity_auth WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "identity_auth SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE id = '"  .$id. "'";
		$this->db->query($sql);
		return $pre_data;
	}
	
	public function detail($data = array())
	{
		if ($data && is_array($data))
		{
		    $condition = '';
		    $alias = 'ia';
    		foreach ($data as $k => $v)
    		{
    			if (is_numeric($v))
    			{
    				$condition .= ' AND ' . $alias . '.' . $k . ' = ' . $v;
    			}
    			elseif (is_string($v))
    			{
    				$condition .= ' AND ' . $alias . '.' . $k . ' = "' . $v . '"';
    			}
    		}
		}
		$sql = "SELECT ia.*,p.name AS province_name,c.name AS city_name,x.name AS district_name FROM " . DB_PREFIX . "identity_auth ia LEFT JOIN " . DB_PREFIX . "province p 
										ON p.code = ia.province LEFT JOIN " . DB_PREFIX . "city c 
										ON c.code = ia.city LEFT JOIN " . DB_PREFIX . "area x 
										ON x.code = ia.district WHERE 1";
		if ($condition) $sql .= $condition;
		$info = array();
		$info = $this->db->query_first($sql);
		if($info)
		{
			$info['status_text'] = $this->settings['identity_auth_status'][$info['status']];
			$info['type_text'] 	= $this->settings['identity_auth_type'][$info['type']];
			$info['identity_type_text'] = $this->settings['identity_type'][$info['identity_type']];
			$info['create_time'] = date('Y-m-d H:i',$info['create_time']);
			if($info['identity_photo'])
			{
				$info['identity_photo'] = @unserialize($info['identity_photo']);
			}
			else 
			{
				$info['identity_photo'] = array();
			}
		}
		return $info;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "identity_auth WHERE 1 " . $condition;
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
		$sql = " SELECT * FROM " .DB_PREFIX. "identity_auth WHERE id IN (" . $id . ")";
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
		$sql = " DELETE FROM " .DB_PREFIX. "identity_auth WHERE id IN (" . $id . ")";
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
		$sql = " SELECT * FROM " .DB_PREFIX. "identity_auth WHERE id = '" .$id. "'";
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
		
		$sql = " UPDATE " .DB_PREFIX. "identity_auth SET status = '" .$status. "' WHERE id = '" .$id. "'";
		$this->db->query($sql);
		return array('status' => $status,'id' => $id);
	}
}
?>