<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: config.php 12606 2012-10-17 09:50:41Z repheal $
***************************************************************************/

$gDBconfig = array(
	'host'     => 'db.dev.hogesoft.com',
	'user'     => 'root',
	'pass'     => 'hogesoft',
	'database' => 'dev_notify',
	'charset'  => 'utf8',
	'pconncet' => 0,
);
define('DB_PREFIX','liv_');//定义数据库表前缀

define('MARK_POINT_LIMIT', 300);
define('BATCH_FETCH_LIMIT', 200); //批量获取数据数目限制
?>