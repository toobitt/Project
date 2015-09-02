<?php
$gDBconfig = array('host' => 'db.dev.hogesoft.com',
'user' => 'root',
'pass' => 'hogesoft',
'database' => 'dev_question',
'charset' => 'utf8',
'pconncet' => '0',
);
define('DB_PREFIX','liv_');//定义数据库表前缀

define('APP_UNIQUEID','question');//应用标识

//验证码接口
$gGlobalConfig['App_verifycode'] = array(
	'protocol' => 'http://',
	'host' => 'localhost',
	'dir' => 'livsns/api/verifycode/admin/',
);
//投票显示其他选项个数
$gGlobalConfig['other_option_count'] = 5;

//时间搜索
$gGlobalConfig['date_search'] = array(
  1 => '所有时间段',
  2 => '昨天',
  3 => '今天',
  4 => '最近3天',
  5 => '最近7天',
  'other' => '自定义时间',
);
$gGlobalConfig['vote_state'] = array(
  -1 => '所有状态',
  0 => '待审核',
  1 => '已审核',
);

//同一IP用户投票时间间隔
$gGlobalConfig['time_offset'] = array(
  'vote' => 3600,
  'question' => 3600
);

?>