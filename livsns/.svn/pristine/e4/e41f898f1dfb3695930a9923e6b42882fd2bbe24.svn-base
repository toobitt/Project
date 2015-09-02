<?php
require_once(ROOT_PATH. 'lib/class/curl.class.php');
class reporterlogin extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function dologin($username,$password)
	{
		
		//验证用户是否存在
		$sql = 'SELECT account_id,name FROM '.DB_PREFIX.'reporter WHERE status = 1 AND account = "'.$username.'"';
		$ret = $this->db->query_first($sql);
		$return = array();
		if (!$ret['account_id'])
		{
			$return = array(
				'ErrorCode'=>1,              //1用户不存在
				'ErrorText'=>'用户名不存在',
			);
			return $return;
		}
		//验证密码
		$userInfor = $this->checklogin($username, $password);
		if (empty($userInfor))
		{
			$return = array(
				'ErrorCode'=>2,              //1用户不存在
				'ErrorText'=>'密码不正确',
			);
			return $return;
		}
		$return = array(
			'member_id'=>$userInfor['id'],
			'nick_name'=>$ret['name'],
			'uc_id'=>0,
			'token'=>$userInfor['token'],
			'avatar'=>$userInfor['avatar'],
		);
		return $return;	
	}
	
	public function logout($user_id,$token)
	{
		$curl = new curl($this->settings['App_auth']['host'],$this->settings['App_auth']['dir']);
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a','logout');
		$curl->addRequestData('user_id',$user_id);	
		$curl->addRequestData('token',$token);
		$ret = $curl->request('get_access_token.php');
		return $ret[0];
	}
	
	private  function  checklogin($username,$password)
	{
		$curl = new curl($this->settings['App_auth']['host'],$this->settings['App_auth']['dir']);
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a','show');
		$curl->addRequestData('username',$username);	
		$curl->addRequestData('password',$password);
		$ret = $curl->request('get_access_token.php');
		return $ret[0];
	}
}