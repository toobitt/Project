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
$id = $_INPUT['vodid'];
if (!$id)
{
	error_output('001', '未指定视频ID');
}
$video_dir = hg_num2dir($id);
$targerdir = TARGET_DIR . $video_dir . $id . '.ssm/';
hg_mkdir($targerdir);
$source = UPLOAD_DIR . $video_dir;

$handle = dir($source);
while ($file = $handle->read())
{
	if (is_file($source . $file))
	{
		break;
	}
}
$filepath =  $video_dir . $file;
if (!is_file(UPLOAD_DIR . $filepath))
{
	error_output('002', '视频文件不存在');
}
include(ROOT_DIR . 'lib/mediainfo.class.php');
$mediainfo = new mediainfo(UPLOAD_DIR . $filepath);
$data = $mediainfo->getMeidaInfo();
if (!$data)
{
	error_output('002', '视频文件不存在');
}
$mediainfo_file = $targerdir . 'source_media_info';
$snaptime = intval($data['General']['Duration'] / 3);
$snapw = $data['Video']['Width'];
$snaph = $data['Video']['Height'];
hg_snap($snaptime, $targerdir, $snapw, $snaph, UPLOAD_DIR . $filepath, 0, 'preview');

$gTransApi['filename'] = 'getVideoInfo.php';

$curl = new curl($gVodApi['host'], $gVodApi['dir'], $gVodApi['token']);
$curl->initPostData();
print_r($gVodApi);
$conf = $curl->request('vod_config.php');
$trans_info = array(
	'sourceFile' => array(
			array(
				'source' => UPLOAD_DIR . $filepath,
				'mediainfo' => $data,
			)
		),
	'id' => $_INPUT['id'],
	'force_codec' => intval($_INPUT['force_codec']),
	'vodid' => $id,
	'targetDir' => $targerdir,
	'config' => $conf[0],
	'callback' => $gTransApi

);
if ($_INPUT['status'])
{
	$trans_info['extra'] = 'status=' . $_INPUT['status'];
}

hg_file_write(UPLOAD_DIR . FILE_QUEUE . $id, json_encode($trans_info));
hg_file_write($targerdir . 'source_media_info', json_encode($data));

$data = array($id);
output($data);
?>