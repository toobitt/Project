<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: config.php 10096 2012-08-30 03:09:35Z qiaoyongchen $
***************************************************************************/

$gDBconfig = array('host' => 'db.dev.hogesoft.com',
'user' => 'root',
'pass' => 'hogesoft',
'database' => 'dev_mms',
'charset' => 'utf8',
'pconncet' => '0',
);
define('DB_PREFIX','liv_');//定义数据库表前缀
define("CHANNEL_IMG_MAX",2);//以M为单位
define("CHANNEL_IMG_DIR",'channel_logo/');//以M为单位
$gGlobalConfig['channel_img_size'] = array(
	"larger" =>array("label"=>"l_","width"=>113,"height"=>43),
	"small" =>array("label"=>"s_","width"=>80,"height"=>30)
);

$gGlobalConfig['program_type'] = array(
	1 => array('id' => 1, 'name' => '自办节目', 'class' => 'program_color_1'),
	2 => array('id' => 2, 'name' => '转播节目', 'class' => 'program_color_2'),
	3 => array('id' => 3, 'name' => '电视电影', 'class' => 'program_color_3'),

);//节目类型

//上传节目单
$gGlobalConfig['txt_conf'] = array('start_time','toff', 'theme', 'subtopic');
$gGlobalConfig['xml_conf'] = array('start_time','toff', 'theme', 'subtopic');

//define("WEEK_SET",true);//true 从星期一开始  false 从星期日开始

$gGlobalConfig['tvie'] = array('open' => '1',
'up_stream_server' => array('client' => 'hoge',
'outhost' => 'stream.dev.hogesoft.com',
'api_server_name' => '10.0.1.32',
'read_token' => '123456789',
'write_token' => '987654321',
'liveport' => '11105',
),
'stream_server' => array('client' => 'hoge',
'outhost' => 'live.dev.hogesoft.com',
'api_server_name' => '10.0.1.21',
'read_token' => '123456789',
'write_token' => '987654321',
'liveport' => '11105',
'append_host' => 'live1.dev.hogesoft.com,live2.dev.hogesoft.com,live3.dev.hogesoft.com,live4.dev.hogesoft.com',
),
);

$gGlobalConfig['stream_logo'] = array(
);
define('MAXWIDTH','100');//定义可以生成缩略图的原图片最小宽度，即原图的宽度必须大于此值才能生成缩略图，否则将原图直接拷贝到存放缩略图的路径下
define('MAXHEIGHT','200');//定义可以生成缩略图的原图片最小高度，即原图的高度必须大于此值才能生成缩略图，否则将原图直接拷贝到存放缩略图的路径下
define('GDIMAGEPATH','' . ROOT_PATH . 'uploads/vod/thumb/'.MAXWIDTH.'X'.MAXHEIGHT.'/');//保存缩略图的路径
define('SOURCE_IMG_DIR','' . ROOT_PATH . 'uploads/vod/');
define('IMG_DIR_DELTA','thumb/'.MAXWIDTH.'X'.MAXHEIGHT.'/');//保存缩略图的路径与保存原图路径的差别，便于后面取图的操作
define('GDIMGAPIHOST','api.dev.hogesoft.com:83');
define('GDIMG_VODDIR','livmedia/admin/');
define('GDIMGDIR','http://221.226.87.26:83/livsns/');
define('SOURCE_IMG','../livtemplates/tpl/lib/images/');// 模板中用到的小图片的资源路径

//define('SOURCE_IMAGE_PATH','http://221.226.87.26:83/livsns/uploads/vod/');//原图的资源路径
//define('SOURCE_THUMB_PATH',SOURCE_IMAGE_PATH.'thumb/'.MAXWIDTH.'X'.MAXHEIGHT.'/');//缩略图的资源路径

define('SOURCE_IMAGE_PATH','http://img.dev.hogesoft.com:83/material/vod/img/');//原图的资源路径
define('SOURCE_THUMB_PATH',SOURCE_IMAGE_PATH.MAXWIDTH.'X'.MAXHEIGHT.'/');//缩略图的资源路径


define('PREVIEW_DIR','' . ROOT_PATH . 'uploads/vod/preview/');//存放预览图片
define('PREVIEW_SOURCE','http://221.226.87.26:83/livsns/uploads/preview/');//预览图片资源路径
define('UPLOAD_BACKUP_MMS_FILE_TYPE', 'flv');
define('UPLOAD_BACKUP_MMS_URL', 'http://221.226.87.26:83/livsns/uploads/backup_mms/');//备播文件上传路径
define('MMS_CONTROL_LIST_PREVIEWIMG_URL', 'http://live.dev.hogesoft.com/api/public/media/snapshot/');
define('VIDEO_MARK_DIR','vod/video/');//标注视频接口目录
define('VIDEO_MARK_FILE','.ssm/manifest.f4m');//标注视频文件

