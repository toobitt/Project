<?php
define('MOD_UNIQUEID','user');//模块标识
define('SCRIPT_NAME', 'user');
require('./global.php');
include_once ROOT_PATH . 'lib/class/auth.class.php';
class user extends adminBase
{
	private $auth = null;
	function __construct()
	{
		parent::__construct();
		$this->auth = new auth();
	}
	function __destruct()
	{
		parent::__destruct();
		$this->auth = null;
	}
	//获取用户信息
	function show()
	{
		
	}
	//
	function login($userlogin =  array())
	{
		$userlogin = $userlogin?$userlogin:array(
		'username'=>$this->input['username'],
		'password'=>$this->input['password'],
		);
		$userinfo = $this->auth->login($userlogin);
		$this->addItem($userinfo);
	 	$this->output();
	}
	//
	function logout()
	{
		$user = array('access_token'=>$this->user['token']);
		$responce = $this->auth->logout($user);
		$this->addItem($responce);
		$this->output();
	}
}
include ROOT_PATH . 'excute.php';
?>