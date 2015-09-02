<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: config.php 17931 2013-02-26 01:34:49Z lijiaying $
***************************************************************************/

$gDBconfig = array('host' => 'db.dev.hogesoft.com',
'user' => 'root',
'pass' => 'hogesoft',
'database' => 'dev_program_record',
'charset' => 'utf8',
'pconncet' => '0',
);

define('APP_UNIQUEID','program_record');//应用标识

define('DB_PREFIX','liv_');//定义数据库表前缀

define('IS_WOZA', false); //录制系统默认

define('CLEAR_LOG_BEFORE_TIME',5);//清除多少天前的收录日志

//时间搜索
$gGlobalConfig['date_search'] = array(
  1 => '所有时间段',
  2 => '昨天',
  3 => '今天',
  4 => '最近3天',
  5 => '最近7天',
  'other' => '自定义时间',
);

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


define('INITED_APP', true);
?>