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
$a = $_INPUT['a'];
if (!in_array($a, array('view', 'start', 'stop', 'restart')))
{
	$a = 'view';
}
$func = 'hg_' . $a;
$func();

function hg_get_status()
{
	$content = @file_get_contents(TRANSCODE_STAT);
	$content = json_decode($content, true);
	$script = explode('/', TRANSCODE_SCRIPT);
	$cmd = PSCMD . $script[count($script) - 1];
	exec($cmd, $out, $t);
	$pid = intval($out[0]);
	$out = array(
		'pid' => $pid,
		'script' => $content['script'],	
		'starttime' => intval($content['starttime']),	
		'runtime' => intval($content['runtime']),
		'trans_file_num' => $content['trans_file_num'],
	);
	return $out;
}
function hg_sec2str($time, $format = '')
{
	$h = intval($time / 3600);
	$h = str_pad($h, 2, '0', STR_PAD_LEFT);
	$sec = $time % 3600;
	$m = intval($sec / 60);
	$m = str_pad($m, 2, '0', STR_PAD_LEFT);
	$sec = $sec % 60;
	return $h . '小时' . $m . '分' . $sec . '秒';
}
function hg_view()
{
	$out = hg_get_status();
	if ($_GET['view'])
	{
		if ($out['pid'])
		{
			echo $out['pid'] . ' 转码正在运行中, 已运行 ' . hg_sec2str(time() - $out['starttime']) . ',';
			echo '当前有 ' . $out['trans_file_num'] . '个视频正在转码中<br />';
			echo '<a href="?a=stop&auth=' . $_GET['auth'] . '&debug=1&view=1">停止</a><br />';
		}
		else
		{
			echo '转码已停止，<a href="?a=start&auth=' . $_GET['auth'] . '&debug=1&view=1">启动</a><br />';
		}
		echo time();
		print_r($out);
		echo '<meta http-equiv="refresh" content="3;url=?a=view&view=1&auth=' . $_GET['auth'] . '" />';
		exit;
	}
	output($out);
}

function hg_start()
{
	$stat = hg_get_status();
	if ($stat['pid'] && !$_GET['force'])
	{
		error_output('001', '转码正在运行');
	}
	$cmd = '';
	if ($pid['pid'])
	{
		$cmd .= "kill -9 " . $stat['pid'] . "\n";
	}
	if (defined('TVIE_FFMPEG_CMD') && TVIE_FFMPEG_CMD)
	{
		$cp = ' --tvieffmpeg=' . TVIE_FFMPEG_CMD;
	}
	$cmd .= 'nohup ' . TRANSCODE_SCRIPT . ' --queue=' . realpath(UPLOAD_DIR . FILE_QUEUE) . '/' . $cp . ' > ' . $stat['script'] . '.out 2>/dev/null &';
	file_put_contents('cmd/' . time(), $cmd);
	if ($_GET['view'])
	{
		header('Location:?a=view&view=1&auth=' . $_GET['auth']);
	}
	output(array('status' => 1, 'msg' => '转码启动成功'));
}

function hg_stop()
{
	$stat = hg_get_status();
	if (!$stat['pid'])
	{
		error_output('001', '转码已停止');
	}
	$cmd = 'kill -9 ' . $stat['pid'];
	file_put_contents('cmd/' . time(), $cmd);
	if ($_GET['view'])
	{
		header('Location:?a=view&view=1&auth=' . $_GET['auth']);
	}
	output(array('status' => 1,'msg' => '转码停止成功'));
}

function hg_restart()
{
}

?>