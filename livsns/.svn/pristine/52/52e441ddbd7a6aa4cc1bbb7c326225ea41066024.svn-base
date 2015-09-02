<?php
require_once(ROOT_PATH . 'lib/class/member.class.php');
require_once(ROOT_PATH. 'lib/class/curl.class.php');
require_once(ROOT_PATH. 'lib/class/members.class.php');
class timeline_mode extends InitFrm
{
	private $members;
	public function __construct()
	{
		parent::__construct();
		$this->member = new member();
		$this->members = new members();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition = '',$orderby = '',$limit = '')
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "timeline  WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			//此处根据情况做一些格式化的处理,如：date('Y-m-d',TIMENOW);
			$member_ids[] = $r['user_id'];
			$info[] = $r;
		}
		if (!empty($member_ids))
		{
			$member_ids = implode(',', $member_ids);
			//新旧会员处理
			if (defined('SEEKHELP_NEW_MEMBER') && SEEKHELP_NEW_MEMBER)
			{
				if ($this->settings['App_members'])
				{
					$userInfor = array();
					$temp_members = $this->members->get_newUserInfo_by_ids($member_ids);
					if ($temp_members && !empty($temp_members) && is_array($temp_members))
					{
						foreach ($temp_members as $val)
						{
                            $userInfor[$val['member_id']] = $val;
						}
					}
					//hg_pre($members);exit();
				}
			}
			else
			{
				$userInfor = $this->get_userinfo_by_id($member_ids);
			}
		}
		if (!empty($info))
		{
			foreach ($info as $key=>$val)
			{
				if ($userInfor[$val['user_id']]['member_name'])
				{
					$info[$key]['member_name'] = IS_HIDE_MOBILE?hg_hide_mobile($userInfor[$val['user_id']]['nick_name']):$userInfor[$val['user_id']]['nick_name'];
				}
				if ($userInfor[$val['user_id']]['avatar']['host'])
				{
					$info[$key]['member_avatar'] = array(
							'host'		=> $userInfor[$val['user_id']]['avatar']['host'],
							'dir'		=> $userInfor[$val['user_id']]['avatar']['dir'],
							'filepath'	=> $userInfor[$val['user_id']]['avatar']['filepath'],
							'filename'	=> $userInfor[$val['user_id']]['avatar']['filename'],
					);
				}
			}
		}
		return $info;
	}
	
	public function create($data = array())
	{
		if(!$data)
		{
			return false;
		}
		
		$sql = " INSERT INTO " . DB_PREFIX . "timeline SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."timeline SET order_id = {$vid}  WHERE id = {$vid}";
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
		$sql = " SELECT * FROM " .DB_PREFIX. "timeline WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "timeline SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE id = '"  .$id. "'";
		$this->db->query($sql);
		return $pre_data;
	}
	
	/**
	 * 更新与我相关的
	 * @param unknown $id
	 * @param unknown $data
	 * @return boolean|unknown
	 */
	public function updateToUser($userId, $data = array(), $condition = '')
	{
		if(!$data || !$userId)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "timeline WHERE to_user_id = '" .$userId. "'".$condition;
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
	
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "timeline SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE to_user_id = '"  .$userId. "'".$condition;
		$this->db->query($sql);
		return $pre_data;
	}
	
	public function detail($id = '')
	{
		if(!$id)
		{
			return false;
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "timeline  WHERE id = '" .$id. "'";
		$info = $this->db->query_first($sql);
		//此处根据情况做一些格式化的处理,如：date('Y-m-d',TIMENOW);
		return $info;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "timeline WHERE 1 " . $condition;
		$total = $this->db->query_first($sql);
		return $total;
	}
	
	public function delete($id = '', $type, $memberId = 0)
	{
		if(!$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "timeline WHERE relation_id IN (" . $id . ") AND type='".$type."' AND user_id =".$memberId."";
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
		$sql = " DELETE FROM " .DB_PREFIX. "timeline WHERE relation_id IN (" . $id . ") AND type='".$type."' AND user_id =".$memberId."";
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
		$sql = " SELECT * FROM " .DB_PREFIX. "timeline WHERE id = '" .$id. "'";
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
		
		$sql = " UPDATE " .DB_PREFIX. "timeline SET status = '" .$status. "' WHERE id = '" .$id. "'";
		$this->db->query($sql);
		return array('status' => $status,'id' => $id);
	}
	
}
?>