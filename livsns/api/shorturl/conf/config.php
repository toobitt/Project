<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: config.php 2768 2011-03-15 05:06:35Z develop_tong $
***************************************************************************/

$gDBconfig = array(
	'host' => 'db.dev.hogesoft.com',
	'user' => 'root',
	'pass' => 'hogesoft',
	'database' => 'dev_sns_shorturl',
	'charset' => 'utf8',
	'pconncet' => 0,
);
 
define('DB_PREFIX', 'liv_'); //数据库表前缀
define('APP_UNIQUEID','shorturl');
define('IS_SHORTURL',false);
define('SITE_URL', 'http://shorturl');//短网址域名
define('ERROR_URL', 'http://localhost/livsns/');
define('URL_PROTOCOLS', 'http|https|ftp|ftps|mailto|news|mms|rtmp|rtmpt|e2dk');//检查输入是否包含这些头部
?>