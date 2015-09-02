<?php
define(ROOT_DIR, '../../');
require(ROOT_DIR . 'global.php');
require_once(ROOT_PATH.'lib/class/login.class.php');
class loginApi extends appCommonFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->login= new login();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	/**
	 * 登陆
	 * @param string $user_name  	用户名
	 * @param string $password  	密码	 
	 */
	public function login()
	{
		$data=array(
			'user_name'=>trim(urldecode($this->input['user_name'])),
			'password'=>trim(urldecode($this->input['password'])),
		);
		if (!$data['user_name'])
		{
			$this->errorOutput('用户名为空');
		}
		if (!$data['password'])
		{
			$this->errorOutput('密码为空');
		}
		$ret = $this->login->dologin($data['user_name'], $data['password']);
		$this->addItem($ret);
		$this->output();

		
	}
	/**
	 * 
	 * 退出
	 * @param string $user_id  	用户ID
	 * @param string $token  	    token
	 */
	public function loginout()
	{
		//参数接收
		$data = array(
			'user_id'=>intval(urldecode($this->input['user_id'])),
			'token'=>urldecode($this->input['token']),
		);
		if (!$data['user_id'])
		{
			$this->errorOutput('用户ID为空');
		}
		if (!$data['token'])
		{
			$this->errorOutput('token is non-existent');
		}
		$ret = $this->login->logout($data['user_id'], $data['token']);
		$this->addItem($ret);
		$this->output();
	}
	
}
$ouput= new loginApi();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'unknow';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
?>