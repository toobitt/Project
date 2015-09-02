<?php
/***************************************************************************
* $Id: interactive_edit.php 16603 2013-01-11 02:03:57Z lijiaying $
***************************************************************************/
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_PATH."global.php");
require(CUR_CONF_PATH."lib/functions.php");
class interactiveEditApi extends BaseFrm
{
	private $mInteractive;
	function __construct()
	{
		parent::__construct();
		include_once CUR_CONF_PATH . 'lib/interactive.class.php';
		$this->mInteractive = new interactive();
		
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	function interactive_add()
	{
		if ($this->user['user_id'] < 0)
		{
			$this->errorOutput('会员未登陆，请登陆后在参加');
		}

		$channel_id  	= intval($this->input['channel_id']);
		$member_id   	= $this->user['user_id'];
		$member_name   	= $this->user['user_name'];
		$plat_id     	= intval($this->input['plat_id']);
		$plat_member_id = $this->input['plat_member_id'];
		$content     	= trim(urldecode($this->input['content']));
	
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
		
		if (!$content)
		{
			$this->errorOutput('内容不能为空');
		}
		
		$dates = date('Y-m-d');
		//检测警告次数
		$warn_count = $this->mInteractive->get_warn_count_by_member_id($member_id, $dates);
		if ($warn_count['total'] >= $this->settings['warn_count'])
		{
			$this->errorOutput('今天您已经被警告过' . $this->settings['warn_count'] . '次，不能在发布内容');
		}
		//检测屏蔽次数
		$shield_count = $this->mInteractive->get_shield_count_by_member_id($member_id, $dates);
		if ($shield_count['total'] >= $this->settings['shield_count'])
		{
			$this->errorOutput('今天您已经被屏蔽过' . $this->settings['shield_count'] . '次，不能在发布内容');
		}
		
		$ret_member = $this->mInteractive->get_member_by_id($member_id, $plat_id);
		
		if (empty($ret_member))
		{
			$member_data = array(
				'member_id'		=> $member_id,
				'plat_id'		=> $plat_id,
				'host'			=> $this->user['avatar']['host'],
				'dir'			=> $this->user['avatar']['dir'],
				'filepath'		=> $this->user['avatar']['filepath'],
				'filename'		=> $this->user['avatar']['filename'],
			);
			$member = $this->mInteractive->member_add($member_data);
		}
		
		$input_info = array(
			'channel_id' 		=> $channel_id,
			'member_id' 		=> $member_id,
			'member_name'		=> $member_name,
			'plat_id'			=> $plat_id,
		//	'plat_name'			=> $plat_name,
			'plat_member_id'	=> $plat_member_id,
			'content' 	 		=> $content,
			'dates' 	 		=> $dates,
			'status' 	 		=> $this->settings['default']['status'],
			'type' 	 			=> $this->settings['default']['type'],
			'appid' 	 		=> $this->user['appid'],
			'appname' 	 		=> $this->user['display_name'],
			'user_id' 	 		=> $this->user['user_id'],
			'user_name'  		=> $this->user['user_name'],
			'create_time' 		=> TIMENOW,
			'ip' 				=> hg_getip(),
		);
	
		$ret_interactive = $this->mInteractive->interactive_add($input_info);
		$this->addItem($ret_interactive);
		$this->output();
	}
	
	function unknow()
	{
		$this->errorOutput('未实现的空方法');
	}
}

$out = new interactiveEditApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'interactive_add';
}
$out->$action();
?>