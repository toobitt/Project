<?php
/***************************************************************************
* $Id: channel_snap.php 42084 2014-11-27 03:32:32Z develop_tong $
***************************************************************************/
define('MOD_UNIQUEID','channel_snap');
define('ROOT_DIR', '../../../');
define('CUR_CONF_PATH', '../');
require(ROOT_DIR.'global.php');
require(CUR_CONF_PATH."lib/functions.php");
class channelSnap extends cronBase
{
	private $mMediaserver;
	private $mChannel;
	private $mServerConfig;
	function __construct()
	{
		parent::__construct();
		
		require_once(ROOT_PATH.'lib/class/curl.class.php');
		$this->mMediaserver = new curl($this->settings['App_mediaserver']['host'], $this->settings['App_mediaserver']['dir'] . 'admin/');
		
		require_once CUR_CONF_PATH . 'lib/channel.class.php';
		$this->mChannel = new channel();

		require_once CUR_CONF_PATH . 'lib/server_config.class.php';
		$this->mServerConfig = new serverConfig();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '频道截图',	 
			'brief' => '电视墙截图',
			'space' => '3',	//运行时间间隔，单位秒
			'is_use' => 0,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	public function show()
	{
		$id = trim($this->input['channel_id']);
		if ($id)
		{
			$condition = " AND id IN (" . $id . ")";
		}
		
		$orderby = " ORDER BY t1.order_id DESC ";
		
		$sql = "SELECT t1.id, t1.main_stream_name, t1.code, t1.server_id, t1.is_mobile_phone, 
				t2.flv_url
				FROM " . DB_PREFIX . "channel t1 ";
		$sql.= " LEFT JOIN " . DB_PREFIX . "channel_stream t2 ON t2.channel_id = t1.id ";
		$sql.= " WHERE t1.status = 1 AND t1.is_audio = 0 AND t2.is_main = 1 " . $condition . $orderby;
		
		$q = $this->db->query($sql);
		
		$channel_info = $server_id = array();
		while ($row = $this->db->fetch_array($q))
		{
			$server_id[] = $row['server_id'];
			$channel_info[$row['id']] = $row;
		}
		
		//服务器配置
		if (!empty($server_id))
		{
			$server_id	   	= implode(',', @array_unique($server_id));
			$_server_info   = $this->mServerConfig->get_server_config($server_id);
		}
		
		foreach ($channel_info AS $k => $v)
		{
			$type		 = $_server_info[$v['server_id']]['type'] ? $_server_info[$v['server_id']]['type'] : 'wowza';
			$server_info = $this->mChannel->get_server_info($_server_info[$v['server_id']]);

			$function = 'set_url_' . $type;
			
			$output_url_rtmp = $this->$function($server_info, $v);
			
			$this->mMediaserver->initPostData();
			$this->mMediaserver->setSubmitType('post');
			$this->mMediaserver->addRequestData('channel_id', $v['id']);
			$this->mMediaserver->addRequestData('a', 'startsnap');
			$this->mMediaserver->addRequestData('stream_uri', $output_url_rtmp);
			$ret = $this->mMediaserver->request('livesnap.php');
			
			$this->additem($ret[0]);
		}
		
		$this->output();
	}
	
	private function set_url_wowza($server_info, $channel_info)
	{
		$wowzaip_output 	= $server_info['wowzaip_output'];
		$suffix_output 		= $this->settings['wowza']['output']['suffix'];
			
		$app_name 	 = $channel_info['code'];
		$stream_name = $channel_info['main_stream_name'] . $suffix_output;
		
		$return = hg_set_stream_url($wowzaip_output, $app_name, $stream_name);
		return $return;
	}
	
	private function set_url_tvie($server_info, $channel_info)
	{
		$return = $channel_info['flv_url'];
		return $return;
	}

	private function set_url_nginx($server_info, $channel_info)
	{
		$nginx_output  = $server_info['host'];
				
		$app_name  = $channel_info['code'];
		$stream_name = $channel_info['main_stream_name'] . $suffix_output;  
		$return = 'rtmp://' . $nginx_output . '/' . $server_info['output_dir'] . '/' . $channel_info['code'] . '_' . $channel_info['main_stream_name'];  
		return $return;
	}
}
$out = new channelSnap();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>