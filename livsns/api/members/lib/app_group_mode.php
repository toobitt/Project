<?php
class app_group_mode extends InitFrm
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
		$sql = "SELECT * FROM " . DB_PREFIX . "app_group  WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			//此处根据情况做一些格式化的处理,如：date('Y-m-d',TIMENOW);
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
		
		$sql = " INSERT INTO " . DB_PREFIX . "app_group SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."app_group SET order_id = {$vid}  WHERE id = {$vid}";
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
		$sql = " SELECT * FROM " .DB_PREFIX. "app_group WHERE app_id = '" .$app_id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "app_group SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE app_id = '"  .$app_id. "'";
		$this->db->query($sql);
		return $pre_data;
	}
	
	public function detail($app_id = '')
	{
		if(!$app_id)
		{
			return false;
		}

        $sql = "SELECT * FROM " . DB_PREFIX . "group";
        $systemGroup[] = $this->db->query_first($sql);
		
		$sql = "SELECT * FROM " . DB_PREFIX . "app_group  WHERE app_id = '" .$app_id. "'";
		$info = $this->db->query_first($sql);
		//此处根据情况做一些格式化的处理,如：date('Y-m-d',TIMENOW);
        if($info['group_info'])
        {
            $info['group_info'] = unserialize($info['group_info']);
            foreach($systemGroup as $k=>$v)
            {
                $v['enable'] = 0;
                foreach($info['group_info'] as $ko=>$vo)
                {
                    if($v['id'] == $vo['id'])
                    {
                        $v['enable'] = 1;
                    }
                }
            }

        }
        file_put_contents('11111.txt',print_r($systemGroup,1));
		return $systemGroup[0];
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "app_group WHERE 1 " . $condition;
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
		$sql = " SELECT * FROM " .DB_PREFIX. "app_group WHERE id IN (" . $id . ")";
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
		$sql = " DELETE FROM " .DB_PREFIX. "app_group WHERE id IN (" . $id . ")";
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
		$sql = " SELECT * FROM " .DB_PREFIX. "app_group WHERE id = '" .$id. "'";
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
		
		$sql = " UPDATE " .DB_PREFIX. "app_group SET status = '" .$status. "' WHERE id = '" .$id. "'";
		$this->db->query($sql);
		return array('status' => $status,'id' => $id);
	}

    public function save($app_id = 0, $data = array())
    {
        if(!$app_id || !$data)
        {
            return false;
        }
        //查询出原来
        $sql = " SELECT * FROM " .DB_PREFIX. "app_group WHERE app_id = '" .$app_id. "'";
        $pre_data = $this->db->query_first($sql);
        if(!$pre_data)
        {
            //插入新的记录
            $sql = " INSERT INTO " . DB_PREFIX . "app_group SET ";
            foreach ($data AS $k => $v)
            {
                $sql .= " {$k} = '{$v}',";
            }
            $sql = trim($sql,',');
            $this->db->query($sql);
            $vid = $this->db->insert_id();
        }
        else
        {
            //更新数据
            $sql = " UPDATE " . DB_PREFIX . "app_group SET ";
            foreach ($data AS $k => $v)
            {
                $sql .= " {$k} = '{$v}',";
            }
            $sql  = trim($sql,',');
            $sql .= " WHERE app_id = '"  .$app_id. "'";
            $this->db->query($sql);
        }

        return $vid;
    }
}
?>