$gGlobalConfig['keep_alive_url'] = 'http://api.dev.hogesoft.com:83/player/live/keep_alive.php';//校对时间


//视频点播类型
$gGlobalConfig['video_upload_type'] = array(
  1 => "编辑上传", 
  2 => "网友上传", 
  3 => "直播归档", 
  4 => "标注归档"
);

//节点的属性
$gGlobalConfig['video_upload_type_attr'] = array(
  1 => array('color' => '#4AA44C'), 
  2 => array('color' => '#0F9AB9'), 
  3 => array('color' => '#BF8144'), 
  4 => array('color' => '#7E4DCB')
);

//码流的十种颜色值
$gGlobalConfig['bitrate_color'] = array(
  0 => '#b2c5e5', 
  1 => '#b2c5e5',
  2 => '#b2c5e5',
  3 => '#7e9cc2',
  4 => '#4e6c9e',
  5 => '#3f5d8f',
  6 => '#2f4974',
  7 => '#2f4974',
  8 => '#2f4974',
  9 => '#2f4974'
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

//视频的状态
$gGlobalConfig['video_upload_status'] = array(
  -1 => "转码失败",
  0 => "转码中",
  1 => "待审核",
  2 => "已审核",
  3 => "被打回"
);

//视频来源的频道
$gGlobalConfig['video_channel'] = array(
  0 => "新闻综合频道",
  1 => "体育综合频道",
  2 => "娱乐综合频道",
  3 => "经济综合频道"
);

$gGlobalConfig['vod_url'] = 'http://vfile.dev.hogesoft.com/';

$gGlobalConfig['video_type'] = 'flv,3gp,mp4,mpg,avi,flv,swf,asf,mkv,mov,mpeg,rmvb,wmv,f4v';
$gGlobalConfig['flash_video_type'] = '*.flv;*.3gp;*.mp4;*.mpg;*.avi;*.swf;*.asf;*.mkv;*.mov;*.mpeg;*.rmvb;*.wmv;*.f4v;';

$gGlobalConfig['vodapi'] = array('protocol' => 'http://',
'host' => 'vapi.dev.hogesoft.com',
'dir' => 'api/',
'token' => 'aldkj12321aasd',
'port' => '80',
);
$gGlobalConfig['media_api'] = array('protocol' => 'http://',
'host' => 'media.dev.hogesoft.com',
'dir' => 'api/',
'token' => 'aldkj12321aasd',
'port' => '80',
);

$gGlobalConfig['mms_api'] = array(
	'protocol' => 'http://',
	'host' => 'api.dev.hogesoft.com',
	'dir' => 'livmedia/admin/',
	'token' => 'aldkj12321aasd',
);

$gGlobalConfig['weibo_api'] = array(
	'protocol' => 'http://',
	'host' => 'api.dev.hogesoft.com',
	'dir' => 'statuses/',
	'token' => 'aldkj12321aasd',
);
$gGlobalConfig['stream_port'] = array(1,2,3,4);

$gGlobalConfig['before_time'] = 300;//提前时间 秒

define('UPLOADAPIHOST',$gGlobalConfig['vodapi']['host']);//暂时用这样一下
define('UPLOAD_VODDIR',$gGlobalConfig['vodapi']['dir']);//暂时用这样一下

$gGlobalConfig['antileech'] = 'http://livemgmt.thmz.com/api/createOTP'; //防盗链

//频道logo
$gGlobalConfig['material_server'] = array(
	'img4' => array(
		'host' => 'http://img.dev.hogesoft.com:83/' ,
		'dir' => 'material/channel/img/' ,
	),
);

define('IMG_URL','http://img.dev.hogesoft.com:83/');//图片服务器
define('APP_UNIQUEID','channel_mms');//应用标识

$gGlobalConfig['mms'] = array(
	'open' => '1',
	'input_stream_server' => array(
		'protocol' => 'http://',
		'host' => '10.0.1.30:8086',
		'dir' => 'inputmanager/',
	),
	'output_stream_server' => array(
		'protocol' => 'http://',
		'host' => '10.0.1.30:8086',
		'dir' => 'outputmanager/',
	),
	'input' =>array(
		'wowzaip' => '10.0.1.30',
		'appName' => 'input',
		'suffix' => '.stream',
	),
	'delay' => array(
		'wowzaip' => '10.0.1.30',
		'appName' => 'input',
		'suffix' => '.delay',
	),
	'output' => array(
		'wowzaip' => '10.0.1.30:1935',
		'app_prefix' => 'delay_',
		'suffix' => '.stream',
	),
);

//0推送  1拉取
$gGlobalConfig['stream_type'] = array(
	'input' => array(
		'pull' => 0, 
		'push' => 1,
	),
	'output' => array(
		'Rtmp' => 0,
		'falsh' => 1,
		'm3u8' => 2,
		'flash_m3u8' => 3,
		'smooth' => 4,
	),
);

?>