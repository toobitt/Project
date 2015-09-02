<?php
$gDBconfig = array(
	'host' => 'db.dev.hogesoft.com',	
	'user' => 'root',	
	'pass' => 'hogesoft',	
	'database' => 'dev_movie',	
	'charset' => 'utf8',
	'pconncet' => '0',
);
define('APP_UNIQUEID','movie');//应用标识
define('DB_PREFIX','liv_');//定义数据库表前缀

//素材信息
$gGlobalConfig['material_server'] = array(
	'img11' => array(
		'host' => 'http://img.dev.hogesoft.com:233/' ,
		'dir' => 'material/member/img/' ,
	),
);

//
$gGlobalConfig['member_status'] = 1;

//时间搜索
$gGlobalConfig['date_search'] = array(
  1 => '所有时间段',
  2 => '昨天',
  3 => '今天',
  4 => '最近3天',
  5 => '最近7天',
  'other' => '自定义时间',
);
$gGlobalConfig['movie_state'] = array(
  	-1 => '全部',
	1 => '未审核',
	2 => '已审核',
	3 => '打回',
);



















?>