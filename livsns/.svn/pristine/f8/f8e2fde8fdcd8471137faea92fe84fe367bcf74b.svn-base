<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: topic.php 6173 2012-03-23 06:46:16Z repheal $
***************************************************************************/
require('global.php');
class channel_snap extends adminBase
{
//	private $mLivemms;
	private $mediaserver;
	private $mLive;
	private $mServerConfig;
	function __construct()
	{
		parent::__construct();
		
	//	require_once (CUR_CONF_PATH . 'lib/livemms.class.php');
	//	$this->mLivemms = new livemms();
		
		require_once(ROOT_PATH.'lib/class/curl.class.php');
		$this->mediaserver = new curl($this->settings['App_mediaserver']['host'], $this->settings['App_mediaserver']['dir'] . 'admin/');
		
		require_once CUR_CONF_PATH . 'lib/server_config.class.php';
		$this->mServerConfig = new serverConfig();
		
		$this->mLive = $this->settings['wowza']['live_output_server'];
	}

	function __destruct()
	{
		parent::__destruct();
	}

	function show()
	{
		$id = $this->input['channel_id'];
		if ($id)
		{
			$condition = " AND id IN (" . $id . ")";
		}
		$sql = "SELECT id, main_stream_name, code, server_id FROM " . DB_PREFIX . "channel ";
		$sql.= " WHERE stream_state=1 AND audio_only=0 " . $condition;
		$q  = $this->db->query($sql);
		$channels = $server_id = array();
		while($r = $this->db->fetch_array($q))
		{
			$server_id[] = $r['server_id'];
			$channels[$r['id']] = $r;
		}
		
		//服务器配置
		if (!empty($server_id))
		{
			$server_id	   	= implode(',', @array_unique($server_id));
			$server_infos   = $this->mServerConfig->get_server_config($server_id);
		}
		
		$sql = "SELECT channel_id, out_stream_id, stream_name FROM " . DB_PREFIX . "channel_stream WHERE channel_id IN(" . implode(',', @array_keys($channels)) . ")";
		$q  = $this->db->query($sql);
		$streams = array();
		while ($row = $this->db->fetch_array($q))
		{
			$streams[$row['channel_id']][$row['stream_name']] = $row;
		}
		
		foreach ($channels AS $k => $v)
		{
			$server_info = $server_infos[$v['server_id']];
			
			if ($server_info['core_in_host'])
			{
				if ($server_info['is_dvr_output'])
				{
					$wowzaip = $server_info['dvr_in_host'] . ':' . $server_info['dvr_out_port'];
				}
				else
				{
					$wowzaip = $server_info['core_in_host'] . ':' . $server_info['core_out_port'];
				}
			}
			else 
			{
				if ($this->settings['wowza']['dvr_output_server'])
				{
					$wowzaip = $this->settings['wowza']['dvr_output_server']['host'] . ':' . $this->settings['wowza']['out_port'];
				}
				else 
				{
					$wowzaip = $this->settings['wowza']['core_input_server']['host'] . ':' . $this->settings['wowza']['out_port'];
				}
			}
			
			$suffix	 = $this->settings['wowza']['dvr_output']['suffix'];
			
			if ($this->mLive)
			{
				if ($server_info['is_live_output'])
				{
					$wowzaip = $server_info['live_in_host'] . ':' . $server_info['live_out_port'];
				}
				else 
				{
					$wowzaip = $this->settings['wowza']['live_output_server']['host'] . ':' . $this->settings['wowza']['out_port'];
				}
				$suffix	 = $this->settings['wowza']['live_output']['suffix'];
			}
			
			$streamurl = hg_streamUrl($wowzaip, $v['code'], $v['main_stream_name'] . $suffix);
			
			$this->mediaserver->initPostData();
			$this->mediaserver->setSubmitType('post');
			$this->mediaserver->addRequestData('channel_id', $k);
			$this->mediaserver->addRequestData('a', 'startsnap');
			$this->mediaserver->addRequestData('stream_uri', $streamurl);
			$this->mediaserver->addRequestData('interval', $this->settings['channel_snap_interval']);
			$ret = $this->mediaserver->request('livesnap.php');
			$this->additem($ret[0]);
		}
		$this->output();
	}
	
}
$out = new channel_snap();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>