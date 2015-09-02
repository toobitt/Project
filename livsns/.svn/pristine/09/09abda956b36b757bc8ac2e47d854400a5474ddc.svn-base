<?php
$gDBconfig = array(
'host'     => 'db.dev.hogesoft.com',
'user'     => 'root',
'pass'     => 'hogesoft',
'database' => 'dev_vote',
'charset'  => 'utf8',
'pconnect' => '',
);
define('DB_PREFIX','liv_');//定义数据库表前缀

define('APP_UNIQUEID','vote');//应用标识

define('PUBLISH_SET_ID', 110);//发布计划配置ID

define('OTHER_OPTION_ID', '-1');//其他选项默认id

define('OTHER_OPTION_TITLE', '其他'); //其他选项中文

define('IS_CREDITS', 1); //投票积分

define('CREDIT_NUM', 10); 

define('RESERVED_IP_LIMIT', 0);// 是否限制保留 ip 地址

define('NO_DEVICE_VOTE', 0);	//未传递device_token 时，是否可以投票

define('NO_DEVICE_TIPS', '您的客户端版本太低，请先升级');	//未传递device_token 时，是否可以投票

define('CORE_DIR', CUR_CONF_PATH.'core/');//定义模板目录

define('DATA_DIR', CUR_CONF_PATH.'data/');//定义h5缓存目录

define('VOTE_DOMAIN','http://localhost/livsns/api/vote/data/');

//投票显示其他选项个数
$gGlobalConfig['other_option_count'] = 5;

$gGlobalConfig['mode_type'] = array(
	'choose' => array('mode_type'=>'choose','name'=>'选择'),
);
$gGlobalConfig['other_mode'] = array(
  'picture'	=> array('mode_type'=>'picture','name'=>'轮转图'),
  'video' => array('mode_type'=>'video','name'=>'视频'),
  'audio' => array('mode_type'=>'audio','name'=>'音频'),
  'header_info' => array('mode_type'=>'header_info','name'=>'头部'),
  'footer_info' => array('mode_type'=>'footer_info','name'=>'底部'),
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
$gGlobalConfig['vote_state'] = array(
  -1 => '所有状态',
  0 => '待审核',
  1 => '已审核',
  2 => '已打回',
);
$gGlobalConfig['status_text'] = array(
  1 => '正在进行',
  2 => '已结束',
  3 => '即将开始',
  4 => '正在进行',
  5	=> '距离投票开始还有{$day}天',
);
//来源类型
$gGlobalConfig['_source_type'] = array(
  -1 => '所有来源',
  0 => '编辑添加',
  1 => '网友添加',
);

//同一IP用户投票时间间隔
$gGlobalConfig['time_offset'] = array(
  'vote' => 3600,
  'question' => 3600
);

define('INITED_APP', true);

$gGlobalConfig['used_search_condition'] =  array (
);
?>