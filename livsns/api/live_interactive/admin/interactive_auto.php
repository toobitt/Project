<?php
/***************************************************************************
* $Id: interactive_auto.php 17441 2013-02-21 01:55:12Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','interactive_auto');
require('global.php');
class interactiveAutoApi extends BaseFrm
{
	private $mInteractive;
	private $mShare;
	public function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/interactive.class.php';
		$this->mInteractive = new interactive();
		
		require_once ROOT_PATH . 'lib/class/share.class.php';
		$this->mShare = new share();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function interactive_add()
	{
		//取队列一条记录
		$sql = "SELECT * FROM " . DB_PREFIX . "interactive_member_queue WHERE 1 ORDER BY create_time ASC LIMIT 0,1";
		$member_queue = $this->db->query_first($sql);

		if (empty($member_queue))
		{
			$this->errorOutput('队列没有内容');
		}
		
		if ($this->input['debug'])
		{
			hg_pre($member_queue);
		}
		
		//取出微博账号信息
		$sql = "SELECT * FROM " . DB_PREFIX . "interactive_member WHERE plat_id = " . $member_queue['plat_id'] . " AND member_id = '" . $member_queue['member_id'] . "' ";
		$member_info = $this->db->query_first($sql);
		
		if (empty($member_info))
		{
			$this->errorOutput('微博账号不存在或已被删除');
		}
		
		$member_info['plat_since_id'] = $member_info['plat_since_id'] ? @unserialize($member_info['plat_since_id']) : array();
		$plat_since_id = $member_info['plat_since_id'];

		//获取@记录
		$ret_plat = $this->mShare->get_mention($this->user['appid'], $member_queue['plat_id'], $member_queue['plat_token'], $plat_since_id[intval($member_queue['channel_id'])], 0, 200);

		if ($this->input['debug'])
		{
			hg_pre($ret_plat);
		}
		
		if (!empty($ret_plat) && !$ret_plat['error'])
		{
			$_plat = array();
			foreach ($ret_plat AS $k => $plat)
			{
				if ($plat['created_at'] > $member_queue['start_time'] && $plat['created_at'] < $member_queue['end_time'])
				{
					$_plat[] = $plat;
					
					$plat['text'] = trim(substr($plat['text'], strlen('@'.$member_queue['member_name'])));
					
					$content = $plat['retweeted_status']['text'] ? $plat['retweeted_status']['text'] : $plat['text'];
					//微博用户id
					$member_id = !$plat['retweeted_status']['screen_name'] ? $member_queue['member_id'] : '';
					//微博用户名称
					$member_name = $plat['screen_name'] ? $plat['screen_name'] : $member_queue['member_name'];
					//微博用户头像
					$weibo_avatar = $plat['avatar'];
					
					$input_info[$k] = array(
						'channel_id' 		=> $member_queue['channel_id'],
						'member_id' 		=> $member_id,
						'member_name'		=> $member_name,
						'plat_id'			=> $member_queue['plat_id'],
						'plat_name'			=> $member_queue['plat_name'],
						'plat_member_id'	=> $member_id,
						'plat_since_id' 	=> $plat['id'],
						'content' 	 		=> $content,
						'dates' 	 		=> date('Y-m-d'),
						'status' 	 		=> $this->settings['default']['status'],
						'type' 	 			=> $this->settings['default']['type'],
						'appid' 	 		=> $this->user['appid'],
						'appname' 	 		=> $this->user['display_name'],
						'user_id' 	 		=> $this->user['user_id'],
						'user_name'  		=> $this->user['user_name'],
						'create_time' 		=> TIMENOW,
						'ip' 				=> hg_getip(),
						'weibo_avatar'		=> $weibo_avatar,
					);

					//入直播互动库
					$ret_interactive = $this->mInteractive->interactive_add($input_info[$k]);
					
					//更新 plat_since_id
					if ($k == 0)
					{
						$plat_since_id[intval($member_queue['channel_id'])] = $plat['id'];
				//		$sql = "UPDATE " . DB_PREFIX . "interactive_member SET plat_since_id = '" . $plat['id'] . "' WHERE plat_id = " . $member_queue['plat_id'] . " AND member_id = '" . $member_queue['member_id'] . "' ";
						$sql = "UPDATE " . DB_PREFIX . "interactive_member SET plat_since_id = '" . serialize($plat_since_id) . "' WHERE plat_id = " . $member_queue['plat_id'] . " AND member_id = '" . $member_queue['member_id'] . "' ";
						$this->db->query($sql);
					}
					
				}
			}
		}
	
		if ($this->input['debug'])
		{
			hg_pre($_plat);
		}
		
		//删除取出来的队列记录
		$sql = "DELETE FROM " . DB_PREFIX . "interactive_member_queue WHERE program_id = " . $member_queue['program_id'] . " AND member_id = '" . $member_queue['member_id'] . "' ";
		$this->db->query($sql);

		$this->addItem($input_info);
		$this->output();
	}
	
}

$out = new interactiveAutoApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'interactive_add';
}
$out->$action();
?>