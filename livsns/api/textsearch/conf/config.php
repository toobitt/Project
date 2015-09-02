<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: config.php 6831 2012-05-29 00:55:16Z repheal $
***************************************************************************/
$gDBconfig = array(
'host'     => 'db.dev.hogesoft.com',
'user'     => 'root',
'pass'     => 'hogesoft',
'database' => 'dev_textsearch',
'charset'  => 'utf8',
'pconncet' => 0,
);

define('DB_PREFIX','liv_');//定义数据库表前缀
define('APP_UNIQUEID','textsearch');//应用标识

$gGlobalConfig['is_open_xs'] = 1;     //是否开启了迅搜

$gGlobalConfig['action_type'] = array(
	'insert'=>'发布',
	'delete'=>'删除',
	'update'=>'更新',
	);
	
$gGlobalConfig['status'] = array(
	'1'=>'成功',
	'2'=>'失败',
	);

//状态搜索
$gGlobalConfig['state'] =  array (
  1 => '全部状态',
  2 => '待审核',
  3 => '已审核',
  4 => '已打回',
);

//状态颜色
$gGlobalConfig['state_color'] =  array (
  	0 => "#8ea8c8",
	1 => "#17b202",
	2 => "#f8a6a6",
);

define('INITED_APP', true);
?>