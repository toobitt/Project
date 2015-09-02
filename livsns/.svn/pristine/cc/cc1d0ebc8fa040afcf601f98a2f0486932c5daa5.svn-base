<?php
class login extends BaseFrm
{
	function __construct()
	{
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gGlobalConfig['App_login']['host'], $gGlobalConfig['App_login']['dir']);
	}

	function __destruct()
	{
		
	}
	function dologin($user_name,$password,$appid,$appkey)
	{
		
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('member_name', $user_name);
		$this->curl->addRequestData('password', $password);
		$this->curl->addRequestData('appid', $appid);
		$this->curl->addRequestData('appkey', $appkey);
		$this->curl->addRequestData('a','login');
		$ret = $this->curl->request('login.php');
		return $ret[0];
	}
	function logout($user_id,$token)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');		
		$this->curl->initPostData();
		$this->curl->addRequestData('member_id', $user_id);
		$this->curl->addRequestData('token', $token);
		$this->curl->addRequestData('a','logout');
		$ret = $this->curl->request('logout.php');
		return $ret;
	}
	
}

?>