<?php
$gDBconfig = array(
'host'     => 'db.dev.hogesoft.com',
'user'     => 'root',
'pass'     => 'hogesoft',
'database' => 'dev_road',
'charset'  => 'utf8',
'pconnect' => '',
);

define('DB_PREFIX','liv_');//定义数据库表前缀
define('APP_UNIQUEID','road');//应用标识
define('MAX_RECORD_NUM', 500); //最多保留多少记录

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

$gGlobalConfig['areaname'] =  '南京';

$gGlobalConfig['default_state'] =  '1';

define('INITED_APP', true);
?>