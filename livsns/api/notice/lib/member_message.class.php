<?php
require_once(ROOT_PATH. 'lib/class/curl.class.php');
class memberMessage extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	//消息内容
	public function insertMessageText($data)
	{
		if (!$data || !is_array($data))
		{
			return false;
		}
		$sql  = 'INSERT INTO '.DB_PREFIX.'member_message_text SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		$id = $this->db->insert_id();
		$data['id'] = $id;
		return $data;
	}
	//消息关系
	public function insertMessage($data)
	{
		if (!$data || !is_array($data))
		{
			return false;
		}
		$sql  = 'INSERT INTO '.DB_PREFIX.'member_message SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		$id = $this->db->insert_id();
		$data['id'] = $id;
		return $data;
	}
	
	public function personalMessageForUnread($userId, $type)
	{
		if (!intval($userId))
		{
			return false;
		}
		$sql = 'SELECT m.id,
				mt.user_id, mt.user_type, mt.title, mt.message, mt.post_date 
				FROM '.DB_PREFIX.'member_message m
				LEFT JOIN '.DB_PREFIX.'member_message_text mt ON m.message_id = mt.id
				WHERE m.receive_user_id = '.intval($userId).' AND m.receive_user_type = '.intval($type).' 
				AND m.status = 0 AND m.is_refused = 0 AND mt.message_type = 1';
		//echo $sql;exit();
		$query = $this->db->query($sql);
		$arr =  array(); 
		//用户类型归类
		$userType  = array();
		while ($row = $this->db->fetch_array($query))
		{
			$row['receive_user_type'] = $type;
			$row['receive_user_id']	= $userId;
			$row['user_type_name'] = $this->settings['member_type'][$row['user_type']];
			$row['receive_user_type_name'] = $this->settings['member_type'][$row['receive_user_type']];
			$userType[$row['receive_user_type']][] = $row['receive_user_id'];
			$userType[$row['user_type']][] = $row['user_id'];
			$arr[] = $row;
		}
		//用户信息
		$userInfor = $this->userInfor($userType);
		//hg_pre($userInfor);exit();
		if (!empty($arr))
		{
			foreach($arr as $key=>$val)
			{
				$arr[$key]['receive_user_name'] = $userInfor[$val['receive_user_type']][$val['receive_user_id']]['user_name'];
				$arr[$key]['receive_user_avatar'] = $userInfor[$val['receive_user_type']][$val['receive_user_id']]['avatar'];
				$arr[$key]['receive_user_email'] = $userInfor[$val['receive_user_type']][$val['receive_user_id']]['email'];
				$arr[$key]['user_name'] = $userInfor[$val['user_type']][$val['user_id']]['user_name'];
				$arr[$key]['avatar'] = $userInfor[$val['user_type']][$val['user_id']]['avatar'];
				$arr[$key]['email'] = $userInfor[$val['user_type']][$val['user_id']]['email'];
			}
		}
		//hg_pre($arr);exit();
		return $arr;
	}
	
	public function personalMessageForRead($userId, $type)
	{
		if (!intval($userId))
		{
			return false;
		}
		$sql = 'SELECT m.id,
				mt.user_id, mt.user_type, mt.title, mt.message, mt.post_date  
				FROM '.DB_PREFIX.'member_message m
				LEFT JOIN '.DB_PREFIX.'member_message_text mt ON m.message_id = mt.id
				WHERE m.receive_user_id = '.intval($userId).' AND m.receive_user_type = '.intval($type).' 
				AND m.status = 1 AND mt.message_type = 1';
		$query = $this->db->query($sql);
		$arr =  array(); 
		//用户类型归类
		$userType  = array();
		while ($row = $this->db->fetch_array($query))
		{
			$row['receive_user_type'] = $type;
			$row['receive_user_id']	= $userId;
			$row['user_type_name'] = $this->settings['member_type'][$row['user_type']];
			$row['receive_user_type_name'] = $this->settings['member_type'][$row['receive_user_type']];
			$userType[$row['receive_user_type']][] = $row['receive_user_id'];
			$userType[$row['user_type']][] = $row['user_id'];
			$arr[] = $row;
		}
		//用户信息
		$userInfor = $this->userInfor($userType);
		//hg_pre($userInfor);exit();
		if (!empty($arr))
		{
			foreach($arr as $key=>$val)
			{
				$arr[$key]['receive_user_name'] = $userInfor[$val['receive_user_type']][$val['receive_user_id']]['user_name'];
				$arr[$key]['receive_user_avatar'] = $userInfor[$val['receive_user_type']][$val['receive_user_id']]['avatar'];
				$arr[$key]['receive_user_email'] = $userInfor[$val['receive_user_type']][$val['receive_user_id']]['email'];
				$arr[$key]['user_name'] = $userInfor[$val['user_type']][$val['user_id']]['user_name'];
				$arr[$key]['avatar'] = $userInfor[$val['user_type']][$val['user_id']]['avatar'];
				$arr[$key]['email'] = $userInfor[$val['user_type']][$val['user_id']]['email'];
			}
		}
		return $arr;
	}
	//受自动清除时间限制
	public function personalMessageForDelete($userId, $type)
	{
		if (!intval($userId))
		{
			return false;
		}
		$sql = 'SELECT m.id,
				mt.user_id, mt.user_type, mt.title, mt.message, mt.post_date  
				FROM '.DB_PREFIX.'member_message m
				LEFT JOIN '.DB_PREFIX.'member_message_text mt ON m.message_id = mt.id
				WHERE m.receive_user_id = '.intval($userId).' AND m.receive_user_type = '.intval($type).' 
				AND m.status = 2 AND mt.message_type IN (1,2,3)';
		$query = $this->db->query($sql);
		$arr =  array(); 
		//用户类型归类
		$userType  = array();
		while ($row = $this->db->fetch_array($query))
		{
			$row['receive_user_type'] = $type;
			$row['receive_user_id']	= $userId;
			$row['user_type_name'] = $this->settings['member_type'][$row['user_type']];
			$row['receive_user_type_name'] = $this->settings['member_type'][$row['receive_user_type']];
			$userType[$row['receive_user_type']][] = $row['receive_user_id'];
			$userType[$row['user_type']][] = $row['user_id'];
			$arr[] = $row;
		}
		$userInfor = $this->userInfor($userType);
		//hg_pre($userInfor);exit();
		if (!empty($arr))
		{
			foreach($arr as $key=>$val)
			{
				$arr[$key]['receive_user_name'] = $userInfor[$val['receive_user_type']][$val['receive_user_id']]['user_name'];
				$arr[$key]['receive_user_avatar'] = $userInfor[$val['receive_user_type']][$val['receive_user_id']]['avatar'];
				$arr[$key]['receive_user_email'] = $userInfor[$val['receive_user_type']][$val['receive_user_id']]['email'];
				$arr[$key]['user_name'] = $userInfor[$val['user_type']][$val['user_id']]['user_name'];
				$arr[$key]['avatar'] = $userInfor[$val['user_type']][$val['user_id']]['avatar'];
				$arr[$key]['email'] = $userInfor[$val['user_type']][$val['user_id']]['email'];
			}
		}
		return $arr;
	}
	//已发送消息
	public function personalMessageFormSelf($userId, $type)
	{
		if (!intval($userId))
		{
			return false;
		}
		$sql = 'SELECT id, user_id, user_type,title, message, message_type, group_id, group_type, post_date 
				FROM '.DB_PREFIX.'member_message_text 
				WHERE user_id = '.$userId.' AND user_type = '.$type;
		$query = $this->db->query($sql);
		$receiveIds = array();
		$arr = array();
		$receiveGroups = array();
		while ($row = $this->db->fetch_array($query))
		{
			if ($row['group_id'])
			{
				$receiveGroups[$row['group_type']][] = $row['group_id'];
			}
			$receiveIds[$row['message_type']][] = $row['id'];
			$arr[$row['id']] = $row;
		}
		
		$receiveMembers = array();		
		if (!empty($receiveIds))
		{
			foreach ($receiveIds as $type=>$mids)
			{
				//私信
				if ($type == 1)
				{
					$messageIds = implode(',', $mids);
					$sql = 'SELECT message_id, receive_user_id, receive_user_type 
							FROM '.DB_PREFIX.'member_message WHERE message_id IN ('.$messageIds.')';
					$query = $this->db->query($sql);
					$receiveUser = array();
					$result = array();
					while ($row = $this->db->fetch_array($query))
					{
						$receiveUser[$row['receive_user_type']][] = $row['receive_user_id'];
						$aa[$row['message_id']][$row['receive_user_type']][] = $row['receive_user_id'];					
					}
					//hg_pre($receiveUser);exit();
					$userInfor = $this->userInfor($receiveUser);
					foreach ($aa as $mid=>$userInfo)
					{
						foreach ($userInfo as $user_type=>$uid)
						{
							foreach ($uid as $iid)
							{
								$receiveMembers[$mid][] = $userInfor[$user_type][$iid];
							}
						}
					}
					//hg_pre($receiveMembers);exit();
				}
				//公共信息，此处只显示组名
				if ($type == 2)
				{
					$Groups = $this->getGroup($receiveGroups);
					//hg_pre($arr);
					//hg_pre($Groups);exit();
					if (is_array($mids) && !empty($mids))
					{
						foreach ($mids as $gid)
						{
							if ($arr[$gid]['group_id'] && is_string($arr[$gid]['group_id']))
							{
								$groupIds = explode(',', $arr[$gid]['group_id']);
								foreach ($groupIds as $val)
								{
									$receiveMembers[$gid][] = $Groups[$arr[$gid]['group_type']][$val];
								}
							}
						}
					}
				}
				//系统消息
				if ($type == 3)
				{
					if (is_array($mids) && !empty($mids))
					{
						foreach ($mids as $sid)
						{
							$receiveMembers[$sid][] = array(
								'id'		=> -1,
								'user_name'	=> '系统消息',
								'avatar'	=> '',
								'email'		=> ''
							);
						}
					}
					
				}
			}
		}
		if (!empty($arr))
		{
			foreach ($arr as $key=>$val)
			{
				$arr[$val['id']]['receive_user'] = $receiveMembers[$val['id']];
			}
		}
		hg_pre($arr);exit();
		return $arr;
	}

	//公共消息未读
	public function commonMessageForUnread($userId, $type, $group, $gtype)
	{
		if (!intval($userId) || !$group)
		{
			return false;
		}
		$sql = 'SELECT id, user_id, user_type,title, message, post_date FROM '.DB_PREFIX.'member_message_text 
				WHERE '.intval($group).' IN ( group_id ) AND message_type = 2 AND group_type = '.intval($gtype).' 
				AND user_id != '.$userId.' AND user_type != '.$type.' 
				AND id  NOT IN (
				SELECT message_id FROM '.DB_PREFIX.'member_message 
				WHERE receive_user_type = '.intval($type).' AND receive_user_id= '.intval($userId).')';
		if (defined('EXPIRY_DATE') && intval(EXPIRY_DATE))
		{
			$expiryDate = intval(EXPIRY_DATE);
			$expiryDate = $expiryDate*24*60*60;//有效期，单位天
			$sql .= ' AND effective_time <'.$expiryDate;
		}
		//echo $sql;exit();
		$query = $this->db->query($sql);
		$arr =  array(); 
		//用户类型归类
		$userType  = array();
		while ($row = $this->db->fetch_array($query))
		{
			$row['receive_user_type'] = $type;
			$row['receive_user_id']	= $userId;
			$row['user_type_name'] = $this->settings['member_type'][$row['user_type']];
			$row['receive_user_type_name'] = $this->settings['member_type'][$row['receive_user_type']];
			$userType[$row['receive_user_type']][] = $row['receive_user_id'];
			$userType[$row['user_type']][] = $row['user_id'];
			$arr[] = $row;
		}
		$userInfor = $this->userInfor($userType);
		//hg_pre($userInfor);exit();
		if (!empty($arr))
		{
			foreach($arr as $key=>$val)
			{
				$arr[$key]['receive_user_name'] = $userInfor[$val['receive_user_type']][$val['receive_user_id']]['user_name'];
				$arr[$key]['receive_user_avatar'] = $userInfor[$val['receive_user_type']][$val['receive_user_id']]['avatar'];
				$arr[$key]['receive_user_email'] = $userInfor[$val['receive_user_type']][$val['receive_user_id']]['email'];
				$arr[$key]['user_name'] = $userInfor[$val['user_type']][$val['user_id']]['user_name'];
				$arr[$key]['avatar'] = $userInfor[$val['user_type']][$val['user_id']]['avatar'];
				$arr[$key]['email'] = $userInfor[$val['user_type']][$val['user_id']]['email'];
			}
		}
		return $arr;
	}
	//公共消息已读
	public function commonMessageForRead($userId, $type, $group, $gtype)
	{
		if (!intval($userId) || !$group)
		{
			return false;
		}
		$sql = 'SELECT id, user_id, user_type, title, message,post_date FROM '.DB_PREFIX.'member_message_text 
				WHERE '.intval($group).' IN ( group_id )  AND message_type = 2 AND group_type = '.intval($gtype).'  
				AND user_id != '.$userId.' AND user_type != '.$type.' 
				AND id IN (
				SELECT message_id FROM '.DB_PREFIX.'member_message 
				WHERE receive_user_type = '.intval($type).' AND receive_user_id= '.intval($userId).')';
		//echo $sql;exit;
		$query = $this->db->query($sql);
		$arr =  array(); 
		//用户类型归类
		$userType  = array();
		while ($row = $this->db->fetch_array($query))
		{
			$row['receive_user_type'] = $type;
			$row['receive_user_id']	= $userId;
			$row['user_type_name'] = $this->settings['member_type'][$row['user_type']];
			$row['receive_user_type_name'] = $this->settings['member_type'][$row['receive_user_type']];
			$userType[$row['receive_user_type']][] = $row['receive_user_id'];
			$userType[$row['receive_user_type']][] = $row['receive_user_id'];
			$userType[$row['user_type']][] = $row['user_id'];
			$arr[] = $row;
		}
		$userInfor = $this->userInfor($userType);
		//hg_pre($userInfor);exit();
		if (!empty($arr))
		{
			foreach($arr as $key=>$val)
			{
				$arr[$key]['receive_user_name'] = $userInfor[$val['receive_user_type']][$val['receive_user_id']]['user_name'];
				$arr[$key]['receive_user_avatar'] = $userInfor[$val['receive_user_type']][$val['receive_user_id']]['avatar'];
				$arr[$key]['receive_user_email'] = $userInfor[$val['receive_user_type']][$val['receive_user_id']]['email'];
				$arr[$key]['user_name'] = $userInfor[$val['user_type']][$val['user_id']]['user_name'];
				$arr[$key]['avatar'] = $userInfor[$val['user_type']][$val['user_id']]['avatar'];
				$arr[$key]['email'] = $userInfor[$val['user_type']][$val['user_id']]['email'];
			}
		}
		return $arr;
	}
	//系统消息未读
	public function globalMessageForUnread($userId, $type)
	{
		if (!intval($userId))
		{
			return false;
		}
		$sql = 'SELECT id, user_id, user_type, title, message,post_date FROM '.DB_PREFIX.'member_message_text 
				WHERE message_type = 3 AND user_id != '.$userId.' AND user_type != '.$type.' AND id NOT IN (
				SELECT message_id FROM '.DB_PREFIX.'member_message 
				WHERE receive_user_type = '.intval($type).' AND receive_user_id= '.intval($userId).')';
		if (defined('EXPIRY_DATE') && intval(EXPIRY_DATE))
		{
			$expiryDate = intval(EXPIRY_DATE);
			$expiryDate = $expiryDate*24*60*60;//有效期，单位天
			$sql .= ' AND effective_time <'.$expiryDate;
		}
		//echo $sql;exit();
		$query = $this->db->query($sql);
		$arr =  array(); 
		//用户类型归类
		$userType  = array();
		while ($row = $this->db->fetch_array($query))
		{
			$row['receive_user_type'] = $type;
			$row['receive_user_id']	= $userId;
			$row['user_type_name'] = $this->settings['member_type'][$row['user_type']];
			$row['receive_user_type_name'] = $this->settings['member_type'][$row['receive_user_type']];
			$userType[$row['receive_user_type']][] = $row['receive_user_id'];
			$userType[$row['user_type']][] = $row['user_id'];
			$arr[] = $row;
		}
		$userInfor = $this->userInfor($userType);
		//hg_pre($userInfor);exit();
		if (!empty($arr))
		{
			foreach($arr as $key=>$val)
			{
				$arr[$key]['receive_user_name'] = $userInfor[$val['receive_user_type']][$val['receive_user_id']]['user_name'];
				$arr[$key]['receive_user_avatar'] = $userInfor[$val['receive_user_type']][$val['receive_user_id']]['avatar'];
				$arr[$key]['receive_user_email'] = $userInfor[$val['receive_user_type']][$val['receive_user_id']]['email'];
				$arr[$key]['user_name'] = $userInfor[$val['user_type']][$val['user_id']]['user_name'];
				$arr[$key]['avatar'] = $userInfor[$val['user_type']][$val['user_id']]['avatar'];
				$arr[$key]['email'] = $userInfor[$val['user_type']][$val['user_id']]['email'];
			}
		}
		return $arr;
	}
	//系统消息已读
	public function globalMessageForRead($userId, $type)
	{
		if (!intval($userId))
		{
			return false;
		}
		$sql = 'SELECT id, user_id, user_type, title, message,post_date FROM '.DB_PREFIX.'member_message_text 
				WHERE message_type = 3 AND user_id != '.$userId.' AND user_type != '.$type.' AND id IN (
				SELECT message_id FROM '.DB_PREFIX.'member_message 
				WHERE receive_user_type = '.intval($type).' AND receive_user_id= '.intval($userId).')';
		$query = $this->db->query($sql);
		$arr =  array(); 
		//用户类型归类
		$userType  = array();
		while ($row = $this->db->fetch_array($query))
		{
			$row['receive_user_type'] = $type;
			$row['receive_user_id']	= $userId;
			$userType[$row['receive_user_type']][] = $row['receive_user_id'];
			$userType[$row['user_type']][] = $row['user_id'];
			$row['user_type_name'] = $this->settings['member_type'][$row['user_type']];
			$row['receive_user_type_name'] = $this->settings['member_type'][$row['receive_user_type']];
			$arr[] = $row;
		}
		$userInfor = $this->userInfor($userType);
		//hg_pre($userInfor);exit();
		if (!empty($arr))
		{
			foreach($arr as $key=>$val)
			{
				$arr[$key]['receive_user_name'] = $userInfor[$val['receive_user_type']][$val['receive_user_id']]['user_name'];
				$arr[$key]['receive_user_avatar'] = $userInfor[$val['receive_user_type']][$val['receive_user_id']]['avatar'];
				$arr[$key]['receive_user_email'] = $userInfor[$val['receive_user_type']][$val['receive_user_id']]['email'];
				$arr[$key]['user_name'] = $userInfor[$val['user_type']][$val['user_id']]['user_name'];
				$arr[$key]['avatar'] = $userInfor[$val['user_type']][$val['user_id']]['avatar'];
				$arr[$key]['email'] = $userInfor[$val['user_type']][$val['user_id']]['email'];
			}
		}
		return $arr;
	}
	
	//用户信息处理
	private  function userInfor($data = array())
	{		
		$infor = array();
		if (empty($data) || !is_array($data))
		{
			return $infor;
		}
		foreach ($data as $type=>$uid)
		{
			$uids = ''; //初始化
			$uids = implode(',', $uid);
			$infor[$type] = $this->get_userInfor($uids, $type);
		}
		return $infor;
	}
	
	//用户数据请求配置
	private function get_userInfor($uid, $type=0)
	{
		if (!$uid)
		{
			return false;
		}
		
		$infor = array();
		switch ($type)
		{
			case 0:$infor = $this->get_memberUserInfo_by_ids($uid); break;
			case 1:$infor = $this->get_m2oUserInfo_by_ids($uid); break;
		}
		return $infor;
	}
	
	//根据用户id获取用户信息，新会员,批量
	private function get_memberUserInfo_by_ids($uid)
	{
		//hg_pre($uid);exit();
		if (!$uid || !$this->settings['App_members'])
		{
			return false;
		}
		$ret = array();
		$curl = new curl($this->settings['App_members']['host'],$this->settings['App_members']['dir']);
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a', 'show');
		$curl->addRequestData('member_id',$uid);
		$ret = $curl->request('member.php');
		if (!$ret || $ret['ErrorCode'])
		{
			return false;
		}		
		$infor = array();
		foreach ($ret as $val)
		{
			$infor[$val['member_id']] = array(
				'id'		=> $val['member_id'],
				'user_name'	=> $val['member_name'],
				'avatar'	=> $val['avatar'],
				'email'		=> $val['email'],
			);
		}
		//hg_pre($infor);exit();
		return $infor;
	}
	//获取m2o用户信息
	private function get_m2oUserInfo_by_ids($uid)
	{
		
		if (!$uid || !$this->settings['App_auth'])
		{
			return false;
		}
		//hg_pre($uid);exit();
		$ret = array();
		$curl = new curl($this->settings['App_auth']['host'],$this->settings['App_auth']['dir']);
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a', 'getMemberById');
		$curl->addRequestData('id',$uid);
		$ret = $curl->request('member.php');
		if (!$ret || $ret['ErrorCode'])
		{
			return false;
		}
		$ret = $ret[0];
		$infor = array();
		foreach ($ret as $val)
		{
			$infor[$val['id']] = array(
				'id'		=> $val['id'],
				'user_name'	=> $val['user_name'],
				'avatar'	=> $val['avatar'],
				'email'		=> '',
			);
		}
		return $infor;
	}
	
	//私信从未读变已读
	public function personalMessageToRead($user_id, $user_type, $ids)
	{
		$sql = 'UPDATE '.DB_PREFIX.'member_message SET status = 1
				WHERE receive_user_id = '.$user_id.' 
				AND receive_user_type = '.$user_type.' 
				AND id IN ('.$ids.')';
		$this->db->query($sql);
		return true;
	}
	
	//公共信息从未读变已读
	public function commonMessageToRead($user_id, $user_type, $ids)
	{
		$ids = explode(',', $ids);
		if (is_array($ids) && !empty($ids))
		{
			$sql = 'REPLACE INTO '.DB_PREFIX.'member_message
			(id,receive_user_id, receive_user_type, message_id, status, fid, mark, is_refused)
			VALUES';
			foreach ($ids as $val)
			{
				$sql .= '("", '.$user_id.', '.$user_type.', '.$val.', 2, 0, 0, 0),';
			}
			$sql = rtrim($sql, ',');
			$this->db->query($sql);	
			return true;
		}
		else 
		{
			return false;
		}	
	}
	//系统信息从未读变已读
	public function globalMessageToRead($user_id, $user_type, $ids)
	{
		$ids = explode(',', $ids);
		if (is_array($ids) && !empty($ids))
		{
			$sql = 'REPLACE INTO '.DB_PREFIX.'member_message
			(id,receive_user_id, receive_user_type, message_id, status, fid, mark, is_refused)
			VALUES';
			foreach ($ids as $val)
			{
				$sql .= '("", '.$user_id.', '.$user_type.', '.$val.', 2, 0, 0, 0),';
			}
			$sql = rtrim($sql, ',');
			$this->db->query($sql);	
			return true;
		}
		else 
		{
			return false;
		}
	}
	//删除消息，此处软删除，最后由计划任务执行
	public function messageToDelete($user_id, $user_type, $ids)
	{
		if (!$ids || !is_string($ids) || !intval($user_id) )
		{
			return false;
		}
		$sql = 'UPDATE '.DB_PREFIX.'member_message SET status = 2 
				WHERE receive_user_id = '.intval($user_id).' 
				AND receive_user_type = '.intval($user_type).' 
				AND id IN ('.$ids.')';
		$this->db->query($sql);
		return $ids;
	}
	
	public function getGroup($data = array())
	{
		$infor = array();
		if (empty($data) || !is_array($data))
		{
			return $infor;
		}
		foreach ($data as $type=>$uid)
		{
			$uids = ''; //初始化
			$uids = implode(',', $uid);
			$infor[$type] = $this->get_groupInfor($uids, $type);
		}
		return $infor;
	}
	
	public function get_groupInfor($uid, $type=0)
	{
		if (!$uid)
		{
			return false;
		}
		$infor = array();
		switch ($type)
		{
			case 0:$infor = $this->getGroupFromMembers($uid); break;
			case 1:$infor = $this->getGroupFromM2O($uid); break;
		}
		return $infor;
	}
	
	//获取M2O的用户组
	public function getGroupFromM2O($ids)
	{
		if (!$this->settings['App_auth'])
		{
			return false;
		} 
		$ret = array();
		$curl = new curl($this->settings['App_auth']['host'],$this->settings['App_auth']['dir']);
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a', 'getMemberByOrg');
		$curl->addRequestData('id',$ids);
		$ret = $curl->request('member.php');
	}
	//获取新会员用户组
	public function getGroupFromMembers($ids = '')
	{
		if (!$this->settings['App_members'])
		{
			return false;
		}
		$curl = new curl($this->settings['App_members']['host'],$this->settings['App_members']['dir']);
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a', 'show');
		if ($ids)
		{
			$curl->addRequestData('id',$ids);
		}
		$ret = $curl->request('member_group.php');
		//hg_pre($ret);exit();
		if (!$ret || $ret['ErrorCode'])
		{
			return false;
		}
		$arr = array();
		if ($ret && is_array($ret))
		{
			foreach ($ret as $value)
			{
				$arr[$value['id']] = array(
					'id'	=> $value['id'],
					'user_name'	=> $value['name'],
					'avatar'=>'',
					'email'	=>'',
				);
			}
		}
		//hg_pre($arr);exit();
		return $arr;
	}
	//保存草稿
	public function insertDraft($data)
	{
		if (!$data || !is_array($data))
		{
			return false;
		}
		$sql  = 'INSERT INTO '.DB_PREFIX.'member_message_draft SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		$id = $this->db->insert_id();
		$data['id'] = $id;
		return $data;
	}
	//删除信息
	public function delMessageText($id)
	{
		if (!$id)
		{
			return false;
		}
		$sql = 'DELETE FROM '.DB_PREFIX.'member_message_text WHERE id IN ('.$id.')';
		$this->db->query($sql);
		return $id;
	}
}

?>