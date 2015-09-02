<?php
define('ROOT_DIR', '../');
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
$video_dir = hg_num2dir($id) . $id . '.ssm/';
$mp4 = TARGET_DIR . $video_dir . $id . '.mp4';
$ismv = TARGET_DIR . $video_dir . $id . '.ismv';

if (!is_file($mp4) && is_file($ismv)) 
{ 
	$cmd = '/usr/local/bin/mp4split -o ' . $mp4 . ' ' . $ismv;
	hg_mkdir(TARGET_DIR . $video_dir);
	exec($cmd);	
}
if (!is_file($mp4)) 
{ 
	error_output('002', '视频文件不存在');
}
else
{
	if ($_INPUT['a'] == 'geturl')
	{
		$arr = array('url' => THUMB_URL . $video_dir . $id . '.mp4');
		echo json_encode($arr);
		exit;
	}
	$filesize = filesize($mp4);
	header("Content-Type: application/force-download");
	header("Content-Transfer-Encoding: binary\n");
	header('Content-Length: ' . $filesize);
	readfile($mp4);
}
?>