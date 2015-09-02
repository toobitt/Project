<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: config.php 19931 2013-04-08 06:42:13Z lijiaying $
***************************************************************************/

$gDBconfig = array(
	'host' => 'db.dev.hogesoft.com',
	'user' => 'root',
	'pass' => 'hogesoft',
	'database' => 'dev_old_live',
	'charset' => 'utf8',
	'pconncet' => '0',
);

define('APP_UNIQUEID','old_live');//应用标识

define('DB_PREFIX','liv_');//定义数据库表前缀

define('MEMBER_PLAN_SET_ID', 89); //发布系统配置ID

define('MMS_CONTROL_LIST_PREVIEWIMG_URL', 'http://img.dev.hogesoft.com:233/livesnap/img/');//直播控制获取截图预览

define('IS_WOZA', false); //录制系统默认

define('BACKUP_PATH', 'cache/');	//本地上传备播文件临时目录

$gGlobalConfig['channel_snap_interval'] = 1;//频道每秒截图数量
$gGlobalConfig['program_property'] = array(
  'zhuchiren' => array(
  	'title' => '主持人',
  	'host' => '',
  	'dir' => '',
  	'filename' => '',
  ),
  'daobo' => array(
  	'title' => '导播',
  	'host' => '',
  	'dir' => '',
  	'filename' => '',
  ),
);

$gGlobalConfig['program_type'] = array(
	1 => array('id' => 1, 'name' => '自办节目', 'class' => 'program_color_1'),
	2 => array('id' => 2, 'name' => '转播节目', 'class' => 'program_color_2'),
	3 => array('id' => 3, 'name' => '电视电影', 'class' => 'program_color_3'),

);//节目类型

