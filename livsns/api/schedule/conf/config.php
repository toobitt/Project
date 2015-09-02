<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: config.php 32768 2013-12-17 03:36:59Z zhuld $
***************************************************************************/

$gDBconfig = array(
'host'     => 'db.dev.hogesoft.com',
'user'     => 'root',
'pass'     => 'hogesoft',
'database' => 'dev_schedule',
'charset'  => 'utf8',
'pconnect' => '',
);

define('APP_UNIQUEID','schedule');//应用标识
define('DB_PREFIX','liv_');//定义数据库表前缀

//串联单离当前时间差 单位:秒
$gGlobalConfig['schedule_time'] = -300;

//录制服务器
$gGlobalConfig['record_server'] = array(
	'protocol' 	=> 'http://',
	'dir' 		=> '',
	'port'		=> 8089,
);
$gGlobalConfig['record'] = array(
	'protocol' 	=> 'http://',
	'prefix' 	=> 'schedul_',
);
$gGlobalConfig['server_info'] =  array (
  'host' => '10.0.1.25:8086',
  'input_dir' => 'inputmanager/',
);
//串联单生成节目单 最小时长 分钟
$gGlobalConfig['schedule2program_min_toff'] = 1;

define('INITED_APP', true);
?>