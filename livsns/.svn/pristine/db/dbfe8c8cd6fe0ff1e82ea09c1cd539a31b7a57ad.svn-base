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
$id = $_INPUT['id'];
if (!$id)
{
	error_output('001', '未指定视频ID');
}
$video_dir = hg_num2dir($id);
echo $targerdir = TARGET_DIR . $video_dir . $id . '.ssm/' . $id . '.mp4';
?>