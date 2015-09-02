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
	'database' => 'dev_travel',
	'charset'  => 'utf8',
	'pconncet' => 0,
);
define('APP_UNIQUEID','travel');//定义应用
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
//状态
$gGlobalConfig['status'] = array(
-1=>'所有状态',
0=>'待审核',
1=>'已审核',
);
//上传的图片格式
$gGlobalConfig['pic_types'] = array(
	'.jpeg',
	'.gif',
	'.bmp',
	'.jpg'
	);

$gGlobalConfig['appmod'] = array(
	'news' => array('app'=>'news','mod'=>'news'),
	'tuji' => array('app'=>'tuji','mod'=>'tuji'),
	);

$gGlobalConfig['app_uniqued'] = array(
	'app' => 'travel',
	'mod' => 'travel',
	);
define('CITY_NAME','无锡');//定义城市
?>