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
'database' => 'dev_publish_plan',
'charset'  => 'utf8',
'pconncet' => 0,
);

define('DB_PREFIX','liv_');//定义数据库表前缀
define('APP_UNIQUEID','publishplan');//应用标识

$gGlobalConfig['action_type'] = array(
	'insert'=>'发布',
	'delete'=>'删除',
	'update'=>'更新',
	);
	
$gGlobalConfig['status'] = array(
	'1'=>'成功',
	'2'=>'失败',
	);


define('INITED_APP', true);
?>