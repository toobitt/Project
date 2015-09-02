<?php
class market_member_mode extends InitFrm
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
		$sql = "SELECT * FROM " . DB_PREFIX . "market_member  WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['create_time'] = date('Y-m-d H:i:s',$r['create_time']);
			if($r['member_id'])
			{
				$status = 2;//已绑定
			}
			else 
			{
				$status = 1;//未绑定
			}
			$r['status'] = $status;
			$r['status_format'] = $this->settings['member_status'][$status];
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
		
		$sql = " INSERT INTO " . DB_PREFIX . "market_member SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."market_member SET order_id = {$vid}  WHERE id = {$vid}";
		$this->db->query($sql);
		
		//每创建一个会员,超市的会员总数要加1
		$sql = " UPDATE " .DB_PREFIX. "supermarket SET total_member = total_member + 1 WHERE id = '" .$data['market_id']. "'";
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
		$sql = " SELECT * FROM " .DB_PREFIX. "market_member WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "market_member SET ";
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
		
		$sql = "SELECT * FROM " . DB_PREFIX . "market_member  WHERE id = '" .$id. "'";
		$info = $this->db->query_first($sql);
		if(!$info)
		{
			return false;
		}
		$info['barcode_img_url'] = hg_fetchimgurl(unserialize($info['barcode_img']),300);
		return $info;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "market_member WHERE 1 " . $condition;
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
		$sql = " SELECT * FROM " .DB_PREFIX. "market_member WHERE id IN (" . $id . ")";
		$q = $this->db->query($sql);
		$pre_data = array();
		$bind_member = 0;//存放绑定的会员数
		while ($r = $this->db->fetch_array($q))
		{
			$pre_data[] 	= $r;
			if($r['member_id'])
			{
				$bind_member++;
			}
		}
		if(!$pre_data)
		{
			return false;
		}

		//删除主表
		$sql = " DELETE FROM " .DB_PREFIX. "market_member WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		$num = $this->db->affected_rows();
		$sql = " UPDATE " .DB_PREFIX. "supermarket SET total_member = total_member - " .$num. ",bind_member = bind_member - " .$bind_member. " WHERE id = '" .$pre_data[0]['market_id']. "'";
		$this->db->query($sql);
		return $pre_data;
	}
	
	/***********************************************扩展操作********************************************/
	public function bind($member_data = array(),$member_id = '')
	{
		if(!$member_data || !$member_id)
		{
			return 1;
		}
		
		$condition = '';
		foreach ($member_data AS $k => $v)
		{
			$condition .= " AND " .$k. " = '" .$v. "' ";
		}
		
		$sql = " SELECT * FROM " .DB_PREFIX. "market_member WHERE 1 " . $condition;
		$arr = $this->db->query_first($sql);
		
		//如果存在该用户
		if($arr)
		{
			//查询当前这个用户有没有已经绑定过该超市账号
			$sql = "SELECT * FROM " .DB_PREFIX. "bind_log WHERE member_id = '" .$member_id. "' AND market_id = '" .$arr['market_id']. "' AND market_member_id = '" .$arr['id']. "'";
			$memberInfo = $this->db->query_first($sql);
			if($memberInfo)
			{
				return 3;
			}
			
			//增加绑定的日志
			$sql = "INSERT INTO " .DB_PREFIX. "bind_log SET market_member_id = '" .$arr['id']. "',member_id = '" .$member_id. "',create_time = '" . TIMENOW . "',market_id = '" .$arr['market_id']. "' ";
			$this->db->query($sql);
			
			//更改超市已绑定用户的数目
			$sql = " UPDATE " .DB_PREFIX. "supermarket SET bind_member = bind_member + 1 WHERE id = '" .$arr['market_id']. "'";
			$this->db->query($sql);
			
			if(!$arr['member_id'])
			{
				$sql = "UPDATE "  . DB_PREFIX . "market_member SET member_id = 1 WHERE id = '" .$arr['id']. "'";
				$this->db->query($sql);
			}

			//查看该账号已经被多少人绑定过
			$sql = "SELECT COUNT(*) AS total FROM " .DB_PREFIX. "bind_log WHERE market_id = '" .$arr['market_id']. "' AND market_member_id = '" .$arr['id']. "'";
			$total = $this->db->query_first($sql);

			$arr['barcode_img'] = hg_fetchimgurl(unserialize($arr['barcode_img']));
			$arr['create_time'] = date('Y-m-d H:i:s',$arr['create_time']);
			$arr['update_time'] = date('Y-m-d H:i:s',$arr['update_time']);
			//$arr['member_id'] = $member_id;
			$arr['card_number'] = hg_split_string($arr['card_number'],4,'-');
			$arr['already_bind_num'] = $total?$total['total']:0;
			return $arr;
		}
		else 
		{
			return 4;
		}
	}
	
	//判断死否绑定了某个用户
	public function isBind($member_id = '',$market_id = '')
	{
		if(!$member_id || !$market_id)
		{
			return false;
		}
		
		//查询该账号有没有绑定用户
		$sql = "SELECT * FROM " .DB_PREFIX. "bind_log WHERE member_id = '" .$member_id. "' AND market_id = '" .$market_id. "'";
		$bindInfo = $this->db->query_first($sql);
		if(!$bindInfo)
		{
			return false;
		}

		//返回数据
		$sql = "SELECT * FROM " .DB_PREFIX. "market_member WHERE id = '" .$bindInfo['market_member_id']. "'";
		$info = $this->db->query_first($sql);
		if(!$info)
		{
			return false;
		}
		$info['barcode_img'] = hg_fetchimgurl(unserialize($info['barcode_img']));
		$info['create_time'] = date('Y-m-d H:i:s',$info['create_time']);
		$info['update_time'] = date('Y-m-d H:i:s',$info['update_time']);
		$info['card_number'] = hg_split_string($info['card_number'],4,'-');
		return $info;
	}
	
	//根据条件查询存不存在某条数据
	public function isExistsMember($condition = '')
	{
		$sql = "SELECT * FROM " .DB_PREFIX. "market_member WHERE 1 AND " . $condition;
		$data = $this->db->query_first($sql);
		if(!$data['id'])
		{
			return false;
		}
		return $data;
	}
	
	//给制定会员推送消息
	public function pushMessageToMember($data = array())
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
	
	//解绑定
	public function unbind($id = '')
	{
		if(!$id)
		{
			return false;
		}
		
		$sql = "SELECT * FROM " .DB_PREFIX. "market_member WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//删除该会员卡所有绑定信息
		$sql = "DELETE FROM " .DB_PREFIX. "bind_log WHERE market_id = '" .$pre_data['market_id']. "' AND market_member_id = '" .$id. "'";
		$this->db->query($sql);
		$num = $this->db->affected_rows();
		
		$sql = "UPDATE " .DB_PREFIX. "market_member SET member_id = 0 WHERE id = '" .$id. "'";
		$this->db->query($sql);
		if($num)
		{
			$sql = "UPDATE " .DB_PREFIX . "supermarket SET bind_member = bind_member - " .$num. " WHERE id = '" .$pre_data['market_id']. "'";
			$this->db->query($sql);
		}
		return true;
	}
	
	//解绑定手机端
	public function unbindMember($member_id = '',$market_id = '')
	{
		if(!$member_id || !$market_id)
		{
			return false;
		}
		
		//查询出绑定信息
		$sql = "SELECT * FROM " .DB_PREFIX. "bind_log WHERE member_id = '" .$member_id. "' AND market_id = '" . $market_id . "'";
		$bindInfo = $this->db->query_first($sql);
		if(!$bindInfo)
		{
			return false;
		}
		
		//删除这条绑定信息
		$sql = "DELETE FROM " .DB_PREFIX. "bind_log WHERE id = '" .$bindInfo['id']. "'";
		$this->db->query($sql);
		
		//查询该会员卡号被几个人绑定了
		$sql = "SELECT COUNT(*) AS total FROM " .DB_PREFIX. "bind_log WHERE market_member_id = '" .$bindInfo['market_member_id']. "' AND market_id = '" . $market_id . "'";
		$total = $this->db->query_first($sql);
		if(!$total)
		{
			//更新商超会员里面判断会员是否绑定的字段
			$sql = "UPDATE " .DB_PREFIX. "market_member SET member_id = 0 WHERE id = '" .$bindInfo['market_member_id']. "'";
			$this->db->query($sql);
		}
		
		$sql = "UPDATE " .DB_PREFIX . "supermarket SET bind_member = bind_member - 1 WHERE id = '" .$market_id. "'";
		$this->db->query($sql);
		return true;
	}
	
	//获取绑定的日志
	public function get_bind_log($market_id = '',$orderby = '',$limit = '')
	{
		if(!$market_id)
		{
			return false;
		}
		
		$cond = " AND market_id = '" .$market_id. "'";
		
		$sql = "SELECT COUNT(*) AS total,FROM_UNIXTIME(create_time,'%Y-%m-%d') AS date FROM " .DB_PREFIX. "bind_log WHERE 1  " .$cond. "  GROUP BY FROM_UNIXTIME(create_time,'%Y-%m-%d') " . $orderby . $limit;
		$q = $this->db->query($sql);
		$ret = array();
		while ($r = $this->db->fetch_array($q))
		{
			$ret[] = $r;
		}
		return $ret;
	}
	
	public function get_total_log($market_id = '')
	{
		$cond = " AND market_id = '" .$market_id. "'";
		$sql  = "SELECT COUNT(*) AS total FROM (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "bind_log WHERE 1 " . $cond . " GROUP BY FROM_UNIXTIME(create_time,'%Y-%m-%d')) AS subtable";
		$ret = $this->db->query_first($sql);
		return $ret['total'];
	}
	
	//获取与会员中心绑定的会员id
	public function get_bind_member_id($market_id = '',$market_member_id = '')
	{
		if(!$market_id || !$market_member_id)
		{
			return false;
		}
		
		$sql = "SELECT * FROM " .DB_PREFIX. "bind_log WHERE market_id = '" .$market_id. "' AND market_member_id = '" .$market_member_id. "'";
		$q = $this->db->query($sql);
		$info = array();
		while ($r = $this->db->fetch_array($q))
		{
			$info[] = $r['member_id'];
		}
		return $info;
	}
	
	/***********************************************扩展操作********************************************/
}
?>