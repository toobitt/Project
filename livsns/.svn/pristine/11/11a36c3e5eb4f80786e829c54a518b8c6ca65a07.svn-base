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
	'database' => 'dev_sns_option',
	'charset' => 'utf8',
	'pconncet' => 0,
);
define('DB_PREFIX','liv_');//定义数据库表前缀
define('APP_UNIQUEID','activity');
define('ACTIVITY_PLAN_SET_ID','85');

define('EDITS',20);//编辑次数限制
define('ABLE_SAME_NAME', 1);//活动名可以重名

$gGlobalConfig['map_using_type'] = 1;//使用地图类型，1：百度，0：谷歌  谷歌地图有问题
$gGlobalConfig['map_key'] = 'abcdef';//google map key
$gGlobalConfig['map_center_point'] = '30.298X120.159';//默认中心点

//状态搜索
$gGlobalConfig['activity_status']=array(
	0 =>'全部状态',
	1 =>'有效',
	2 =>'无效',
	3 =>'待审核',
);

$gGlobalConfig['activity_type'] = array(
  1 => "最新更新", 
  2 => "最多评论", 
  3 => "最多举报", 
  4 => "置顶帖 "
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
?>