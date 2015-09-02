<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: config.php 5165 2011-11-26 08:45:05Z repheal $
***************************************************************************/

$gDBconfig = array(
	'host' => 'db.dev.hogesoft.com',
	'user' => 'root',
	'pass' => 'hogesoft',
	'database' => 'dev_video_split',
	'charset' => 'utf8',
	'pconncet' => '0',
);
define('APP_UNIQUEID','video_split');//应用标识
define('DB_PREFIX','liv_');//定义数据库表前缀

$gGlobalConfig['date_search'] = array(
  1 => '所有时间段',
  2 => '昨天',
  3 => '今天',
  4 => '最近3天',
  5 => '最近7天',
  'other' => '自定义时间',
);
$gGlobalConfig['live_time_shift_open'] = 1;//直播时移开关
$gGlobalConfig['live_time_shift_timeout'] = 30;//时移超时时间
$gGlobalConfig['live_time_shift_max_time'] = 30;//直播拆条最大时间；单位:分钟
$gGlobalConfig['live_time_shift_data_timeout'] = 1;//直播拆条已完成历史数据保留时间(存在视频库和时移的数据)单位:小时
define('INITED_APP', true);
?>