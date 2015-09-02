<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: update_profile.php 6098 2012-03-16 01:27:22Z repheal $
***************************************************************************/
require('global.php');
class updateProfileApi extends BaseFrm
{
	function __construct()
	{
		parent::__construct();
		include_once(ROOT_DIR . 'lib/class/member.class.php');
		$this->member = new member();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	* 更新用户资料
	* @return 
	*/
	
	public function updateProfile()
	{
		$userinfo = array(
			'id' => $this->input['id'],
			'truename' => urldecode($this->input['truename']),
			'sex' => intval($this->input['sex']),
			'email' => urldecode($this->input['email']),
			'cur_email' => urldecode($this->input['cur_email']),
			'username' => urldecode($this->input['username']),
			'cur_username' => urldecode($this->input['cur_username']),
			'location' => urldecode($this->input['location']),
			'location_code' => urldecode($this->input['location_code']),
			'birthday' => $this->input['birthday'],
			'qq' => $this->input['qq'],
			'mobile' => $this->input['mobile'],
			'msn' => urldecode($this->input['msn']),
			'tv' => urldecode($this->input['tv']),
			'privacy' =>$this->input['privacy']
		);		
		if(!$userinfo['id'])
		{
			$this -> errorOutput(OBJECT_NULL);//返回0x0000代码
		}
		else 
		{
			if(strcmp($userinfo['username'],$userinfo['cur_username'])!=0)
			{
			//	$result = $this->member->checkUsername($userinfo['username']);
				if($result)
				{
					$this->setXmlNode('userinfo','repeat_user');
					$rt['user_exist'] =1;
					$this->addItem($rt);
					$this->output();
					exit;
				}
			}
			if(strcmp($userinfo['email'],$userinfo['cur_email'])!=0)
			{
			//	$result = $this->member->checkEmail($userinfo['email']);
				if($result)
				{
					$this->setXmlNode('email','repeat_email');
					$rt['email_exist'] =1;
					$this->addItem($rt);
					$this->output();
					exit;
				}
			}
			$sql = "UPDATE ".DB_PREFIX."member SET 
			email = '".$userinfo['email']."',
			truename = '".$userinfo['truename']."',
			sex = '".$userinfo['sex']."',
			username = '".$userinfo['username']."',
			location = '".$userinfo['location']."',
			location_code = '".$userinfo['location_code']."',
			birthday = '".$userinfo['birthday']."',
			qq = '".$userinfo['qq']."',
			mobile = '".$userinfo['mobile']."',
			digital_tv = '".$userinfo['tv']."',
			privacy =replace(privacy,privacy,concat('".$userinfo['privacy']."',substring(privacy,7))),
			msn = '".$userinfo['msn']."' WHERE id = ".$userinfo['id'];
			$this->setXmlNode('userinfo','user');
			try {
				$query = $this->db->query($sql);
				$this->addItem(array('done'=>'1'));
			}catch(Exception $e)
			{
				$this->addItem(array('done'=>'0'));
			}		
			return $this->output();
		}
		
	}
	
	
	public function updateType()
	{
		$user_id = $this->input['id'];
		if(!$user_id)
		{
			$this -> errorOutput(OBJECT_NULL);//返回0x0000代码
		}
		else
		{
			$sql = "UPDATE ".DB_PREFIX."member SET type = 1 WHERE id = ".$user_id;
			$this->db->query($sql);
			$this->setXmlNode('userinfo','user');
			$this->addItem($user_id);
			$this->output();
		}
	}

	public function update_last_status()
	{
		$user_id = $this->input['user_id'] ? $this->input['user_id'] : 0;
		$last_status_id = $this->input['last_status_id'] ? $this->input['last_status_id'] : 0;
		if(!$user_id || !$last_status_id)
		{
			$this -> errorOutput(OBJECT_NULL);//返回0x0000代码
		}
		else
		{
			$sql = "UPDATE " . DB_PREFIX . "member_extra SET last_status_id=" . $last_status_id . " WHERE member_id=" . $user_id;
			$this->db->query($sql);
			$this->setXmlNode('userinfo','user');
			$this->addItem($user_id);
			$this->output();
		}
	}
}

$out = new updateProfileApi();
$action = $_REQUEST['a'];
if (!method_exists($out,$action))
{
	$action = 'updateProfile';
}
$out->$action();
?>