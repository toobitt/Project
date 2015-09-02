<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: config.php 15986 2013-03-21 14:28:00 jeffrey $
***************************************************************************/

$gDBconfig = array(
'host'     => 'db.dev.hogesoft.com',
'user'     => 'root',
'pass'     => 'hogesoft',
'database' => 'dev_webapp',
'charset'  => 'utf8',
'pconnect' => '',
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

define('APP_UNIQUEID','webapp');//应用标识
define('DB_PREFIX','liv_');//定义数据库表前缀
define('UNKNOW', '未知错误'); //未知错误
define('OBJECT_NULL','数据缺失');

define('IS_REPEAT', 1);    //是否重复
define('TIMEOUT', 300);    //间隔时间


define('INITED_APP', true);
?>