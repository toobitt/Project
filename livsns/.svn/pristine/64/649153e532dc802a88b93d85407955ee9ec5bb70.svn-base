<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: config.php 17931 2013-02-26 01:34:49Z lijiaying $
***************************************************************************/

$gDBconfig = array(
'host'     => 'db.dev.hogesoft.com',
'user'     => 'root',
'pass'     => 'hogesoft',
'database' => 'dev_albums_app',
'charset'  => 'utf8',
'pconnect' => '',
);

define('APP_UNIQUEID', 'albums_app'); //应用标识

define('DB_PREFIX','liv_');//定义数据库表前缀

$gGlobalConfig['status'] = array(
	1 => '全部状态',
	2 => '待审核',
	3 => '已审核'
);

//状态颜色值
$gGlobalConfig['status_color'] =  array (
  0 => '#8ea8c8',
  1 => '#17b202',
  2 => '#f8a6a6',
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

//设置推荐、置顶等类型
$gGlobalConfig['type'] =  array (
  'recommend' => 
  array (
    'name' => '推荐',
    'value' => '1',
  ),
  'top' => 
  array (
    'name' => '置顶',
    'value' => '2',
  ),
);

//是否含有图片
define('HAS_PIC', 0);
//设置初始是否审核
define('INIT_AUDIT', 1);

define('INITED_APP', true);
?>