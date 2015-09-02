<?php
	$gDBconfig = array(
	'host' => 'db.dev.hogesoft.com',
	'user' => 'root',
	'pass' => 'hogesoft',
	'database' => 'dev_userspace',
	'charset' => 'utf8',
	'pconncet' => '0',
);
define('APP_UNIQUEID','userSpace');//应用标识
define('DB_PREFIX','liv_');//定义数据库表前缀
define('INITED_APP', true);

//时间搜索
$gGlobalConfig['date_search'] = array(
  1 => '所有时间段',
  2 => '昨天',
  3 => '今天',
  4 => '最近3天',
  5 => '最近7天',
  'other' => '自定义时间',
);
$gGlobalConfig['UpYun']['user_apiurl'] 		= 'https://api.upyun.com/accounts/';
$gGlobalConfig['UpYun']['client_id'] 		= '10302';
$gGlobalConfig['UpYun']['client_secret'] 	= '44fc672d41a92967bf6feb627621b6cd3b5a9a9a';
$gGlobalConfig['UpYun']['username'] 	= 'hogesoftayou';
$gGlobalConfig['UpYun']['password'] 	= 'hogesoftayou';
$gGlobalConfig['UpYun']['grant_type'] 	= 'password';

define('OAUTH_CLIENT_ID', '10302');
define('OAUTH_CLIENT_SECRET', '44fc672d41a92967bf6feb627621b6cd3b5a9a9a');
define('OAUTH_BASE_URI', 'https://api.upyun.com/');
define('OAUTH_AUTHORIZE_URI', 'https://api.upyun.com/oauth/authorize/');
define('OAUTH_ACCESS_TOKEN_URI', 'https://api.upyun.com/oauth/access_token/');
define('OAUTH_REDIRECT_URI', 'http://callbackurl/');
define('SPACENAMEPREFIX', 'hogesoft-');//空间名前缀
define('SPACEDOMAINPREFIX', 'hogesoft-');//域名前缀
define('SPACENAMELINKS', '');//空间名连接符号
define('SPACEDOMAINLINKS', '');//域名连接符号
define('SPACEDOMAIN', 'hoge.cn');//绑定域名
define('SPACEOPERATORS', 'hogesoft');//操作员帐号
define('SPACEOPERATORSPASSWORD', 'hogesoft');//操作员密码
define('SPACEQUOTA', 1000);//空间大小
define('SPACETYPE', 'file');//空间类型,目前仅支持file类型
$gGlobalConfig['space']['space_type'] = array(	
	'file'    => '文件空间',//文件空间，可以存储所有格式的文件，但是不能使用缩略图等功能
); 

$gGlobalConfig['default_role'] =  array (
  0 => '132',
);
$gGlobalConfig['form_api_param'] =  array (
'action' => 'http://v0.api.upyun.com/',
'allow_file_type'=>'*.flv;*.mp4;*.amr;*.mp3',
'allow_max_size'=>'2 GB',
'expiration'=>600,
'filepath'=>'/{year}/{mon}/{day}/upload_',
'suffix'=>'{.suffix}',
'notify-url'=>'http://218.2.102.114:8881/livsns/api/userSpace/callback.php?',
);
define('DEFAULT_ORG', 91);
define('TRANSCODE_PROGRESS_BAR', 'http://p0.api.upyun.com/status/');
define('DEFAULT_M3U8','http://hogesoft-1259.hoge.cn/2014/10/30/upload_1414662925_hls_time6_spauto_vb500.mp4.m3u8');
define('DEFAULT_IMG', '');
define('DEFAULT_LEIXING', 1);
define('ENCRYPT_VID_KEY', '23E23RWQER221#$$Q21DA');
define('SWF_PLAYER_URL', 'http://player.hogecloud.com/player.swf');
//define('DEFAULT_PLAYER_CONFIG', 'devod.xml');
define('CLOUD_VIDEO_DOMIAN','http://localhost/cloudvideo');//云播放url
define('LOG_LEVEL', 2); //0关闭 2调试 1错误 3调试和错误信息
define('LOG_FOR_USER', 'ALL');//只记录特定用户的日志 默认ALL所有用户
define('REWRITE', 1);//是否开启播放地址rewrite规则
define('DEFAULT_BALANCE', 100);//100云豆
define('CHARGE_STA', 7200);//新注册用户多久之后开始统计
define('CHARGE_LIMIT', 5);//每次计算的会员数目
define('UPLOAD_TYPE', 0);//1删除原文件
define('DISCHARGE', 0.45);//流出价格（元/GB 含税）
define('DEFAULT_ORG_2', 5);//正式用户
define('DEFAULT_BITRATE', '500');
define('DEFAULT_IMG_START', '00:00:05');
$gGlobalConfig['player'] = array(
'config_xml'=>'devod.xml',
//'player_url'=>'http://player.hoge.cn/player.swf',
'config_xml_prefix'=>'v',
'default_player_api'=>'http://play.hogecloud.com/player/vod/',
);
$gGlobalConfig['playerapi'] = array(
    'protocol'   => 'http://',
//    'host'       => '218.2.102.114:233',
	'host'       => 'playerapi.hogecloud.com',
    'dir'        => '',
);
$gGlobalConfig['user_type_name'] = array(
DEFAULT_ORG=>'正式用户',
DEFAULT_ORG_2=>'试用用户',
);
?>