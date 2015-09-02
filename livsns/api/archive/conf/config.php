<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
$gDBconfig = array(
	'host'     => 'db.dev.hogesoft.com',
	'user' 	   => 'root',
	'pass' 	   => 'hogesoft',
	'database' => 'dev_archive',
	'charset'  => 'utf8',	
	'pconncet' => '0'
);
define('DB_PREFIX','liv_');					//定义数据库表前缀
define('APP_UNIQUEID','archive');			//应用标识
define('OPEN_ARCHIVE', true);				//是否开启归档
define('INITED_APP', true);
?>