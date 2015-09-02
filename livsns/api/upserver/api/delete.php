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
$ids = explode(',', $id);
$info = array();
foreach ($ids AS $id)
{
	$video_dir = TARGET_DIR . hg_num2dir($id) . $id . '.ssm/';
	$delete_video_dir = TARGET_DIR . hg_num2dir($id) . 'deleted_' . $id . '.ssm/';
	$r = @rename($video_dir, $delete_video_dir);
	if (!$r)
	{
		$id = 0;
	}
	if (WOWZA_DIR)
	{
		@unlink(WOWZA_DIR . $id . '.mp4');
	}
	$info[] = $id;
}
output($info);

?>