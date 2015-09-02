<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: verify.php 17947 2013-02-26 02:57:46Z repheal $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class verifyApi extends appCommonFrm
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
	 * 验证用户名是否存在
	 * @param $username 传入的用户名
	 * @return Boolean 
	 */
	function verifyUsername($username)
	{
		$flag = false;
		$username = strtolower($username);		
		if(!empty($username))
		{
			$sql = "SELECT username FROM ".DB_PREFIX."member WHERE LOWER(username)='".$username."'";
			
			$result = $this->db->query_first($sql);

			if($result)
			{
				$flag = true;
			}
			else
			{
				$flag = false;
			}
		}
		else
		{
			$this->errorOutput(FAILED);
		}		
		$this->addItem($flag);
		return $this->output();
	}
	
	/**
	 * 验证邮箱是否存在
	 * @param $email 传入的邮箱
	 * @return Boolean 
	 */
	function verifyEmail($email)
	{
		$flag = false;
		if(!empty($email))
		{
			$sql = "SELECT email FROM ".DB_PREFIX."member WHERE email='".urldecode($email)."'";
			
			$result = $this->db->query_first($sql);
			
			if($result)
			{
				$flag = true;
			}
			else
			{
				$flag = false;
			}
		}
		else
		{
			$this->errorOutput(FAILED);
		}		
		$this->addItem($flag);
		return $this->output();
	}
	
	/**
	 * 入口
	 */
	public function show()
	{
		if($this->input['username'])
		{
			$this->verifyUsername(urldecode(trim($this->input['username'])));
		}
		if($this->input['email'])
		{
			$this->verifyEmail(urldecode(trim($this->input['email'])));
		}
		
	}
	
	
}
$out = new verifyApi();
$out->show();
?>