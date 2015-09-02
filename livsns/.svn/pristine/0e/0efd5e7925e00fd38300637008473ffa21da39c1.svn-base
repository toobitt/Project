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
if ($_INPUT['debug'])
{
	if (function_exists('disk_free_space'))
	{
		$diskspace = disk_free_space(UPLOAD_DIR);
		echo round($diskspace / 1024 /1024 / 1024, 2) . 'G';
	}
}
if (!in_array($_INPUT['auth'], $gToken))
{
	error_output('009', '通信令牌错误');
}
if (!$_FILES['videofile']['tmp_name'])
{
	error_output('001', '未指定文件传输');
}
if (function_exists('disk_free_space'))
{
	$diskspace = disk_free_space(UPLOAD_DIR);
	if ($diskspace && $diskspace < 5368709120)
	{
		error_output('010', '硬盘空间不足5个G，上传已关闭');
	}
}
$last_id = hg_get_video_id();


$original = $_FILES['videofile']['name'];
$filetype = strtolower(strrchr($original, '.'));
$allowtype = explode(',', $gConfig['allow_type']);
if (!in_array($filetype, $allowtype))
{
	error_output('002', '非法的视频类型', $_FILES);
}
$video_dir = hg_num2dir($last_id);
if (!hg_mkdir(UPLOAD_DIR . $video_dir) || !is_writeable(UPLOAD_DIR . $video_dir))
{
	error_output('004', UPLOAD_DIR . '目录不可写入文件');
}

if (!hg_mkdir(UPLOAD_DIR . FILE_QUEUE) || !is_writeable(UPLOAD_DIR . FILE_QUEUE))
{
	error_output('004', UPLOAD_DIR . FILE_QUEUE . '目录不可写入文件');
}


$targerdir = TARGET_DIR . $video_dir . $last_id . '.ssm/';
hg_mkdir($targerdir);
$filepath =  $video_dir . $last_id . $filetype;

$_INPUT['client'] = intval($_INPUT['client']);
//file_put_contents($targerdir . 'uploading', $filesize . "\n" . $_FILES['videofile']['tmp_name'] . "\n" . UPLOAD_DIR . $filepath);
if (!@move_uploaded_file($_FILES['videofile']['tmp_name'], UPLOAD_DIR . $filepath))
{
	error_output('003', '视频未成功移动到指定目录');
}
include(ROOT_DIR . 'lib/mediainfo.class.php');
$mediainfo = new mediainfo(UPLOAD_DIR . $filepath);
$data = $mediainfo->getMeidaInfo();
$snaptime = intval($data['General']['Duration'] / 3);
$snapw = $data['Video']['Width'];
$snaph = $data['Video']['Height'];
$preview = hg_snap($snaptime, $targerdir, $snapw, $snaph, UPLOAD_DIR . $filepath, 0, 'preview');

if ($_INPUT['client'] > -1 && $gVodApi['host'])
{	

	$vod = array(
		'vodid' => $last_id,
		'totalsize' => $filesize,
		'type' => $filetype,
		'title' => str_replace($filetype, '', $original),
		'vod_leixing' => $_INPUT['vod_leixing'] ? $_INPUT['vod_leixing'] : 1,
		'admin_id' => $_INPUT['admin_id'],
		'admin_name' => $_INPUT['admin_name'],
	);
	$vod['audio'] = $data['Audio']['Format'] . '-' . $data['Audio']['Format profile'] . ' ' . $data['Audio']['Format version'];
	$vod['audio_channels'] = $data['Audio']['Channel positions'];
	$vod['sampling_rate'] = $data['Audio']['Sampling rate'];
	$vod['bitrate'] = $data['General']['Overall bit rate'];
	$vod['duration'] = $data['General']['Duration'];
	$vod['video'] =  $data['Video']['Format'] . ' ' . $data['Video']['Format profile'];
	$vod['width'] =  $data['Video']['Width'];
	$vod['height'] =  $data['Video']['Height'];
	$vod['aspect'] =  $data['Video']['Display aspect ratio'];
	$vod['frame_rate'] =  $data['Video']['Frame rate'];
	$vod['img_src'] = THUMB_URL . $video_dir . $last_id . '.ssm/preview.jpg';
	include_once(ROOT_DIR . 'lib/curl.class.php');
	$curl = new curl($gVodApi['host'], $gVodApi['dir'] . 'admin/', $gVodApi['token']);
	$curl->initPostData();
	$curl->setSubmitType('post');
	foreach ($vod AS $k => $v)
	{
		$curl->addRequestData($k, $v);
	}
	$curl->addRequestData('a', 'create');
	$ret = $curl->request('vod_update.php');
	if(is_array($ret))
	{
		$vodid = intval($ret[0]['id']);
		file_put_contents('../tmp/t.txt', $vodid);
	}
}

$curl = new curl($gVodApi['host'], $gVodApi['dir'], $gVodApi['token']);
$curl->initPostData();
$conf = $curl->request('vod_config.php');
$gTransApi['filename'] = 'getVideoInfo.php';
$trans_info = array(
	'sourceFile' => array(
			array(
				'source' => UPLOAD_DIR . $filepath,
				'mediainfo' => $data,
			)
		),
	'id' => $vodid,
	'force_codec' => intval($_INPUT['force_codec']),
	'vodid' => $last_id,
	'video_id' => $last_id,
	'targetDir' => $targerdir,
	'config' => $conf[0],
	'callback' => $gTransApi

);
hg_file_write(UPLOAD_DIR . FILE_QUEUE . $last_id, json_encode($trans_info));

hg_file_write($targerdir . 'source_media_info', json_encode($data));
$video_list_file = 'video_' . date('Ymd') . '.list';
file_put_contents(UPLOAD_DIR . $video_list_file, $last_id . ';', FILE_APPEND);
$filesize = $_FILES['videofile']['size'];
$data = array(
	'id' => $vodid ? $vodid : $last_id,
	'vodid' => $last_id,
	'type' => $filetype,
	'size' => $filesize,
	'vod_leixing' =>  $_INPUT['vod_leixing'] ? $_INPUT['vod_leixing'] : 1,
	'vod_sort_id' => $_INPUT['vod_sort_id'],
	'filepath' => $_INPUT['vod_sort_id'],
	'image' => THUMB_URL . $video_dir . $last_id . '.ssm/preview.jpg',
	'server' => defined('SERVER_NAME') ? SERVER_NAME : 'codec1',
);
$data = $data + $_INPUT;
//视频上传成功，返回视频id
output($data);
?>