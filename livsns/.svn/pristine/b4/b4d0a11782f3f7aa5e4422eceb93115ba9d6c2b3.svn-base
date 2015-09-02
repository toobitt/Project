<?php 
require_once(ROOT_PATH . 'lib/class/material.class.php');
require_once(ROOT_PATH. 'lib/class/curl.class.php');
require_once(ROOT_PATH . 'lib/class/member.class.php');
require_once(ROOT_PATH . 'lib/class/members.class.php');
class ClassSeekhelpComment extends InitFrm
{
	private $members;
	public function __construct()
	{
		parent::__construct();
		$this->material = new material();
		$this->member = new member();
		$this->members = new members();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create($data)
	{
		$sql = 'INSERT INTO '.DB_PREFIX.'comment SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.addslashes($val).'",';	
		}
		$sql = rtrim($sql, ',');
		$this->db->query($sql);
		$id = $this->db->insert_id();
		$sql = 'UPDATE '.DB_PREFIX.'comment SET order_id = '.$id.' WHERE id = '.$id;
		$this->db->query($sql);
		$data['id'] = $id;
		return $data;
		
	}
	
	public function show($condition, $orderby, $offset, $count)
	{
		if(!$offset || !$count)
		{
			$limit = "";
		}
		else 
		{
			$limit = " limit {$offset}, {$count}";
		}
		
		$sql = 'SELECT c.*,s.title AS seekhelp_title FROM '.DB_PREFIX.'comment c LEFT JOIN ' .DB_PREFIX. 'seekhelp s ON c.cid = s.id  WHERE 1 '. $condition.$orderby.$limit;
		$query = $this->db->query($sql);
		$res = array();
		$member_ids = array();
		while ($row = $this->db->fetch_array($query))
		{
			$row['seekhelp_title'] = seekhelp_clean_value($row['seekhelp_title']);
			$row['content'] = seekhelp_clean_value(stripcslashes(urldecode($row['content'])));
			$member_ids[] = $row['member_id'];
			$row['pass_time'] = TIMENOW-$row['create_time'];
			$row['format_create_time'] = date('Y-m-d H:i:s', $row['create_time']);
			switch ($row['status'])
			{
				case 0 : $row['status_name'] = '未审核';break;
				case 1 : $row['status_name'] = '已审核';break;
				case 2 : $row['status_name'] = '被打回';break;
			}
			$res[] = $row; 
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
		if (!empty($res))
		{
			foreach ($res as $key=>$val)
			{
				//新旧会员处理
				if (defined('SEEKHELP_NEW_MEMBER') && SEEKHELP_NEW_MEMBER)
				{
					if ($userInfor[$val['member_id']]['member_name'])
					{
						$res[$key]['member_name'] = IS_HIDE_MOBILE?hg_hide_mobile($userInfor[$val['member_id']]['nick_name']):$userInfor[$val['member_id']]['nick_name'];
					}
					if ($userInfor[$val['member_id']]['avatar']['host'])
					{
						$res[$key]['member_avatar'] = array(
							'host'		=> $userInfor[$val['member_id']]['avatar']['host'],
							'dir'		=> $userInfor[$val['member_id']]['avatar']['dir'],
							'filepath'	=> $userInfor[$val['member_id']]['avatar']['filepath'],
							'filename'	=> $userInfor[$val['member_id']]['avatar']['filename'],
						);
						$res[$key]['member_level'] = intval($userInfor[$val['member_id']]['digital']);
						$res[$key]['avatar'] = array(
								'host'		=> $userInfor[$val['member_id']]['avatar']['host'],
								'dir'		=> $userInfor[$val['member_id']]['avatar']['dir'],
								'filepath'	=> $userInfor[$val['member_id']]['avatar']['filepath'],
								'filename'	=> $userInfor[$val['member_id']]['avatar']['filename'],
						);
					}
				}
				else 
				{
					if ($userInfor[$val['member_id']]['nick_name'])
					{
						$res[$key]['member_name'] = IS_HIDE_MOBILE?hg_hide_mobile($userInfor[$val['member_id']]['nick_name']):$userInfor[$val['member_id']]['nick_name'];
					}
					if ($userInfor[$val['member_id']]['host'])
					{
						$res[$key]['member_avatar'] = array(
							'host'		=> $userInfor[$val['member_id']]['host'],
							'dir'		=> $userInfor[$val['member_id']]['dir'],
							'filepath'	=> $userInfor[$val['member_id']]['filepath'],
							'filename'	=> $userInfor[$val['member_id']]['filename'],
						);
					}
				}
			}
		}
		return $res;
	}
	
	public function count($condition)
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'comment c WHERE 1'.$condition;
		$ret = $this->db->query_first($sql);
		return $ret;
	}
	
