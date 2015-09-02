<?php
/***************************************************************************
* $Id: interactive_member.php 16034 2012-12-25 06:39:40Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','interactive_member');
require('global.php');
class interactiveMemberApi extends BaseFrm
{
	private $mInteractiveMember;
	private $mInteractive;
	private $mShare;
	public function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/interactive_member.class.php';
		$this->mInteractiveMember = new interactiveMember();
		
		require_once CUR_CONF_PATH . 'lib/interactive.class.php';
		$this->mInteractive = new interactive();
		
		require_once ROOT_PATH . 'lib/class/share.class.php';
		$this->mShare = new share();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		$channel_info = $this->mInteractive->get_channel_by_id();
		
		$condition	= $this->get_condition();
		$offset 	= $this->input['offset'] ? $this->input['offset'] : 0;			
		$count 		= $this->input['count'] ? intval($this->input['count']) : 20;
		
		$week_day	= array('1' => '一', '2' => '二', '3' => '三', '4' => '四', '5' => '五', '6' => '六', '7' => '日');
		
		$member_info = $this->mInteractiveMember->show($condition, $offset, $count);

		if (!empty($member_info))
		{
			$_member = array();
			foreach ($member_info AS $member)
			{
				$member['dates']		= date('Y-m-d', $member['start_time']);
				$member['end_time']  	= date('H:i:s', ($member['start_time'] + $member['toff']));
				$member['start_time']	= date('H:i:s', $member['start_time']);
				
				$member['is_expired'] = ($member['plat_expired_time'] > TIMENOW) ? 0 : 1;
				
				$member['plat_expired_time'] = date('Y-m-d H:i:s', $member['plat_expired_time']);
				/*
				if ($channel_info && $member['channel_id'])
				{
					$channel_name = array();
					foreach ($channel_info AS $channel)
					{
						foreach ($member['channel_id'] AS $k=>$channel_id)
						{
							if ($channel_id == $channel['id'])
							{
								$channel_name[$k] = $channel['name'];
							}
						}
					}
				}
				
				$member['channel_name'] = implode(',', $channel_name);
				
				if(!empty($member['week_day']))
				{
					if(count($member['week_day']) == 7)
					{
						$member['cycle'] = '每天';
					}
					else
					{
						$spac = '';
						foreach($member['week_day'] AS $k => $v)
						{
							$member['cycle'] .= $spac.$week_day[$v];
							$spac = '&nbsp;|&nbsp;';
						}		
					}
				}
				else
				{
					$member['cycle'] = $member['dates'];
				}
				*/
				if ($this->input['reset_oauth'])
				{
					$_member[] = $member;
				}
				else 
				{
					$this->addItem($member);
				}
			}
		}
		if ($this->input['reset_oauth'])
		{
			$this->addItem($_member);
		}
		$this->output();
	}
	
	function detail()
	{
		$id = urldecode($this->input['id']);
		$info = $this->mInteractiveMember->detail($id);
		$channel_info = $this->mInteractive->get_channel_by_id();
		$info['channel_info'] = $channel_info;
		$this->addItem($info);
		$this->output();
		
	}
	
	function get_plat_member()
	{
		$plat_id 			= intval($this->input['plat_id']);
		$access_plat_token	= $this->input['access_plat_token'];
		$appid				= intval($this->user['appid']);
		$mid		 		= intval($this->input['_mid']);

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
		
		$check_member = $this->mInteractiveMember->check_member_exists_by_id($plat_user['uid'], $plat_id);
		
		/*
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
			'id'				=> $ret_member['id'],
	//		'channel_id'		=> $ret_member['channel_id'],
	//		'week_day'			=> $ret_member['week_day'],
	//		'start_time'		=> $ret_member['start_time'],
	//		'end_time'			=> $ret_member['end_time'],
			'member_id' 		=> $plat_user['uid'],
			'member_name' 		=> $plat_user['name'],
			'nick_name' 		=> $plat_user['screen_name'],
			'avatar' 			=> $plat_user['avatar'],
			'plat_id' 			=> $plat_info['id'],
			'plat_name' 		=> $plat_info['name'],
			'plat_type' 		=> $plat_info['type'],
			'plat_can_access' 	=> $plat_info['can_access'],
			'plat_expired_time' => $plat_info['expired_time'],
			'plat_token' 		=> $access_plat_token,
			'referto'			=> $referto,
	//		'is_exists'			=> $is_exists ? $is_exists : 0,
		);
		
//		$channel_info = $this->mInteractive->get_channel_by_id();
//		$add_info['channel_info'] = $channel_info;
		
		if ($check_member)
		{
			$add_info = array(
				'plat_token' 		=> $access_plat_token,
				'plat_can_access' 	=> $plat_info['can_access'],
				'plat_expired_time' => $plat_info['expired_time'],
			);
		
			$this->mInteractiveMember->member_edit($add_info, $check_member['id']);
		}
		else
		{
			$this->mInteractiveMember->create($add_info);
		}
		
		$ret_member = $this->mInteractive->get_member_by_id($plat_user['uid'], $plat_id);
			
		if (empty($ret_member))
		{
			$member_data = array(
				'member_id'		=> $plat_user['uid'],
				'plat_id'		=> $plat_id,
				'avatar_url'	=> $plat_user['avatar'],
			);
			$this->mInteractive->member_add($member_data);
		}
		
		$this->input['a'] = 'show';
		$this->input['referto'] = $referto;
		$this->input['reset_oauth'] = 1;
		$this->show();
	}
	
	/**
	 * 获取平台信息
	 * Enter description here ...
	 */
	public function get_plat()
	{
		$ret_get_plat = $this->mShare->get_plat();
	//	$ret_channel  = $this->mInteractive->get_channel_by_id();
		
		$ret = array(
			'plat'	  	   => $ret_get_plat,
	//		'channel_info' => $ret_channel,
		);

		$this->addItem($ret);
		$this->output();
	}

	/**
	 * 获取站外登陆地址
	 * Enter description here ...
	 */
	public function oauthlogin()
	{
		$type  		= intval($this->input['type']);
		$plat_id 	= intval($this->input['plat_id']);
		$mid 		= intval($this->input['_mid']);
		if (!$type)
		{
			$this->errorOutput('平台类型不能为空');
		}
		
		if (!$plat_id)
		{
			$this->errorOutput('平台id不能为空');
		}
		
		$ret_oauthlogin = $this->mShare->oauthlogin($plat_id);
		$ret_oauthlogin = $ret_oauthlogin[0];
		
		if (empty($ret_oauthlogin))
		{
			$this->errorOutput('站外平台信息不存在或已被删除');
		}
		
		$ret = array();
		
		$ret_oauthlogin['url'] = $ret_oauthlogin['sync_third_auth'] . '?oauth_url=' . $ret_oauthlogin['oauth_url'] . '&access_plat_token=' .$ret_oauthlogin['access_plat_token']; 
		$callback 			   = $this->settings['oauthlogin']['protocol'] . $this->settings['oauthlogin']['host'] .'/'. $this->settings['oauthlogin']['dir'] . 'run.php?mid='.$mid.'&infrm=1&_mid='.$mid.'&a=get_plat_member&access_plat_token='.$ret_oauthlogin['access_plat_token'].'&plat_id=' . $plat_id . '&appid='.$this->user['appid'].'&access_token=' . $this->user['token'];
		
//		$callback = urlencode($this->settings['App_live_interactive']['protocol'].$this->settings['App_live_interactive']['host'].'/'.$this->settings['App_live_interactive']['dir'].'admin/interactive_member?a=plat_user_add&mid='.$mid.'&access_plat_token='.$ret_oauthlogin['access_plat_token'].'&plat_id=' . $plat_id . '&channel_id=' . $channel_id . '&appid='.$this->user['appid'].'&access_token=' . $this->user['token']);
			
		$ret['url'] = $ret_oauthlogin['url'] . '&refer_url=' . urlencode($callback);
		
		$this->addItem($ret);
		$this->output();
	}
	
	/**
	 * 重新授权
	 * Enter description here ...
	 */
	public function retset_oauthlogin()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入账号id');
		}
		
		$ret_member = $this->mInteractiveMember->detail($id);
		
		if (empty($ret_member))
		{
			$this->errorOutput('该用户不存在或已被删除');
		}
		
		$type  		= $ret_member['plat_type'];
		$plat_id 	= $ret_member['plat_id'];
		$mid 		= intval($this->input['_mid']);
		$plat_token = $ret_member['plat_token'];

		if (!$type)
		{
			$this->errorOutput('平台类型不能为空');
		}
		
		if (!$plat_id)
		{
			$this->errorOutput('平台id不能为空');
		}
		
		$ret_oauthlogin = $this->mShare->oauthlogin($plat_id, $plat_token);
		$ret_oauthlogin = $ret_oauthlogin[0];
		
		if (empty($ret_oauthlogin))
		{
			$this->errorOutput('站外平台信息不存在或已被删除');
		}
		
		$ret = array();
		
		$ret_oauthlogin['url'] = $ret_oauthlogin['sync_third_auth'] . '?oauth_url=' . $ret_oauthlogin['oauth_url'] . '&access_plat_token=' .$ret_oauthlogin['access_plat_token']; 
		$callback 			   = $this->settings['oauthlogin']['protocol'] . $this->settings['oauthlogin']['host'] .'/'. $this->settings['oauthlogin']['dir'] . 'run.php?mid='.$mid.'&infrm=1&_mid='.$mid.'&a=reset_oauth&id='.$id.'&access_plat_token='.$ret_oauthlogin['access_plat_token'].'&plat_id=' . $plat_id . '&appid='.$this->user['appid'].'&access_token=' . $this->user['token'];
		
		$ret['url'] = $ret_oauthlogin['url'] . '&refer_url=' . urlencode($callback);
		
		$this->addItem($ret);
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
		
		$check_member = $this->mInteractiveMember->check_member_exists_by_id($plat_user['uid'], $plat_id);
		
		if ($check_member['id'] != $id)
		{
			$this->errorOutput('您没有权限授权该账号');
		}
		/*
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
		
		$referto = '/livworkbench/run.php?mid='.$mid.'&infrm=1&nav=1';
		
		$add_info = array(
		//	'plat_token' 		=> $access_plat_token,
			'plat_can_access' 	=> $plat_info['can_access'],
			'plat_expired_time' => $plat_info['expired_time'],//1354312491
			'referto'			=> $referto,
		);
		
		$ret = $this->mInteractiveMember->member_edit($add_info, $id);
	
		if (!$ret)
		{
			$this->errorOutput('重新授权失败');
		}
		unset($this->input['id']);
		$this->input['a'] = 'show';
		$this->input['referto'] = $referto;
		$this->input['reset_oauth'] = 1;
		$this->show();
	//	$this->addItem($ret);
	//	$this->output();
	}
	
	private function get_condition()
	{
		$condition = '';
		
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' AND member_name like \'%'.urldecode($this->input['k']).'%\'';
		}
		
		if (isset($this->input['id']) && $this->input['id'])
		{
			$condition .= " AND id IN (" . $this->input['id'] . ")";
		}
		
		if (isset($this->input['member_id']) && $this->input['member_id'])
		{
			$condition .= " AND member_id IN (" . $this->input['member_id'] . ")";
		}

		if (isset($this->input['member_name']) && $this->input['member_name'])
		{
			$condition .= " AND member_name = '" . trim($this->input['member_name']) . "' ";
		}
		
		if(isset($this->input['start_time']) && !empty($this->input['start_time']))
		{
			$start_time = strtotime(trim($this->input['start_time']));
			$condition .= " AND create_time >= '".$start_time."'";
		}
		
		if(isset($this->input['end_time']) && !empty($this->input['end_time']))
		{
			$end_time = strtotime(trim($this->input['end_time']));
			$condition .= " AND create_time <= '".$end_time."'";
		}
		
		if(isset($this->input['status']) && $this->input['status'] != -1)
		{
			$condition .= " AND status = " . intval($this->input['status']);
		}
		
		if(isset($this->input['date_search']) && !empty($this->input['date_search']))
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date_search']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND create_time > '".$yesterday."' AND create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND create_time > '".$today."' AND create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND create_time > '".$last_threeday."' AND create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND create_time > '".$last_sevenday."' AND create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}
		return $condition;
	}
	
	public function unknow()
	{
		$this->errorOutput('未实现的空方法');
	}
}

$out = new interactiveMemberApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>