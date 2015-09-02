<?php
$gDBconfig = array(
	'host' => 'db.dev.hogesoft.com',
	'user' => 'root',
	'pass' => 'hogesoft',
	'database' => 'dev_email',
	'charset' => 'utf8',
	'pconncet' => '0',
);

define('DB_PREFIX','liv_');//定义数据库表前缀

define('APP_UNIQUEID','email');//应用标识

$gGlobalConfig['email_type'] = array(
	'-1' => '请选择',
	'member_register' => '会员注册',
	'member_password' => '会员忘记密码',
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

$gGlobalConfig['email_settings_status'] = array(
	 -1 => '所有状态',
	  0 => '待审核',
	  1 => '已审核',
);
define('INITED_APP',true);


/*****************************************sendcloud************************************************/
$gGlobalConfig['sendcloud'] = array(
    'api_user' => 'dingdone_register',
    'api_key'  => 'eqNzu6eB28WBbn9K',
    'from'	   => 'no-reply@mail.dingdone.com',
    'fromname' => '叮当APP自助生产平台',
    'subject'  => array(
        'member_resetpassword' => '找回密码',
    ),
    'url'	   => 'https://sendcloud.sohu.com/webapi/mail.send.json',
    'auth_url' => 'http://10.0.1.40/applant/verifyemail.php',//激活的url
    'confirm_url' => 'http://10.0.1.40/applant/confirm_email.php',//确认url
    'confirm_expire' => 48 * 3600,//确认邮箱过期时间
    'send_rate' => 60,//发送的频率控制
);
/*****************************************sendcloud************************************************/

?>