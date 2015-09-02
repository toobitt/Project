<?php
class market_message_mode extends InitFrm
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
		$sql = "SELECT * FROM " . DB_PREFIX . "market_message  WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['create_time'] = date('Y-m-d H:i:s',$r['create_time']);
			$r['scope_format'] = $this->settings['message_scope'][$r['scope']];
			$r['status_format'] = $this->settings['message_status'][$r['status']];
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
		
		$sql = " INSERT INTO " . DB_PREFIX . "market_message SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."market_message SET order_id = {$vid}  WHERE id = {$vid}";
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
		$sql = " SELECT * FROM " .DB_PREFIX. "market_message WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "market_message SET ";
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
		
		$sql = "SELECT * FROM " . DB_PREFIX . "market_message  WHERE id = '" .$id. "'";
		$info = $this->db->query_first($sql);
		if($info['member_id'])
		{
			$sql = "SELECT * FROM " .DB_PREFIX. "market_member WHERE id IN (" .$info['member_id']. ")";
			$q = $this->db->query($sql);
			$info['member'] = array();
			while ($r = $this->db->fetch_array($q))
			{
				$info['member'][$r['id']] = $r['name'];
			}
		}
		$info['expire_time'] = date('Y-m-d',$info['expire_time']);
		return $info;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "market_message WHERE 1 " . $condition;
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
		$sql = " SELECT * FROM " .DB_PREFIX. "market_message WHERE id IN (" . $id . ")";
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
		$sql = " DELETE FROM " .DB_PREFIX. "market_message WHERE id IN (" . $id . ")";
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
		$sql = " SELECT * FROM " .DB_PREFIX. "market_message WHERE id = '" .$id. "'";
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
		
		$sql = " UPDATE " .DB_PREFIX. "market_message SET status = '" .$status. "' WHERE id = '" .$id. "'";
		$this->db->query($sql);
		return array('status' => $status,'id' => $id);
	}
	
	/*************************************扩展操作*****************************************************/
	
	//根据会员名称获取会员id
	public function getMemberIdByName($names = array(),$market_id = '')
	{
		if(!$names || !$market_id)
		{
			return false;
		}
		
		$condition = "";
		foreach ($names AS $k => $v)
		{
			$condition .= " name = '" .$v. "' OR";
		}
		$condition = rtrim($condition,"OR");
		$sql = "SELECT id FROM " . DB_PREFIX . "market_member WHERE " .$condition. " AND market_id = '" .$market_id. "'";
		$q = $this->db->query($sql);
		$ids = array();
		while ($r = $this->db->fetch_array($q))
		{
			$ids[] = $r['id'];
		}
		return $ids;
	}
	
	//发送消息
	public function sendMessage($id = '')
	{
		if(!$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "market_message WHERE id IN (" .$id. ") AND status = 1";
		$q = $this->db->query($sql);
		$ids = array();
		while ($r = $this->db->fetch_array($q))
		{
			$ids[] = $r['id'];
		}
		
		if(!$ids)
		{
			return false;
		}
		
		$sql = " UPDATE " .DB_PREFIX. "market_message SET status = 2 WHERE id IN (" .implode(',',$ids). ")";
		$this->db->query($sql);
		return array('status' => 2,'id' => $id ,'status_format' => $this->settings['message_status'][2]);
	}
	
	//获取会员信息
	private function getMmeberInfo($id = '')
	{
		if(!$id)
		{
			return false;
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "market_member  WHERE id = '" .$id. "'";
		$info = $this->db->query_first($sql);
		return $info;
	}
	
	//获取可某个会员相关的所有信息
	public function getMessageByMemberId($market_id = '',$member_id = '',$device = '')
	{
		if(!$market_id || !$member_id)
		{
			return false;
		}
		
		//根据会员中心的id查询出超市里面的会员的id
		$sql = "SELECT market_member_id FROM " .DB_PREFIX. "bind_log WHERE member_id = '" .$member_id. "' AND market_id = '" . $market_id . "' ";
		$bind_info = $this->db->query_first($sql);
		if(!$bind_info)
		{
			return false;
		}
		else 
		{
			$member_id = $bind_info['market_member_id'];
		}
		$message = array();
		
		/*********************************先查询出发给所有用户的信息*************************************/
		$sql = " SELECT * FROM " . DB_PREFIX . "market_message WHERE scope = 1 AND status = 2 AND market_id = '" . $market_id . "' AND expire_time > " . TIMENOW . " ORDER BY create_time DESC ";
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$r['expire_time'] = date('Y-m-d',$r['expire_time']);
			$r['create_time'] = date('Y-m-d H:m:s',$r['create_time']);
			$r['update_time'] = date('Y-m-d H:m:s',$r['update_time']);
			$message['all'][] = $r;
		}
		/*********************************先查询出发给所有用户的信息*************************************/
		
		/*********************************查询出直接发给该会员的信息*************************************/
		$sql = " SELECT * FROM " . DB_PREFIX . "market_message WHERE scope = 3 AND status = 2  AND market_id = '" . $market_id . "' AND FIND_IN_SET('" . $member_id . "',member_id) AND expire_time > " . TIMENOW . " ORDER BY create_time DESC ";
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$r['expire_time'] = date('Y-m-d',$r['expire_time']);
			$r['create_time'] = date('Y-m-d H:m:s',$r['create_time']);
			$r['update_time'] = date('Y-m-d H:m:s',$r['update_time']);
			$message['direct'][] = $r;
		}
		/*********************************查询出直接发给该会员的信息*************************************/
		
		/*********************************查询出与该会员匹配的特定条件的信息*******************************/
		$memberInfo = $this->getMmeberInfo($member_id);
		$sql = " SELECT * FROM " . DB_PREFIX . "market_message WHERE scope = 2 AND status = 2  AND market_id = '" . $market_id . "' AND expire_time > " . TIMENOW . " ORDER BY create_time DESC ";
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			//客户端限制
			if($r['send_device'])
			{
				$send_device_arr = explode(',',$r['send_device']);
				if(!in_array($device,$send_device_arr))
				{
					continue;
				}
			}
			
			//年龄限制
			if($r['age_start'] && $r['age_end'])
			{
				if($memberInfo['age'] < $r['age_start'] || $memberInfo['age'] > $r['age_end'])
				{
					continue;
				}
			}
			//星座限制
			if($r['constellation_id'])
			{
				$constellation_arr = explode(',',$r['constellation_id']);
				if(!in_array($memberInfo['constellation_id'],$constellation_arr))
				{
					continue;
				}
			}
			
			//出生日期限制
			if($r['birthday_start'] && $r['birthday_end'])
			{
				//先把出生的月份与天转换为任意的某一年的日期（这里就用2013年）的时间戳
				$birthday_start = strtotime('2013-' . $r['birthday_start']);
				$birthday_end = strtotime('2013-' . $r['birthday_end']);
				
				//会员的日期
				$member_birthday = strtotime('2013-' . $memberInfo['month'] . '-' . $memberInfo['day']);
				
				if($member_birthday < $birthday_start || $member_birthday > $birthday_end)
				{
					continue;
				}
			}
			$r['expire_time'] = date('Y-m-d',$r['expire_time']);
			$r['create_time'] = date('Y-m-d H:m:s',$r['create_time']);
			$r['update_time'] = date('Y-m-d H:m:s',$r['update_time']);
			$message['match'][] = $r;
		}
		/*********************************查询出与该会员匹配的特定条件的信息*******************************/
		return $message;
	}
	
	//判断某个用户在某个超市里面是否已经有生日消息了
	public function isHasBirthdayMessage($market_id = '',$member_id = '')
	{
		if(!$market_id || !$member_id)
		{
			return false;
		}
		
		$sql = "SELECT * FROM " .DB_PREFIX. "market_message WHERE market_id = '" .$market_id. "' AND member_id = '" .$member_id. "' AND is_birthday = 1";
		$info = $this->db->query_first($sql);
		if(!$info)
		{
			return false;
		}
		return $info;
	}
	
	//清除过期的消息
	public function clean_expire_message()
	{
		$sql = "DELETE FROM " .DB_PREFIX. "market_message WHERE expire_time < " . TIMENOW;
		$this->db->query($sql);
	}
}
?>