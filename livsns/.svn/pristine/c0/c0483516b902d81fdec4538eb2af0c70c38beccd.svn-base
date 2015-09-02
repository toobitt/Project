<?php
class member
{
	function __construct()
	{
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		if($gGlobalConfig['App_member'])
		{
			$this->curl = new curl($gGlobalConfig['App_member']['host'], $gGlobalConfig['App_member']['dir']);
		}
	}

	function __destruct()
	{
	}
	
	public function show($count=-1,$offset=0)
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','show');
		$this->curl->addRequestData('count',$count);
		$this->curl->addRequestData('offset',$offset);
		return $this->curl->request('member.php');
	}

	public function getMemberById($member_id)
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_member_by_id');
		$this->curl->addRequestData('member_id', $member_id);
		return $this->curl->request('member.php');
	}
	public function getMemberByIds($member_id)
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','_get_member_by_id');
		$this->curl->addRequestData('member_id', $member_id);
		return $this->curl->request('member.php');
	}
	
	public function getMemberByName($username)
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_member_by_nick_name');
		$this->curl->addRequestData('nick_name', $username);
		return $this->curl->request('member.php');
	}
	
	public function getMemberInfoById($uid)
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','getMemberInfoById');
		$this->curl->addRequestData('member_id', $uid);
		$result = $this->curl->request('member.php');
		return $result[0];
	}
	
	public function get_authority($id)
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('id', $id);
		$tmp = $this->curl->request('weibo/get_authority.php');				
		return $tmp[0];
	}
	
	public function show_relation($s_uid,$t_uid)
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('source_id', $s_uid);
		$this->curl->addRequestData('target_id', $t_uid);
		return $this->curl->request('weibo/show.php');
	}
	
	public function get_location()
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'getLocation'); 
		$ret = $this->curl->request('weibo/location.php');
		return $ret[0];
	}
	
	/**
	 * 
	 * 添加积分日志
	 * @param int $rule_id 积分类型
	 * @param int $oid 被回复或被评论的ID 注册和登录为0
	 */
	public function add_credit_log($rule_id , $oid = 0)
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('rule_type', $rule_id);
		$this->curl->addRequestData('oid', $oid);
		$this->curl->request('weibo/credit_log.php');	
	}

	public function add_activity_account()
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','add_activity_account');
		return $this->curl->request('member_edit.php');
	}	
	
	public function del_activity_account()
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','del_activity_account');
		return $this->curl->request('member_edit.php');
	}
	
	public function add_visit($member_id,$scan_num=1)
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','add_visit');
		$this->curl->addRequestData('member_id',$member_id);
		$this->curl->addRequestData('scan_num',$scan_num);
		return $this->curl->request('member_edit.php');
	}
	
	public function update_last_status($user_id,$last_status_id)
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'update_last_status');
		$this->curl->addRequestData('user_id',$user_id);
		$this->curl->addRequestData('last_status_id',$last_status_id);
		return $this->curl->request('weibo/update_profile.php');
	}
	
	//根据用户id获取用户详细信息
	public function getUserinfoById($user_id)
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'getMemberInfoById');
		$this->curl->addRequestData('id',$user_id);
		return $this->curl->request('admin/member_update.php');
	}
	//获取所有用户标签
	public function get_all_mark($member_id, $offset = 0, $count = -1)
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_all_mark');
		$this->curl->addRequestData('member_id', $member_id);
		$this->curl->addRequestData('offset', $offset);
		$this->curl->addRequestData('count', $count);
		return $this->curl->request('member.php');
	}
}
?>
