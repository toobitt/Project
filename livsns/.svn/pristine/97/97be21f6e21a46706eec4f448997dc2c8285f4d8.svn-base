<?php
$gDBconfig = array(
	'host' => 'db.dev.hogesoft.com',
	'user' => 'root',
	'pass' => 'hogesoft',
	'database' => 'dev_member',
	'charset' => 'utf8',
	'pconncet' => '0',
);

define('DB_PREFIX','liv_');//定义数据库表前缀

define('APP_UNIQUEID','member');//应用标识

define('BATCH_FETCH_LIMIT', 200); //批量获取数据数目限制
define('MEMBER_PLAN_SET_ID', 84); //发布系统配置ID
define('DIFFER_SIZE',false);//是否区分大小些

//会员注册默认状态
$gGlobalConfig['member_status'] = 1;

//头像大小
$gGlobalConfig['avatar_size'] = array(
	'width' => 40,
	'height' => 40,
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
$gGlobalConfig['member_state'] = array(
  -1 => '所有状态',
  0 => '待审核',
  1 => '已审核',
);

//用户中心接口
$gGlobalConfig['ucenter'] = array(
	'open' => 0,
);

//ucenter配置

define('UC_CONNECT', 'mysql');
define('UC_DBHOST', '10.0.1.31');
define('UC_DBUSER', 'root');
define('UC_DBPW', 'hogesoft');
define('UC_DBNAME', 'dev_ucenter2');
define('UC_DBCHARSET', 'utf8');
define('UC_DBTABLEPRE', '`dev_ucenter2`.uc_');
define('UC_DBCONNECT', '0');
define('UC_KEY', 'a53dewyZj0YGdfJI7HhcpS1/2ne7Jfurnee2kuI');
define('UC_API', 'http://10.0.1.40/ucenter');
define('UC_CHARSET', 'utf-8');
define('UC_IP', '');
define('UC_APPID', '9');
define('UC_PPP', '20');


//邮件应用标识
$gGlobalConfig['appuniqueid'] = array(
	'member_register' => 'member_register',
	'member_pasword' => 'member_pasword',
);

//绑定手机前缀
$gGlobalConfig['mobile_prefix'] = '+86';

//其他账号登陆标识
$gGlobalConfig['platform'] = array(
	'uc' 		=> 'ucenter',
);

//是否开启昵称唯一性
$gGlobalConfig['nick_name_unique'] = array(
	'open' 				=> 1,	//开启昵称的唯一性
	'sync_member_name'	=> 1,	//修改昵称时是否同步修改会员名
);

//激活邮件的有效期
$gGlobalConfig['App_email'] = array(
	'open' 			=> 1,
	'email_toff' 	=> 172800,
	'password_forget_key_toff' => 86400,
);

//注册开启邮箱
$gGlobalConfig['is_email_checked'] = 0;

define('INITED_APP', true);
?>