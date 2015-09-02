<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('APP_UNIQUEID','tuwenol');
$gDBconfig = array(
'host'     => '10.0.1.31',
'user'     => 'root',
'pass'     => 'hogesoft',
'database' => 'dev_tuwenol',
'charset'  => 'utf8',
'pconnect' => '',
);
 
define('DB_PREFIX','liv_');//定义数据库表前缀
define('INITED_APP', True);

define('SHORT_URL', 'http://url.cn/');
define('BAIDU_CONVERT_DOMAIN', 'http://api.map.baidu.com/ag/coord/convert?from=0&to=4');
define('REDUN_LATEST_NUM', 3);//冗余条目

?>