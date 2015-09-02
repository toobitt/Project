<?php
/***************************************************************************
* $Id: live_control_snap.php 30923 2013-10-26 06:44:24Z tong $
***************************************************************************/
define('MOD_UNIQUEID','live_control_snap');
define('ROOT_DIR', '../../../');
define('CUR_CONF_PATH', '../');
require(ROOT_DIR.'global.php');
require(CUR_CONF_PATH."lib/functions.php");
class liveControlSnap extends cronBase
{
	private $mMediaserver;
	private $mLive;
	function __construct()
	{
		parent::__construct();
		
		require_once(ROOT_PATH.'lib/class/curl.class.php');
		$this->mMediaserver = new curl($this->settings['App_mediaserver']['host'], $this->settings['App_mediaserver']['dir'] . 'admin/');
		
		require_once(ROOT_PATH . 'lib/class/live.class.php');
		$this->mLive = new live();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '播控截图',	 
			'brief' => '播控电视墙截图',
			'space' => '3',	//运行时间间隔，单位秒
			'is_use' => 0,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	public function live_control_snap()
	{
		$channel_data = array(
			'offset'	=> 0,
			'count'		=> 100,
			'is_server'	=> 1,
			'status'	=> 1,
			'is_audio'	=> 0,
			'field'		=> 'id, main_stream_name, code, server_id, is_mobile_phone',
		);
		
		$id = trim($this->input['channel_id']);
		if ($id)
		{
			$channel_data['id'] = $id;
		}
		
		$channel_info = $this->mLive->getChannelInfo($channel_data);
		
		if (!empty($channel_info))
		{
			foreach ($channel_info AS $k => $v)
			{
				foreach ($v['channel_stream'] AS $vv)
				{
					if ($vv['stream_name'] == $v['main_stream_name'])
					{
						$output_url_rtmp = $vv['output_url_rtmp'];
					}
				}
				
				$this->mMediaserver->initPostData();
				$this->mMediaserver->setSubmitType('post');
				$this->mMediaserver->addRequestData('channel_id', $v['id']);
				$this->mMediaserver->addRequestData('a', 'startsnap');
				$this->mMediaserver->addRequestData('stream_uri', $output_url_rtmp);
				$ret = $this->mMediaserver->request('livesnap.php');
				
				$this->additem($ret[0]);
			}
		}
	
		$this->output();
	}
}
$out = new liveControlSnap();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'live_control_snap';
}
$out->$action();
?>