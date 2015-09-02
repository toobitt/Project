<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: channel.php 4370 2011-08-09 08:13:28Z lijiaying $
***************************************************************************/
require('global.php');
class tvie extends BaseFrm
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 显示
	 */
	function show()
	{
		include(CUR_CONF_PATH . 'lib/tvie_api.php');
		$tvie_api = new TVie_api($this->settings['tvie']['up_stream_server']);
		//create_channel_epg($channel_id, $start_time, $end_time, $uri, $uptodate = 1, $description = '');
		$channel_id = 334;
		$start_time = time() + 250;
		$end_time = $start_time + 120;
		$uri = 'tvie://stream.dev.hogesoft.com/live/hoge/touch/sd/';
		$ret1 = $tvie_api->create_channel_epg($channel_id, $start_time, $end_time, $uri, '安徽公共');
		$ret1 = $tvie_api->get_channel_epg_by_id($ret1['result']['id']);
		$start_time = $end_time;
		$end_time = $start_time + 120;
		$uri = 'tvie://stream.dev.hogesoft.com/live/hoge/lizhi/cd/';
		$ret2 = $tvie_api->create_channel_epg($channel_id, $start_time, $end_time, $uri, '安徽科教');
		$ret2 = $tvie_api->get_channel_epg_by_id($ret2['result']['id']);
		$start_time = $end_time;
		$end_time = $start_time + 120;
		$uri = 'tvie://stream.dev.hogesoft.com/live/hoge/boluo/sd/';
		$ret3 = $tvie_api->create_channel_epg($channel_id, $start_time, $end_time, $uri, '安徽经济');
		$ret3 = $tvie_api->get_channel_epg_by_id($ret3['result']['id']);
		echo '<pre>';
		print_r($ret1);
		print_r($ret2);
		print_r($ret3);
	}

	
}

$out = new tvie();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>