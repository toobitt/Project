<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
//配置视频库的数据库
$gDBconfig = array(
	'host' => 'db.dev.hogesoft.com',
	'user' => 'root',
	'pass' => 'hogesoft',
	'database' => 'dev_media',
	'charset' => 'utf8',
	'pconncet' => '0',
);


define('DB_PREFIX','liv_');
define('APP_UNIQUEID','mediaserver');//应用标识
define('UPLOAD_DIR', '/web/mediauploads/media/');
define('TARGET_DIR', '/web/mediauploads/mp4/');
define('FTP_UPLOAD_DIR', '/web/mediauploads/');
define('PICK_UP_DIR', '/web/mediauploads/');
define('MEDIAINFO_CMD', '/usr/local/bin/mediainfo');
define('FFMPEG_CMD', '/usr/local/bin/ffmpeg');
define('MP4SPLIT_CMD', '/usr/local/bin/mp4split  -o ');
define('FFMPED2TS_CMD', '/usr/local/ffmpeg2.0.1/bin/ffmpeg');
define('TS_DURATION', 10); //ts片段长度
define('APPID', '55');
define('APPKEY', 'GLtPX7N7ijwb83wupXuIrEl1YvIeBbm7');
define('SOURCE_VIDEO_DOMIAN','http://10.0.1.58/mediauploads/media/');//源视频目录访问域名（内部使用）
define('TARGET_VIDEO_DOMAIN','vfile1.dev.hogesoft.com:233');//目标视频目录访问域名
define('WATER_POS','0,0');//水印默认位置
define('MORE_BITRATE_SERVER',0);//指定多码流转码服务器
define('MANDATORY_SERVER',0);//制定强制转码服务器
define('NOT_CREATE_ISMV',0);
define('SNAP_PIC_POS',3);//截图选取的视频的位置默认是1/3处
define('PUBLISH_SET_ID',25);
define('IS_TRANSCODE_URL',1); //url提交视频是否需要转码

//视频上传到转码服务器之后转码完成之后的回调配置
define('MAX_CLOUD_DELETE', 10);
define('MAX_CLOUD_DELETE_TIME', 36);
$gGlobalConfig['App_mediaserver']['filename'] = 'video.php';
$gGlobalConfig['video_cloud'] = '';
$gGlobalConfig['video_cloud_delete'] = 2; //1全部 2转码服务器上的原文件 3转码之后的文件;
$gGlobalConfig['video_cloud_title'] = '乐视云';
$gGlobalConfig['video_file_cloud'] = 2;//1直接上传 2转码服务器上的原文件 3转码之后的文件
$gGlobalConfig['cloud_user'] = '';
$gGlobalConfig['cloud_secret_key'] = '';

//视频类型配置
$gGlobalConfig['video_type'] = array(
	'allow_type' => '.wmv,.avi,.dat,.asf,.rm,.rmvb,.ram,.mpg,.mpeg,.3gp,.mov,.mp4,.m4v,.dvix,.dv,.dat,.mkv,.flv,.vob,.ram,.qt,.divx,.cpk,.fli,.flc,.mod,.m4a,.f4v,.3ga,.caf,.mp3,.vob,.aac,.amr,.ts'
);

//时间搜索
$gGlobalConfig['date_search'] = array(
  1 => '所有时间段',
  2 => '昨天',
  3 => '今天',
  4 => '最近3天',
  5 => '最近7天',
  'other' => '自定义时间',
);

//设置视频头信息
$gGlobalConfig['metadata'] = array(
	'album' 			=> '',
	'artist' 			=> '',
	'comment' 			=> '',
	'composer' 			=> '',
	'copyright' 		=> '',
	'creation_time' 	=> '',
	'genre' 			=> '',
	'title' 			=> '',
);

define('INITED_APP', true);
?>