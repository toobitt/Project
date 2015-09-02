<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: config.php 7642 2012-07-06 09:13:47Z wangleyuan $
***************************************************************************/

$gDBconfig = array(
'host'     => 'db.dev.hogesoft.com',
'user'     => 'root',
'pass'     => 'hogesoft',
'database' => 'dev_jf_mall',
'charset'  => 'utf8',
'pconnect' => '',
);

define('DB_PREFIX','liv_');     //定义数据库表前缀
define('APP_UNIQUEID','jf_mall');

define('INITED_APP', true);

define('DOWNLOAD_URL', 'http://m2o.hoge.cn');

?>