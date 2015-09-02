<?php
/***************************************************************************
* $Id: member_checked.php 19006 2013-03-21 09:07:30Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','member');//模块标识
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
define('UC_CLIENT_PATH', './');
require(ROOT_PATH."global.php");
require(CUR_CONF_PATH."lib/functions.php");
class memberCheckedApi extends appCommonFrm
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
	 * 检测用户名存在
	 * Enter description here ...
	 */
	public function check_member_name_exists()
	{
		$member_name = trim(urldecode($this->input['member_name']));
		if (!$member_name)
		{
			$this->errorOutput('用户名不能为空');
		}
		
		$info = $this->mMember->check_member_name_exists($member_name);
		$this->addItem($info);
		$this->output();
	}
	
	/**
	 * 检测邮箱存在
	 * Enter description here ...
	 */
	public function check_email_exists()
	{
		$email = trim(urldecode($this->input['email']));
		if (!$email)
		{
			$this->errorOutput('邮箱不能为空');
		}
		
		$info = $this->mMember->check_email_exists($email);
		$this->addItem($info);
		$this->output();
	}
	
	/**
	 * 检测昵称存在
	 * Enter description here ...
	 */
	public function check_nick_name_exists()
	{
		$nick_name = trim(urldecode($this->input['nick_name']));
		if (!$nick_name)
		{
			$this->errorOutput('昵称不能为空');
		}
		
		$info = $this->mMember->check_nick_name_exists($nick_name,$this->user['user_id']);
		$this->addItem($info);
		$this->output();
	}
	
	/**
	 * 检测是否激活
	 * Enter description here ...
	 */
	public function check_email_activate()
	{
		$member_id = intval($this->input['member_id']);
		if (!$member_id)
		{
			$this->errorOutput('未传入会员id');
		}
		
		$info = $this->mMember->check_email_activate($member_id);
		$this->addItem($info);
		$this->output();
	}
	
	/**
	 * 检测会员是否绑定
	 * Enter description here ...
	 */
	public function check_member_bound_exists()
	{
	//	$member_name = trim(urldecode($this->input['member_name']));		
		$platform 	 = intval($this->input['platform']);
		$platform_id = intval($this->input['platform_id']);
		
		$appid		 = intval($this->input['appid']);
		$appkey		 = trim($this->input['appkey']);
		
		if (!$platform || !$platform_id)	//!$member_name || 
		{
			$this->errorOutput('未传入平台信息');
		}
		
		$info = $this->mMember->check_member_bound_exists('', $platform, $platform_id);

		$ret = array();
		if (!empty($info))
		{
			$ret_auth = $this->mMember->get_token($info['member_id'], $info['member_name'], $appid, $appkey);
			
			$ret_member = $this->mMember->_get_member_by_id($info['member_id'], '', 'email, mobile');
			$ret_member = $ret_member[$info['member_id']];
			if (empty($ret_member))
			{
				$this->errorOutput('该会员不存在或已被删除');
			}
			
			$ret = array(
				'member_id' => $info['member_id'],
				'nick_name' => $ret_member['nick_name'],
				'token'		=> $ret_auth['token'],
				'sex'		=> $ret_member['sex'],
				'email'		=> $ret_member['email'],
				'mobile'	=> $ret_member['mobile'],
				'avatar'	=> array(
					'host'		=> $ret_member['host'],
					'dir'		=> $ret_member['dir'],
					'filepath'	=> $ret_member['filepath'],
					'filename'	=> $ret_member['filename'],
				),
			);
		}

		$this->addItem($ret);
		$this->output();
	}
	
	/**
	 * 绑定
	 * Enter description here ...
	 */
	function member_bind_by_token()
	{
		$access_token = $this->input['access_token'];
		if (!$access_token)
		{
			$this->errorOutput('NO_ACCESS_TOKEN');
		}
		
		$member_id = intval($this->user['user_id']);
		if (!$member_id)
		{
			$this->errorOutput('NO_MEMBER_ID');
		}
		
		$ret_member = $this->mMember->_get_member_by_id($member_id,'', 'member_name, email, mobile');
		$ret_member = $ret_member[$member_id];
		
		if (empty($ret_member))
		{
			$this->errorOutput('NO_MEMBER_INFO');
		}
		
		$member_name 		= $ret_member['member_name'];		
		$platform 	 		= intval($this->input['platform']);
		$platform_id 		= intval($this->input['platform_id']);
		$access_plat_token 	= $this->input['access_plat_token'] ? $this->input['access_plat_token']: '';
		$avatar_url 		= $this->input['avatar_url'] ? $this->input['avatar_url']: '';
		$plat_member_name	= trim($this->input['plat_member_name']);
		
		if (!$platform || !$platform_id)	//!$member_name || 
		{
			$this->errorOutput('NO_PLAT_INFO');
		}
		
		$ret_bound = $this->mMember->check_member_bound_exists('', $platform, $platform_id);
		
		if (!empty($ret_bound))
		{
			$this->errorOutput('ALREADY_BIND');
		}
		
		$add_input = array(
			'member_id'			=> $member_id,
			'member_name'		=> $member_name,
			'platform'			=> $platform,
			'platform_id'		=> $platform_id,
			'avatar_url'		=> $avatar_url,
			'access_plat_token' => $access_plat_token,
			'plat_member_name'	=> $plat_member_name,
		);
		
		$ret_bind = $this->mMember->add_bound($add_input);
		
		if (empty($ret_bind))
		{
			$this->errorOutput('BIND_FAILURE');
		}
		
		$member_info = array(
			'is_bound'	=> 1,
		);
		
		$ret_member_edit = $this->mMember->member_info_edit($member_id, $member_info);
		
		$ret = array();
		if (!empty($ret_member_edit))
		{
			$ret = array(
				'member_id' => $member_id,
				'nick_name' => $ret_member['nick_name'],
				'token'		=> $access_token,
				'sex'		=> $ret_member['sex'],
				'email'		=> $ret_member['email'],
				'mobile'	=> $ret_member['mobile'],
				'avatar'	=> array(
					'host'		=> $ret_member['host'],
					'dir'		=> $ret_member['dir'],
					'filepath'	=> $ret_member['filepath'],
					'filename'	=> $ret_member['filename'],
				),
			);
			
		}
		
		$this->addItem($ret);
		$this->output();
	}
	
	/**
	 * 解除绑定
	 * Enter description here ...
	 */
	function member_unbind_by_token()
	{
		$access_token = $this->input['access_token'];
		if (!$access_token)
		{
			$this->errorOutput('NO_ACCESS_TOKEN');
		}
		
		$member_id = intval($this->user['user_id']);
		if (!$member_id)
		{
			$this->errorOutput('NO_MEMBER_ID');
		}
		
		$ret_member = $this->mMember->_get_member_by_id($member_id,'', 'member_name');
		$ret_member = $ret_member[$member_id];
		
		if (empty($ret_member))
		{
			$this->errorOutput('NO_MEMBER_INFO');
		}
		
		$member_name = $ret_member['member_name'];		
		$platform 	 = intval($this->input['platform']);
		$platform_id = intval($this->input['platform_id']);
		
		if (!$platform || !$platform_id)	//!$member_name || 
		{
			$this->errorOutput('NO_PLAT_INFO');
		}
		
		$ret_bound = $this->mMember->check_member_bound_exists('', $platform, $platform_id);
		
		if (empty($ret_bound))
		{
			$this->errorOutput('NO_BIND');
		}
		
		$plat_info = array(
			'member_id'		=> $member_id,
			'platform'		=> $platform,
			'platform_id'	=> $platform_id,
		);
		
		$ret_unbind = $this->mMember->delete_bound_by_plat_info($plat_info);
		
		if (empty($ret_unbind))
		{
			$this->errorOutput('UNBIND_FAILURE');
		}
		
		$ret_bind = $this->mMember->get_member_bound_info($member_id, 'id');
		
		if (count($ret_bind) == 0)
		{
			$member_info = array(
				'is_bound'	=> '0',
			);
			$ret_member_edit = $this->mMember->member_info_edit($member_id, $member_info);
		}
		
		$ret = array(
			'member_id' => $member_id,
			'nick_name' => $ret_member['nick_name'],
			'token'		=> $access_token,
		);
	
		$this->addItem($ret);
		$this->output();
	}
	
	public function unknow()
	{
		$this->errorOutput('此方法不存在！');
	}
}

$out = new memberCheckedApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>