<?php 
require_once(ROOT_PATH . 'lib/class/material.class.php');
require_once(ROOT_PATH. 'lib/class/curl.class.php');
require_once(ROOT_PATH . 'lib/class/member.class.php');
require_once(ROOT_PATH . 'lib/class/members.class.php');
class ClassSeekhelpJoint extends InitFrm
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
		$sql = 'INSERT INTO '.DB_PREFIX.'joint SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.addslashes($val).'",';	
		}
		$sql = rtrim($sql, ',');
		$this->db->query($sql);
		$id = $this->db->insert_id();
		$data['id'] = $id;
		return $data;
		
	}
	
	public function show($condition, $orderby, $offset, $count)
	{
		if(!$offset && !$count)
		{
			$limit = '';
		}
		else
		{
			$limit = " limit {$offset}, {$count}";
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'joint  WHERE 1 '. $condition.$orderby.$limit;
		$query = $this->db->query($sql);
		$res = array();
		$member_ids = array();
		while ($row = $this->db->fetch_array($query))
		{
			$member_ids[] = $row['member_id'];
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
						$res[$key]['member_name'] = IS_HIDE_MOBILE?hg_hide_mobile($userInfor[$val['member_id']]['member_name']):$userInfor[$val['member_id']]['member_name'];
					}
					if ($userInfor[$val['member_id']]['avatar']['host'])
					{
						$res[$key]['member_avatar'] = array(
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
	
	public function detail($id)
	{
		$sql = 'SELECT * FROM '.DB_PREFIX.'joint WHERE id='.$id;
		$ret = $this->db->query_first($sql);
		return $ret;
	}
	
	public function count($condition)
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'joint WHERE 1'.$condition;
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
	
	/**
	 * 
	 * @Description 取消联名
	 * @author Kin
	 * @date 2013-6-15 上午09:13:30
	 */
	public function delete($cid, $user_id, $joint_type)
	{
		$sql = 'DELETE FROM '.DB_PREFIX.'joint 
				WHERE cid ='.$cid.' AND member_id = '.$user_id.' AND joint_type="'.$joint_type.'"';
		$this->db->query($sql);
		$arr = array(
			'cid'=>$cid,
			'member_id'=>$user_id,
			'joint_type' => $joint_type,	
		);
		return $arr;
	}
	
	/**
	 * 取消点赞
	 * @param unknown $id
	 * @return multitype:unknown
	 */
	public function delete_joint($id)
	{
		$sql = 'DELETE FROM '.DB_PREFIX.'joint WHERE id ='.$id.'';
		$this->db->query($sql);
		$arr = array(
				'id'=>$id,
		);
		return $arr;
	}
	
}
?>