<?php
$gDBconfig = array(
	'host'     => 'db.dev.hogesoft.com', //221.226.87.26
	'user'     => 'root',
	'pass'     => 'hogesoft',
	'database' => 'dev_workbench',
	'charset'  => 'utf8',
	'pconncet' => 0,
);
define('DB_PREFIX','liv_');//定义数据库表前缀

define('DEBUG_MODE', false); //debug模式开关
define('DEVELOP_MODE',true); //开发模式开关

//测试客户安装配置
define('CUSTOM_APPID',29);
define('CUSTOM_APPKEY','OjEN52E9LieIe9yx8mfDZEpDlUnxuya9');

define('APPID', '55');
define('APPKEY', 'GLtPX7N7ijwb83wupXuIrEl1YvIeBbm7'); 


//define('GOOGLE_MAP_KEY', 'ABQIAAAAcR9WO5MhlzqSal8bHphuDRRyk9R03h4R4ZJDKZMqZnC8yqgEVRT2bGP3fqEsz7VY-3dTDzqLS7VQ7g');
define('GOOGLE_MAP_KEY', 'AIzaSyAdCOYn-vDh2HtKgkI5-w1v7hW7sa5XyDM');

define('DEFAULT_LOCATION', '31.998177x118.775253');

$gGlobalConfig['officeconvert']=array(
	'host' => 'officeconvert.hogesoft.com:8080',
	'dir' => 'converter/'
);
$gGlobalConfig['App_auth'] = array(
	'host' => 'api.dev.hogesoft.com',
	'dir' => 'auth/',
	'admin'=>'auth/admin/'
);

$gGlobalConfig['App_liv_mms']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/liv_mms/',
	'token' => '3e67f243c4965f5f233d2003cacfb16d',
);

$gGlobalConfig['App_appstore']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/appstore/',
	'token' => '3e67f243c4965f5f233d2003cacfb16d',
);

$gGlobalConfig['App_auth']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/auth/',
);
$gGlobalConfig['App_upserver']=array(
	'host' => 'vapi.dev.hogesoft.com',
	'dir' => 'api/',
	'token' => 'aldkj12321aasd',
);
$gGlobalConfig['App_mediaserver']=array(
	'host' => 'media.dev.hogesoft.com',
	'dir' => 'api/',
	'token' => 'aldkj12321aasd',
);
$gGlobalConfig['App_live']=array(
	'host' => 'api.dev.hogesoft.com',
	'dir' => 'live/',
);
$gGlobalConfig['App_livcms']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/livcms/',
);

$gGlobalConfig['App_ad']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/adv/',
);
$gGlobalConfig['App_weather']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/weather/',
);
$gGlobalConfig['App_publishconfig']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/publishcontent/',
);

$gGlobalConfig['App_material'] = array(
	'host' => '10.0.1.40',
	'dir' => 'livsns/api/material/',
	'token' => '3e67f243c4965f5f233d2003cacfb16d',
);

$gGlobalConfig['App_publishcontent']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/publishcontent/',
);
$gGlobalConfig['App_mcp_statues']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/mcp_statues/',
);
$gGlobalConfig['App_news']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/news/',
	
);
	
$gGlobalConfig['App_tuji']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/tuji/',
	
);
$gGlobalConfig['App_livmedia']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/livmedia/',
	
);
$gGlobalConfig['App_photoedit']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/photoedit/',
	'token' => '3e67f243c4965f5f233d2003cacfb16d',
);
//直播互动
$gGlobalConfig['App_interactive'] = array(
	'host' => 'dev.hogesoft.com',
	'mid' => array(
		'16' => 323,
		'13'  => 308,
	),
);
//专题配置
$gGlobalConfig['App_special']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/special/',
);
$gGlobalConfig['App_block']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/block/',
);
$gGlobalConfig['App_settings']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/settings/',
	'token' => '3e67f243c4965f5f233d2003cacfb16d',
);
$gGlobalConfig['App_publishsys']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/publishsys/',	
);
$gGlobalConfig['App_catalog'] =array(
	'host' => 'localhost',
	'dir' => 'livsns/api/catalog/',
	
);
$gGlobalConfig['App_textsearch'] = array(
	'protocol' => 'http://',
	'host' => 'localhost',
	'dir' => 'livsns/api/textsearch/',
);
$gGlobalConfig['App_verifycode'] = array(
    'protocol' => 'http://',
    'host' => 'localhost',
    'dir' => 'livsns/api/verifycode/',
);

$gGlobalConfig['App_livmedia'] = array(
    'protocol' => 'http://',
        'host' => 'localhost',
            'dir' => 'livsns/api/livmedia/',
);

$gGlobalConfig['version'] = '5.0.0';

define('INITED_APP', true);
?>
