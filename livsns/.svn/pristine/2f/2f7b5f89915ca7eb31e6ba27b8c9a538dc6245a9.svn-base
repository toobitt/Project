<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: config.php 19895 2013-04-08 02:42:01Z lijiaying $
***************************************************************************/

$gDBconfig = array(
'host'     => 'db.dev.hogesoft.com',
'user'     => 'root',
'pass'     => 'hogesoft',
'database' => 'dev_live',
'charset'  => 'utf8',
'pconnect' => '',
);

define('APP_UNIQUEID','live');//应用标识
define('DB_PREFIX','liv_');//定义数据库表前缀
define('LIVE_CONTROL_LIST_PREVIEWIMG_URL', 'livesnap/img/');//直播控制获取截图预览
define('PRIVATE_TOKEN','sdfafad');//防盗链token
$gGlobalConfig['live_expire'] = '7200';
$gGlobalConfig['sign_type'] = 'upyun'; //m2o / upyun /chinanet1 /chinanet2
$gGlobalConfig['channel_table_limit'] = '1'; //设置多少个频道共用一张dvr表
$gGlobalConfig['limit_referer'] = '';
//时间搜索
$gGlobalConfig['date_search'] = array(
  1 => '所有时间段',
  2 => '昨天',
  3 => '今天',
  4 => '最近3天',
  5 => '最近7天',
  'other' => '自定义时间',
);

//媒体服务器
/*
$gGlobalConfig['wowza'] = array(
	'counts'		=> 100,							//信号数目
	'live_input_port'		=> '8086',				//输入端口
	'live_output_port'		=> '1935',				//输出端口
	'record_input_port'		=> '8086',				//录制配置 输入端口
	'record_output_port'	=> '1935',				//录制配置 输出端口
	'live_server'	=> array(
		'protocol' 		=> 'http://',
		'input_dir'		=> 'inputmanager/',
		'output_dir'	=> 'outputmanager/',
		'input_port'	=> '8086',
		'output_port'	=> '1935',
	),
	'input' =>array(
		'app_name' 	=> 'input',
		'suffix' 	=> '.stream',					//suffix后缀
	),
	//延时
	'delay' => array(
		'app_name' 	=> 'input',
		'suffix' 	=> '.delay',
	),
	//切播 1935
	'change' => array(
		'app_name' 	=> 'input',
		'suffix' 	=> '.output',
	),
	//输出 1935
	'output' => array(
		'suffix' 	=> '.stream',
	),
	//备播信号形成list流
	'list' =>array(
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
);
*/
//时移 (小时)、延时 (秒)
$gGlobalConfig['max_time_shift'] =  '168';
$gGlobalConfig['max_delay'] = 300;

//ts输出数目
$gGlobalConfig['ts_num'] = 3;

//切播层启动停止延时时间 (秒)
$gGlobalConfig['change_sleep_time'] = 3;
$gGlobalConfig['dvr_sleep_time'] = 3;

//默认屏蔽节目时长
$gGlobalConfig['shield_toff'] = 600;

//缓存屏蔽节目目录
$gGlobalConfig['program_shield_dir'] = 'program_shield';

//缓存alive文件名
$gGlobalConfig['alive_filename'] = 'alive';
$gGlobalConfig['bantype'] = 'player';

//tvie配置
/*
$gGlobalConfig['tvie'] = array(
	'app_name' 		=> 'live',
	'api_token_dir'	=> 'server/api_token/',
	'tvie_server' => array(
		'protocol'		=> 'http://',
		'api_dir' 		=> 'mediaserver/media/live/',
		'api_port' 		=> '80',
		'server_dir' 	=> 'mediaserver/service/',
		'server_port' 	=> '10080',
	),
	'pubpoints'	=> array(	//发布点
		'protocol'	=> 'http://',
		'host'		=> '127.0.0.1',
		'port'		=> '10080',
		'dir'		=> 'live/',
		'suffix'	=> 'live.ismv',
	),
);
*/
/*
$gGlobalConfig['live'] = array(
	'nginx'	=>array('input_dir'=>'control/', 'output_dir'=>'live'),
	'tvie'	=>array(),
	'wowza'	=>array(),
);
*/
//服务器类型 
$gGlobalConfig['server_type'] = array(
//	'wowza'	=> 'WOWZA',
//	'tvie'	=> 'TVIE',
	'nginx'	=> 'NGINX',									//增加nginx选项
);

//live服务器信息
/*
$gGlobalConfig['live_server'] = array(
	'host' => '10.0.1.20',
	'dir' => 'control',								
);
*/
define('INITED_APP', true);

define('OUTPUT_PORT', 80);

//define('wowza', true);
//用于控制是否具有播控和串联单功能
$gGlobalConfig['schedule_control_wowza'] =  array (
  'host' => '10.0.1.25:8086',
  'inputdir' => 'inputmanager/',
  'is_wowza' => 0,
);
$gGlobalConfig['live_time_shift'] =  '4';
?>