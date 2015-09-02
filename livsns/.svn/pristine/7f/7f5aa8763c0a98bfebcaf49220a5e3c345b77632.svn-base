<?php
$gDBconfig = array(
'host'     => 'db.dev.hogesoft.com',
'user'     => 'root',
'pass'     => 'hogesoft',
'database' => 'dev_members',
'charset'  => 'utf8',
'pconnect' => '0',
);
define('DB_PREFIX','liv_');//定义数据库表前缀
define('APP_UNIQUEID','members');//应用标识
define('IS_BINARY', true);//是否区分大小写 $binary
define('IS_VERIFYCODE_BINARY', false);//是否区分大小写 $binary
define('MAX_SENDSMS_LIMITS', 50);
define('IS_LOGIN_VERIFYCODE', 0);//是否启用登陆验证码验证
define('IS_REGISTER_VERIFYCODE', 0);//是否启用注册登陆验证码验证
define('IS_RESETPASSWORD_VERIFYCODE', 0);//是否启用找回密码验证码
define('CREDITS_PLAN', 0);//总积分计算方案，后台可配置
define('GRADEDIGITAL_PREFIX','LV.');//数字等级前缀
/**1.5.7新增**/
define('NO_VERIFY_EMAILBIND', 1);//无需验证码直接绑定邮箱
define('NO_VERIFY_MOBILEBIND', 0);//无需验证码直接绑定手机
define('ALLOW_UPDATE_MEMBERNAME', 0);//开启修改用户名功能(仅对UC未同步有效)
/**结束**/
//验证码有效时长 秒
define('VERIFYCODE_EXPIRED_TIME', 300);
//可设置的错误提示
$gGlobalConfig['error_text'] =  array (
  'closesms' => '短信服务器关闭，无法注册手机用户',
  'sms_max_limits' => '短信服务器超出当日最大次数，请明日再试',
);
//会员注册默认状态
$gGlobalConfig['member_status'] =  '1';
$gGlobalConfig['member_name_length'] = array(
	'max' => 32,
	'min' => 3,
);
//会员后台添加可选注册类型
$gGlobalConfig['member_admin_type'] = array('m2o'=>
array('type'=>'m2o', 'type_name'=>'m2o'),'shouji'=>
array('type'=>'shouji', 'type_name'=>'手机'),
);

$gGlobalConfig['bind_mobile'] = array(
1=>'已绑定',
);

$gGlobalConfig['isFrozen'] = 1;//是否开启冻结积分功能

//星星数,0关闭.值为2,2个星星为一个月亮,2个月亮为一个太阳,余数部分按照星星数计算
$gGlobalConfig['showstars'] =  '4';
//会员组升级方式
$gGlobalConfig['updatetype'] = array(
0=>'总积分',
1=>'授予',
2=>'购买',
);
//勋章发放方式
$gGlobalConfig['medal_type'] = array(
0=>'人工授予',
1=>'自主申请',
2=>'人工审核',
);
//积分规则
$gGlobalConfig['cycletype'] = array(
0=>'一次',
1=>'每天',
2=>'整点',
3=>'间隔分钟',
4=>'不限',
);
//权限类型
$gGlobalConfig['member_purview_type'] = array(
0=>'阻止',
1=>'通过',
);
//积分是否允许自定义
$gGlobalConfig['credits_diy_type'] = array(
0=>'禁止',
1=>'允许',
);
$gGlobalConfig['credits_rules_open'] = array(
1 => '已开启',
0 => '未启用',
);
$gGlobalConfig['staricon_open'] = array(
1 => '已开启',
0 => '未启用',
);

//积分规则周期等级
$gGlobalConfig['RulesCycleLevel'] = array(
'0'=>array(
'name'=>'规则',
'type'=>'rule',
),
'1'=>array(
'name'=>'应用',
'type'=>'app',
),
'2'=>array(
'name'=>'模块',
'type'=>'mod',
),
'3'=>array(
'name'=>'分类',
'type'=>'sort',
),
'4'=>array(
'name'=>'内容',
'type'=>'content',
),
);

//会员基础资料字段
$gGlobalConfig['member_base_info'] = array(
			array('field'=>'avatar','field_name'=>'会员头像'),
			array('field'=>'mobile','field_name'=>'手机号'),
			array('field'=>'email','field_name'=>'邮箱'),
			array('field'=>'nick_name','field_name'=>'昵称'),
		//	array('field'=>'realname','field_name'=>'姓名'),
			//array('field'=>'gender','field_name'=>'性别'),
			//array('field'=>'idcardtype','field_name'=>'证件类型'),
			//array('field'=>'idcard','field_name'=>'证件号码'),
		//	array('field'=>'idcardimg','field_name'=>'证件图片'),
		);
//支持实名认证可选字段
$gGlobalConfig['member_verify_field'] = array(
//			'realname'=>array('field'=>'realname','field_name'=>'姓名'),
	//		'gender'=>array('field'=>'gender','field_name'=>'性别'),
		//	'idcardtype'=>array('field'=>'idcardtype','field_name'=>'证件类型'),
			//'idcard'=>array('field'=>'idcard','field_name'=>'证件号码'),
//			'idcardimg'=>array('field'=>'idcardimg','field_name'=>'证件图片'),
		);
//会员扩展字段类型
$gGlobalConfig['extension_field_type'] = array(
 			'0'=>array('type'=>'text','type_name'=>'文本数据'),
			'1'=>array('type'=>'img','type_name'=>'图片上传'),
		);
                
 /***1.5.4版本新增参数**/
