<?php
	$gDBconfig = array(
	'host' => 'db.dev.hogesoft.com',
	'user' => 'root',
	'pass' => 'hogesoft',
	'database' => 'dev_hogecloud',
	'charset' => 'utf8',
	'pconncet' => '0',
);
define('APP_UNIQUEID','hogecloud');//应用标识
define('DB_PREFIX','liv_');//定义数据库表前缀
define('INITED_APP', true);
//时间搜索
$gGlobalConfig['date_search'] = array(
  1 => '所有时间段',
  2 => '昨天',
  3 => '今天',
  4 => '最近3天',
  5 => '最近7天',
  'other' => '自定义时间',
);

/*********** 厚建云 *************/
$gGlobalConfig['hogecloud_index'] = 'http://i.hogecloud_local.com/index/custom/index';
$gGlobalConfig['hogecloud_bound'] = 'http://i.hogecloud_local.com/index/custom/bound';
/*********** 厚建云 *************/
?>