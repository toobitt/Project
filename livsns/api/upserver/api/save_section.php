<?php
define('ROOT_DIR', '../');
require(ROOT_DIR . 'global.php');
set_time_limit(0);
/**
* 视频上传接口
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
$video_id = $_INPUT['id'];
if (!$video_id)
{
	error_output('001', '未指定视频id');
}
$svodid = $_INPUT['svodid'];
if (!$svodid)
{
	error_output('002', '未指定源视频vodid');
}
$start = $_INPUT['start'];
$duration = $_INPUT['duration'];
if(!is_array($svodid))
{
	$svodid = array($svodid);
	$start = array($start);
	$duration = array($duration);
}
if (count($svodid) != count($start) || count($svodid) != count($duration))
{
	error_output('003', '视频信息不匹配');
}
include(ROOT_DIR . 'lib/mediainfo.class.php');

$mediainfo = new mediainfo();
$source = array();
foreach ($svodid AS $k => $sid)
{
	$video_dir = hg_num2dir($sid);
	$targerdir = TARGET_DIR . $video_dir . $sid . '.ssm/';
	$mp4 = $targerdir . $sid . '.mp4';
	$mediainfo->setFile($mp4);
	$data = $mediainfo->getMeidaInfo();
	$source[] = array(
				'source' => $mp4,
				'start' => intval($start[$k]),
				'duration' => intval($duration[$k]),
				'mediainfo' => $data,
			);
}
$file_id = $_INPUT['vodid'];
if (!$file_id)
{
	$file_id = hg_get_video_id();
}
$dir = hg_num2dir($file_id);
$video_dir = hg_num2dir($file_id);
$targerdir = TARGET_DIR . $video_dir . $file_id . '.ssm/';
hg_mkdir($targerdir);

$curl = new curl($gVodApi['host'], $gVodApi['dir'], $gVodApi['token']);
$curl->initPostData();
$conf = $curl->request('vod_config.php');
$gTransApi['filename'] = 'getVideoInfo.php';
$trans_info = array(
	'sourceFile' => $source,
	'id' => $video_id,
	'vodid' => $file_id,
	'targetDir' => $targerdir,
	'config' => $conf[0],
	'callback' => $gTransApi

);
hg_file_write(UPLOAD_DIR . FILE_QUEUE . $file_id, json_encode($trans_info));

$data = array('vodid' => $file_id, 'cmd' => $cmd, 'trans_info' => $trans_info, 'target' => $targerdir);
output($data);
?>