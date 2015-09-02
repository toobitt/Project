<?php
	$gDBconfig = array(
'host'     => 'db.dev.hogesoft.com',
'user'     => 'root',
'pass'     => 'hogesoft',
'database' => 'dev_tv_interact',
'charset'  => 'utf8',
'pconnect' => '',
);
define('APP_UNIQUEID','tv_interact');//应用标识
define('DB_PREFIX','liv_');//定义数据库表前缀

define('TV_SCORE_TYPE', '金币');//定义奖励类型
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
$gGlobalConfig['status'] = array(
  -1=> '全部审核状态',
  0 => '待审核',
  1 => '已审核',
  2 => '已打回',
);
//组团方式
$gGlobalConfig['activ_status'] = array(
  -1=> '全部活动状态',
  1 => '未开始',
  2 => '进行中',
  3 => '已结束',
);
define('INITED_APP', true);

$gGlobalConfig['used_search_condition'] =  array (
);
?>