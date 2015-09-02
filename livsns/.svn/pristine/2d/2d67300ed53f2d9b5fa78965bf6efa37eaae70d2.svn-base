<?php
define('ROOT_DIR', '../');
require(ROOT_DIR . 'global.php');
define ('PSCMD', '/usr/bin/pgrep ');

set_time_limit(0);
/**
* 转码控制接口
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
output(hg_get_status());

function hg_get_status()
{
	$cmd = PSCMD . 'control.py';
	//$cmd = 'ls -l';
	exec($cmd, $out, $t);
	if ($_GET['debug'])
	{
		print_r($out);
	}
	$pid = intval($out[0]);
	$out = array(
		'pid' => $cmd,
		'script' => 'control.py',	
	);
	return $out;
}

?>