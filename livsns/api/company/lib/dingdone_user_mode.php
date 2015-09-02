<?php
class dingdone_user_mode extends InitFrm
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
		$sql = "SELECT * FROM " . DB_PREFIX . "user  WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			if($r['avatar'])
			{
				$r['avatar'] = @unserialize($r['avatar']);
			}
			else 
			{
				$r['avatar'] = array();
			}
			
			if($r['avatar'])
			{
				$r['avatar_url'] = $r['avatar']['host'] .  $r['avatar']['dir'] .  $r['avatar']['filepath'] .  $r['avatar']['filename']; 
			}
			else 
			{
				$r['avatar_url'] = '';
			}
			$r['create_time'] = date('Y-m-d H:i',$r['create_time']);
			$r['push_status_text'] = $this->settings['push_status'][$r['push_status']];
			$r['business_status_text'] = $this->settings['yes_no'][$r['is_business']];
			$info[] = $r;
		}
		return $info;
	}
	
	/**
	 * 获取所有权限信息
	 * @return multitype:unknown
	 */
	public function getPermissionInfo()
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "business_permission ORDER BY order_id  ASC";
		$q = $this->db->query($sql);
		$info = array();
		while ($r = $this->db->fetch_array($q))
		{
			$info[] = $r;
		}
		return $info;
	}
	
	/**
	 * 根据用户的权限
	 */
	public function getPermissionById($id)
	{
		if(!$id)
		{
			return false;
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "business_permission ORDER BY order_id  ASC";
		$q = $this->db->query($sql);
		$permissionInfo = array();
		while ($r = $this->db->fetch_array($q))
		{
		    $permissionInfo[] = $r;
		}
				
		$sql = "SELECT * FROM ".DB_PREFIX."user_permission WHERE id=".$id."";
		$q = $this->db->query($sql);
		$user_permission = array();
		while($r = $this->db->fetch_array($q))
		{
			$user_permission[] = $r;
		}
		
		$info = array();
		foreach ($permissionInfo as $k=>$v)
		{
		    $v['have'] = 0;
		    foreach ($user_permission as $ko=>$vo)
		    {
		
		        if($v['id'] == $vo['permission']){
		            $v['have'] = 1;
		        }
		        else
		        {
		            continue;
		        }
		    }
		    array_push($info,$v);
		}
		
		return $info;
	}
	
	/**
	 * 更新用户权限信息
	 * @param unknown $data
	 */
	public function UpdatePermission($id,$data=array())
	{
		if(!$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT permission FROM " .DB_PREFIX. "user_permission WHERE id = '" .$id. "'";
		$q = $this->db->query($sql);
		$pre_data = array();
		while($r = $this->db->fetch_array($q))
		{
			$pre_data[] = $r;
		}
		
		$info = array();
		foreach ($pre_data as $k=>$v)
		{
			array_push($info,$v['permission']);
		}
		
		if(array_diff($data,$info))
		{
			//新增数据
			$createData = array_diff($data,$info);
			
			$sql = " INSERT INTO " . DB_PREFIX . "user_permission (id,permission) value";
			foreach ($createData AS $k => $v)
			{
				$sql .= "(".$id.",".$v."),";
			}
			$sql = trim($sql,',');
			$res = $this->db->query($sql);
		}
		if(array_diff($info,$data)) 
		{
			//删除数据
			$delData = array_diff($info,$data);
			$str = join(",",$delData);
			$sql = " DELETE FROM " .DB_PREFIX. "user_permission WHERE id=".$id." AND permission IN (".$str.");";
			$res = $this->db->query($sql);
		}
		return $res;
	}
	
	public function array_diff($array_1, $array_2) {
		$array_2 = array_flip($array_2);
		foreach ($array_1 as $key => $item) {
			if (isset($array_2[$item])) {
				unset($array_1[$key]);
			}
		}
		return $array_1;
	}
	
	public function create($data = array())
	{
		if(!$data)
		{
			return false;
		}
		
		$sql = " INSERT INTO " . DB_PREFIX . "user SET ";
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
		$sql = " SELECT * FROM " .DB_PREFIX. "user WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "user SET ";
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
		$sql = "SELECT u.*,p.app_id,p.app_key,p.master_key,p.prov_id,p.push_accounts_id,p.app_name  FROM " . DB_PREFIX . "user u LEFT JOIN " .DB_PREFIX. "push_api_config p ON u.id = p.user_id  WHERE u.id = '" .$id. "'";
        $info = $this->db->query_first($sql);
		if($info)
		{
			if($info['avatar'])
			{
				$info['avatar'] = @unserialize($info['avatar']);
			}
			else
			{
				$info['avatar'] = array();
			}
		}

		return $info;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "user WHERE 1 " . $condition;
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
		$sql = " SELECT * FROM " .DB_PREFIX. "user WHERE id IN (" . $id . ")";
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
		$sql = " DELETE FROM " .DB_PREFIX. "user WHERE id IN (" . $id . ")";
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
		$sql = " SELECT * FROM " .DB_PREFIX. "user WHERE id = '" .$id. "'";
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
		
		$sql = " UPDATE " .DB_PREFIX. "user SET status = '" .$status. "' WHERE id = '" .$id. "'";
		$this->db->query($sql);
		return array('status' => $status,'id' => $id);
	}
	
	//推送配置的操作
	public function pushApiConfig($data = array())
	{
		if(!$data || !$data['user_id'])
		{
			return false;
		}
		
		//首先查询这个人是不是已经推送配置了
		$sql = "SELECT * FROM " .DB_PREFIX. "push_api_config WHERE user_id = '" .$data['user_id']. "' ";
		$config = $this->db->query_first($sql);
		//如果存在就更新
		if($config)
		{
			$sql = " UPDATE " . DB_PREFIX . "push_api_config SET ";
			foreach ($data AS $k => $v)
			{
				$sql .= " {$k} = '{$v}',";
			}
			$sql  = trim($sql,',');
			$sql .= " WHERE id = '"  .$config['id']. "'";
			$this->db->query($sql);
		}
		else //不存在就创建 
		{
			$sql = " INSERT INTO " . DB_PREFIX . "push_api_config SET ";
			foreach ($data AS $k => $v)
			{
				$sql .= " {$k} = '{$v}',";
			}
			$sql = trim($sql,',');
			$this->db->query($sql);
		}
		return true;
	}
	
	//获取推送接口api
	public function getPushApiConfig($user_id = '')
	{
		if(!$user_id)
		{
			return false;
		}
		
		//查询用户当前的推送状态
		$sql = " SELECT * FROM " .DB_PREFIX. "user WHERE user_id = '" .$user_id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data || $pre_data['push_status'] != 5)
		{
			return false;
		}
		
		//状态必须是已开通
		$sql = "SELECT * FROM " .DB_PREFIX. "push_api_config WHERE user_id = '" .$user_id. "' ";
		$ret = $this->db->query_first($sql);
		if($ret)
		{
			return $ret;
		}
		else 
		{
			return false;
		}
	}

    /**
     * 获取多个用户的配置
     *
     * @param string $user_ids
     * @return array|bool
     */
    public function getPushApiConfigByuids($user_ids = '')
    {
        if(!$user_ids)
        {
            return false;
        }

        //状态必须是已开通
        $sql = "SELECT * FROM " .DB_PREFIX. "push_api_config WHERE user_id IN(" .$user_ids. ")";
        $q = $this->db->query($sql);
        $info = array();
        while ($r = $this->db->fetch_array($q))
        {
            $info[] = $r;
        }
        return $info;
    }
	
	//按条件获取用户信息
	public function getUserByCond($cond = '')
	{
		$sql = " SELECT * FROM " .DB_PREFIX. "user WHERE 1 " . $cond;
		$info = $this->db->query_first($sql);
		if(!$info)
		{
			return false;
		}
		return $info;
	}
	
	public function getPermission($user_id = '')
	{
		if(!$user_id)
		{
			return false;
		}
		$sql = " SELECT * FROM " .DB_PREFIX. "user_permission as u LEFT JOIN " .DB_PREFIX. "business_permission as b ON u.permission=b.id  WHERE u.id IN (" . $user_id . ") ORDER BY b.order_id  DESC";
		$q = $this->db->query($sql);
		$info = array();
		while ($r = $this->db->fetch_array($q))
		{
			$info[$r['type']] = $r['name'];
		}
		return $info;
	}
	
	//获取邮箱信息
	public function getEmailInfo($cond = '')
	{
	    if(!$cond)
	    {
	        return FALSE;
	    }
	    
	    $sql = " SELECT * FROM " .DB_PREFIX. "emailcontrol WHERE 1 " . $cond;
		$info = $this->db->query_first($sql);
		if(!$info)
		{
			return FALSE;
		}
		return $info;
	}
	
	//新增邮件发送信息
	public function createEmailControl($data = array())
	{
	    if(!$data)
		{
			return FALSE;
		}
		
		$sql = " INSERT INTO " . DB_PREFIX . "emailcontrol SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		return $vid;
	}
	
	/**
	 * 获取推送接口信息
	 * @package userid 
	 */
	public function getPushInfo($user_id = 0)
	{
		//状态必须是已开通
		$sql = "SELECT * FROM " .DB_PREFIX. "push_api_config WHERE user_id = '" .$user_id. "' ";
		$ret = $this->db->query_first($sql);
		if($ret)
		{
			return $ret;
		}
	}
	
	/**
	 * 更新master_key
	 * @param number $user_id
	 * @param array $data
	 */
	public function updateMasterkey($user_id = 0 , $data = array())
	{
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "push_api_config WHERE user_id = '" .$user_id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "push_api_config SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE user_id = '"  .$user_id. "'";
		$this->db->query($sql);
		return $pre_data;
	}
}