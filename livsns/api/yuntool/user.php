<?php
require_once './global.php';
include_once ROOT_PATH . 'lib/class/auth.class.php';
include_once ROOT_PATH . 'lib/class/curl.class.php';
define('MOD_UNIQUEID', 'register');  //模块标识
class user extends appCommonFrm
{
	private $auth;
	private $upyun;

	public function __construct()
	{
		parent::__construct();
		$this->auth = new Auth();
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->auth);
	}

	/**
	 * 注册接口
	 */
	public function register()
	{
		$data = $this->filter_data();
		if (empty($data['password'])) $this->errorOutput(PARAM_WRONG);
		//默认角色
		$data['admin_role_id'] = $this->input['role_id'] ? $this->input['role_id'] : implode(',', $this->settings['default_role']);
		//创建组织机构
		if (defined('DEFAULT_ORG') && DEFAULT_ORG)
		{
			$data['father_org_id'] = $this->input['org_id'] ? $this->input['org_id'] : DEFAULT_ORG;
		}
		else
		{
			$orgData = array(
				'name' => $data['user_name']
			);
			$org_info = $this->auth->create_org($orgData);//创建组织
			if (!$org_info) $this->errorOutput('组织注册失败');
			$data['father_org_id'] = $org_info['id'];
		}
		//创建用户
		$user_info = $this->auth->create_user($data);
		log2file(array(), 'debug', '注册接口输入输出', $data, $user_info);
		if (!$user_info) $this->errorOutput('注册失败');
		$userlogin = array(
			'username'=>$user_info['user_name'],
			'password'=>$data['password'],
			'isextend'=>1,
		);
		$reUserLogin = $this->login($userlogin,true);		
		$this->addItem($reUserLogin);
		$this->output();
	}
	/**
	 * 
	 * 登陆接口 ...
	 * @param unknown_type $userlogin
	 * @param unknown_type $isRe
	 */
	public function login($userlogin = array(),$isRe = false)
	{
		$userlogin = $userlogin?$userlogin:array(
		'username'=>$this->input['username'],
		'password'=>$this->input['password'],
		'isextend'=>1
		);
		$UserLogin = $this->auth->login($userlogin);
		unset($userlogin['password']);
		log2file(array(), 'debug', '用户登陆', $userlogin, $UserLogin);
		
	 	unset($UserLogin['password']);
	 	if($isRe)return $UserLogin;
	 	$this->addItem($UserLogin);
	 	$this->output();
	}
	
	public function logout()
	{
		$user = array('access_token'=>$this->user['token']);
		$responce = $this->auth->logout($user);
		$this->addItem($responce);
		$this->output();
	}
/**
 * 
 * 用户名检测 ...
 * @param unknown_type $username
 * @param unknown_type $userid
 */
	public function checkUserName($username = '',$userid = 0,$isRe = 0)
	{
		$params = array(
		'user_name' => $username?$username:trim($this->input['username']),
		'id' => $userid?intval($userid):intval($this->input['userid']),
		);
		$ret = $this->auth->CheckUserName($params);
		if($isRe)
		{
			return $ret['status']>0?true:false;
		}
		$this->addItem($ret);
		$this->output();
	}

	/**
	 * 处理提交的数据
	 */
	private function filter_data()
	{
		$username = trim(urldecode($this->input['username']));
		$password = trim(urldecode($this->input['password']));
		$passwordRepeat = trim(urldecode($this->input['passwordRepeat']));
		if (empty($username)) $this->errorOutput(NOUSERNAME);
		if($this->checkUserName($username,0,1))$this->errorOutput(USERNAMEEXISTS);
		if (empty($password)) $this->errorOutput(NOPASSWORD);
		if($password!=$passwordRepeat)
		{
			$this->errorOutput('密码和确认密码不相同');
		}
		return array(
			'user_name' => $username,
			'password' => $password
		);
	}

	function update_userinfo()
	{
		$data = array(
			'id'=>intval($this->user['user_id']),
			'password'=>trim($this->input['password']),
			'password_again'=>trim($this->input['password_again']),
			'old_password'=>trim($this->input['old_password']),
		);
		if(!$data['old_password'])
		{
			$this->errorOutput("请输入原始密码");
		}
		if(!$data['password'])
		{
			$this->errorOutput("新密码不可以为空");
		}
		if ($data['password'] && ($data['password'] != $data['password_again']))
		{
			$this->errorOutput('两次输入的密码不一样');
		}
		$curl = new curl($this->settings['App_auth']['host'],$this->settings['App_auth']['dir']);
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a','update_password');
		foreach ( $data as $key=>$val)
		{
			$curl->addRequestData($key,$val);
		}
		if ($_FILES['Filedata'])
		{
			$curl->addFile($_FILES);
		}
		$return = $curl->request('member.php');
		
		if($return && $return[0])
		{
			if ($return[0]['error'] == -1)
			{
				$this->errorOutput('原始密码错误');
			}
		}
		$this->addItem($return[0]);
		$this->output();
	}
}

$out = new user();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'register';
}
$out->$action();
?>