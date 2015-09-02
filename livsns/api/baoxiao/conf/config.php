<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: config.php 17931 2013-02-26 01:34:49Z lijiaying $
***************************************************************************/

$gDBconfig = array(
'host'     => 'db.dev.hogesoft.com',
'user'     => 'root',
'pass'     => 'hogesoft',
'database' => 'dev_baoxiao',
'charset'  => 'utf8',
'pconncet' => 0,
);

define('APP_UNIQUEID','baoxiao');//应用标识

define('DB_PREFIX','liv_');//定义数据库表前缀

define('FORCE_LEVEL',false);//强制按照制定的等级审核

define('DEFAULT_ROLE',3);//默认的用户角色，管理员=3

define('INITED_APP', true);

$gGlobalConfig['approve'] = array(
		0 => '未审批',
		1 => '审批通过',
);


?>