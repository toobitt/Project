<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: verify_user_exist.php 17947 2013-02-26 02:57:46Z repheal $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class verifyUserExistApi extends appCommonFrm
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	* 验证是否存在
	* @return array 返回用户名和密码 
	*/
	public function verifyUserExist()
	{
		$flag = false;
		if($this->input['user'] && $this->input['pass'])
		{
			$user = urldecode(trim($this->input['user']));
			$pass = urldecode(trim($this->input['pass']));
			if (strpos($user, '@'))
			{
				$cond = "email = '{$user}'";
			}
			else
			{
			}

				$cond = "LOWER(username) = '" . strtolower($user) . "'";
			$sql = "SELECT 
				id,
				username,
				password,
				salt,
				email,
				avatar,
				join_time,
				last_login,
				group_id,
				privacy,
				type
			FROM " . DB_PREFIX . "member 
			WHERE {$cond}";
			$info = $this->db->query_first($sql);
	
			$userinfo = array(
				'id' => $info['id'],
				'username' => $user,
				'password' => $password
			);
			$this->setXmlNode('userinfo','user');
			$this->addItem($info);
			$this->output();
		}
		else
		{			
			$this->addItem($flag);
			$this->output();
		}
		
	}
}
$out = new verifyUserExistApi();
$out->verifyUserExist();
?>