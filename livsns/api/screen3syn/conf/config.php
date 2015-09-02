<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/

$gDBconfig = array('host' => 'db.dev.hogesoft.com',
'user' => 'root',
'pass' => 'hogesoft',
'database' => 'dev_screen3syn',
'charset' => 'utf8',
'pconncet' => '0',
);
define('DB_PREFIX','liv_');//定义数据库表前缀
define('SLEEP_TIME', 2);
define('WWW_API_URL', 'http://www.hoge.cn:234/api/bridge.php');
define('CHANNEL_WEBURL', 'http://www.hoge.cn:234/live/?channel=');
define('APP_UNIQUEID','screen3syn');
define('EXPIRED_IDENTIFIER', 3600);
define('INITED_APP', true);
?>