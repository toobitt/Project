<?php
class promote_info_mode extends InitFrm
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
		$sql = "SELECT * FROM " . DB_PREFIX . "promote_info  WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			//此处根据情况做一些格式化的处理,如：date('Y-m-d',TIMENOW);
            $r['status_text'] = $this->settings['general_audit_status'][$r['status']];
            $r['created_at'] = date('y-m-d H-i',strtotime($r['created_at']));
            $r['picture_1'] = unserialize($r['picture_1']);
            $r['picture_2'] = unserialize($r['picture_2']);
            $r['picture_3'] = unserialize($r['picture_3']);
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
		
		$sql = " INSERT INTO " . DB_PREFIX . "promote_info SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."promote_info SET order_id = {$vid}  WHERE id = {$vid}";
		$this->db->query($sql);
		return $vid;
	}
	
	public function update($app_id,$data = array())
	{
		if(!$data || !$app_id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "promote_info WHERE app_id = '" .$app_id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "promote_info SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE app_id = '"  .$app_id. "'";
		$this->db->query($sql);
		return $pre_data['id'];
	}

    public function updateStatus($id,$data = array())
    {
        if(!$data || !$id)
        {
            return false;
        }
        //查询出原来
        $sql = " SELECT * FROM " .DB_PREFIX. "promote_info WHERE id = '" .$id. "'";
        $pre_data = $this->db->query_first($sql);
        if(!$pre_data)
        {
            return false;
        }

        //更新数据
        $sql = " UPDATE " . DB_PREFIX . "promote_info SET ";
        foreach ($data AS $k => $v)
        {
            $sql .= " {$k} = '{$v}',";
        }
        $sql  = trim($sql,',');
        $sql .= " WHERE id = '"  .$id. "'";
        $this->db->query($sql);
        return $pre_data['id'];
    }
	
	public function detail($id = '')
	{
		if(!$id)
		{
			return false;
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "promote_info  WHERE id = '" .$id. "'";
		$info = $this->db->query_first($sql);
		//此处根据情况做一些格式化的处理,如：date('Y-m-d',TIMENOW);
        if($info)
        {
            $info['status_text'] = $this->settings['general_audit_status'][$info['status']];
            $info['created_at'] = date('y-m-d H-i',strtotime($info['created_at']));
            $info['picture_1'] = unserialize($info['picture_1']);
            $info['picture_2'] = unserialize($info['picture_2']);
            $info['picture_3'] = unserialize($info['picture_3']);
        }
		return $info;
	}

    public function getInfoByAppId($app_id = '')
    {
        if(!$app_id)
        {
            return false;
        }

        $sql = "SELECT * FROM " . DB_PREFIX . "promote_info  WHERE app_id = '" .$app_id. "'";
        $info = $this->db->query_first($sql);
        //此处根据情况做一些格式化的处理,如：date('Y-m-d',TIMENOW);
        if($info)
        {
            $info['status_text'] = $this->settings['general_audit_status'][$info['status']];
            $info['created_at'] = date('y-m-d H-i',strtotime($info['created_at']));
            $info['picture_1'] = unserialize($info['picture_1']);
            $info['picture_2'] = unserialize($info['picture_2']);
            $info['picture_3'] = unserialize($info['picture_3']);
        }

        return $info;
    }
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "promote_info WHERE 1 " . $condition;
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
		$sql = " SELECT * FROM " .DB_PREFIX. "promote_info WHERE id IN (" . $id . ")";
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
		$sql = " DELETE FROM " .DB_PREFIX. "promote_info WHERE id IN (" . $id . ")";
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
		$sql = " SELECT * FROM " .DB_PREFIX. "promote_info WHERE id = '" .$id. "'";
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
		
		$sql = " UPDATE " .DB_PREFIX. "promote_info SET status = '" .$status. "' WHERE id = '" .$id. "'";
		$this->db->query($sql);
		return array('status' => $status,'id' => $id);
	}
}
?>