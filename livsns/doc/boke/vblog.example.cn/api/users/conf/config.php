<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: config.php 3469 2011-04-08 11:44:08Z develop_tong $
***************************************************************************/

$gDBconfig = array(
	'host'     => '10.0.1.80',
	'user'     => 'root',
	'pass'     => 'hogesoft',
	'database' => 'sns_ucenter',
	'charset'  => 'utf8',
	'pconncet' => 0,
);
define('DB_PREFIX','liv_');//定义数据库表前缀

define('MARK_POINT_LIMIT', 300);
?>