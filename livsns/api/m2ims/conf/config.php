<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: config.php 1120 2010-12-24 05:19:23Z develop_tong $
***************************************************************************/

//数据库配置

$gDBconfig = array(
'host'     => 'db.dev.hogesoft.com',
'user'     => 'root',
'pass'     => 'hogesoft',
'database' => 'm2ims',
'charset'  => 'utf8',
'pconnect' => '0',
);

//定义数据库表前缀
define('DB_PREFIX','liv_');  
define('APP_UNIQUEID','mmobject');//应用标识
define('USE_FTP_UPLOAD', 0);
//ftp
$gGlobalConfig['ftp'] = array(
'hostname'=>'10.0.2.75',
'username'=>'zhuld',
'password'=>'zhuld',
'port'	  => 21,
'passive' => true,
'debug'	  =>true,
);
//wsdl
define('IMS_WSDL', 'http://localhost/testsoap.wsdl');
define('INITED_APP', true);
?>