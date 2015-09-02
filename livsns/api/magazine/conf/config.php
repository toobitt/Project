<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
$gDBconfig = array('host' => 'db.dev.hogesoft.com',
'user' => 'root',
'pass' => 'hogesoft',
'database' => 'dev_magazine',
'charset' => 'utf8',
'pconncet' => '0',
);
define('DB_PREFIX','liv_');//定义数据库表前缀
define('APP_UNIQUEID','magazine');//应用标识

define('MAGAZINE_PLAN_SET_ID',74);		//杂志表发布计划配置ID
define('ISSUE_PLAN_SET_ID',75);			//期刊表发布计划配置ID
define('ARTICLE_PLAN_SET_ID',76);		//文章表发布计划配置ID
define('MATERIAL_PLAN_SET_ID',77);		//素材表发布计划配置ID
define('IMG_URL', 'http://img.dev.hogesoft.com:233/');

//时间搜索
$gGlobalConfig['date_search'] = array(
  1 => '所有时间段',
  2 => '昨天',
  3 => '今天',
  4 => '最近3天',
  5 => '最近7天',
  'other' => '自定义时间',
);
//发布周期
$gGlobalConfig['release_cycle'] = array(
  1 => '周刊',
  2 => '月刊',
  3 => '季刊',
  4 => '旬刊',
  5 => '半年刊',
  6 => '年刊',
);
$gGlobalConfig['client'] = array(
	1=>'手机',
	2=>'网站',
);
$gGlobalConfig['audit'] = array(
	-1=>'所有状态',
	0=>'待审核',
	1=>'已审核',
	2=>'已打回',
);
$gGlobalConfig['status_color'] = array(
  	0 => "#8ea8c8",
  	1 => "#17b202",
 	2 => "#f8a6a6",
);
$gGlobalConfig['issue_audit'] = array(
	0=>'未审核',
	1=>'已审核',
	2=>'已打回',
);

$gGlobalConfig['default_size'] = array(
	'label' => '100x75',
	'width' => 100,
	'height' => 75,
);
$gGlobalConfig['small_size']=array(
     'label' => '40x30',
	 'width' =>40,
	 'height' =>30 ,
);

$gGlobalConfig['default_index'] = array(
	'label' => '160x120',
	'width' => 160,
	'height' => 120,
);

$gGlobalConfig['default_state'] = 0;

define('INITED_APP', true);
?>