<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: config.php 32028 2013-11-28 05:18:11Z tong $
***************************************************************************/

$gDBconfig = array(
	'host' => 'db.dev.hogesoft.com',
	'user' => 'root',
	'pass' => 'hogesoft',
	'database' => 'dev_live_control',
	'charset' => 'utf8',
	'pconncet' => '0',
);

define('APP_UNIQUEID','live_control');//应用标识
define('DB_PREFIX','liv_');//定义数据库表前缀
define('LIVE_CONTROL_LIST_PREVIEWIMG_URL', 'livesnap/img/');//直播控制获取截图预览
define('BACKUP_PATH', 'cache/');//本地上传备播文件临时目录

//检测备播信号时间间隔 秒
$gGlobalConfig['live_control_auto'] = 1800;
$gGlobalConfig['wowza_output_id'] = array(
	20 => array('output_id' => 11, 'stream_id' => 88)
);

$gGlobalConfig['server_info'] = array(
	'host' 	=> '10.0.1.25:8086',
	'input_dir' 	=> 'inputmanager/',
);
//媒体服务器
$gGlobalConfig['wowza'] = array(
	'input' =>array(
		'app_name' 	=> 'input',
		'suffix' 	=> '.stream',
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


define('INITED_APP', true);
?>