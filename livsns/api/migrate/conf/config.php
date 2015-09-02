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
'database' => 'dev_migrate',
'charset' => 'utf8',
'pconncet' => '0',
);

define('APP_UNIQUEID','migrate');//应用标识

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


define('INITED_APP', true);

?>