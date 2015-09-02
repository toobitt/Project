<?php
define('ROOT_DIR', '../');
require(ROOT_DIR . 'global.php');
set_time_limit(0);
/**
* 视频录制接口
*
* 输入: videofile  $_FILES文件流
* 返回: json
array(
	'id' => $last_id, //视频id
	'type' => $filetype， //视频类型
	'size' =>  //视频总大小
);
* 错误返回: 001 - 未指定文件传输，002 - 非法的文件类型， 003 - 视频移动失败
*/
if (!in_array($_INPUT['auth'], $gToken))
{
	error_output('009', '通信令牌错误');
}

$channel_id =  $_INPUT['channel_id'];
$starttime =  $_INPUT['starttime'];
$endtime =  $_INPUT['endtime'];
$stream =  urldecode($_INPUT['stream']);
$program =  urldecode($_INPUT['program']);
$save_time =  $_INPUT['save_time'];
$vod_sort_id =  $_INPUT['vod_sort_id'];
if (!$channel_id || !$stream)
{
	error_output('001', '请设置频道和流信息');
}
if (!$starttime || !$endtime || $endtime < $starttime)
{
	error_output('002', '时间设置错误');
}
if ((time() - $starttime)  > $save_time * 3600)
{
	error_output('003', '直播节目已不存在，无法获取');
}
if (($endtime - $starttime)  > 86400)
{
	error_output('004', '时间设置过长');
}

$endtime = $endtime + 5;
if ($endtime > time())
{
	error_output('005', date('Y-m-d H:i:s', $endtime) . '节目尚未结束，请' . date('Y-m-d H:i:s', time()) . '开始');
}
$filetype = '.flv';
if (!$_INPUT['vodid'])
{
	$last_id = hg_get_video_id();
	$a = 'create';
}
else
{
	$last_id = $_INPUT['vodid'];
}
$video_dir = hg_num2dir($last_id);
if (!hg_mkdir(UPLOAD_DIR . $video_dir) || !is_writeable(UPLOAD_DIR . $video_dir))
{
	error_output('004', UPLOAD_DIR . '目录不可写入文件');
}
$targerdir = TARGET_DIR . $video_dir . $last_id . '.ssm/';
hg_mkdir($targerdir);
$filepath =  $video_dir . $last_id . $filetype;

if (!$_INPUT['id'] && $last_id && $gVodApi['host'] && $a)
{
	$vod = array(
		'vodid' => $last_id,
		'totalsize' => $filesize,
		'type' => $filetype,
		'source' => $channel_id,
		'starttime' => $starttime,
		'delay_time' => $_INPUT['delay_time'],
		'vod_sort_id' => $vod_sort_id,
		'title' => $program,
		'vod_leixing' => 3,
		'admin_id' => $_INPUT['admin_id'],
		'admin_name' => $_INPUT['admin_name'] ? $_INPUT['admin_name'] : '自动录制',
	);
	include_once(ROOT_DIR . 'lib/curl.class.php');
	$curl = new curl($gVodApi['host'], $gVodApi['dir'] . 'admin/', $gVodApi['token']);
	$curl->initPostData();
	$curl->setSubmitType('post');
	$curl->addRequestData('a', $a);
	foreach ($vod AS $k => $v)
	{
		$curl->addRequestData($k, $v);
	}
	$ret = $curl->request('vod_update.php');
	if(is_array($ret))
	{
		$vodid = intval($ret[0]['id']);
	}
	$video_list_file = 'video_' . date('Ymd') . '.list';
	file_put_contents(UPLOAD_DIR . $video_list_file, $last_id . ';', FILE_APPEND);
}
else
{
	$vodid = intval($_INPUT['id']);
}
$stream = $stream . $starttime . '000,' . $endtime . '000';
$savefile = UPLOAD_DIR . $filepath;

if ($_INPUT['status'])
{
	$extra = '&status=' . $_INPUT['status'];
}
if ($_INPUT['force_codec'])
{
	$extra .= '&force_codec=' . $_INPUT['force_codec'];
}
$curl_cmd = 'curl "' . $stream . '" > "' . $savefile . '"' . "\n";
file_put_contents('../tmp/cmd_' . $channel_id . $starttime . '000,' . $endtime . '000' . '.txt', $curl_cmd);
$curl_cmd .= 'curl "http://' . $gTransApi['host'] . '/' . $gTransApi['dir'] . 'recode.php?auth=' . $gTransApi['token'] . '&id=' . $vodid . '&vodid=' . $last_id . $extra . '"';
$filename = hg_get_cmd_file('record_');
file_put_contents($filename, $curl_cmd);

$data = array(
	'id' => $vodid ? $vodid : $last_id,
	'vodid' => $last_id,
	'type' => $filetype,
	'size' => $filesize,
	'path' => UPLOAD_DIR . $filepath . $endtime . '-' . $starttime,
	'admin_id' => $vod['admin_id'],
	'admin_name' => $vod['admin_name'],
);
output($data);
?>