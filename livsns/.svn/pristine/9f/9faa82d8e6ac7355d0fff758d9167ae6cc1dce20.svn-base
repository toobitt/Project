<?php
class group_mode extends InitFrm
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
		$sql = "SELECT * FROM " . DB_PREFIX . "session  WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			//此处根据情况做一些格式化的处理,如：date('Y-m-d',TIMENOW);
			$r['create_time'] = date('Y-m-d h:i:s',$r['create_time']);
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
		
		$sql = " INSERT INTO " . DB_PREFIX . "session SET ";
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
		$sql = " SELECT * FROM " .DB_PREFIX. "session WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "session SET ";
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
		
		$sql = "SELECT * FROM " . DB_PREFIX . "session  WHERE id = '" .$id. "'";
		$info = $this->db->query_first($sql);
		//此处根据情况做一些格式化的处理,如：date('Y-m-d',TIMENOW);

        if($info['last_uavatar'])
        {
            $info['last_uavatar'] = unserialize($info['last_uavatar']);
        }
        if($info['indexpic'])
        {
            $info['indexpic'] = unserialize($info['indexpic']);
        }
        if($info['create_time'])
        {
            $info['create_time'] = date('Y-m-d H:i:s',$info['create_time']);
        }
		return $info;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "session WHERE 1 " . $condition;
		$total = $this->db->query_first($sql);
		return $total;
	}
	
	public function delete($group_id = '')
	{
		if(!$group_id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "session WHERE id IN (" . $group_id . ")";
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
        $sql = 'DELETE s, su, mg FROM '.DB_PREFIX.'session s
                LEFT JOIN ' .DB_PREFIX.'session_user su
                    ON s.id = su.session_id
                LEFT JOIN ' .DB_PREFIX.'member_group mg
                ON s.id = mg.group_id
                WHERE s.id IN('.$group_id.')';
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
		$sql = " SELECT * FROM " .DB_PREFIX. "session WHERE id = '" .$id. "'";
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
		
		$sql = " UPDATE " .DB_PREFIX. "session SET status = '" .$status. "' WHERE id = '" .$id. "'";
		$this->db->query($sql);
		return array('status' => $status,'id' => $id);
	}
}
?>