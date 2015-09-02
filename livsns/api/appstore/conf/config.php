<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
$gDBconfig = array(
	'host' => 'db.dev.hogesoft.com',
	'user' => 'root',
	'pass' => 'hogesoft',
	'database' => 'dev_appstore',
	'charset' => 'utf8',
	'pconncet' => '0',
);
define('APP_UNIQUEID','appstore');//应用标识
define('DB_PREFIX','liv_');//定义数据库表前缀
$gGlobalConfig['App_serverAuth'] = array(
	'host' => 'auth.hogesoft.com',
	'port' => 80,
	'dir' => '',
);
$gGlobalConfig['App_upgradeServer'] = array(
	'host' => 'upgrade.hogesoft.com',
	'port' => 80,
	'dir' => '',
);
//时间搜索
$gGlobalConfig['date_search'] = array(
  1 => '所有时间段',
  2 => '昨天',
  3 => '今天',
  4 => '最近3天',
  5 => '最近7天',
  'other' => '自定义时间',
);
//模板生成的路径
define('TEMP_DIR', '/web/publish_product/release/app_templates/');

define('INITED_APP', true);
?>