<?php
/***************************************************************************
* $Id: auto_login.php 33897 2014-01-25 07:00:48Z zhuld $
***************************************************************************/
define('MOD_UNIQUEID','member');//模块标识
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
define('UC_CLIENT_PATH', './');
require(ROOT_PATH."global.php");
require(CUR_CONF_PATH."lib/functions.php");
class autoLoginApi extends appCommonFrm
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
	 * 自动登陆
	 * $access_token
	 * Enter description here ...
	 */
	public function auto_login()
	{
		/*
		$access_token = trim($this->input['access_token']);
		if (!$access_token)
		{
			$this->errorOutput('NO_ACCESS_TOKEN');
		}
		
		$is_expired = $this->access_token_expired($access_token);
		if ($is_expired['result'])
		{
			$this->errorOutput('IS_EXPIRED');
		}
		*/
		$member_id = intval($this->user['user_id']);
		if (!$member_id)
		{
			$this->errorOutput('NO_MEMBER_ID');
		}
		
		$ret = $this->mMember->_get_member_by_id($member_id,'', 'email, password, mobile');
		$ret = $ret[$member_id];
		
		if (empty($ret))
		{
			$this->errorOutput('NO_MEMBER_INFO');
		}
		
		$ret['member_id'] = $ret['id'];
		
		$avatar = array(
			'host'		=> $ret['host'],
			'dir'		=> $ret['dir'],
			'filepath'	=> $ret['filepath'],
			'filename'	=> $ret['filename'],
		);
		
		$ret['is_exist_email'] = $ret['email'] ? 1 : 0;
		$ret['is_exist_password'] = $ret['password'] ? 1 : 0;
			
		unset($ret['id'], $ret['host'], $ret['dir'], $ret['filepath'], $ret['filename'], $ret['password']);
		
		$ret['avatar'] = $avatar;
		
		$ret_bound = $this->mMember->get_member_bound_info($member_id, 'member_name, platform, platform_id, plat_member_name');
		
		$ret['bound'] = $ret_bound;
		
		$this->addItem($ret);
		$this->output();
	}
	
	private function access_token_expired($access_token)
	{
		//获取需要修改的配置
		require_once ROOT_PATH . 'lib/class/curl.class.php';
		$curl = new curl($this->settings['App_auth']['host'],$this->settings['App_auth']['dir']);
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a', 'access_token_expired');
		$curl->addRequestData('access_token', $access_token);
		$ret = $curl->request('get_app_info.php');
		return $ret[0];
	}
	
	public function unknow()
	{
		$this->errorOutput('此方法不存在！');
	}
	

}

$out = new autoLoginApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'auto_login';
}
$out->$action();
?>