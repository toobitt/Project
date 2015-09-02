<?php
/***************************************************************************
* $Id: interactive_member_update.php 15427 2012-12-13 01:22:49Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','interactive_member');
require('global.php');
class interactiveMemberUpdateApi extends BaseFrm
{
	private $mInteractiveMember;
	private $mShare;
	public function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/interactive_member.class.php';
		$this->mInteractiveMember = new interactiveMember();
		
		require_once ROOT_PATH . 'lib/class/share.class.php';
		$this->mShare = new share();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function create()
	{
		$channel_id 	= $this->input['channel_id'];
		$member_id		= urldecode($this->input['member_id']);
		$member_name	= trim(urldecode($this->input['member_name']));
		$nick_name 		= trim(urldecode($this->input['nick_name']));
		$avatar 		= trim(urldecode($this->input['avatar']));
		$plat_id 		= intval($this->input['plat_id']);
		$plat_name	 	= trim(urldecode($this->input['plat_name']));
		$plat_type 		= intval($this->input['plat_type']);
		$plat_token 	= trim(urldecode($this->input['plat_token']));
		$week_day		= $this->input['week_day'];
		$dates 			= $week_day ? date('Y-m-d') : trim(urldecode($this->input['dates']));
		$start_time 	= strtotime($dates . ' ' . trim(urldecode($this->input['start_time'])));
		$end_time	 	= strtotime($dates . ' ' . trim(urldecode($this->input['end_time'])));
		$toff 			= $end_time - $start_time;
		
		$plat_can_access	= intval($this->input['plat_can_access']);
		$plat_expired_time  = intval($this->input['plat_expired_time']);
		
		if (!$channel_id)
		{
			$this->errorOutput('未传入频道id');
		}
		
		if (!$member_id)
		{
			$this->errorOutput('未传入会员id');
		}
		
		if (!$member_name)
		{
			$this->errorOutput('未传入会员名');
		}
		
		if (!$plat_id)
		{
			$this->errorOutput('未传入站外平台id');
		}
		
		if (!$plat_name)
		{
			$this->errorOutput('未传入站外平台名');
		}
		
		if (!$plat_type)
		{
			$this->errorOutput('未传入站外平台类型');
		}
	
		if ($toff <= 0)
		{
			$this->errorOutput('结束时间不能小于开始时间');
		}
		
		$add_info = array(
			'channel_id'	=> serialize($channel_id),
			'member_id' 	=> $member_id,
			'member_name' 	=> $member_name,
			'nick_name' 	=> $nick_name,
			'avatar' 		=> $avatar,
			'plat_id' 		=> $plat_id,
			'plat_name' 	=> $plat_name,
			'plat_type' 	=> $plat_type,
			'plat_token' 	=> $plat_token,
			'start_time' 	=> $start_time,
			'toff'		 	=> $toff,
			'week_day'	 	=> $week_day ? serialize($week_day) : '',
			'plat_can_access' 	=> $plat_can_access,
			'plat_expired_time' => $plat_expired_time,
			'appid' 	 	=> $this->user['appid'],
			'appname' 	 	=> $this->user['display_name'],
			'user_id' 	 	=> $this->user['user_id'],
			'user_name'  	=> $this->user['user_name'],
		);
		
		$ret = $this->mInteractiveMember->create($add_info);
		
		if (!$ret)
		{
			$this->errorOutput('添加站外用户失败');
		}
		
		$relation_info = array(
			'm_id'			=> $ret['id'],
		//	'channel_id' 	=> $channel_id,
			'member_id' 	=> $member_id,
			'start_time'	=> $start_time,
			'end_time'		=> $end_time,
		);
		
		$this->mInteractiveMember->interactive_member_relation_edit($relation_info, $week_day);
		
		$this->addItem($ret);
		$this->output();
	}
	
	public function update()
	{
		$id				= intval($this->input['id']);
		$channel_id 	= $this->input['channel_id'];
		$member_id		= intval($this->input['member_id']);
		$member_name	= trim(urldecode($this->input['member_name']));
		$nick_name 		= trim(urldecode($this->input['nick_name']));
		$avatar 		= trim(urldecode($this->input['avatar']));
		$plat_id 		= intval($this->input['plat_id']);
		$plat_name	 	= trim(urldecode($this->input['plat_name']));
		$plat_type 		= intval($this->input['plat_type']);
		$plat_token 	= trim(urldecode($this->input['plat_token']));
		$week_day		= $this->input['week_day'];
		$dates 			= $week_day ? date('Y-m-d') : trim(urldecode($this->input['dates']));
		$start_time 	= strtotime($dates . ' ' . trim(urldecode($this->input['start_time'])));
		$end_time	 	= strtotime($dates . ' ' . trim(urldecode($this->input['end_time'])));
		$toff 			= $end_time - $start_time;
		
		$plat_can_access	= intval($this->input['plat_can_access']);
		$plat_expired_time  = intval($this->input['plat_expired_time']);
		
		if (!$id)
		{
			$this->errorOutput('未传入id');
		}
		
		if (!$channel_id)
		{
			$this->errorOutput('未传入频道id');
		}
		
		if (!$member_id)
		{
			$this->errorOutput('未传入会员id');
		}
		
		if (!$member_name)
		{
			$this->errorOutput('未传入会员名');
		}
		
		if (!$plat_id)
		{
			$this->errorOutput('未传入站外平台id');
		}
		
		if (!$plat_name)
		{
			$this->errorOutput('未传入站外平台名');
		}
		
		if (!$plat_type)
		{
			$this->errorOutput('未传入站外平台类型');
		}
		
		if ($toff <= 0)
		{
			$this->errorOutput('结束时间不能小于开始时间');
		}
		
		$add_info = array(
			'channel_id'	=> $channel_id ? serialize($channel_id) : '',
			'member_id' 	=> $member_id,
			'member_name' 	=> $member_name,
			'nick_name' 	=> $nick_name,
			'avatar' 		=> $avatar,
			'plat_id' 		=> $plat_id,
			'plat_name' 	=> $plat_name,
			'plat_type' 	=> $plat_type,
			'plat_token' 	=> $plat_token,
			'start_time' 	=> $start_time,
			'toff'		 	=> $toff,
			'week_day'	 	=> $week_day ? serialize($week_day) : '',
			'plat_can_access' 	=> $plat_can_access,
			'plat_expired_time' => $plat_expired_time,
		);
		
		$ret = $this->mInteractiveMember->update($add_info, $id);
		
		if (!$ret)
		{
			$this->errorOutput('更新站外用户失败');
		}
		
		$relation_info = array(
			'm_id'			=> $id,
		//	'channel_id' 	=> $channel_id,
			'member_id' 	=> $member_id,
			'start_time'	=> $start_time,
			'end_time'		=> $end_time,
		);
		
		$this->mInteractiveMember->interactive_member_relation_edit($relation_info, $week_day);
		
		$this->addItem($ret);
		$this->output();
	}
	
	public function delete()
	{
		$id = trim(urldecode($this->input['id']));
		if (!$id)
		{
			$this->errorOutput('未传入id');
		}
		$info = $this->mInteractiveMember->delete($id);
		if (!$info)
		{
			$this->errorOutput('删除失败');
		}
		$this->addItem($info);
		$this->output();
	}

	public function audit()
	{
		$id    = $this->input['id'];
		$audit = $this->input['audit'];

		if (!$id)
		{
			$this->errorOutput('未传入id');
		}
	
		$audit_array = array(1,2);
		if (!in_array($audit, $audit_array))
		{
			$this->errorOutput('不合法类型');
		}
		
		$ret = $this->mInteractiveMember->audit($id, $audit);
		
		if (!$ret)
		{
			$this->errorOutput('操作失败');
		}

		$return = array(
			'audit' => $audit,
			'id' 	=> $id,
		);
		$this->addItem($return);
		$this->output();
	}
	
	public function reset_oauth()
	{
		$id 				= intval($this->input['id']);
		$plat_id 			= intval($this->input['plat_id']);
		$access_plat_token	= $this->input['access_plat_token'];
		$appid				= intval($this->user['appid']);
		$mid		 		= intval($this->input['_mid']);

		if (!$id)
		{
			$this->errorOutput('未传入账号id');
		}
		if (!$plat_id)
		{
			$this->errorOutput('未传入站外平台id');
		}
		
		if (!$access_plat_token)
		{
			$this->errorOutput('站外平台token不能为空');
		}
		
		//获取用户信息
		$plat_user = $this->mShare->get_user('','',$access_plat_token);

		$plat_user = $plat_user[0];
		
		if (empty($plat_user))
		{
			$this->errorOutput('站外用户登陆失败');
		}
		
		if ($plat_user['error'])
		{
			$this->errorOutput($plat_user['error']);
		}
		/*
		$check_member = $this->mInteractiveMember->check_member_exists_by_id($plat_user['uid'], $plat_id);
		
		if ($check_member)
		{
			$ret_member = $this->mInteractiveMember->detail($check_member['id']);
			$is_exists = 1;
		}
		*/
		$ret_get_plat = $this->mShare->get_plat($access_plat_token, $appid);

		if (empty($ret_get_plat))
		{
			$this->errorOutput('站外平台信息不存在或已被删除');
		}
		
		$plat_info = array();
		foreach ($ret_get_plat AS $v)
		{
			if ($v['id'] == $plat_id)
			{
				$plat_info = $v;
			}
		}

		if (empty($plat_info))
		{
			$this->errorOutput('获取站外平台信息失败');
		}
		
		$referto = 'run.php?mid='.$mid.'&infrm=1';
		
		$add_info = array(
			'plat_can_access' 	=> $plat_info['can_access'],
			'plat_expired_time' => $plat_info['expired_time'],//1354312491
			'plat_token'		=> $access_plat_token,
		);
		
		$ret = $this->mInteractiveMember->member_edit($add_info, $id);
		
		if (!$ret)
		{
			$this->errorOutput('重新授权失败');
		}
		
		$this->addItem($ret);
		$this->output();
	}
	
	public function unknow()
	{
		$this->errorOutput('未实现的空方法');
	}
}

$out = new interactiveMemberUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>