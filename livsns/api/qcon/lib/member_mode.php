<?php
class member_mode extends InitFrm
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
		$sql = "SELECT m.*,a.guest_type FROM " . DB_PREFIX . "member m LEFT JOIN " .DB_PREFIX. "activation_code a ON a.id = m.activate_code_id WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['create_time'] = date('Y-m-d H:i',$r['create_time']);
			$r['avatar'] = $r['avatar']?@unserialize($r['avatar']):array();
			$r['avatar_url'] = $r['avatar']?hg_fetchimgurl($r['avatar']):'';
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
		
		$sql = " INSERT INTO " . DB_PREFIX . "member SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."member SET order_id = {$vid}  WHERE id = {$vid}";
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
		$sql = " SELECT * FROM " .DB_PREFIX. "member WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "member SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE id = '"  .$id. "'";
		$this->db->query($sql);
		return $pre_data;
	}
	
	public function detail($id = '', $condition = '', $fields = '*')
	{
		if (!$id && !$condition) 
		{
            return false;
        }

        $sql = "SELECT " . $fields . " FROM " . DB_PREFIX . "member  WHERE 1 ";
        if ($id) 
        {
            $sql .= " AND id = '" . $id . "'";
        }
        
        if ($condition) 
        {
            $sql .= ' ' . $condition;
        }
        $info = $this -> db -> query_first($sql);
		return $info;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "member m LEFT JOIN " .DB_PREFIX. "activation_code a ON a.id = m.activate_code_id WHERE 1 " . $condition;
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
		$sql = " SELECT * FROM " .DB_PREFIX. "member WHERE id IN (" . $id . ")";
		$q = $this->db->query($sql);
		$pre_data = array();
		$activate_arr = array();
		while ($r = $this->db->fetch_array($q))
		{
			$pre_data[] 	= $r;
			if($r['activate_code_id'])
			{
				$activate_arr[] = $r['activate_code_id'];
			}
		}
		
		if(!$pre_data)
		{
			return false;
		}
		//删除主表
		$sql = " DELETE FROM " .DB_PREFIX. "member WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		
		//设置对应的激活码为未使用状态
		$_activate = implode(',',$activate_arr);
		$sql = "UPDATE " .DB_PREFIX. "activation_code SET is_use = 0 WHERE id IN (" .$_activate. ")";
		$this->db->query($sql);
		
		//删除交换表
		$sql = "DELETE FROM " . DB_PREFIX . "exchange_cards WHERE self_exchange_id IN (" .$id. ") OR other_exchange_id IN (" .$id. ") ";
		$this->db->query($sql);
		
		//删除对应的device_token
		$sql = "DELETE FROM " .DB_PREFIX. "device WHERE user_id IN (" .$id. ")";
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
		$sql = " SELECT * FROM " .DB_PREFIX. "member WHERE id = '" .$id. "'";
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
		
		$sql = " UPDATE " .DB_PREFIX. "member SET status = '" .$status. "' WHERE id = '" .$id. "'";
		$this->db->query($sql);
		return array('status' => $status,'id' => $id);
	}
	
	//交换嘉宾名片信息
	public function exchange_cards_info($data = array())
	{
		if(!$data)
		{
			return false;
		}
		
		$sql = " INSERT INTO " . DB_PREFIX . "exchange_cards SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		return $vid;
	}
	
	//判断两个用户是否已经交换过
	public function isHaveExchanged($m_a = '',$m_b = '')
	{
		if(!$m_a || !$m_b)
		{
			return false;
		}
		
		$sql = "SELECT * FROM " .DB_PREFIX. "exchange_cards WHERE (self_exchange_id = '" . $m_a . "' AND other_exchange_id = '" .$m_b. "') OR (self_exchange_id = '" .$m_b. "' AND other_exchange_id = '" .$m_a. "' )";
		$ret = $this->db->query_first($sql);
		return $ret;
	}
	
	//根据用户id获取已经交换的用户
	public function get_exchanged_members_by_id($self_id = '',$orderby = '',$limit = '')
	{
		if(!$self_id)
		{
			return false;
		}
		
		$sql = "SELECT m.id,m.avatar,m.name,m.company,m.job,m.telephone,m.email FROM " .DB_PREFIX. "exchange_cards e LEFT JOIN " .DB_PREFIX. "member m ON e.other_exchange_id = m.id WHERE e.self_exchange_id = '" .$self_id. "' " . $orderby . $limit;
		$q = $this->db->query($sql);
		$ret = array();
		while ($r = $this->db->fetch_array($q))
		{
			$r['avatar'] = $r['avatar']?unserialize($r['avatar']):array();
			$ret[] = $r;
		}
		return $ret;
	}
	
	//查询出需要碰撞的用户id，每次只查询一个人
	public function get_need_collision($condArr = array())
	{
		$_cond = '';
		if($condArr['create_time'])
		{
			$_cond .= " AND e.create_time > '" . $condArr['create_time'] . "' ";
		}
		
		$sql = "SELECT e.*,m1.name AS self_name,m1.avatar AS self_avatar,m2.name AS other_name,m2.avatar AS other_avatar FROM " .DB_PREFIX. "exchange_cards e LEFT JOIN " .DB_PREFIX. "member m1 ON m1.id = e.self_exchange_id LEFT JOIN " . DB_PREFIX . "member m2 ON m2.id = e.other_exchange_id WHERE 1 " .$_cond. " AND e.is_use = 0 ORDER BY e.create_time ASC ";
		$q = $this->db->query($sql);
		$ret = array();
		while ($r = $this->db->fetch_array($q))
		{
			$r['self_avatar'] = $r['self_avatar']?@unserialize($r['self_avatar']):array();
			$r['other_avatar'] = $r['other_avatar']?@unserialize($r['other_avatar']):array();
			$ret[] = $r;
		}
		return $ret;
	}
	
	//设置某一个碰撞任务已经使用
	public function set_collision_use($exchange_id = '')
	{
		if(!$exchange_id)
		{
			return false;
		}
		
		$sql = "UPDATE " .DB_PREFIX. "exchange_cards SET is_use = 1 WHERE id = '" .$exchange_id. "'";
		$this->db->query($sql);
		return true;
	}
	
	//根据用户id查询出设备id
	public function getDeviceInfoByUserId($user_id = '')
	{
		if (!$user_id) 
		{
            return false;
        }

        $sql = "SELECT * FROM " . DB_PREFIX . "device WHERE user_id = '" . $user_id . "'";
        $info = $this -> db -> query_first($sql);
        if ($info) 
        {
            return $info;
        }
        return false;	
	}
	
	//绑定用户设备号
	public function bindDevice($data = array())
	{
		if(!$data)
		{
			return false;
		}
		
		//查询存不存在改用户的设备token，如果存在就更新
		$sql = "SELECT * FROM " .DB_PREFIX. "device WHERE user_id = '" .$data['user_id']. "'";
		$_predata = $this->db->query_first($sql);
		if($_predata)
		{
			$sql = "UPDATE " .DB_PREFIX. "device SET ";
		}
		else 
		{
			$sql = " INSERT INTO " . DB_PREFIX . "device SET ";
		}
		
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		if($_predata)
		{
			$sql .= " WHERE id = '" .$_predata['id']. "' ";
		}
		$this->db->query($sql);
		if($_predata)
		{
			$vid = $_predata['id'];
		}
		else 
		{
			$vid = $this->db->insert_id();
		}
		return $vid;
	}
	
	//获取已经签到的嘉宾
	public function get_signed_members($condArr = array())
	{
		$ret = array();
		if(!$condArr || !$condArr['screen_id'])
		{
			return $ret;
		}
		
		$cond = " AND m.is_sign = 1 AND m.screen_id = '" .$condArr['screen_id']. "' ";
		if($condArr['sign_time'])
		{
			$cond .= " AND m.sign_time > '" .$condArr['sign_time']. "' ";
		}
		
		$order_by = ' ORDER BY m.sign_time ASC ';
		//查询已经签到的用户，按签到时间正顺排列
		$sql = "SELECT m.*,a.guest_type FROM " .DB_PREFIX. "member m LEFT JOIN " .DB_PREFIX. "activation_code a ON a.id = m.activate_code_id WHERE 1 " . $cond . $order_by;
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$r['avatar'] = $r['avatar']?@unserialize($r['avatar']):array();
			$r['guest_type'] = $r['guest_type']?$r['guest_type']:1;
			$r['guest_type_text'] = $this->settings['guest_type'][$r['guest_type']];
			$ret[] = $r;
		}
		return $ret;
	}
	
	//获取某人已经交换名片的个数
	public function get_exchanged_nums($id = '')
	{
		if(!$id)
		{
			return '0';
		}
		
		$sql = "SELECT COUNT(*) AS total FROM " .DB_PREFIX. "exchange_cards WHERE self_exchange_id = '" .$id. "'";
		$arr = $this->db->query_first($sql);
		if(!$arr)
		{
			return '0';
		}
		return $arr['total'];
	}
	
	//设置交换名片的两个人的交换个数加1
	public function setExchangeNums($ids = array())
	{
		if($ids)
		{
			$id = implode(',',$ids);
			$sql = "UPDATE " .DB_PREFIX. "member SET exchange_num = exchange_num + 1 WHERE id IN (" .$id. ") ";
			$this->db->query($sql);
		}
		return true;
	}
	
	//验证device_token与原来是否相同，不同则更新成当前的
	public function check_device_token($_deviceInfo)
	{
		if(!$_deviceInfo || !$_deviceInfo['device_token'] || !$_deviceInfo['user_id'])
		{
			return;
		}
		
		$sql = "SELECT m.id,d.device_token FROM " .DB_PREFIX. "member m LEFT JOIN " .DB_PREFIX. "device d ON d.user_id = m.id WHERE m.member_id = '" .$_deviceInfo['user_id']. "' ";
		$arr = $this->db->query_first($sql);
		if($arr && $arr['device_token'] != $_deviceInfo['device_token'])
		{
			$sql = "UPDATE " .DB_PREFIX. "device SET device_token = '" .$_deviceInfo['device_token']. "',source = '" .$_deviceInfo['source']. "' WHERE user_id = '" .$arr['id']. "' ";
			$this->db->query($sql);
		}
	}
	
	public function get_exchange_info($cond = '')
	{
		if(!$cond)
		{
			return false;
		}
		
		$sql = "SELECT * FROM " .DB_PREFIX. "exchange_cards WHERE 1 " . $cond;
		$arr = $this->db->query_first($sql);
		return $arr;
	}
	
	//删除交换名片表
	public function deleteExchangeCards($_ids = array())
	{
		if(!$_ids)
		{
			return false;
		}
		
		$ids = implode(',',$_ids);
		$sql = "DELETE FROM " .DB_PREFIX. "exchange_cards WHERE (self_exchange_id = '" .$_ids[0]. "' AND other_exchange_id = '" .$_ids[1]. "') OR (self_exchange_id = '" .$_ids[1]. "' AND other_exchange_id = '" .$_ids[0]. "') ";
		$this->db->query($sql);
		//修改交换个数
		$sql = "UPDATE "  .DB_PREFIX. "member SET exchange_num = exchange_num - 1 WHERE id IN (" .$ids. ")";
		$this->db->query($sql);
		return true;
	}
}
?>