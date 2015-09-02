<?php
define('ROOT_DIR', '../../../');
define('CUR_CONF_PATH', '../');
require(CUR_CONF_PATH . 'global.php');

$timestamp = empty($_REQUEST['time']) ? $_SERVER["REQUEST_TIME"] : $_REQUEST['time'] / 1000;
$time  = date('Y-m-d',$timestamp);
//$time = '2011-10-08';
$channel_id = $_REQUEST['channel_id'];
$channel_id = $channel_id ? $channel_id : 1;

$extend_para = hg_parse_para();
$cache_outxml_filename = md5($extend_para['cache_outputxml_dir']);

hg_check_outputxml($channel_id . '/' . date('d', $timestamp) . '/'.$cache_outxml_filename, 'program');
if ($gGlobalConfig['App_program'])
{
	$curl = new curl($gGlobalConfig['App_program']['host'], $gGlobalConfig['App_program']['dir']);


	$curl->setSubmitType('post');		
	$curl->setReturnFormat('json');
	$curl->initPostData();
	$curl->addRequestData('channel_id', $channel_id);
	$curl->addRequestData('dates', $time);
	$ret = $curl->request('program.php');	
}
else
{
	$curl = new curl($gGlobalConfig['App_live']['host'], $gGlobalConfig['App_live']['dir']);


	$curl->setSubmitType('post');		
	$curl->setReturnFormat('json');
	$curl->initPostData();
	$curl->addRequestData('id', $channel_id);
	$curl->addRequestData('a', 'channels');
	$channelinfo = $curl->request('channel.php');
	$channelinfo = $channelinfo[0];
	$ret = array();
	for ($i = 0; $i < 24; $i++)
	{
		$start_time = strtotime($dates . ' ' . $i . ':00:00');
		$end_time = strtotime($dates . ' ' . $i . ':00:00');
		$url = str_replace('playlist.m3u8', $start_time . '000,3600000.m3u8', $channelinfo['m3u8']);

		if (time() >= $start_time && time() <= $end_time )
		{
			$now_play = 1;
		}
		else
		{
			$now_play = 0;
		}
		if ($end_time <= time())
		{
			$display = 1;
		}
		else
		{
			$display = 0;
		}
		$ret[] = array(
			'theme' => '精彩节目',
			'start_time' => $start_time,
			'end_time' => $end_time,
			'toff' => 3600,
			'now_play' => $now_play,
			'm3u8' => $url,
			'display' => $display,
		);
	}
}
if(is_array($ret))
{
	$dom = new DOMDocument('1.0', 'utf-8');
	$program= $dom->createElement('program');
	foreach($ret as $key => $value)
	{
		$name = $value['subtopic'] ? ($value['theme'].':'.$value['subtopic']):$value['theme'];
		$startTime = $value['start_time'];
		$duration = $value['toff'];

		$item= $dom->createElement('item');
		$item->setAttribute('name', $name);
		$item->setAttribute('startTime', $startTime . '000');
		$item->setAttribute('duration', $duration * 1000);
		$item->setAttribute('url', $value['m3u8']);
		$item->setAttribute('display', $value['display']);
		$item->setAttribute('now_play', $value['now_play']);
		$item->setAttribute('zhi_play', $value['zhi_play']);
		$program->appendChild($item);
	}
	$dom->appendChild($program);
	$output_xml =  $dom->saveXML();
	hg_cache_outputxml(CACHE_DIR . 'program/' . $channel_id . '/'.date('d', $timestamp).'/', $cache_outxml_filename, $output_xml);
	echo $output_xml;
}

?>