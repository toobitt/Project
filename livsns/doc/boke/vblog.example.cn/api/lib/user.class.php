<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: user.class.php 2483 2011-03-03 04:24:30Z develop_tong $
***************************************************************************/
class user extends BaseFrm
{
	var $mUserName;
	var $mPassword;
	function __construct()
	{
		parent:: __construct();
		$user = $this->input['user'] ? $this->input['user'] : hg_get_cookie('user');
		$pass = $this->input['pass'] ? $this->input['pass'] : hg_get_cookie('pass');
		$this->setUser($user, $pass);
	}
	
	function __destruct()
	{
	}

	public function setUser($user, $pass)
	{
		$this->mUserName = $user;
		$this->mPassword = $pass;
	}

/**
 * 验证用户名是否存在
 * @param $username 传入的用户名
 * @return Boolean 
 */
	function checkUsername($username)
	{
		
		if(!empty($username))
		{
			$sql = "SELECT id,username FROM ".DB_PREFIX."member WHERE username='".$username."'";
			
			$result = $this->db->query_first($sql);

			if($result)
			{
				return $result;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
/**
 * 验证邮箱是否存在
 * @param $email 传入的邮箱
 * @return Boolean 
 */
	function checkEmail($email)
	{
		if(!empty($email))
		{
			$sql = "SELECT email FROM ".DB_PREFIX."member WHERE email='".$email."'";
			
			$result = $this->db->query_first($sql);
			
			if($result)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}

    /**
     *
     * 验证用户是否登录
     */
	public function verify_user()
	{		
		if (!$this->mUserName || !$this->mPassword )
		{
			return false;
		}

		$sql = "SELECT id, username,password,salt FROM " . DB_PREFIX . "member WHERE username = '{$this->mUserName}'";

		$r = $this->db->query_first($sql);
				
		if (!$r)
		{
			/**
			 * 用户不存在
			 */
			return false;
		}
		else
		{
			if ($this->mPassword == $r['password'])
			{
				return $r;
			}
			$pass = md5(md5($this->mPassword) . $r['salt']);
			if ($pass == $r['password'])
			{
				return $r;
			}
			return false;
		}
	}
	
	/**
	 * 获取网台的用户信息
	 */
	public function getWebStationUserInfo()
	{
		print_r();
	}
			
}