<?php
include_once (ROOT_PATH.'lib/class/curl.class.php');
class gatherMenu extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function adminShow($offset, $count)
	{
		$limit = " limit {$offset}, {$count}";
		$orderby = 'ORDER BY order_id DESC';
		$sql = 'SELECT user_id, FROM_UNIXTIME(MAX(create_time), "%Y-%m-%d") AS format_date, count(*) as total 
				FROM (SELECT * FROM '.DB_PREFIX.'gather ORDER BY create_time DESC)gather 
				WHERE user_id != 0 GROUP BY user_id '.$orderby.$limit;
		//echo $sql;exit();
		$query = $this->db->query($sql);
		$arr = array();
		$userIds = array();
		while ($row = $this->db->fetch_array($query))
		{
			$userIds[] = $row['user_id']; 
			$arr[] = $row;
		}
		if (!empty($userIds))
		{
			$uIds = implode(',', $userIds);
			$userInfo = $this->getSystemUserInfo($uIds);
			if ($userInfo && is_array($userInfo))
			{
				foreach ($arr as $key=>$val)
				{
					foreach ($userInfo as $info)
					{
						if ($val['user_id'] == $info['id'])
						{
							$arr[$key]['user_name'] = $info['user_name'];
							$arr[$key]['avatar'] = $info['avatar'];
						}
					}
				}
			}
		}
		return $arr;
	}
	
	public function guestShow($offset, $count, $user_id, $sortIds)
	{
		$limit = " limit {$offset}, {$count}";
		$orderby = 'ORDER BY create_time DESC';
		$sql = 'SELECT COUNT(*) AS total, FROM_UNIXTIME( create_time, "%Y-%m-%d") AS format_date 
				FROM (SELECT * FROM '.DB_PREFIX.'gather ORDER BY create_time DESC )gather 
				WHERE user_id = '.$user_id.' AND sort_id IN ('.$sortIds.') GROUP BY format_date '.$orderby.$limit;
		//echo $sql;exit();
		$query = $this->db->query($sql);
		$arr = array();
		$userIds = array();
		while ($row = $this->db->fetch_array($query))
		{
			$arr[] = $row;
		}
		return $arr;
	}
	
	public function getSystemUserInfo($uid)
	{
		if (!$uid || !$this->settings['App_auth'])
		{
			return false;
		}
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
		return $ret;
	}
}
?>
