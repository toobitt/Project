<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: config.php 1120 2010-12-24 05:19:23Z develop_tong $
***************************************************************************/

//数据库配置

$gDBconfig = array(
'host'     => 'db.dev.hogesoft.com',
'user'     => 'odev',
'pass'     => 'odev@hoge',
'database' => 'odev_cityparty',
'charset'  => 'utf8',
'pconnect' => '0',
);

//定义数据库表前缀
define('DB_PREFIX','m2o_');  
define('APP_UNIQUEID','hoolo_cityparty');//应用标识
define('MAX_RECORD_NUM','500'); //最多保留多少记录
$gGlobalConfig['jwd'] = array(
	'wd' =>  array(
		'min' => '0',
		'max' => '90',
	),
	'jd' =>  array(
		'min' => '0',
		'max' => '180',
	),
);
//状态搜索
$gGlobalConfig['status']=array(
	1 =>'全部状态',
	2 =>'待审核',
	3 =>'已审核',
	4 =>'已打回',
);
define('INITED_APP', true);
?>