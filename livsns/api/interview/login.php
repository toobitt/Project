<?php
define(ROOT_DIR, '../../');
require(ROOT_DIR . 'global.php');
require("./lib/interview.class.php");
require_once(ROOT_PATH.'lib/class/login.class.php');
class loginApi extends BaseFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->interview = new interview();
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
			'member_name'=>trim($this->input['name']),
			'password'=>trim($this->input['password']),
			'interview_id'=>intval($this->input['interview_id']),
			'appid'=>trim($this->input['appid']),
			'appkey'=>trim($this->input['appkey']),
		);
		$data['interview_id'] = $data['interview_id']?$data['interview_id']:0;
		if (!$data['member_name'])
		{
			$this->errorOutput('用户名为空');
		}
		if (!$data['password'])
		{
			$this->errorOutput('密码为空');
		}
		$user_info = $this->login->dologin($data['member_name'], $data['password'],$data['appid'],$data['appkey']);
		if ($user_info)
		{
			$arr = array(
				'user_id'=>$user_info['member_id'],
				'name'=>$user_info['nick_name'],
				'ip'=>$this->user['ip'],
				'login_time'=>TIMENOW,
				'token'=>$user_info['token'],
				'interview_id'=>$data['interview_id']
			);
			$info = $this->interview->dologin($arr);
			
		}
		$this->addItem($info);
		$this->output();
		
	}
	/**
	 * 
	 * 退出
	 * @param string $member_id  	用户ID
	 * @param string $token  	    token
	 * @param int    $interview_id  访谈ID，可选，不传则默认退出所有访谈
	 */
	public function loginout()
	{
		//参数接收
		$data = array(
			'member_id'=>intval(urldecode($this->input['user_id'])),
			'token'=>urldecode($this->input['token']),
			'interview_id'=>intval(urldecode($this->input['interview_id'])),
		);
		if (!$data['member_id'])
		{
			$this->errorOutput('用户ID为空');
		}
		if (!$data['token'])
		{
			$this->errorOutput('token is non-existent');
		}
		$res = $this->login->logout($data['member_id'], $data['token']);
		if ($res) {
			$r =$this->interview->logout($data['member_id'],$data['token'],$data['interview_id']);
			$this->addItem($r);
		}
		$this->output();
	}
	//昵称登录
	public function nlogin()
	{
		$data = array(
			'name'=>urldecode($this->input['nickname']),
			'interview_id'=>intval(urldecode($this->input['interview_id'])),		
		);
		if (!$data['name'])
		{
			$this->errorOutput('请输入昵称');
		}
		$ret = $this->interview->checkname($data);
		if (!$ret)
		{
			$this->errorOutput('昵称已存在');
		}
		$data['user_id'] = 0;
		$data['login_time'] = TIMENOW;
		$data['ip'] = $this->user['ip'];
		$data['token'] = '';
		$info = $this->interview->dologin($data);
		$this->addItem($info);
		$this->output();
		
	}
	//昵称退出
	public function nlogout()
	{
		$data = array(
			'name'=>urldecode($this->input['nickname']),
			'interview_id'=>intval(urldecode($this->input['interview_id'])),
		);
		if (!$data['name'])
		{
			$this->errorOutput('昵称为空');
		}
		$r = $this->interview->nlogout($data);
		$this->addItem($r);
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