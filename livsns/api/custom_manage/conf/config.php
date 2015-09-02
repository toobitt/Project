<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: config.php 6705 2012-05-14 09:48:30Z wangleyuan $
***************************************************************************/

$gDBconfig = array(
'host'     => 'db.dev.hogesoft.com',
'user'     => 'root',
'pass'     => 'hogesoft',
'database' => 'dev_custom_manage',
'charset'  => 'utf8',
'pconnect' => '',
);

define('DB_PREFIX','test_');//定义数据库表前缀
define('APP_UNIQUEID','custom_manage');//应用标识

//时间搜索
$gGlobalConfig['date_search'] = array(
	1 		=> '所有时间段',
	2 		=> '昨天',
	3 		=> '今天',
	4 		=> '最近3天',
	5 		=> '最近7天',
	'other' => '自定义时间',
);

$gGlobalConfig['install_type'] = array(
    0 => '预发布',
	1 => '发布',
);
$gGlobalConfig['source'] = array(
	1 => '加密',
	0 => '未加密',
);
$gGlobalConfig['tip_way'] = array(
    0 => '不提示',
	1 => '邮箱',
	2 => '手机短信',
);

define('INITED_APP', true);

?>