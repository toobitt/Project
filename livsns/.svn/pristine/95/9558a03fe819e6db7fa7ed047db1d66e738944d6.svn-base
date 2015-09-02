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
	'database' => 'dev_company',
	'charset'  => 'utf8',
	'pconnect' => '',
);

define('APP_UNIQUEID', 'company'); //应用标识
define('DB_PREFIX','liv_');//定义数据库表前缀
define('DINGDONE_ORG', 87);
define('APPLANT_DOMAIN', '');
define('WEBURL', 'dd.com');
define('SITE_DIR', '/web/share.dd.com/');
define('SUB_WEBURL', 'share');
define('INDEXNAME', 'index');
define('CUSTOM_CONTENT_DIR', 's/');
define('PRODUCE_FORMAT', 1);
define('SUFFIX', '.html');
define('INITED_APP', true);

/****************************ios推送相关配置*******************/
define('IS_APP_PUBLISHED',0);//定义该应用是否是发布版本
define('IOS_PEMS','push.pems');//定义ios推送用到的证书
/****************************ios推送相关配置*******************/

/****************************极光推送相关配置*******************************/
define('JPUSH_APP_KEY', 'ff1438f10dcd586a691bc0c9');//在极光推送上注册的应用标识
define('MASTER_SECRET', 'd089719359e2f7b188c88c65');//API MasterSecret
/****************************极光推送相关配置*******************************/

//定义新老用户注册时间的界定
define('OLD_USER_TIME','2014-12-31');

$gGlobalConfig['status'] = array(
	1 => '全部状态',
	2 => '待审核',
	3 => '已审核',
	4 => '未通过',
);

