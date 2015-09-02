<?php
	$gDBconfig = array(
'host'     => 'db.dev.hogesoft.com',
'user'     => 'root',
'pass'     => 'hogesoft',
'database' => 'dev_survey',
'charset'  => 'utf8',
'pconnect' => '',
);
define('APP_UNIQUEID','survey');//应用标识
define('DB_PREFIX','liv_');//定义数据库表前缀
define('INITED_APP', true);
define('PUBLISH_SET_ID', 781);//发布计划配置ID
define('CORE_DIR', CUR_CONF_PATH.'core/');//定义模板目录
define('DATA_DIR', CUR_CONF_PATH.'data/');//定义h5缓存目录
define('SV_DOMAIN', 'http://10.0.1.40/livsns/api/survey/data/');
define('SV_CSS_DOMAIN', 'http://10.0.1.40/livsns/api/survey/data/');
define('SV_HOST', 'http://10.0.1.40/livsns/api/survey/');
define('TFILE', 'television');
define('CHECK_DEVICE', 0);//是否检测设备号
define('NO_DEVICE_TIPS', '请尝试打开消息功能后再投票');//无设备号时的错误提示
define('VU', 'http://10.0.1.40/livsns/api/survey/survey_update.php');
define('IS_ENDEVICE', 0);//是否使用device加密
define('REDIS_KEY','');
define('SALT_TIME',3600);
define('RESULT_CACHE_TIME',3600);

//视频类型配置
$gGlobalConfig['video_type'] = array(
	'type' => '.wmv,.avi,.dat,.asf,.rm,.rmvb,.ram,.mpg,.mpeg,.3gp,.mov,.mp4,.m4v,.dvix,.dv,.dat,.mkv,.flv,.vob,.ram,.qt,.divx,.cpk,.fli,.flc,.mod,.m4a,.f4v,.3ga,.caf,.mp3,.vob,.aac,.amr,.ts'
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
//问题类型
$gGlobalConfig['type'] = array(
  1 => '单选题',
  2 => '多选题',
  3 => '填空题',
  4 => '问答题',
);
$gGlobalConfig['mode_type'] = array(
  1 => 'radio',
  2 => 'checkbox',
  3 => 'inputs',
  4 => 'textarea',
);
$gGlobalConfig['other_mode'] = array(
  'picture'	=> array('mode_type'=>'picture','name'=>'轮转图'),
  'video' => array('mode_type'=>'video','name'=>'视频'),
  'audio' => array('mode_type'=>'audio','name'=>'音频'),
  'header_info' => array('mode_type'=>'header_info','name'=>'头部'),
  'footer_info' => array('mode_type'=>'footer_info','name'=>'底部'),
  'verifycode' => array('mode_type'=>'verifycode','name'=>'验证码'),
  'result' => array('mode_type'=>'result','name'=>'结果区'),
);
$gGlobalConfig['other_mode_type'] = array(
  0 => 'picture',
  1 => 'video',
  2 => 'audio',
);
//审核状态
$gGlobalConfig['status'] = array(
  -1=> '全部状态',
  0 => '未审核',
  1 => '已审核',
  2 => '已打回',
);
/**
$gGlobalConfig['redis'] = array(
	'redis1' => array( //读redis配置
		'host'	=> '127.0.0.1',
		'port'	=> 6379,
		'num'	=> 0
	),
	'redis2' => array( //写redis配置
		'host'	=> '127.0.0.1',
		'port'	=> 6379,
		'num'	=> 0
	)
);
**/
?>