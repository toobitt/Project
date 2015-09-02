<?php
	$gDBconfig = array(
'host'     => 'db.dev.hogesoft.com',
'user'     => 'root',
'pass'     => 'hogesoft',
'database' => 'dev_outpush',
'charset'  => 'utf8',
'pconnect' => '',
);
define('APP_UNIQUEID','outpush');//应用标识
define('DB_PREFIX','liv_');//定义数据库表前缀
define('INITED_APP', true);

//CRE对于m2o的标志。
define('SYSMARK_VDO','hoge');
define('SYSMARK_NEWS','hoge-news');
define('SYSMARK_TUJI','hoge-image-set');
define('SYSMARK_PICS','hoge-image');

define('SEND_URL','http://118.26.64.136:8080/cre/api/task/import/add');//CRE发送接口

//时间搜索
$gGlobalConfig['date_search'] = array(
  1 => '所有时间段',
  2 => '昨天',
  3 => '今天',
  4 => '最近3天',
  5 => '最近7天',
  'other' => '自定义时间',
);

//通用审核状态
$gGlobalConfig['general_audit_status'] = array(
 0 => '待审核',
 1 => '已审核',
 2 => '被打回',
);		

?>