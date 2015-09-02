<?php
class app_extension_mode extends InitFrm
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
		//
        $sql = "SELECT *  FROM
		        ". DB_PREFIX . "member_extension_field";
        $q = $this->db->query($sql);
        while($r = $this->db->fetch_array($q))
        {
            //此处根据情况做一些格式化的处理,如：date('Y-m-d',TIMENOW);
            $r['is_choose'] = 0;
            $r['is_required'] = 0;

            $r['type'] = $this->settings['extension_field_type'][$r['type']]['type'];
            $extensionInfo[] = $r;
        }


        $sql = "SELECT *  FROM
		        ". DB_PREFIX . "app_extension_field
		        WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
        $userExtInfo = array();
		while($r = $this->db->fetch_array($q))
		{
			//此处根据情况做一些格式化的处理,如：date('Y-m-d',TIMENOW);
            if($r['extension_field'])
            {
                $r['extension_field'] = unserialize($r['extension_field']);
            }
            else
            {
                $r['extension_field'] = array();
            }

			$userExtInfo = $r;
		}

        if(!empty($userExtInfo['extension_field']) )
        {
            foreach($userExtInfo['extension_field'] as $k=>$v)
            {
                foreach($extensionInfo as $ko=>$vo)
                {
                    if($v['extension_field_id'] == $vo['extension_field_id'])
                    {
                        $userExtInfo['extension_field'][$k] = $vo;
                        $userExtInfo['extension_field'][$k]['is_choose'] = 1;
                    }
                }
            }
        }

		return $userExtInfo['extension_field'];
	}

    public function getAllExtension($condition = '',$orderby = '',$limit = '')
    {
        //
        $sql = "SELECT *  FROM
		        ". DB_PREFIX . "member_extension_field";
        $q = $this->db->query($sql);
        while($r = $this->db->fetch_array($q))
        {
            //此处根据情况做一些格式化的处理,如：date('Y-m-d',TIMENOW);
            $r['is_choose'] = 0;
            $r['is_required'] = 0;

            $r['type'] = $this->settings['extension_field_type'][$r['type']]['type'];
            $extensionInfo[] = $r;
        }


        $sql = "SELECT *  FROM
		        ". DB_PREFIX . "app_extension_field
		        WHERE 1 " . $condition . $orderby . $limit;
        $q = $this->db->query($sql);
        $userExtInfo = array();
        while($r = $this->db->fetch_array($q))
        {
            //此处根据情况做一些格式化的处理,如：date('Y-m-d',TIMENOW);
            if($r['extension_field'])
            {
                $r['extension_field'] = unserialize($r['extension_field']);
            }
            else
            {
                $r['extension_field'] = array();
            }

            $userExtInfo = $r;
        }

        if(!empty($userExtInfo['extension_field']))
        {
            foreach($extensionInfo as $k=>$v)
            {
                foreach($userExtInfo['extension_field'] as $ko=>$vo)
                {
                    if($v['extension_field_id'] == $vo['extension_field_id'])
                    {
                        $extensionInfo[$k]['is_choose'] = 1;
                    }
                }
            }
        }

        return $extensionInfo;
    }
	
	public function create($data = array())
	{
		if(!$data)
		{
			return false;
		}
		
		$sql = " INSERT INTO " . DB_PREFIX . "app_extension_field SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."app_extension_field SET order_id = {$vid}  WHERE id = {$vid}";
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
		$sql = " SELECT * FROM " .DB_PREFIX. "app_extension_field WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "app_extension_field SET ";
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
		
		$sql = "SELECT * FROM " . DB_PREFIX . "app_extension_field  WHERE id = '" .$id. "'";
		$info = $this->db->query_first($sql);
		//此处根据情况做一些格式化的处理,如：date('Y-m-d',TIMENOW);
		return $info;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "app_extension_field WHERE 1 " . $condition;
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
		$sql = " SELECT * FROM " .DB_PREFIX. "app_extension_field WHERE id IN (" . $id . ")";
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
		$sql = " DELETE FROM " .DB_PREFIX. "app_extension_field WHERE id IN (" . $id . ")";
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
		$sql = " SELECT * FROM " .DB_PREFIX. "app_extension_field WHERE id = '" .$id. "'";
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
		
		$sql = " UPDATE " .DB_PREFIX. "app_extension_field SET status = '" .$status. "' WHERE id = '" .$id. "'";
		$this->db->query($sql);
		return array('status' => $status,'id' => $id);
	}
	
	/**
	 * 更新用户扩展信息
	 * @param unknown $data
	 */
	public function UpdateExtension($app_id,$data=array())
	{
		if(!$app_id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT extension_field FROM " .DB_PREFIX. "app_extension_field WHERE app_id = '" .$app_id. "'";
		$q = $this->db->query($sql);
		$pre_data = array();
		while($r = $this->db->fetch_array($q))
		{
			$pre_data[] = $r;
		}

        //
        if(!$pre_data)
        {
            //新增
            $sql = " INSERT INTO " . DB_PREFIX . "app_extension_field (app_id,extension_field,user_id,user_name,create_time) value";
            $sql .= "(".$app_id.",'".$data['extension_field']."',".$data['user_id'].",'".$data['user_name']."',".$data['create_time']."),";
            $sql = trim($sql,',');
            $res = $this->db->query($sql);
        }
        else
        {
            //更新
            $sql = " UPDATE " . DB_PREFIX . "app_extension_field SET extension_field='".$data['extension_field']."'";
            $sql .= " WHERE app_id=".$app_id."";
            $res = $this->db->query($sql);
        }
		
		
		return $res;
	}
}
?>