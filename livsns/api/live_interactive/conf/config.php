<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: config.php 19978 2013-04-09 02:16:31Z lijiaying $
***************************************************************************/

$gDBconfig = array(
	'host' 		=> 'db.dev.hogesoft.com',
	'user' 		=> 'root',
	'pass' 		=> 'hogesoft',
	'database' 	=> 'dev_live_interactive',
	'charset'  	=> 'utf8',
	'pconncet' 	=> '0',
);

define('APP_UNIQUEID','live_interactive');//应用标识

define('DB_PREFIX','liv_');//定义数据库表前缀

$gGlobalConfig['default'] = array(
	'status' => 0,
	'type' 	 => 0,
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
//状态
$gGlobalConfig['state'] = array(
 -1 => '所有状态',
  0 => '待审核',
  1 => '已审核',
  2 => '被打回',
);
//类型
$gGlobalConfig['type'] = array(
 -1 => '所有类型',
  1 => '推送',
  2 => '推荐',
  3 => '警告',
  4 => '屏蔽',
);
//互动数据菜单
$gGlobalConfig['interactive_nav'] = array(
  308  => '听众来信',
  347  => '基本信息',
  323  => '主持人页',
//  319  => '节目设置',
);

//节目单背景颜色
$gGlobalConfig['program_color'] = array(
  1 => array('#FEF2F2','#DF6564'),//虚拟节目
  2 => array('#EFFDEE','#4BA14A'),//真实节目
  3 => array('#E5EEFF','#537ABF'),//计划节目
  4 => array('#FCF9EA','#CAB562'),//计划节目
);

//主持人类型
$gGlobalConfig['admin_type'] = array(
	'presenter' => 16,
	'director'  => 13,
);

//节目开始时间和当前时间差 单位秒
$gGlobalConfig['time_offset'] = 300;
//警告次数
$gGlobalConfig['warn_count'] = 3;
//屏蔽次数
$gGlobalConfig['shield_count'] = 1;

//授权登陆回调host
$gGlobalConfig['oauthlogin'] = array(
	'protocol' => 'http://',
	'host' => $_SERVER["HTTP_HOST"],
	'dir' => 'livworkbench/',
);

define('INITED_APP', true);
?>