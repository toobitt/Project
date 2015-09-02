<?php
define('ROOT_DIR', '../');
require(ROOT_DIR . 'global.php');

/**
* 视频截图接口
*
* 输入: videofile  $_FILES文件流
* 返回: json
array(
	'id' => $last_id, //视频id
	'type' => $filetype， //视频类型
	'size' =>  //视频总大小
);
* cmd = 'ffmpeg -ss %s -s %dx%d "%s" -i "%s" -r 1 -vframes 1' % (snaptime, video_info['width'], video_info['height'], snapname, videoname)
* rewrite 
*		RewriteEngine On
*		RewriteCond %{REQUEST_URI} ^/api/snap/(.*).jpg$
*		RewriteRule ^/api/snap/(\d+)/(\d+)/([\d.]*)\-([\d.]*).jpg$ http://host/api/snap.php?stime=$2&data=1&id=$1&width=$3&height=$4 [P]
**/

$id = ($_INPUT['id']);
if (!$id)
{
	error_output('001', '未指定视频ID');
}

if (isset($_INPUT['stime']))
{
	$stime = intval($_INPUT['stime']); //开始时间 单位秒
	$stime = $stime ? $stime : 1; //开始时间 单位秒
}
$etime = intval($_INPUT['etime']); //结束时间 可以不设置
$aspect = $_INPUT['aspect'];
if ($etime && $etime < $stime)
{
	error_output('003', '结束时间设置错误');
}
$video_dir = hg_num2dir($id);
$infofile = MP4_TARGET_DIR . $video_dir . $id . '.ssm/media_info';
$infovar = 'data';
if (!is_file($infofile))
{
	$infofile = TARGET_DIR . $video_dir . $id . '.ssm/info';
	$infovar = 'info';
}
do
{
	$$infovar = @file_get_contents($infofile);
	$$infovar = json_decode($$infovar, true);
	$time++;
}
while (!is_array($info) && $time < 50);

if ($data)
{
	$info['duration'] = $data['General']['Duration'];
	$info['width'] =  $data['Video']['Width'];
	$info['height'] =  $data['Video']['Height'];
	$info['aspect'] =  $data['Video']['Display aspect ratio'];
	if (!$info['aspect'])
	{
		$info['aspect'] = '4:3';
	}
	if (!$info['width'])
	{
		$info['width'] = 400;
	}
	if (!$info['height'])
	{
		$info['height'] = 300;
	}
	$aspect = explode(':',$info['aspect']);
	$rate = $aspect[1] / $aspect[0];
	$vrate = $info['height'] / $info['width'];
	if ($rate != $vrate)
	{
		$info['height'] = intval($info['width'] * $vrate);
	}
}
if (!is_array($info))
{
	error_output('002', '未找到视频信息');
}
$filetype = $info['filetype'];
$source = TARGET_DIR . $video_dir . $id . '.ssm/' . $id . '.ismv';
if ($_INPUT['type'] == 'sou')
{
	$source = UPLOAD_DIR . $video_dir . $id . $filetype;
}
elseif ($_INPUT['type'] != 'ismv')
{
	$tmp = MP4_TARGET_DIR . $video_dir . $id . '.ssm/' . $id . '.mp4';
	if (is_file($tmp))
	{
		$source = $tmp;
	}
	else
	{
		$cmd = '/usr/local/bin/mp4split -o ' . $tmp . ' ' . $source;
		$source = UPLOAD_DIR . $video_dir . $id . $filetype;
		hg_mkdir(MP4_TARGET_DIR . $video_dir . $id . '.ssm/');
		exec($cmd);	
		if (is_file($tmp))
		{
			$source = $tmp;
		}
	}
}
if (!is_file($source))
{
	error_output('004', '视频文件未找到', $source);
}
if (!$stime)
{
	$stime = $info['Duration'] / 3;
}
if (!$etime)
{
	$etime = $stime;
}
$count = intval($_INPUT['count']); //截取图片数
$count = $count ? $count : 1;

$dir = ceil($stime / 6000) . '/';
$section = $etime - $stime ;
if ($section >= $count)
{
	$timestep = intval($section / ($count - 1));
}
else
{
	$timestep = 1;
}
$snapdir = TARGET_DIR . $video_dir . $id . '.ssm/snap/' . $dir;
hg_mkdir($snapdir);
$visit_url = THUMB_URL . $video_dir . $id . '.ssm/snap/' . $dir;
$width = intval($_INPUT['width']);
$height = intval($_INPUT['height']);
if (!$width && !$height)
{
	$width = intval($info['width']);
	$height = intval($info['height']);
}
elseif (!$height)
{
	$height = $width * $info['height'] / $info['width'];
}
elseif (!$width)
{
	$width = $height * $info['width'] / $info['height'];
}
$snaps = array();
if ($_REQUEST['data'])
{
	$visit_url = '';
}
for ($i = 0; $i < $count;  $i++)
{
	$time = $i * $timestep + $stime;
	$jpg = hg_snap($time, $snapdir, $width, $height, $source);
	$pre = $visit_url;
	if (!is_file($snapdir . $jpg))
	{
		$jpg = $width . '_fail.jpg';
		$pre = '';
	}
	$snaps[] = $pre . $jpg;
}
if ($_REQUEST['data'] && count($snaps) == 1)
{
	$file = $snapdir . $snaps[0];
	$filesize = @filesize($file);
	header('Content-Type: image/jpeg');
	if (!$filesize)
	{
		exit;
	}
	header('Cache-control: max-age=31536000');
	header('Expires: ' . gmdate("D, d M Y H:i:s", time() + 31536000) . ' GMT');
	header('Content-Disposition: inline; filename="snap.jpg"');
	header('Content-Transfer-Encoding: binary');
	header('Content-Length: ' . $filesize);
	readfile($file);
	exit;
}
else
{
	if ($_REQUEST['debug'])
	{
		//echo $source;
		foreach ($snaps AS $k => $v)
		{
			$snaps[$k] = '<img src="' . $v . '", width="300" alt="' . $v . '" /><br />' . $v;
		}
	}
	output($snaps);
}
?>