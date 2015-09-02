<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: config.php 6831 2012-05-29 00:55:16Z repheal $
***************************************************************************/
$gDBconfig = array(
	'host'     => 'db.dev.hogesoft.com',
	'user'     => 'root',
	'pass'     => 'hogesoft',
	'database' => 'dev_statistics',
	'charset'  => 'utf8',
	'pconncet' => 0,
);

define('DB_PREFIX','liv_');//定义数据库表前缀
define('APP_UNIQUEID','statistics');

$gGlobalConfig['stat_app'] = 'news,tuji,media_channel,livmedia,live,webvod,contribute';

$gGlobalConfig['status'] = array(
	'1'=>'启用',
	'2'=>'未启用',
	);

//时间搜索
$gGlobalConfig['date_search'] = array(
  -1 => '所有时间段',
  2 => '昨天',
  3 => '今天',
  4 => '最近3天',
  5 => '最近7天',
  'other' => '自定义时间',
);

$gGlobalConfig['statistics_type']=array(
	'insert' => 1,
	'update' => 2,
	'delete' => 3,
	'verify_suc' => 4,
	'verify_fail' => 5,
	'verify_pass' => 6,
);

$gGlobalConfig['statistics_type_cn']=array(
	1 => '插入',
	2 => '修改',
	3 => '删除',
	4 => '审核通过',
	5 => '审核打回',
);

define('INITED_APP', true);
?>