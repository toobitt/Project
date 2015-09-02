<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: config.php 5165 2011-11-26 08:45:05Z repheal $
***************************************************************************/

$gDBconfig = array(
'host'     => 'db.dev.hogesoft.com',
'user'     => 'root',
'pass'     => 'hogesoft',
'database' => 'dev_servermonitor',
'charset'  => 'utf8',
'pconnect' => '',
);
define('APP_UNIQUEID','servermonitor');//应用标识
define('DB_PREFIX','liv_');//定义数据库表前缀

//时间搜索
$gGlobalConfig['date_search'] = array(
  1 => '所有时间段',
  2 => '昨天',
  3 => '今天',
  4 => '最近3天',
  5 => '最近7天',
  'other' => '自定义时间',
);

//服务的状态
$gGlobalConfig['status'] = array(
  0 => '无',
  1 => '已启动',
  2 => '已停止',
);

//程序类别
$gGlobalConfig['program_type'] = array(
  1 => '主程序',
  2 => '应用程序',
);


define('INITED_APP', true);
?>