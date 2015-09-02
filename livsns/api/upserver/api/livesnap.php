<?php
define('ROOT_DIR', '../');
require(ROOT_DIR . 'global.php');

/**
* 视频截图接口
*
* 输入: videofile  $_FILES文件流
* 返回: json
array(
	'id' => $last_id, //视频id
	'type' => $filetype， //视频类型
	'size' =>  //视频总大小
);
* cmd = 'ffmpeg -ss %s -s %dx%d "%s" -i "%s" -r 1 -vframes 1' % (snaptime, video_info['width'], video_info['height'], snapname, videoname)
* rewrite 
*		RewriteEngine On
*		RewriteCond %{REQUEST_URI} ^/api/snap/(.*).jpg$
*		RewriteRule ^/api/snap/(\d+)/(\d+)/([\d.]*)\-([\d.]*).jpg$ http://host/api/snap.php?stime=$2&data=1&id=$1&width=$3&height=$4 [P]
**/

$stram = ($_INPUT['stream']);
$width = intval($_INPUT['width']);
$height = intval($_INPUT['height']);
$stram = $stram ? $stram : 'rtmp://10.0.1.30:1935/pd1/cd.stream';
$width = $width ? $width : 400;
$height = $height ? $height : 300;
$jpg = realpath('../tmp/') . '/' . md5($stram) . '.png';
$cmd = FFMPEG_CMD . ' -s ' . $width .'x' . $height . ' -y "' . $jpg . '" -i "' . $stram . '" -vframes 1';
if ($_INPUT['debug'])
{
	echo $cmd;
}
exec($cmd, $out, $s);
$pre = $visit_url;
if (!is_file($snapdir . $jpg))
{
	$jpg = $width . '_fail.jpg';
	$pre = '';
}
$file = $snapdir . $snaps[0];
$filesize = @filesize($jpg);
header('Content-Type: image/png');
if (!$filesize)
{
	exit;
}
header('Cache-control: max-age=31536000');
header('Expires: ' . gmdate("D, d M Y H:i:s", time() + 31536000) . ' GMT');
header('Content-Disposition: inline; filename="snap.jpg"');
header('Content-Transfer-Encoding: binary');
header('Content-Length: ' . $filesize);
readfile($jpg);
exit;
?>