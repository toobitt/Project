<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: config.php 9348 2012-08-15 05:41:26Z wangleyuan $
***************************************************************************/

$gDBconfig = array(
'host'     => 'db.dev.hogesoft.com',
'user'     => 'root',
'pass'     => 'hogesoft',
'database' => 'dev_notice',
'charset'  => 'utf8',
'pconncet' => 0,
);


define('DB_PREFIX', 'liv_');            //定义数据库表前缀
define('APP_UNIQUEID','inform');        //应用标识



define('INITED_APP', true);
?>