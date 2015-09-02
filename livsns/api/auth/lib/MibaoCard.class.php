<?php
/*
 * 密保卡操作类
 */
class MibaoCard extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	//获取密保信息
	public function get_mibao_info($id = '')
	{
		if(!$id)
		{
			return false;
		}
		$sql = "SELECT a.cardid,s.zuobiao,a.user_name FROM " .DB_PREFIX. "admin a LEFT JOIN " .DB_PREFIX. "security_card s ON a.cardid = s.id WHERE a.id = '" .$id. "'";
		$card = $this->db->query_first($sql);
		if(!$card || !$card['cardid'])
		{
			return false;
		}
		$card['zuobiao'] = @unserialize($card['zuobiao']);
		return $card;
	}
	
	//绑定(重新绑定)
	public function bind_card($id = '')
	{
		if (!$id)
		{
			return false;
		}
		//查看原来有没有绑定密保卡，如果已经绑定了就删除原来的密保数据
		$sql = "SELECT cardid FROM " .DB_PREFIX. "admin WHERE id = '" .$id. "'";
		$user = $this->db->query_first($sql);
		//产生密保数据
		$secret = $this->create_card_data();
		$data = array(
			'zuobiao'=>serialize($secret),
			'create_time' => TIMENOW,
			'update_time' => TIMENOW
		);
		$sql = " INSERT INTO ".DB_PREFIX."security_card SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$cardid = $this->db->insert_id();
		//执行绑定
		$sql = "UPDATE " .DB_PREFIX. "admin SET cardid = '" .$cardid. "' WHERE id = '" .$id. "'";
		$this->db->query($sql);
		$ret = array(
			'zuobiao' => $secret,
			'cardid' => $cardid,
		);
		//如果原来绑定了密保卡将原来的密保卡数据删除掉
		if($user['cardid'])
		{
			$sql = "DELETE FROM " .DB_PREFIX. "security_card WHERE id = '" .$user['cardid']. "'";
			$this->db->query($sql);
		}
		return $ret;
	}
	
	//为所有用户绑定密保($is_retain指明是否保留原有已经绑定的密保，默认是保留，如果不保留重新绑定)
	public function bind_all_user($is_retain = true)
	{
		//先查询出所有用户
		$sql = "SELECT a.*,s.zuobiao FROM " .DB_PREFIX. "admin a LEFT JOIN " .DB_PREFIX. "security_card s ON a.cardid = s.id ";
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			if($r['zuobiao'])
			{
				$r['zuobiao'] = unserialize($r['zuobiao']);
			}
			$info[$r['user_name']] = $r['zuobiao'];
			$ids[$r['user_name']] = $r['id'];
		}
		
		if($info)
		{
			foreach($info AS $k => $v)
			{
				if($v && $is_retain)
				{
					continue;
				}
				//没绑定的就去绑定
				$bindInfo = $this->bind_card($ids[$k]);
				$info[$k] = $bindInfo['zuobiao'];
			}
		}
		return $info;
	}
	
	
	//取消绑定密保卡
	public function cancel_bind($id = '')
	{
		if (!$id)
		{
			return false;
		}
		//取出已经绑定的密保卡id
		$sql = "SELECT cardid FROM " .DB_PREFIX. "admin WHERE id = '" .$id. "'";
		$card = $this->db->query_first($sql);
		//更新admin
		$sql = "UPDATE " .DB_PREFIX. "admin SET cardid = 0 WHERE id = '" .$id. "'";
		$this->db->query($sql);
		//删除原来绑定的密保卡
		if($card['cardid'])
		{
			$sql = "DELETE FROM " . DB_PREFIX . "security_card WHERE id = '" .$card['cardid']. "'";
			$this->db->query($sql);
		}
		$ret = array('return' => 'success');
		return $ret;
	}
	
	//产生密保卡数据
	private function create_card_data()
	{
		/*产生密保随机数*/
		$secret = array();/*将密保数据以值对的形式保存*/
	    for($i = 'A';$i<='H';$i++)
		{
			for($j = 1;$j<=8;$j++)
			{
				$num = rand(0,99);
				if($num < 10)
				{
					$num = '0'.$num;
				}
				$secret["$i$j"] = $num;
			}
		}
		return $secret;
	}
}
?>