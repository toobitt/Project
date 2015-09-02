<?php
/***************************************************************************
* $Id: register.php 30547 2013-10-17 06:49:20Z zhuld $
***************************************************************************/
define('MOD_UNIQUEID','member');//模块标识
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
define('UC_CLIENT_PATH', './');
require(ROOT_PATH."global.php");
require(CUR_CONF_PATH."lib/functions.php");
class memberRegisterApi extends appCommonFrm
{
	private $mMember;
	public function __construct()
	{
		parent::__construct();
		include_once CUR_CONF_PATH . 'lib/member.class.php';
		$this->mMember = new member();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 注册
	 * $member_name
	 * $email
	 * $password
	 * $repassword
	 * Enter description here ...
	 */
	public function register()
	{
		$member_name = trim($this->input['member_name']);
		$password 	 = trim($this->input['password']);
		$platform 	 = trim($this->input['platform']);
		$openid		 = $this->input['openid'];
		
		if (!$this->input['member_name'])
		{
			$this->errorOutput('用户名不能为空');
		}
		
		if (strpos($member_name, '@'))
		{
			$this->errorOutput('用户名不能含有@符号');
		}
		
		$input_data = array(
			'member_name' => $member_name,
			'openid'	=> $openid,
		);

		//站外注册
		if (isset($this->input['platform']) && $platform)
		{
			$platform_id 		= trim($this->input['platform_id']);
			$plat_member_name 	= trim($this->input['plat_member_name']);
			if ($platform_id < 0)
			{
				$this->errorOutput('站外用户id不能为空');
			} 
			
			$input_data['platform'] 			= $platform;
			$input_data['plat_member_name'] 	= $plat_member_name;
			$input_data['platform_id'] 			= $platform_id;
		}
		else 
		{
			if (!$password)
			{
				$this->errorOutput('密码不能为空');
			}
		}
		
		$email = trim($this->input['email']);
		if (!$email && $this->settings['is_email_checked'])
		{
			$this->errorOutput('邮箱不能为空');
		}
	/*
		if ($password != trim($this->input['repassword']))
		{
			$this->errorOutput('两次密码不一致');
		}
	*/	
		if ($this->mMember->_check_member_name($member_name) == -1)
		{
			$this->errorOutput('用户名不合法');
		}
		else if($this->mMember->_check_member_name($member_name) == -2)
		{
			$this->errorOutput('用户名已被注册');
		}
		
		if ($email)
		{
			if ($this->mMember->_check_email($email) == -3)
			{
				$this->errorOutput('邮箱不合法');
			}
			else if($this->mMember->_check_email($email) == -4)
			{
				$this->errorOutput('邮箱已被注册');
			}
		}
		
		if($this->input['avatar_url'])
		{
			$input_data['avatar_url'] = trim($this->input['avatar_url']);
		}
		
		if($this->input['access_plat_token'])
		{
			$input_data['access_plat_token'] = $this->input['access_plat_token'];
		}
		
		$input_data['password'] = $password;
		$input_data['email'] 	= $email;
	
		
		//性别
		if (isset($this->input['sex']))
		{
			$input_data['sex'] = intval($this->input['sex']);
		}
		
		//头像
		$files = '';
		if (isset($this->input['avatar']) && $_FILES['avatar']['tmp_name'])
		{
			$files = $_FILES['avatar'];
		}
		
		//分组
		if (isset($this->input['group_id']) && intval($this->input['group_id']))
		{
			$input_data['group_id'] = intval($this->input['group_id']);
		}
		
		//昵称
		if (isset($this->input['nick_name']))
		{
			$nick_name = trim($this->input['nick_name']);
			
			if (strpos($nick_name, '@'))
			{
				$this->errorOutput('昵称不能含有@符号');
			}
			
			if ($this->settings['nick_name_unique']['open'])
			{
				$ret_nick_name = $this->mMember->check_nick_name_exists($nick_name);
				if ($ret_nick_name)
				{
					$this->errorOutput('该昵称已被使用');
				}
			}
		
			$input_data['nick_name'] = $nick_name;
		}

		//开启ucenter
		if ($this->settings['ucenter']['open'])
		{
			$ret_uc_user_register = $this->mMember->uc_user_register($member_name, $password, $email);

			switch ($ret_uc_user_register)
			{
				case -1 :
					$this->errorOutput('用户名不合法');
					break;
				case -2 :
					$this->errorOutput('包含不允许注册的词语');
					break;
				case -3 :
					$this->errorOutput('用户名已经存在');
					break;
				case -4 :
					$this->errorOutput('Email 格式有误');
					break;
				case -5 :
					$this->errorOutput('Email 不允许注册');
					break;
				case -6 :
					$this->errorOutput('该 Email 已经被注册');
					break;
				default:
					break;
			}
			
		//	$input_data['platform'] = $this->settings['platform']['uc'];
			
			$uc_id = $ret_uc_user_register;
		}
	
		$appid   = intval($this->input['appid']);
		$appkey  = trim($this->input['appkey']);
		$appname = $this->user['display_name'];
		
		if (!$appid || !$appkey)
		{
			$this->errorOutput('数据来源不合法');
		}
		
		//注册
		$data = $this->mMember->create($input_data, $uc_id, $files, $appid, $appname);

		if (!$data)
		{
			$this->errorOutput('注册失败');
		}
		
		if($this->settings['is_open_xs'])
		{
			require_once ROOT_PATH . 'lib/class/team.class.php';
			$obj_team = new team();
			$obj_team->add_search($data['id'],'user');
		}
		
		//登陆
		$ret_login = $this->mMember->login($member_name, $password, $appid, $appkey, $platform, $platform_id, $input_data['access_plat_token'], $input_data['avatar_url']);
		
		switch ($ret_login)
		{
			case -1 :
				$this->errorOutput('用户名不存在');
				break;
			case -2 :
				$this->errorOutput('密码不正确');
				break;
			case -3 :
				$this->errorOutput('站外标识不正确');
				break;
			case -4 :
				$this->errorOutput('该用户未绑定');
				break;
			default :
				break;
		}

		if ($this->settings['App_email']['open'] && !$input_data['platform'])
		{
			$ret_activate_key = $this->mMember->activate_key_add($data['id']);
			
			$ret_login['activate_key'] = $ret_activate_key['activate_key'];
		}
		$ret_login['member_name'] = $ret_login['nick_name'];
		$ret_login['access_token'] = $ret_login['token'];
		$this->addItem($ret_login);
		$this->output();
	}
	
	public function activate_key_add()
	{
		if(!$this->user['user_id'])
		{
			$this->errorOutput('请登陆');
		}
		$ret = $this->mMember->activate_key_add($this->user['user_id']);
		$this->addItem($ret);
		$this->output();
	}

	public function unknow()
	{
		$this->errorOutput('此方法不存在！');
	}
}

$out = new memberRegisterApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'register';
}
$out->$action();
?>