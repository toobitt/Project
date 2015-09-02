<?php 
define('UC_DBHOST', '10.0.1.80');
define('UC_DBUSER', 'root');
define('UC_DBPW', 'hogesoft');
define('UC_DBNAME', 'sns_ucenter');
define('UC_DBCHARSET', 'utf8');
define('UC_DBTABLEPRE', 'liv_');
define('UC_COOKIEPATH', '/');
define('UC_COOKIEDOMAIN', '');
define('UC_DBCONNECT', 0);
define('UC_CHARSET', 'utf-8');
define('UC_FOUNDERPW', 'f695d25ae420bf501458f14f87a914ce');
define('UC_FOUNDERSALT', '220924');
define('UC_KEY', '0Q20348meTfa5x6y2i2Hfu2w9l63d11qcYby6S9b9XaI670a7T1S77egaQ917j3l');
define('UC_SITEID', '022P3h8MeVfQ5N6M2I24fu2L916zdx1NcYbY659y99a46b077d1B7ReZam937z30');
define('UC_MYKEY', '0g2C3o8geIf35s6o2m2QfA2n906Jdt1Scabm6c9I94aW6c0O7l1t7Qemas9m7M3X');
define('UC_DEBUG', false);
define('UC_PPP', 20);

//屏蔽字接口配置
$gApiConfig = array(
	'host'   => '127.0.0.1',
	'apidir' => 'livsns/api/banword/',
);
//积分接口配置
$gCreditApiConfig = array(
'host' => '127.0.0.1',
'apidir' => 'livsns/api/'
);

$gGlobalConfig = array(
	'cookie_prefix'  => 'liv_',
	'cookie_domain'  => '',
	'cookie_path'    => '/',
);

//用户设置接口设置
$gUsetApiConfig = array(
'host' => '127.0.0.1',
'apidir' => 'livsns/api/users/',
);
$gSoapConfig = array(
'u' => 'root',
'p' => 'admin',
'wsdl_url' =>'http://10.0.1.80/hulu/livcms/plugin/content_server.wsdl'
);