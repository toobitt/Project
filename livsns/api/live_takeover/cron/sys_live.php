<?php
/***************************************************************************
* $Id: sys_live.php 26189 2013-07-22 01:05:16Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','sys_live');
define('ROOT_DIR', '../../../');
define('CUR_CONF_PATH', '../');
require(ROOT_DIR.'global.php');
require(CUR_CONF_PATH."lib/functions.php");
class sysLive extends cronBase
{
	private $mChannel;
	function __construct()
	{
		parent::__construct();
		
		require_once CUR_CONF_PATH . 'lib/channel.class.php';
		$this->mChannel = new channel();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '同步频道',	 
			'brief' => '同步频道',
			'space' => '60',	//运行时间间隔，单位秒
			'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	public function show()
	{
		$channel_data = array(
			'is_sys' => 1,
		);
		
		$channel_info_sys = $this->mChannel->getChannelInfo($channel_data);
		if (empty($channel_info_sys))
		{
			$this->errorOutput('暂无频道数据同步');
		}
		
		$sql = "SELECT id, sys_id, order_id FROM " . DB_PREFIX . "channel WHERE is_sys = 1 ORDER BY id ASC ";
		$q = $this->db->query($sql);
		
		$channel = $channel_id = array();
		while ($row = $this->db->fetch_array($q))
		{
			$channel[$row['id']] = $row;
			$channel_id[] = $row['id'];
		}
		
		$channel_stream = array();
		if (!empty($channel_id))
		{
			$channel_id = implode(',', $channel_id);
			$sql = "SELECT id, channel_id, sys_id FROM " . DB_PREFIX . "channel_stream WHERE channel_id IN (" . $channel_id . ") ORDER BY order_id ASC ";
			$q = $this->db->query($sql);
			
			while ($row = $this->db->fetch_array($q))
			{
				$channel_stream[$row['channel_id']][$row['sys_id']] = $row;
			}
		}
		
		$channel_info = array();
		foreach ($channel AS $v)
		{
			$v['channel_stream'] = $channel_stream[$v['id']];
			$channel_info[$v['sys_id']] = $v;
		}

		foreach ($channel_info_sys AS $v)
		{
			$data = array(
				'name'				=> $v['name'],
				'code'				=> $v['code'],
				'application_id'	=> $v['application_id'],
				'is_url'			=> 1,
				'is_mobile_phone'	=> $v['is_mobile_phone'],
				'is_audio'			=> $v['is_audio'],
				'is_control'		=> $v['is_control'],
				'time_shift'		=> $v['time_shift'],
				'server_id'			=> $v['server_id'],
				'node_id'			=> $v['node_id'],
				'org_id'			=> $this->user['org_id'],
				'user_id'			=> $this->user['user_id'],
				'user_name'			=> $this->user['user_name'],
				'appid'				=> $this->user['appid'],
				'appname'			=> $this->user['display_name'],
				'create_time'		=> TIMENOW,
				'update_time'		=> TIMENOW,
				'ip'				=> hg_getip(),
				'logo_rectangle' 	=> serialize($v['_logo_rectangle']),
				'client_logo'	 	=> serialize($v['_client_logo']),
				'is_sys'	 		=> 1,
				'sys_id'	 		=> $v['id'],
				'is_live'	 		=> $v['is_live'],
				'is_record'	 		=> $v['is_record'],
				'change_id'	 		=> $v['change_id'],
				'change_name'	 	=> $v['change_name'],
				'change_type'	 	=> $v['change_type'],
				'stream_id'	 		=> $v['stream_id'],
				'input_id'	 		=> $v['input_id'],
				'beibo'	 			=> $v['beibo'],
				'stream_count'	 	=> $v['stream_count'],
				'level'	 			=> $v['level'],
				'core_count'	 	=> $v['core_count'],
				'main_stream_name'	=> $v['main_stream_name'],
				'stream_name'		=> serialize($v['stream_name']),
				'drm'				=> $v['drm'],
			);
			
			if ($channel_info[$v['id']]['id'])
			{
				//update
				$data['id'] = $channel_info[$v['id']]['id'];
				
				$ret = $this->mChannel->update($data);
			}
			else
			{
				//create
				$ret = $this->mChannel->create($data);
			}
		
			if (!$ret['id'])
			{
				continue;
			}
			
			//同步channel_stream
			$main_stream_id = 0;
			foreach ($v['channel_stream'] AS $kk => $vv)
			{
				$stream_data = array(
					'channel_id'		=> intval($ret['id']),
					'stream_name'		=> trim($vv['stream_name']),
					'output_url'		=> trim($vv['output_url']),
					'output_url_rtmp'	=> trim($vv['output_url_rtmp']),
					'm3u8'				=> trim($vv['m3u8']),
					'is_main'			=> $vv['is_main'] ? 1 : 0,
					'order_id'			=> $kk,
					'sys_id'			=> $vv['id'],
					'input_id'			=> $vv['input_id'],
					'delay_id'			=> $vv['delay_id'],
					'change_id'			=> $vv['change_id'],
					'output_id'			=> $vv['output_id'],
					'bitrate'			=> $vv['bitrate'],
				);
				
				if ($channel_info[$v['id']]['channel_stream'][$vv['id']]['id'])
				{
					//update
					$stream_data['id'] = $channel_info[$v['id']]['channel_stream'][$vv['id']]['id'];

					$ret_stream = $this->mChannel->channel_stream_update($stream_data);
				}
				else 
				{
					//create
					$ret_stream = $this->mChannel->channel_stream_create($stream_data);
				}
				
				if ($ret_stream['id'] && $vv['is_main'])
				{
					$main_stream_id = $ret_stream['id'];
				}
			}
			
			$update_data = array(
				'id'				=> $ret['id'],
				'order_id'			=> $channel_info[$v['id']]['id'] ? $channel_info[$v['id']]['order_id'] : $ret['id'],
				'main_stream_id'	=> $main_stream_id,
			);
			$this->mChannel->update($update_data);
		}
		
		$this->addItem('success');
		$this->output();
	}
	
}
$out = new sysLive();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>