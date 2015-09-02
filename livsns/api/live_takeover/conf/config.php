<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: config.php 25795 2013-07-16 09:36:56Z lijiaying $
***************************************************************************/

$gDBconfig = array(
'host'     => 'db.dev.hogesoft.com',
'user'     => 'root',
'pass'     => 'hogesoft',
'database' => 'dev_live_takeover',
'charset'  => 'utf8',
'pconnect' => '',
);

define('APP_UNIQUEID','live_takeover');//应用标识
define('DB_PREFIX','liv_');//定义数据库表前缀
define('LIVE_CONTROL_LIST_PREVIEWIMG_URL', 'livesnap/img/');//直播控制获取截图预览

//时间搜索
$gGlobalConfig['date_search'] = array(
  1 => '所有时间段',
  2 => '昨天',
  3 => '今天',
  4 => '最近3天',
  5 => '最近7天',
  'other' => '自定义时间',
);

//时移 (小时)
$gGlobalConfig['max_time_shift'] =  '168';

define('INITED_APP', true);
?>