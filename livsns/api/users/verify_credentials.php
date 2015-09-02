<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: verify_credentials.php 17947 2013-02-26 02:57:46Z repheal $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class verifyCredentialsApi extends appCommonFrm
{
	var $mUserName;
	var $mPassword;
	function __construct()
	{
				
		parent::__construct();
		$user = urldecode($_SERVER['PHP_AUTH_USER'] ? $_SERVER['PHP_AUTH_USER'] : hg_get_cookie('user'));
		
		if(!$user)
		{
			$user = $this->input['user'];	
		}
		
		$pass = urldecode($_SERVER['PHP_AUTH_PW'] ? $_SERVER['PHP_AUTH_PW'] : hg_get_cookie('pass'));
		
		if(!$pass)
		{
			$pass = $this->input['pass'];
		}
		
		$this->setUser($user, $pass);
	}

	function __destruct()
	{
		parent::__destruct();
	}
	

	public function setUser($user, $pass)
	{
		$this->mUserName = $user;
		$this->mPassword = $pass;
	}
	
	/**
	* 验证是否登录
	* @return array 返回用户名和密码 和权限
	*/
	public function verifyCredentials()
	{
		if ($this->input['innerTransKey'] == INNERTRANSKEY)
		{
			$this->setXmlNode('userinfo','user');
			$this->addItem(array('login' => true));
			$this->output();
		}
		if (!$this->mUserName || !$this->mPassword )
		{
			$this->errorOutput(FAILED);
		}

		if (strpos($this->mUserName, '@'))
		{
			$cond = "m.email = '{$this->mUserName}'";
		}
		else
		{
			$cond = "LOWER(m.username) = '" . strtolower($this->mUserName) . "'";
		}

		$sql = "SELECT 
				m.id,
				username,
				truename,
				email,
				password,
				salt,
				avatar,
				qq_login,
				qq,
				mobile,
				msn,
				sex,
				birthday,
				join_time,
				last_login,
				user_group_id,
				privacy,
				type,
				last_activity,
				followers_count,
				attention_count,
				status_count,
				video_count,
				thread_count,
				post_count,
				salt,
				password,
				m.group_id,
				group_name,
				cellphone
				FROM " . DB_PREFIX . "member m 
				LEFT JOIN ".DB_PREFIX."member_extra e 
				ON m.id = e.member_id 
				left join " . DB_PREFIX . "member_location l 
				on m.id = l.member_id
				WHERE $cond";

		$r = $this->db->query_first($sql);
		if (!$r)
		{
			$this -> errorOutput(LOGIN_FAILED);//返回0x4000代码
		}
		else
		{
			if ($this->mPassword != $r['password'] && md5(md5($this->mPassword) . $r['salt']) != $r['password'])
			{
				$this -> errorOutput(LOGIN_FAILED);//返回0x4000代码
			}
			unset($r['salt']);
			if(strlen($r['avatar']) > 32)//qq同步的用户头像
			{
				$r['large_avatar']= hg_avatar($r['id'],"100",$r['avatar'],0);
				$r['middle_avatar']= hg_avatar($r['id'],"50",$r['avatar'],0);
				$r['small_avatar'] = hg_avatar($r['id'],"10",$r['avatar'],0);
			}
			else 
			{
				$r['large_avatar']= hg_avatar($r['id'],"larger",$r['avatar']);
				$r['middle_avatar']= hg_avatar($r['id'],"middle",$r['avatar']);
				$r['small_avatar'] = hg_avatar($r['id'],"small",$r['avatar']);
			}
			
			if($r['user_group_id'] == 1)
			{
				$r['is_admin']=1;
			}
			else
			{
				$r['is_admin']=0;
			}
			$r['home'] = SNS_UCENTER . 'user-' . $r['id'] . '.html';
			if (!$r['group_name'])
			{
				$r['group_name'] = '暂未设定';
			}
			$this->setXmlNode('userinfo','user');
			$this->addItem($r);
			$this->output();
		}
	}
}
$out = new verifyCredentialsApi();
$out->verifyCredentials();
?>