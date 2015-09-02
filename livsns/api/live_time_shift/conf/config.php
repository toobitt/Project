<?php
$gDBconfig = array(
	'host' => 'db.dev.hogesoft.com',
	'user' => 'root',
	'pass' => 'hogesoft',
	'database' => 'dev_live_time_shift',
	'charset' => 'utf8',
	'pconncet' => '0',
);
define('APP_UNIQUEID','live_time_shift');//应用标识
define('DB_PREFIX','liv_');//定义数据库表前缀
define('INITED_APP', true);

$gGlobalConfig['status'] = array(
	'0' => '失败',
	'1' => '成功',
	'2'	=> '正在时移',
	'3'	=> '时移成功转码失败',
);


?>