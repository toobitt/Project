<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: config.php 1120 2010-12-24 05:19:23Z develop_tong $
***************************************************************************/

//数据库配置
$gDBconfig = array(
	'host'     => '10.0.1.31',
	'user'     => 'root',
	'pass'     => 'hogesoft',
	'database' => 'dev_sns_video',
	'charset'  => 'utf8',
	'pconncet' => 0,
);

//定义数据库表前缀
define('DB_PREFIX','liv_');

?>