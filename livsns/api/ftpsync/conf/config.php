<?php 
$gDBconfig = array(
'host'     => 'db.dev.hogesoft.com',
'user'     => 'root',
'pass'     => 'hogesoft',
'database' => 'dev_ftpsync',
'charset'  => 'utf8',
'pconnect' => '',
);
 
define('DB_PREFIX','liv_');//定义数据库表前缀
define('INITED_APP',true);
define('APP_UNIQUEID', 'ftpsync');
define('ERROR_LIMIT', 20);
define('TRY_TIMES', 3);

$gGlobalConfig['used_search_condition'] =  array (
);
?>