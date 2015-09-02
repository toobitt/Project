<?php
/***************************************************************************
* $Id: uc_login.php 19006 2013-03-21 09:07:30Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','member');//模块标识
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
define('UC_CLIENT_PATH', './');
require(ROOT_PATH."global.php");
require(CUR_CONF_PATH."lib/functions.php");
class ucLoginApi extends appCommonFrm
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
	 * 用户登陆
	 * $member_name 可以是用户名
	 * $password 密码
	 * $appid
	 * $appkey
	 * Enter description here ...
	 */
	public function uc_login()
	{
		$member_name = trim($this->input['member_name']);

		if (!$member_name)
		{
			$this->errorOutput('请输入用户名');
		}
	
		$password = trim($this->input['password']);

		if (!$password)
		{
			$this->errorOutput('请输入密码');
		}
		
		$uc_member_info = $this->mMember->ucenter_login($member_name, $password);
		
		$uc_id = $uc_member_info[0];
		
		$email = $uc_member_info[3];
	
		switch ($uc_id)
		{
			case -1 :
				$this->errorOutput('用户不存在，或者被删除');
				break;
			case -2 :
				$this->errorOutput('密码错');
				break;
			case -3 :
				$this->errorOutput('安全提问错');
				break;
			default:
				break;
		}
		
		$appid = intval($this->input['appid']);
		$appkey = trim($this->input['appkey']);
		
		if (!$appid || !$appkey)
		{
			$this->errorOutput('数据来源不合法');
		}
	
		if ($uc_id > 0 && !$this->mMember->check_member_name_exists($member_name, $uc_id) && $email)
		{
			$input_info = array(
				'uc_id' 		=> $uc_id,
				'member_name' 	=> $member_name,
				'password' 		=> $password,
				'email' 		=> $email,
				'platform'		=> $this->settings['platform']['uc'],
			);
			
			$ret_register_info = $this->mMember->create($input_info, $uc_id,'',$this->user['appid'],$this->user['display_name']);
			
			if (!$ret_register_info)
			{
				$this->errorOutput('注册本地失败');
			}
		}

		$info = $this->mMember->login($member_name, $password, $appid, $appkey);
		
		switch ($info)
		{
			case -1 :
				$this->errorOutput('用户名不存在');
				break;
			case -2 :
				$this->errorOutput('密码不正确');
				break;
			default :
				$this->addItem($info);
				break;
		}
		$this->output();
	}
	function uc_syn_login()
	{
		$ret = array();
		$data = array(
			'member_name'=>urldecode($this->input['username']),
			'ucid'=>intval($this->input['uid']),
			);
		if($data['member_name'] && $data['ucid'])
		{
			$sql = 'SELECT id FROM '.DB_PREFIX.'member WHERE uc_id = ' . $data['ucid'] . ' AND member_name="'.$data['member_name'].'" limit 1';
			$memberinfo = $this->db->query_first($sql);
			//echo $sql;exit;
			//print_r($memberinfo);exit;
			if($memberinfo['id'])
			{
				$accesstoken = $this->mMember->get_token($memberinfo['id'], $data['member_name'],$this->input['appid'], $this->input['appkey']);
				if($accesstoken['token'])
				{
					$ret = array(
						'member_id'=>$memberinfo['id'],
						'nick_name'=>$data['member_name'],
						'token'=>$accesstoken['token'],	
						);
				}
			}
		}
		$this->addItem($ret);
		$this->output();
	}
}

$out = new ucLoginApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'uc_login';
}
$out->$action();
?>