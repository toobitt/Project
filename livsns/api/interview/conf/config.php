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
'database' => 'dev_interview',
'charset' => 'utf8',
'pconncet' => '0',
);
define('DB_PREFIX','liv_');//定义数据库表前缀
define('QQBIAOQING_DIR',ROOT_DIR .'../livtemplates/tpl/lib/images/biaoqing/');
define('IMG_URL','http://img.dev.hogesoft.com:83/');//应用标识
define('APP_UNIQUEID','interview');//应用标识
//时间搜索
$gGlobalConfig['date_search'] = array(
  1 => '所有时间段',
  2 => '昨天',
  3 => '今天',
  4 => '最近3天',
  5 => '最近7天',
  'other' => '自定义时间',
);
$gGlobalConfig['object_type'] = array(
	 '0' => '标准访谈',
	 '1' => '新闻发布会',
);
$gGlobalConfig['roles'] = array(
	 1 => '管理员', 
	 2 => '主持人', 
	 3 => '嘉宾', 
	 4 => '书记员', 
	 5 => '记者', 
	 6 => '普通用户',
	 7 => '游客'
);
$gGlobalConfig['record_states'] = array(
	'0' => '不能发言',
	'1' => '待审核',
	'2' => '已审核',
	'3' => '已忽略',
	'4' => '待回复',
);
$gGlobalConfig['file_type'] = array(
	'1' => 'gif',
	'2' => 'jpg',
	'3' => 'png',
	'4' => 'jpeg',
);
$gGlobalConfig['pub_state'] = array(
	'0' => '未发布',
	'1' => '发布',
);
$gGlobalConfig['login_url'] = array(
	'host'=>'localhost',
	'dir'=>'livsns/api/member/'
);
$gGlobalConfig['channel'] = array(
	'host'=>'localhost',
	'dir'=>'livsns/api/live/admin/'
);
$gGlobalConfig['roleoption'] = array(
		1 => array(1,2,3,4), //管理员 1审核，2忽略,3回复，4撤消
		2 => array(1,2,3,4),
		3 => array(3),
		4 => array(),
		6 => array(),
		7 => array(),
);

define('INITED_APP', true);
?>