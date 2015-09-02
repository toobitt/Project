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
$vodid = $_INPUT['vodid'];
if (!$vodid)
{
	error_output('002', '未指定要编辑视频的vodid');
}
$svodid = $_INPUT['svodid'];
$start = $_INPUT['start'];
$duration = $_INPUT['duration'];
if(!$svodid || !$start || !$duration)
{
	error_output('003', '未指定要编辑视频的片段');
}
if (!is_array($svodid))
{
	$start = array($start);
	$duration = array($duration);
	$svodid = array($vodid);
}

if (count($start) != count($duration) ||  count($svodid) != count($start))
{
	error_output('006', '视频段信息不匹配');
}
include(ROOT_DIR . 'lib/mediainfo.class.php');

$video_dir = hg_num2dir($vodid);
$targerdir = TARGET_DIR . $video_dir . $vodid . '.ssm/';
$mp4 = $targerdir . $vodid . '.mp4';
if (!is_file($mp4))
{
	error_output('004', '原视频不存在');
}
$source_file = $targerdir . $vodid . '_s.mp4';
$rename = @rename($mp4, $source_file);
if (!$rename)
{
	error_output('005', '视频权限限制，无法编辑视频');
}
$mediainfo = new mediainfo($source_file);
$source_file_data = $mediainfo->getMeidaInfo();
$source = array();
foreach ($svodid AS $k => $vid)
{
	$video_dir = hg_num2dir($vid);
	$targerdir = TARGET_DIR . $video_dir . $vid . '.ssm/';
	if ($vid == $vodid)
	{
		$sourcef = $source_file;
		$data = $source_file_data;
	}
	else
	{
		$sourcef = $targerdir . $vid . '.mp4';
		if (!is_file($sourcef))
		{
			error_output('006', '指定片段视频不存在');
		}
		$mediainfo->setFile($sourcef);
		$data = $mediainfo->getMeidaInfo();
	}
	$source[] = array(
				'source' => $sourcef,
				'start' => intval($start[$k]),
				'duration' => intval($duration[$k]),
				'mediainfo' => $data,
			);
}
$curl = new curl($gVodApi['host'], $gVodApi['dir'], $gVodApi['token']);
$curl->initPostData();
$conf = $curl->request('vod_config.php');
$gTransApi['filename'] = 'getVideoInfo.php';
$trans_info = array(
	'sourceFile' => $source,
	'id' => $video_id,
	'vodid' => $vodid,
	'targetDir' => $targerdir,
	'config' => $conf[0],
	'callback' => $gTransApi
);
hg_file_write(UPLOAD_DIR . FILE_QUEUE . $vodid, json_encode($trans_info));

$data = array('id' => $video_id, 'vodid' => $vodid, 'trans_info' => $trans_info);
output($data);
?>