<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: config.php 6705 2012-05-14 09:48:30Z wangleyuan $
***************************************************************************/

$gDBconfig = array(
'host'     => 'db.dev.hogesoft.com',
'user'     => 'root',
'pass'     => 'hogesoft',
'database' => 'dev_catalog',
'charset'  => 'utf8',
'pconnect' => '',
);

define('DB_PREFIX','liv_');//定义数据库表前缀
define('APP_UNIQUEID','catalog');//应用标识
define('CATALOG_PREFIX', 'catalog_');//编目标识前缀
define('MATERIALDEL', 'materialdel');//删除某个图片或者视频素材记录删除掉的id
define('CATALOGDEL', 'catalogdel');//删除某个编目记录删除掉的编目
define('CACHE_FILE_NAME', 'init.php');//缓存文件及后缀
define('REPLACE_DATA','{$data}');//替换
define('REPLACE_NAME','{$name}');//替换
define('CACHE_SORT',CACHE_DIR . CACHE_FILE_NAME);//缓存目录和文件名

//时间搜索
$gGlobalConfig['date_search'] = array(
	1 		=> '所有时间段',
	2 		=> '昨天',
	3 		=> '今天',
	4 		=> '最近3天',
	5 		=> '最近7天',
	'other' => '自定义时间',
);

$gGlobalConfig['catalog_switch'] = array(
	1 => '已启用',
	0 => '未启用',
);
$gGlobalConfig['catalog_bak'] = array(
	1 => '已启用',
	0 => '未启用',
);
$gGlobalConfig['catalog_required'] = array(
	1 => '已启用',
	0 => '未启用',
);

define('INITED_APP', true);

?>