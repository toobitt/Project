<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: config.php 1120 2010-12-24 05:19:23Z develop_tong $
***************************************************************************/
$gDBconfig = array(
	'host' => '10.0.1.31',
	'user' => 'root',
	'pass' => 'hogesoft',
	'database' => 'dev_sns_action',
	'charset' => 'utf8',
	'pconncet' => 0,
);
define('DB_PREFIX',"liv_");//定义数据库表前缀
define('EDIT_LIMIT',0);//0无限
define('DELETE_TYPE','discuss');//按照discuss删除，systerm自定义删除
?>