	/**
	 * 
	 * @Description 根据用户id取用户信息
	 * @author Kin
	 * @date 2013-6-9 下午04:22:41
	 */
	private function get_userinfo_by_id($ids)
	{
		$ret = $this->member->getMemberByIds($ids);
		return $ret[0];
	}
	
	
	public function delete($ids='')
	{
		if(!$ids)
		{
			return false;
		}
		$sql = 'DELETE FROM '.DB_PREFIX.'comment WHERE id IN ('.$ids.')';
		$this->db->query($sql);
		return $ids;
	}
	
	
	public function audit($ids, $status)
	{
		$sql = 'UPDATE '.DB_PREFIX.'comment SET status = '.$status.' WHERE id IN ('.$ids.')';
		$this->db->query($sql);
		$ids = explode(',', $ids);
		$arr = array(
			'id'=>$ids,
			'status'=>$status,
		);

        switch ($status)
        {
            case 0 : $arr['status_name'] = '未审核';break;
            case 1 : $arr['status_name'] = '已审核';break;
            case 2 : $arr['status_name'] = '被打回';break;
        }
		return $arr;
	}
	
	public function detail($id)
	{
		if (!$id)
		{
			return false;
		}
		$sql = 'SELECT c.*,s.title AS seekhelp_title FROM '.DB_PREFIX.'comment c LEFT JOIN ' .DB_PREFIX. 'seekhelp s ON c.cid = s.id  WHERE c.id = '.$id;
		$ret = $this->db->query_first($sql);
		$ret['member_name'] = '';
		if ($ret['member_id'])
		{
			//新旧会员处理
			if (defined('SEEKHELP_NEW_MEMBER') && SEEKHELP_NEW_MEMBER)
			{
				$member = $this->members->get_newUserInfo_by_id($ret['member_id']);				
				$ret['member_name'] = IS_HIDE_MOBILE?hg_hide_mobile($member[$ret['member_id']]['member_name']):$member[$ret['member_id']]['member_name'];
			}
			else
			{
				$member = $this->get_userinfo_by_id($ret['member_id']);
				$ret['member_name'] = IS_HIDE_MOBILE?hg_hide_mobile($member[$ret['member_id']]['nick_name']):$member[$ret['member_id']]['nick_name'];
			}
		}
		$ret['seekhelp_title'] = seekhelp_clean_value($ret['seekhelp_title']);
		$ret['content'] = seekhelp_clean_value(stripcslashes(urldecode($ret['content'])));
		if (!$ret)
		{
			return false;
		}
		return $ret;
	}
	
	/**
	 * comment详情
	 */
	public function comment_detail($id)
	{
		$info = array();
		$member_ids = array();
		$sql = 'SELECT * FROM '.DB_PREFIX.'comment WHERE 1 AND id IN('.$id.')';
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$member_ids[] = $row['member_id'];
			$row['content'] = seekhelp_clean_value(stripcslashes(urldecode($row['content'])));
			$info[] = $row;
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
				//新旧会员处理
				if (defined('SEEKHELP_NEW_MEMBER') && SEEKHELP_NEW_MEMBER)
				{
					if ($userInfor[$val['member_id']]['member_name'])
					{
						$info[$key]['member_name'] = IS_HIDE_MOBILE?hg_hide_mobile($userInfor[$val['member_id']]['nick_name']):$userInfor[$val['member_id']]['nick_name'];
					}
					if ($userInfor[$val['member_id']]['avatar']['host'])
					{
						$info[$key]['member_avatar'] = array(
								'host'		=> $userInfor[$val['member_id']]['avatar']['host'],
								'dir'		=> $userInfor[$val['member_id']]['avatar']['dir'],
								'filepath'	=> $userInfor[$val['member_id']]['avatar']['filepath'],
								'filename'	=> $userInfor[$val['member_id']]['avatar']['filename'],
						);
						$info[$key]['member_level'] = intval($userInfor[$val['member_id']]['digital']);
					}
				}
				else
				{
					if ($userInfor[$val['member_id']]['nick_name'])
					{
						$info[$key]['member_name'] = IS_HIDE_MOBILE?hg_hide_mobile($userInfor[$val['member_id']]['nick_name']):$userInfor[$val['member_id']]['nick_name'];
					}
					if ($userInfor[$val['member_id']]['host'])
					{
						$info[$key]['member_avatar'] = array(
								'host'		=> $userInfor[$val['member_id']]['host'],
								'dir'		=> $userInfor[$val['member_id']]['dir'],
								'filepath'	=> $userInfor[$val['member_id']]['filepath'],
								'filename'	=> $userInfor[$val['member_id']]['filename'],
						);
					}
				}
			}
		}
		return $info;
	}
	
	public function update($id, $data)
	{
		if (!$id || empty($data) || !is_array($data))
		{
			return false;
		}
		$sql = 'UPDATE '.DB_PREFIX.'comment SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.addslashes($val).'",';
		}
		$sql = rtrim($sql,',');
		$sql .= ' WHERE id = '.$id;
		$this->db->query($sql);
		$data['id'] = $id;
		return $data;
	}
}
?>