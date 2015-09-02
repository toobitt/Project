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
$id = $_INPUT['id'];
mt_srand(1271);
echo $last_id =  mt_rand(0, 99999999999);
echo '<br />';
echo TARGET_DIR . $video_dir = hg_num2dir($last_id);
if (!$id)
{
	error_output('001', '未指定视频ID');
}
$video_dir = hg_num2dir($id);
$out = TARGET_DIR . $video_dir . $id . '.ssm/uploading';
$content = @file_get_contents($out);
if (!$content)
{
	$out = array(
		'progress' => 0	
	);
	output($out);
}
$content = explode("\n", $content);
$totalsize = $content[0];
$source = $content[1];

$progress = round(@filesize($source) / $totalsize, 4);
$target = $content[2];
$out = array(
	'progress' => $progress	
);
output($out);
?>