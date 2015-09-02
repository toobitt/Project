<?php
define('ROOT_DIR', '../');
define('WITHOUT_LOGIN', false);
require(ROOT_DIR . 'global.php');

/**
* 获取视频信息接口
*
* 输入: id  视频id
* 返回: 视频转码状态及信息
* 错误返回: 001 - 未指定视频ID， 002 - 未找到视频信息
*/

if (!in_array($_INPUT['auth'], $gToken))
{
	error_output('009', '通信令牌错误');
}
$id = $_INPUT['id'];
if (!$id)
{
	error_output('001', '未指定视频ID');
}
$ids = explode(',', $id);
if (count($ids) == 1)
{
	$info = getVideoInfoById($ids[0]);
	if (!$info)
	{
		error_output('002', '未找到视频信息');	
	}
	output($info);
}
$info = array();
foreach ($ids AS $id)
{
	$data = getVideoInfoById($id);
	if (!$data)
	{
		$data = array(
			'errorno' => '002',
			'errortext' => '未找到视频信息',	
		);
	}
	$info[] = $data;
}
output($info);


function getVideoInfoById($id)
{
	if (!$id)
	{
		return false;
	}
	$time = 0;

	$video_dir = hg_num2dir($id);

	include(ROOT_DIR . 'lib/mediainfo.class.php');
	$mediainfo = new mediainfo(MP4_TARGET_DIR . $video_dir . $id . '.ssm/' . $id . '.mp4');
	$data = $mediainfo->getMeidaInfo();

	$sourceinfo = @file_get_contents(MP4_TARGET_DIR . $video_dir . $id . '.ssm/source_media_info');
	$sourceinfo = json_decode($sourceinfo, true);

	if (!$data && !$sourceinfo)
	{
		$_INPUT['status'] = -1;
		hg_update_vod(array(), '', '');
		return false;
	}
	$info = array();
	$targerdir = MP4_TARGET_DIR . $video_dir . $id . '.ssm/';
	$info['id'] = $id;
	$out = @file_get_contents(MP4_TARGET_DIR . $video_dir . $id . '.ssm/out');
	// frame=20302 fps= 17 q=14.0 size=  272224kB time=2582.58 bitrate= 863.5kbits/s dup=0 drop=39852 
	//duplicate frame(s)!Pos:5193.4s 129587f (99%) 263.62fps Trem:   0min 574mb  A-V:0.029 [687:236]
	preg_match_all('/frame=\s*\d*\s*fps=\s*\d*\s*q=\s*[0-9\.]*\s*size=\s*(\d*)kB\s*time=\s*([0-9\.]*)\s*bitrate=\s*([0-9\.]*)/is', $out, $result);
	preg_match_all('/frame=\s*\d*\s*fps=\s*\d*\s*q=\s*[0-9\.\-]*\s*Lsize=\s*(\d*)/is', $out, $complete);
	preg_match_all('/\(([\d\.]*)%\)/is', $out, $mecoder);
	$mecoder = $mecoder[1];
	$mecoder_progress = $mecoder[count($mecoder) - 1];
	$count = count($result[0]) - 1;
	//print_r($result);
	$progress = $result[1][$count];
	$curtime = $result[2][$count];
	$curbitrate = $result[3][$count];
	$complete = $complete[1][0];
	$info['audio'] = $data['Audio']['Format'] . '-' . $data['Audio']['Format profile'] . ' ' . $data['Audio']['Format version'];
	$info['audio_channels'] = $data['Audio']['Channel positions'];
	$info['sampling_rate'] = $data['Audio']['Sampling rate'];
	$info['bitrate'] = $data['General']['Overall bit rate'];
	$info['duration'] = $data['General']['Duration'];
	$info['video'] =  $data['Video']['Format'] . ' ' . $data['Video']['Format profile'];
	$info['width'] =  $data['Video']['Width'];
	$info['height'] =  $data['Video']['Height'];
	$info['aspect'] =  $data['Video']['Display aspect ratio'];
	$info['frame_rate'] =  $data['Video']['Frame rate'];
	$info['img_src'] = THUMB_URL . $video_dir . $id . '.ssm/preview.jpg';
	$info['transize'] = $progress * 1024;
	$info['status'] = 0;
	//print_r($sourceinfo);
	if ($complete)
	{
		$info['status'] = 1; //转码完成
		$progress = $complete;
		$info['totalsize'] = $data['General']['File size'];
	}
	else
	{
		if (!$curbitrate)
		{
			$curbitrate = 70;
		}
		else
		{
			$curbitrate = $curbitrate / 8;
		}
		$speed = $curbitrate;
		$info['totalsize'] = $sourceinfo['General']['Duration'] * $curbitrate / 1000 * 1024;
	}
	
	if ($mecoder_progress && !$info['transize'])
	{
		$info['totalsize'] = $info['totalsize'] + $sourceinfo['General']['File size'];
		$info['transize'] = intval($sourceinfo['General']['File size'] * (100 - $mecoder_progress) / 100);
		$speed = mt_rand(20, 120);
	}
	$info['speed'] = $speed;
	$info['mecoder_progress'] = $mecoder_progress;
	$mediainfo_file = $targerdir . 'media_info';
	hg_file_write($mediainfo_file, json_encode($data));
	$file = $targerdir . $id . '.mp4';
	if (is_file($file))
	{
		$mp4 = THUMB_URL . $video_dir . $id . '.ssm/' . $id . '.mp4';
	}
	hg_update_vod($info, $mp4, $video_dir . $id . '.ssm/');
	return $info;
}

function hg_send2meida($info, $file, $filepath)
{
	global $gMediaApi;
	if (!$gMediaApi['host'])
	{
		return;
	}
	$curl = new curl($gMediaApi['host'], $gMediaApi['dir'], $gMediaApi['token']);
	$curl->initPostData();
	$curl->setSubmitType('post');
	$curl->addRequestData('file', $file);
	$curl->addRequestData('filename', $info['id']);
	$curl->addRequestData('filepath', $filepath);
	$ret = $curl->request('getmp4.php');
}

function hg_update_vod($info = array(), $file, $filepath)
{
	global $gVodApi, $_INPUT;
	if (!$gVodApi['host'] || !$_INPUT['update'] || !$_INPUT['video_id'])
	{
		return;
	}
	include_once(ROOT_DIR . 'lib/curl.class.php');
	if ($file)
	{
		hg_send2meida($info, $file, $filepath);
	}
	$curl = new curl($gVodApi['host'], $gVodApi['dir'] . 'admin/', $gVodApi['token']);
	$curl->initPostData();
	$curl->setSubmitType('post');
	$curl->addRequestData('a', 'update_video_info');
	$curl->addRequestData('trans_use_time', $_INPUT['trans_use_time']);
	$curl->addRequestData('vodid', $info['id']);
	foreach ($info AS $k => $v)
	{
		$curl->addRequestData($k, $v);
	}
	$curl->addRequestData('status', $_INPUT['status']);
	$curl->addRequestData('vtype', $_INPUT['vtype']);
	$curl->addRequestData('id', $_INPUT['video_id']);
	file_put_contents('../tmp/1s.php',$_INPUT['id']);
	file_put_contents('../tmp/2s.php',json_encode($_INPUT));
	$ret = $curl->request('vod_update.php');
	//print_r($ret);
}
?>