//上传节目单
$gGlobalConfig['txt_conf'] = array('start_time','toff', 'theme', 'subtopic');
$gGlobalConfig['xml_conf'] = array('start_time','toff', 'theme', 'subtopic');
//视频点播类型
$gGlobalConfig['video_upload_type'] = array(
  1 => "编辑上传", 
  2 => "网友上传", 
  3 => "直播归档", 
  4 => "标注归档"
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

$gGlobalConfig['stream_port'] = array(1,2,3,4);

$gGlobalConfig['before_time'] = 300;//提前时间 秒


$gGlobalConfig['mms'] = array(
	'open' => '1',
	'input_stream_server' => array(//主控输入
		'protocol' => 'http://',
		'host' => 'live.hoge.cn:8086',
		'dir' => 'inputmanager/',
	),
	'schedul_stream_server' => array(//主控切播
		'protocol' => 'http://',
		'host' => 'live.hoge.cn:8086',
		'dir' => 'inputmanager/',
	),
	
	/*
	'record_server' => array(//录制
		'protocol' => 'http://',
		'host' => 'live.hoge.cn:8086',
		'dir' => 'recordmanager/',
	),
	*/
	
	'record_server' => array(//录制
		'protocol' => 'http://',
		'host' => '10.0.1.58:8089',
		'dir' => '',
	),
	'record_server_callback' => array(//wowza抓取时移
		'protocol' => 'http://',
		'host' => '10.0.1.58',
		'dir' => 'mediauploads/media/',
		'prefix' => 'schedul_',
	),
	'output_stream_server' => array(//时移(输出)
		'protocol' => 'http://',
		'host' => 'live.hoge.cn:8086',
		'dir' => 'outputmanager/',
	),
	/*
	'live_stream_server' => array(//直播
		'protocol' => 'http://',
		'host' => '10.0.1.20:8086',
		'dir' => 'outputmanager/',
	),
	*/
	'input' =>array(
		'protocol' => 'rtmp://',
		'wowzaip' => 'live.hoge.cn', //前台js调用
		'type' => 'push',
		'appName' => 'input',
		'suffix' => '.stream',
	),
	//备播信号形成list流
	'file' =>array(
		'protocol' => 'rtmp://',
		'wowzaip' => 'live.hoge.cn',
		'appName' => 'file',
		'suffix' => '.list',
	),
	//备播文件
	'output_file' =>array(
		'protocol' => 'rtmp://',
		'wowzaip' => 'live.hoge.cn',
		'appName' => 'file',
		'prefix' => 'mp4:',
		'fileNamePrefix' => 'vod_',
		'suffix' => '.mp4',
	),
	'delay' => array(
		'wowzaip' => 'live.hoge.cn',
		'appName' => 'input',
		'suffix' => '.delay',
	),
	'output' => array(
		'wowzaip' => 'live.hoge.cn:1935',
		'suffix' => '.stream',
	),
	'_output' => array(
		'wowzaip' => 'live20.dev.hogesoft.com:1935',
		'suffix' => '.stream',
	),
	'chg' => array(
		'wowzaip' => 'live.hoge.cn:1935',
		'appName' => 'input',
		'suffix' => '.output',
	),
	'chg_append_host' => array(
		'1' => 'live.hoge.cn:1935',
		'2' => 'live.hoge.cn:1935',
		'3' => 'live.hoge.cn:1935',
		'4' => 'live.hoge.cn:1935',
	),
	'_chg_append_host' => array(
		'1' => 'live201.dev.hogesoft.com:1935',
		'2' => 'live202.dev.hogesoft.com:1935',
		'3' => 'live203.dev.hogesoft.com:1935',
		'4' => 'live204.dev.hogesoft.com:1935',
	),
);

//媒体服务器配置
$gGlobalConfig['wowza'] = array(
	'counts' 		=> 100,
	'in_port'		=> 8086,
	'out_port'		=> 1935,
	'record_port'	=> 8089,
	'core_input_server' => array(//主控
		'protocol' 		=> 'http://',
		'host' 			=> 'live.hoge.cn',
		'port'			=> '8086',
		'input_dir'		=> 'inputmanager/',
		'output_dir'	=> 'outputmanager/',
	),
	'dvr_output_server' => array(//时移
		'protocol' 		=> 'http://',
		'host' 			=> 'live.hoge.cn',
		'port'			=> '8086',
		'output_dir' 	=> 'outputmanager/',
	),
	/*
	'live_output_server' => array(//直播
		'protocol' 		=> 'http://',
		'host' 			=> '10.0.1.20',
		'port'			=> '8086',
		'output_dir' 	=> 'outputmanager/',
	),
	*/
	'record_server' => array(//录制
		'protocol' 		=> 'http://',
		'host' 			=> '10.0.1.58:8089',
		'dir' 			=> '',
	),
	//输入的输入
	'input' =>array(
		'protocol' 	=> 'rtmp://',
		'type' 		=> 'push',
		'app_name' 	=> 'input',
		'suffix' 	=> '.stream',
	),
	//输入的输出 1935
	'chg' => array(
		'protocol' 	=> 'rtmp://',
		'app_name' 	=> 'input',
		'suffix' 	=> '.output',
	),
	//备播信号形成list流
	'list' =>array(
		'protocol' 	=> 'rtmp://',
		'app_name' 	=> 'file',
		'suffix' 	=> '.list',
	),
	//备播文件
	'backup' =>array(
		'protocol' 	=> 'rtmp://',
		'app_name' 	=> 'file',
		'prefix'	=> 'mp4:',
		'midfix' 	=> 'vod_',
		'suffix' 	=> '.mp4',
	),
	//延时
	'delay' => array(
		'protocol' 	=> 'rtmp://',
		'app_name' 	=> 'input',
		'suffix' 	=> '.delay',
	),
	//dvr输出 1935
	'dvr_output' => array(
		'protocol' 	=> 'rtmp://',
		'suffix' 	=> '.stream',
	),
	//直播输出 1935
	'live_output' => array(
		'protocol' 	=> 'rtmp://',
		'suffix' 	=> '.stream',
	),
	//录制
	'record' => array(//wowza抓取时移
		'protocol' 	=> 'http://',
		'host' 	=> '10.0.1.58',
		'dir' 	=> 'mediauploads/media/',
		'prefix' 	=> 'schedul_',
	),
	'dvr_append_host' => array(
		'1' => '1live.hoge.cn',
		'2' => '2live.hoge.cn',
		'3' => '3live.hoge.cn',
		'4' => '4live.hoge.cn',
	),
	'live_append_host' => array(
		'1' => 'live201.dev.hogesoft.com',
		'2' => 'live202.dev.hogesoft.com',
		'3' => 'live203.dev.hogesoft.com',
		'4' => 'live204.dev.hogesoft.com',
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

//信号流 选择 备播文件 时 分页参数
$gGlobalConfig['stream2BackupCount'] = 20;
//串联单 直播频道 备播信号 备播文件 分页参数 
$gGlobalConfig['channelChgPlan2BackupCount'] = 21;
$gGlobalConfig['channelChgPlan2ChannelCount'] = 12;
$gGlobalConfig['channelChgPlan2StreamCount'] = 12;
//初始化 直播控制 信号流 备播文件 数目
$gGlobalConfig['mmsControl2StreamCount'] = 20;
$gGlobalConfig['mmsControl2BackupCount'] = 7;

//时移、延时
$gGlobalConfig['max_save_time'] = 168;
$gGlobalConfig['max_live_delay'] = 300;

//切播层启动停止延时时间 (秒)
$gGlobalConfig['chg_sleep_time'] = 3;
$gGlobalConfig['dvr_sleep_time'] = 3;

//屏蔽节目
$gGlobalConfig['shield'] = array(
	'toff' 		=> 600,	//秒
	'offset'	=> 300,	//秒
);

//本地上传备播文件大小 单位 M
$gGlobalConfig['backup_file_size'] = 500;
$gGlobalConfig['backup_file_toff'] = 300;	//秒
$gGlobalConfig['backup_status'] = array(
	'1' => '已上传',
	'2' => '上传失败',
	'3' => '上传中',
);

define('INITED_APP', true);
?>