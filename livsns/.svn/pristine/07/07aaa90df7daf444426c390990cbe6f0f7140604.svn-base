<?php
define('ROOT_DIR', '../');
require(ROOT_DIR . 'global.php');
require(ROOT_DIR . 'lib/mediainfo.class.php');
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
$filepath = $_INPUT['file'];
if (!is_file($filepath))
{
	$id = $_INPUT['id'];
	$filetype = $_INPUT['filetype'];
	if (!$id)
	{
		error_output('001', '未指定视频ID');
	}
	$video_dir = hg_num2dir($id);
	$filepath = UPLOAD_DIR .  $video_dir . $id . $filetype;
}
$mediainfo = new mediainfo($filepath);
$data = $mediainfo->getMeidaInfo();
output($data);
?>