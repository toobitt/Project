<?php
$gDBconfig = array(
'host'     => 'db.dev.hogesoft.com',
'user'     => 'root',
'pass'     => 'hogesoft',
'database' => 'dev_acp',
'charset'  => 'utf8',
'pconnect' => '',
);


define('DB_PREFIX','liv_');     //定义数据库表前缀
define('APP_UNIQUEID','acp');   //应用标识
define('DEBUG_OPEN',false);
define('INITED_APP', true);

$gGlobalConfig['date_search'] = array(
        1 => '所有时间段',
        2 => '昨天',
        3 => '今天',
        4 => '最近3天',
        5 => '最近7天',
        'other' => '自定义时间',
);

?>