$gGlobalConfig['yes_no'] = array(
		1 => '是',
		0 => '否',
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

$gGlobalConfig['state'] = array(
	'-1'=> '转码失败',
	'0' => '未发布',
	'1' => '已发布',
	'2' => '已取消发布',
	'3' => '转码中'
);

$gGlobalConfig['default_role'] =  array (
  0 => '126',
);

//推送接口状态
$gGlobalConfig['push_status'] =  array (
	  0 => '全部状态',
	  1 => '未提交申请',
	  2 => '申请审核中',
	  3 => '待开通',
	  4 => '审核未通过',
	  5 => '已开通',
);

/*****************************************推送账号相关配置**********************************************/
$gGlobalConfig['push_plant'] =  array (
  0 => '所有状态',
  1 => 'AVOS平台',
  2 => '信鸽平台',
);
/*****************************************推送账号相关配置**********************************************/

/*****************************************短信相关配置*************************************************/
//无限通短信平台
/*
$gGlobalConfig['sms_code'] = array(
    'account' 			=> 'dh21453',//账户名
    'password'  		=> '21453.com',//密码
    'content'   		=> '校验码：{code}，用于注册叮当账号或忘记密码，有效期{time}分钟，请勿泄露。',//发送的内容
	'sgid'				=> '002',
    'request_send_url'  => 'http://www.10690300.com/http/SendSms',
);
*/

$gGlobalConfig['sms_code'] = array(
    'account' 			=> 'dh21453',//账户名
    'password'  		=> 'tp19x73z',//密码
    'content'   		=> array(
							1 => '尊敬的用户：您正在注册账号，验证码是{code}，有效期{time}分钟，工作人员不会索取，请勿泄漏。如非本人，请忽略。',//注册的内容,
							2 => '尊敬的用户：您正在重置密码，验证码是{code}，有效期{time}分钟，工作人员不会索取，请勿泄漏。如非本人，请忽略。',//注册的内容,
							3 => '尊敬的用户：您正在绑定手机号，验证码是{code}，有效期{time}分钟，工作人员不会索取，请勿泄漏。如非本人，请忽略。',//注册的内容,
						),
	'subcode'			=> '001',
	'sign'				=> '【叮当APP】',
    'request_send_url'  => 'http://3tong.net/http/sms/Submit',
);


/**
 * 发送短信验证码  手机号 ip地址 同一时间段内发送次数限制
 * 
 * time_limit  时间长度 
 * num_limit 时间长度内的次数限制
 */
$gGlobalConfig['sms_code_limit'] = array(
    'telephone' => array(
        'time_limit'   => 30,      //手机号时间限制 单位秒  0不限制
        'num_limit'    => 1,       //次数限制 时间内准许发送的次数  time_limit为0时不起作用
    ),
    'ip' => array(
        'time_limit'   => 86400,  //ip时间限制 单位秒 0不限制
        'num_limit'    => 500,   //次数限制 时间内容准许发送的次数  time_limit为0时不起作用
    ),
	'email' => array(
			'time_limit'   => 60,      //邮箱时间限制 单位秒  0不限制
			'num_limit'    => 1,       //次数限制 时间内准许发送的次数  time_limit为0时不起作用
	),
);

//短信验证码的有效时间
define('AUTHCODE_EXPIRED', 300); //单位秒
//邮箱验证码的有效时间
define('EMAIL_AUTHCODE_EXPIRED', 600); //单位秒

/*****************************************短信相关配置*************************************************/

/*****************************************叮当访问m2o超级管理员账号**************************************/
$gGlobalConfig['super_account'] = array(
   	'username' 	=> 'zhoujiafei',
   	'password' 	=> '123',
	'appid'		=> '55',
	'appkey'	=> 'GLtPX7N7ijwb83wupXuIrEl1YvIeBbm7',
);

/*****************************************叮当访问m2o超级管理员账号*************************************/


/*****************************************用户注册账号发送邮件文案**************************************/
$gGlobalConfig['register_email'] = array(
    'subject'       => '欢迎加入叮当，请登录验证邮箱',
    'body'          => "{username},您好\n感谢您注册叮当，请点击下面的链接完成邮箱验证：{url}",
    'host'	        => 'smtp.qq.com',
    'stmp_username' => 'dingdone@hoge.cn',
    'stmp_password' => 'dd@hoge833',
    'form'			=> 'dingdone@hoge.cn',
    'from_name'		=> '叮当APP自助生产平台',
    'auth_url'		=> 'http://10.0.1.40/applant/verifyemail.php',
);
/*****************************************用户注册账号发送邮件文案**************************************/

/*****************************************sendcloud************************************************/
$gGlobalConfig['sendcloud'] = array(
    'api_user' => 'dingdone_register',
    'api_key'  => 'eqNzu6eB28WBbn9K',
    'from'	   => 'no-reply@mail.dingdone.com',
    'fromname' => '叮当APP自助生产平台',
    'subject'  => array(
    				'login' => '欢迎加入叮当，请登录验证邮箱',
    				'change'  => '设置叮当新邮箱',
    				'find_password' => '找回叮当密码',
                  ),
    'url'	   => 'https://sendcloud.sohu.com/webapi/mail.send.json',
    'auth_url' => 'http://10.0.1.40/applant/verifyemail.php',//激活的url
    'confirm_url' => 'http://10.0.1.40/applant/confirm_email.php',//确认url
    'confirm_expire' => 48 * 3600,//确认邮箱过期时间
    'send_rate' => 60,//发送的频率控制
);
/*****************************************sendcloud************************************************/

//通用审核状态
$gGlobalConfig['general_audit_status'] = array(
    0 => '待审核',
    1 => '已审核',
    2 => '被打回',
);

//通用审核状态
$gGlobalConfig['general_publish_status'] = array(
	0 => '待发布',
	1 => '已发布',
	2 => '未采纳',
);

define('LIUSHI_TIME', 3);

/**********************************扩展字段相关数量限制********************************************/
$gGlobalConfig['catalog_num_limit'] = array(
	'list_ui_num'	=> 5,
	'radio_num'	=> 1,
	'time_num'		=> 2,
	'price_num'		=> 1,
	'content_ui_num'	=> 6,
	'main_num'		=> 1,
	'minor_num'		=> 1,
		
		
);
/**********************************扩展字段相关数量限制end********************************************/
