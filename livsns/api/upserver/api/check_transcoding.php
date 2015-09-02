<?php
define('ROOT_DIR', '../');
require(ROOT_DIR . 'global.php');

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
$content = @file_get_contents(TRANSCODE_STAT);
$info = json_decode($content, true);
if($info['files'])
{
	$script = explode('/', TRANSCODE_SCRIPT);
	$cmd = PSCMD . $script[count($script) - 1];
	exec($cmd, $out, $t);
	$pid = intval($out[0]);
	if (!$pid)
	{
		include(ROOT_DIR . 'lib/mediainfo.class.php');
		$mediainfo = new mediainfo();
		foreach ($info['files'] AS $filepath)
		{
			if (!is_file(UPLOAD_DIR . $filepath))
			{
				continue;
			}
			$mediainfo->setFile(UPLOAD_DIR . $filepath);
			$data = $mediainfo->getMeidaInfo();
			if (!$data)
			{
				continue;
			}
			$id = explode('/', $filepath);
			$id = $id[count($id) - 1];
			$id = explode('.', $id);
			$id = $id[0];
			hg_file_write(UPLOAD_DIR . FILE_QUEUE . $id, $filepath);
		}
	}
}
output(array('sucess' => 1));
?>