<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: config.php 8670 2012-08-01 05:45:42Z hanwenbin $
***************************************************************************/

$gDBconfig = array(
'host'     => 'db.dev.hogesoft.com',
'user'     => 'root',
'pass'     => 'hogesoft',
'database' => 'dev_recycle',
'charset'  => 'utf8',
'pconnect' => '',
);

define('DB_PREFIX','liv_');//定义数据库表前缀
define('ISOPEN',1); //是否开启回收站
define('EXPIRED',TIMENOW + 86400*30);  //过期时间，一个月
define('APP_UNIQUEID','recycle');


define('CLEAR_DATE', 50);  //单位天，清除此时间之前未审核的内容



//时间搜索
$gGlobalConfig['date_search'] = array(
  1 => '所有时间段',
  2 => '昨天',
  3 => '今天',
  4 => '最近3天',
  5 => '最近7天',
  'other' => '自定义时间',
);


define('INITED_APP', true);
?>