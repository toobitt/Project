<?php
define('ROOT_DIR', '../');
require(ROOT_DIR . 'global.php');

/**
* 下载视频
*
* 输入: videofile  $_FILES文件流
* 返回: json
array(
	'id' => $last_id, //视频id
	'type' => $filetype， //视频类型
	'size' =>  //视频总大小
);
* cmd = 'ffmpeg -ss %s -s %dx%d "%s" -i "%s" -r 1 -vframes 1' % (snaptime, video_info['width'], video_info['height'], snapname, videoname)
**/
$source = urldecode($_INPUT['file']);
$filename = urldecode($_INPUT['filename']);
if (!$source || !$filename)
{
	error_output('001', '源文件不存在');
}
$filepath = urldecode($_INPUT['filepath']);
$target = MP4_TARGET_DIR . $filepath;
$vod_media_target = TARGET_DIR . $filepath;
hg_mkdir($target);
hg_mkdir($vod_media_target);
//file_put_contents('debug/d.txt', $source . "\n" . $target);
$path = explode('/', $source);
unset($path[(count($path) - 1)]);
$path = implode('/', $path);
if (is_file($target . 'out'))
{
	$cmd = 'mp4split -o ' . $vod_media_target . $filename . '.ismv ' . $target . $filename . '.mp4';
	$cmd .= "\n" . 'mp4split -o ' . $vod_media_target . $filename . '.ism ' . $vod_media_target . $filename . '.ismv';
	$filename = hg_get_cmd_file();
	file_put_contents($filename, $cmd);
	output(array('msg' => 'success'));
}
@unlink($target . $filename . '.mp4');
@unlink($target . 'preview.jpg');
@unlink($target . 'media_info');
$cmd = 'cd ' . $target . " \nwget " . $source;
$cmd .= " \nwget " . $path . "/preview.jpg"; 
$cmd .= " \nwget " . $path . "/media_info"; 
$cmd .= "\n" . 'mp4split -o ' . $vod_media_target . $filename . '.ismv ' . $target . $filename . '.mp4';
$cmd .= "\n" . 'mp4split -o ' . $vod_media_target . $filename . '.ism ' . $vod_media_target . $filename . '.ismv';
$cmd .= "\n" . 'chmod -Rf 777 ' . $target;
if (WOWZA_DIR)
{
	$cmd .= "\n" . 'ln -s ' . $target . $filename . '.mp4' . ' ' . WOWZA_DIR . $filename . '.mp4';
}
$filename = hg_get_cmd_file();
file_put_contents($filename, $cmd);
output(array('msg' => 'success'));

?>