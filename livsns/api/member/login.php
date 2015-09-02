<?php
/***************************************************************************
* $Id: login.php 31802 2013-11-22 02:42:32Z zhuld $
***************************************************************************/
define('MOD_UNIQUEID','member');//模块标识
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
define('UC_CLIENT_PATH', './');
require(ROOT_PATH."global.php");
require(CUR_CONF_PATH."lib/functions.php");
class memberLoginApi extends appCommonFrm
{
	private $mMember;
	private $curlAuth;
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
	 * 用户登陆
	 * $member_name 可以是用户名或者邮箱
	 * $password 密码
	 * $appid
	 * $appkey
	 * $is_more 获取更多信息 (性别、邮箱、手机)
	 * Enter description here ...
	 */
	public function login()
	{
		$member_name = trim($this->input['member_name']);

		if (!$member_name)
		{
			$this->errorOutput('请输入用户名或者邮箱');
		}
		
		$password = trim(urldecode($this->input['password']));
		
		$platform = trim($this->input['platform']);
		if ($platform)	//站外登陆
		{
			$platform_id = intval($this->input['platform_id']);
			if ($platform_id < 0)
			{
				$this->errorOutput('站外登陆失败');
			}
		}
		else	//本地登陆
		{
			if (!$password)
			{
				$this->errorOutput('请输入密码');
			}
		}
		
		$appid  = intval($this->input['appid']);
		$appkey = trim($this->input['appkey']);
		
		if (!$appid || !$appkey)
		{
			$this->errorOutput('数据来源不合法');
		}
		
		$access_plat_token 	= $this->input['access_plat_token'] ? $this->input['access_plat_token']: '';
		$avatar_url 		= $this->input['avatar_url'] ? $this->input['avatar_url']: '';
		$is_more 			= $this->input['is_more'] ? intval($this->input['is_more']) : 0;
		$plat_member_name	= trim($this->input['plat_member_name']);
		
		$info = $this->mMember->login($member_name, $password, $appid, $appkey, $platform, $platform_id, $access_plat_token, $avatar_url, $is_more, $plat_member_name);

		switch ($info)
		{
			case -1 :
				$this->errorOutput('用户不存在');
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
		$this->addItem($info);
		$this->output();
	}
	public function mobile_login()
	{
		$data = array(
		'member_name'=>$this->input['member_name'],
		'password'=>$this->input['password'],
		'type'=>$this->input['type'],
		'nick_name'=>$this->input['nick_name'] ? $this->input['nick_name'] : $this->input['member_name'],
		'openid'=>$this->input['platform_id'],
		'avatar_url'=>$this->input['avatar_url'],
		'sex'=>$this->input['sex'],
		);
		$appid  = intval($this->input['appid']);
		$appkey = trim($this->input['appkey']);
		$appname = $this->user['display_name'];
		if($data['openid'])
		{
			$sql = 'SELECT id as member_id,uc_id,nick_name,member_name,openid,host,dir,filepath,filename FROM '.DB_PREFIX.'member WHERE openid = "'.$data['openid'].'"';
			$member = $this->db->query_first($sql);
			$_avatar = array();
			if(!$member)
			{
				//$this->errorOutput("未知的绑定用户");
				//入库
				if(!$data['member_name'])
				{
					$this->errorOutput("请填写用户名");
				}
				//$data['nick_name'] = $data['member_name'] .= TIMENOW;
				$uc_id = 0;
				/*
				if ($this->settings['ucenter']['open'])
				{
					$data['member_name'] = str_replace('@', '', $data['member_name']);
					$data['email'] = TIMENOW . '@anonymous.com';
					$ret_uc_user_register = $this->mMember->uc_user_register($data['member_name'], $data['password'], $data['email']);
					
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
					$uc_id = $ret_uc_user_register;
				}
				*/
				$member = $this->mMember->create($data, $uc_id, "", $appid, $appname);
				$member['member_id'] = $member['id'];
				$member['openid'] = $data['openid'];
				$_avatar = $member['avatar'];
			}
			$member['avatar'] = $_avatar ? $_avatar : array(
				'host'		=> $member['host'] ? $member['host'] : "",
				'dir'		=> $member['dir'] ? $member['dir'] : "",
				'filepath'	=> $member['filepath'] ? $member['filepath'] : "",
				'filename'	=> $member['filename'] ? $member['filename'] : "",
			);
			$this->curlAuth = new curl($this->settings['App_auth']['host'], $this->settings['App_auth']['dir']);
			$appid = $this->input['appid'];
			$appkey = $this->input['appkey'];
			$this->curlAuth->setSubmitType('post');
			$this->curlAuth->setReturnFormat('json');
			$this->curlAuth->addRequestData('a', 'show');
			$this->curlAuth->addRequestData('appid', $appid);
			$this->curlAuth->addRequestData('appkey', $appkey);
			$this->curlAuth->addRequestData('ip', hg_getip());
			$this->curlAuth->addRequestData('user_name', $member['nick_name']);
			$this->curlAuth->addRequestData('id', $member['member_id']);
			$this->curlAuth->addRequestData('admin_group_id', 0);
			$this->curlAuth->addRequestData('group_type', 0);
			$ret = $this->curlAuth->request('get_access_token.php');
			$token = $ret[0]['token'];
		}
		else
		{
			if (!$data['password'])
			{
				$this->errorOutput('请输入密码');
			}
			$info = $this->mMember->login($data['member_name'], $data['password'], $appid, $appkey);
			switch ($info)
			{
				case -1 :
					$this->errorOutput('用户不存在');
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
			$member = array(
			'member_id'=>$info['member_id'],
			'member_name'=>$data['member_name'],
			'avatar'=>$info['avatar'],
			'openid'=>$info['openid'],
			);
			$token = $info['token'];
		}
		$return = array(
			'member_id' 	=> $member['member_id'],
			'member_name' 	=> $data['member_name'],
			'type' 			=> $data['type'],
			'avatar' 		=> $member['avatar'],
			'access_token' 	=> $token,
			'isbindqq'		=> $member['openid'] ? 1 : 0,
		);
		$this->addItem($return);
		$this->output();
	}
	public function bindqq()
	{
		$member_id = $this->user['user_id'];
		if(!$member_id)
		{
			$this->errorOutput("用户未登陆");
		}
		$openid = $this->input['platform_id'];
		$sql = 'UPDATE ' . DB_PREFIX . 'member SET openid = "'.$openid.'" WHERE id = '.$member_id;
		$this->db->query($sql);
		$this->addItem(array('openid'=>$openid, 'member_id'=>$member_id));
		$this->output();
	}
	
	public function mobile_auto_login()
	{
		$auto_login = array("success"=>1);
		$this->addItem($auto_login);
		$this->output();
	}
	public function unknow()
	{
		$this->errorOutput('此方法不存在！');
	}
	

}

$out = new memberLoginApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'login';
}
$out->$action();
?>