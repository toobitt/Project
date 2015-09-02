<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: config.php 1120 2010-12-24 05:19:23Z develop_tong $
***************************************************************************/

//数据库配置
$gDBconfig = array(
	'host'     => '10.0.1.31',
	'user'     => 'root',
	'pass'     => 'hogesoft',
	'database' => 'dev_sns_video',
	'charset'  => 'utf8',
	'pconncet' => 0,
);


//定义数据库表前缀
define('DB_PREFIX','liv_');
define('NUM_IMG',10000);
define('LOGO_DIR', 'logo/');//网台logo默认是存放路径
define('LOGO_SIZE_SMALL',235);//网台logo
define('APP_UNIQUEID','boke');//应用标识
$write_token = "ld2b5thbvanukhkq80md";
$read_token = "8k30f1p6u9yf9vou1lqc";
$api_url = '10.0.1.68';
$config = array('read_token' => $read_token, 'write_token' => $write_token, 'api_server_name' => $api_url);

//时间搜索
$gGlobalConfig['date_search'] = array(
  1 => '所有时间段',
  2 => '昨天',
  3 => '今天',
  4 => '最近3天',
  5 => '最近7天',
  'other' => '自定义时间',
);

//查询条件
$gGlobalConfig['video_node'] = array(
  1 => '最新更新',
  2 => '最新创建',
  3 => '最新评论',
  4 => '最近视频',
  5 => '最近举报',
);

$gGlobalConfig['comment_status'] = array(
  -1=>"全部状态",
  0 => "待审核",
  1 => "已审核",
);
//频道状态
$gGlobalConfig['channel_state'] = array(
  -1 => '全部状态',
   0 => '待审核',
   1 => '审核通过',
   2 => '审核不通过',
);
//视频状态
$gGlobalConfig['video_state'] = array(
  -1 => '全部状态',
   0 => '待审核',
   1 => '未通过',
   2 => '已发布',
);
?>