$gGlobalConfig['mySetUseSource'] = array(
	'0'=>'不限制',
	'1'=>'网页端专用',
	'2'=>'手机端专用',
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

define('INITED_APP', true);


//是否开启uc
$gGlobalConfig['ucenter'] =  array (
  'open' => '0',
);

//ucenter配置

define('UC_CONNECT', '');
define('UC_DBHOST', 'hogeayou.mophp.com');
define('UC_DBUSER', 'root');
define('UC_DBPW', 'hogesoft');
define('UC_DBNAME', 'ultrax');
define('UC_DBCHARSET', 'utf8');
define('UC_DBTABLEPRE', '`ultrax`.pre_ucenter_');
define('UC_DBCONNECT', 0);
define('UC_KEY', '15cahqINvcD2U0ZjmMFKuL5DW7/NqlZndN2If9Q');
define('UC_API', 'http://bbs.mophp.com/uc_server');
define('UC_CHARSET', 'utf-8');
define('UC_IP', '');
define('UC_APPID', 3);
define('UC_PPP', 20);

$gGlobalConfig['closesms'] =  '0';
//下面2个配置为了兼容老会员增加的配置
$gGlobalConfig['compatible_plat'] = array(
	'1'=>array('type'=>'sina', 'type_name'=>'新浪微博'),
	'2'=>array('type'=>'renren', 'type_name'=>'人人网'),
	'3'=>array('type'=>'tencent', 'type_name'=>'腾讯微博'),
	'4'=>array('type'=>'douban', 'type_name'=>'豆瓣网'),
	'5'=>array('type'=>'netease', 'type_name'=>'网易微博'),
	'6'=>array('type'=>'qq', 'type_name'=>'腾讯QQ'),
	'7'=>array('type'=>'discuz', 'type_name'=>'Discuz论坛'),
);
$gGlobalConfig['compatible_type2id'] = array(
	'sina'=>'1',
	'renren'=>'2',
	'tencent'=>'3',
	'douban'=>'4',
	'netease'=>'5',
	'qq'=>'6',
	'discuz'=>'7',
);
define('CLIENT_VERSION', '1.0');//兼容的版本

$gGlobalConfig['used_search_condition'] =  array (
);

 /***1.6.0 + 新增参数**/
define('SPREADDTONLY', 1);//推广设备唯一，开启后，一个设备只能被推广1次！

 /***1.6.2 + 新增参数**/
//会员后台添加可选注册类型开关
$gGlobalConfig['closeRegTypeSwitch'] =  array (
  'm2o' => '0',
  'shouji' => '0',
  'email' => '0',
);
$gGlobalConfig['closeRegTypeSwitchAppid'] =  '';//closeRegTypeSwitch配置生效appid，空则全局生效，否则仅限制填写的客户端

$gGlobalConfig['closeLoginTypeSwitch'] =  array (
  'm2o' => '0',
  'shouji' => '0',
  'email' => '0',
);
$gGlobalConfig['closeLoginTypeSwitchAppid'] =  '';//closeLoginTypeSwitch配置生效appid，空则全局生效，否则仅限制填写的客户端


$gGlobalConfig['regConfig'] =  array (
  'title' => '',
  'close' => '0',
  'url' => '',
);
$gGlobalConfig['loginConfig'] =  array (
  'title' => '',
  'close' => '0',
  'url' => '',
);

//系统默认类型 ...
$gGlobalConfig['SystemMemberType'] = array(
'm2o'=>array(
'systemname' => '普通用户',
'name' => '普通用户',
'mark' => 'm2o',
'status' => 1
),
'shouji'=>array(
'systemname' => '手机快速注册',
'name' => '手机快速注册',
'mark' =>'shouji',
),
'email'=>array(
'systemname' => '邮箱快速注册',
'name' => '邮箱快速注册',
'mark' =>'email',
'status' => 1
)
);

$gGlobalConfig['autoRegReviseType'] =  '1';//用户名类型注册自动更正，开启后，可根据用户传用户名来判断属于何种注册类型，除非客户端或者网页支持此功能，否则建议关闭
$gGlobalConfig['autoLoginReviseType'] =  '1';//用户名类型登陆自动更正
$gGlobalConfig['avoidLoginVerifyCode'] =  'sina,qq,txweibo,wechat';//免除登陆验证码类型

 /***1.6.4 + 新增参数**/
$gGlobalConfig['identifierUserSystem'] =  '0';//是否强制检测多套用户系统
$gGlobalConfig['memberNameToNickName'] = 1;//是否强制把 客户端用户名字段传值 映射到 nick_name,只有当nick_name未传值时生效;
$gGlobalConfig['checkLoginType'] =  '0';//会员登陆类型不合法检测//为了默认兼容设置1为关闭检测，0为开启检测；关闭后，将不检测登陆用户名与类型不匹配问题

$gGlobalConfig['uniquetype'] = array(
					'0'=>'不限制',//不对数据唯一性进行限制
					'1'=>'限制',//限制数据唯一性，当有重复字段数据（根据设置禁止字段内容md5值判断）提交时警告用户已有相同数据存在
				);

//绑定邮箱是否需要验证码
define('BIND_EMAIL_NEED_VERIFYCODE',0);

//邮箱token过期时间
$gGlobalConfig['email_token_limit'] = array(
    'time_limit' => 1000,
    'num_limit' => 5
);
define('EMAIL_TOKEN_EXPIRE_TIME',1000);
define('MAX_SENDSMS_COUNT_LIMITS',300);
?>