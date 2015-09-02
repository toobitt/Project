<?php
function hg_get_rate($out)
{
	$content = file_get_contents($out);
	preg_match('/.*bitrate:\s*(\d*)\s*kb\/s/is', $content, $match);
	return $match[1];
}
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
$stream = $_INPUT['stream'];
$stream_id = $_INPUT['stream_id'];
if (!$stream)
{
	error_output('001', '未指定流地址');
}
$stream_id = $_INPUT['stream_id'];
if (!$stream_id)
{
	error_output('002', '未指定流ID');
}
/*
$cmd = FFMPEG_CMD . ' -i ' . $stream;
exec($cmd, $out, $s);
print_r($out);*/
$out = realpath(ROOT_DIR . 'tmp') . '/' . $stream_id . '.out';
$cmd = FFMPEG_CMD . ' -i ' . $stream . ' &> ' . $out . " \n";
$cmd .= 'chmod 777 ' . $out . "\n";
$filename = hg_get_cmd_file('get_bitrate_');
file_put_contents($filename, $cmd);
$time = TIMENOW;
$timesleep = 0;
while (!is_file($out) && $timesleep < 10)
{
	sleep(1);
	$timesleep = time() - $time;
}
if (is_file($out))
{
	$rate = hg_get_rate($out);
	$time = time();
	while(!$rate && $timesleep < 10)
	{
		sleep(1);
		$rate = hg_get_rate($out);
		$timesleep = time() - $time;
	}
	unlink($out);
	if (!$rate)
	{
		$rate = 0;
	}
	$ret = array(
		'stream_id'	=> $stream_id,
		'uri'	=> $stream,
		'bitrate'	=> $rate,
	);
}
else
{
	$ret = array(
		'stream_id'	=> $stream_id,
		'uri'	=> $stream,
		'bitrate'	=> 500,
	);
}
output($ret);
?>