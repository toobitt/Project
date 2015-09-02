<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: update_password.php 45997 2015-06-02 08:30:56Z develop_tong $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class updatePasswordApi extends appCommonFrm
{
	function __construct()
	{
		parent::__construct();
		require_once(ROOT_DIR.'lib/user/user.class.php');
		$this->user = new user();
	}

	function __destruct()
	{
		parent::__destruct();
	}	
	/**
	* 更新用户密码
	* @return  
	*/
	
	public function updatePassword()
	{
		$userinfo = $this->user->verify_credentials(); 
		$password =array(
			'id' => $userinfo['id'],
			'pwd' =>$this->input['password']
		);		
		if(!$password['id'])
		{
			$this -> errorOutput(OBJECT_NULL);//返回0x0000代码
		}
		else 
		{
			$salt = hg_generate_salt();
			$pass = md5(md5($password['pwd']).$salt);
			$this->setXmlNode('userinfo','ID');
			$sql = "UPDATE ".DB_PREFIX."member SET 
			password = '".$pass."',salt='".$salt."' 
			WHERE id = ".$password['id'];		
			$this->db->query($sql);
			$this->addItem($password['id']);
			return $this->output();
		}	
	}
	
	public function verifyPassword()
	{
		$userinfo = $this->user->verify_credentials(); 
		$uid = $userinfo['id'];
		$pwd = $this->input['password']?$this->input['password']:"";
		$encrypt_num  = intval($this->input['encrypt_num']);
		$sql = "SELECT salt,password FROM ".DB_PREFIX."member WHERE id = ".$uid;
		$first = $this->db->query_first($sql);
		
		if ($encrypt_num == 1)
		{
			$pwd = md5($pwd . $first['salt']);
		}
		else
		{
			$pwd = md5(md5($pwd) . $first['salt']);
		}
		if($first['password'] == $pwd)
		{
			$salt = $first['salt'];
		}
		else 
		{
			$salt = '';
		}
		$this->setXmlNode('userinfo','ID');
		$this->addItem($salt);
		$this->output();
	}


}
$out = new updatePasswordApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'updatePassword';
}
$out->$action();

?>