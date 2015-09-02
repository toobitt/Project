<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: config.php 2354 2011-02-28 09:25:18Z chengqing $
***************************************************************************/

$gDBconfig = array(
	'host' => '10.0.1.31',
	'user' => 'root',
	'pass' => 'hogesoft',
	'database' => 'dev_sns_banword',
	'charset' => 'utf8',
	'pconncet' => 0,
	'dbprefix' => 'liv_',
);

define('DB_PREFIX', 'liv_'); //数据库表前缀

define('APP_UNIQUEID', 'banword');

define('INITED_APP', true);
?>