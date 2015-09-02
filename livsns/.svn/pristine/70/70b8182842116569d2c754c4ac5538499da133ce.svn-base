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
$start = intval($_INPUT['start']);
$duration = intval($_INPUT['duration']);
$mp4 = $targerdir . $id . '.mp4';
if (!is_file($mp4))
{
	$cmd = '/usr/local/bin/mp4split -o ' . $mp4 . ' ' . $targerdir . $id . '.ismv';
	exec($cmd);
}
if (!is_file($mp4))
{
	error_output('002', '原视频文件丢失');
}
$start = hg_mc_sec2format($start);
$duration = hg_mc_sec2format($duration);


$new_flv = TARGET_DIR . $video_dir . $id . '.flv';//-ss ' . $start . ' -t ' . $duration . '
$cmd = FFMPEG_CMD . ' -y -i ' . $mp4 . ' -acodec copy  -vcodec copy ' . $new_flv;
//$cmd = FFMPEG_CMD . ' -y -ss ' . $start . ' -t ' . $duration . ' -i ' . $mp4 . ' -sameq ' . $new_mp4;

exec($cmd, $out, $s);
if (!is_file($new_flv))
{
	error_output('003', 'flv视频文件未能生成');
}
$data = array('vodid' => $id, 'filepath' => $video_dir, 'filename' => $id . '.flv');
output($data);
?>