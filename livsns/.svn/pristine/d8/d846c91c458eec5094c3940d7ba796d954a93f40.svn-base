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
'database' => 'dev_program',
'charset'  => 'utf8',
'pconncet' => 0,
);

define('APP_UNIQUEID','program');//应用标识

define('DB_PREFIX','liv_');//定义数据库表前缀

define('IS_WOZA', false); //录制系统默认

define('PROGRAM_DEFAULT', false); //前台默认节目显示

$gGlobalConfig['program_type'] = array(
	1 => array('id' => 1, 'name' => '自办节目', 'class' => 'program_color_1'),
	2 => array('id' => 2, 'name' => '转播节目', 'class' => 'program_color_2'),
	3 => array('id' => 3, 'name' => '电视电影', 'class' => 'program_color_3'),

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
//上传节目单
$gGlobalConfig['txt_conf'] = array('start_time','toff', 'theme', 'subtopic');
$gGlobalConfig['xml_conf'] = array('start_time','toff', 'theme', 'subtopic');
$gGlobalConfig['xls_conf'] = array('start_time','toff', 'theme', 'subtopic');

define('INITED_APP', true);
?>