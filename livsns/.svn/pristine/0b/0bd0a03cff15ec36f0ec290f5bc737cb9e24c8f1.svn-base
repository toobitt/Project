<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: config.php 1120 2010-12-24 05:19:23Z develop_tong $
***************************************************************************/
$gDBconfig = array(
	'host' => '10.0.1.31',
	'user' => 'root',
	'pass' => 'hogesoft',
	'database' => 'dev_sns_report',
	'charset' => 'utf8',
	'pconncet' => 0,
);
define('DB_PREFIX','liv_');//定义数据库表前缀
define('APP_UNIQUEID','report');
define('NEED_TIME_TO_UNIX', true);//是否需要时间
define('DATA_NO_KEEP', 0);//数据保留
define('SYSTEAM_USER', -9);//系统管理员
//状态搜索
$gGlobalConfig['state']=array(
	2 =>'待处理',
	0 =>'保留数据',
	//1 =>'删除',
);

//状态搜索
$gGlobalConfig['source']=array(
	'team' => '小组',
	'topic' => '话题',
	'activity' => '行动',
	'reply' => '回复'
);


//节点的属性
$gGlobalConfig['activity_type_attr'] = array(
  1 => array('color' => '#4AA44C'), 
  2 => array('color' => '#0F9AB9'), 
  3 => array('color' => '#BF8144'), 
  4 => array('color' => '#7E4DCB')
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

define('INITED_APP', true);
?>