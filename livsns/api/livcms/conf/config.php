<?php
$gDBconfig = array(
	'host' => 'db.dev.hogesoft.com',
	'user' => 'root',
	'pass' => 'hogesoft',
	'database' => '2012_hoge',
	'charset' => 'utf8',
	'pconnect' => 0,
	'dbprefix' => 'liv_'
);
 
define('DB_PREFIX', 'liv_');

define('APP_UNIQUEID','livcms');

$gGlobalConfig['liv_cms'] = array(
'folderPreFix' => 'folder',
);

define('LIVCMS_HOST','10.0.1.40/hoge_2012/cp/');
define('LIVCMS_PLUGIN_DIR', 'plugin/');
define('CMS_IMG_DOMAIN', 'http://web.dayang.com.cn/urlimg/');

define('INITED_APP', true);