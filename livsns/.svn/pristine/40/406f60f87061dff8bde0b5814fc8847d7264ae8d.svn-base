<?php
/***************************************************************************
 * $Id: logout.php 40953 2014-10-23 08:51:43Z youzhenghuan $
 ***************************************************************************/
define('MOD_UNIQUEID','logout');//模块标识
require('./global.php');
class logoutApi extends appCommonFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 */
	public function logout()
	{
		if($access_token = $this->input['access_token'])
		{
			$data['access_token'] = $access_token;
		}
		else {			
			 $this->errorOutput(NO_ACCESS_TOKEN);
		}
		$Members = new members();
		$device_token = $Members->check_device_token(trim($this->input['device_token']));
		$udid = $Members->check_udid(trim($this->input['uuid']));
		if($device_token===0)
		{
			$this->errorOutput(ERROR_DEVICE_TOKEN);
		}
		if($udid===0)
		{
			$this->errorOutput(ERROR_UDID);
		}
		$auth = new auth();
		$logoutInfo = $auth->logout($data);
		//会员痕迹
		$member_trace_data = array(
			'member_id'		=> $logoutInfo['user_id'],
			'member_name'	=> $logoutInfo['user_name'],
			'content_id'	=> $logoutInfo['user_id'],
			'title'			=> $logoutInfo['user_name'],
			'type'		=> 'logout',
			'op_type'		=> '退出',
			'appid'			=> $logoutInfo['appid'],
			'appname'		=> $logoutInfo['display_name'],
			'create_time'	=> TIMENOW,
			'ip'			=> hg_getip(),
			'device_token'	=> $device_token,
			'udid'          => $udid,	
		);
		$mMember = new member();
		$mMember->member_trace_create($member_trace_data);
		$_logoutInfo = array(
		'member_id' => $logoutInfo['user_id'],
		'member_name'=> $logoutInfo['user_name'],
		'is_member'  => $logoutInfo['is_member'],
		'logout'    => $logoutInfo['logout'],
		);
		$this->addItem($_logoutInfo);
		$this->output();
	}
}

$out = new logoutApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'logout';
}
$out->$action();
?>