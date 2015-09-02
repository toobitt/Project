<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/

$gDBconfig = array(
	'host'     => 'db.dev.hogesoft.com',
	'user'     => 'root',
	'pass'     => 'hogesoft',
	'database' => 'dev_gongjiao1',
	'charset'  => 'utf8',
	'pconnect' => '',
);
define('APP_UNIQUEID','gongjiao');//应用标识
define('DB_PREFIX','m2o_');//定义数据库表前缀
define('INITED_APP', true);

define('BAIDU_AK', '2d9c8c1bb23f3689ba05426d79ca011d');//百度地图密钥
define('BAIDU_CONVERT_DOMAIN', 'http://api.map.baidu.com/ag/coord/convert?from=0&to=4');
define('BAIDU_CONVERT_DOMAIN_GPS_TO_BAIDU', 'http://api.map.baidu.com/geoconv/v1/?from=1&to=5');
define('EARTH_RADIUS', 6378.137);//地球半径 
define('PI', 3.1415926);


$gGlobalConfig['android_appid'] =  '6';//安卓客户端appid

$gGlobalConfig['busapi'] = array(
	'host' => 'html.wifiwx.com',
	'dir' => 'bus/',	
	'wsdl' => 'http://218.90.160.85:10086/BusTravelGuideWebService/bustravelguide.asmx?wsdl',	
);
$gGlobalConfig['8684api'] = array(
	'host' => 'api.8684.cn',
	'dir' => '',
	'key' => 'ee47beef0f0ff012d6e2fead1f7b0bbb'
);
$gGlobalConfig['8684api_'] = array(
	'host' => 'api.dev.hogesoft.com:233',
	'dir' => 'mobile/api/transit/',
	'key' => 'ee47beef0f0ff012d6e2fead1f7b0bbb'
);

$gGlobalConfig['city'] = array(
	'id' 	=> '1',
	'name'  => '无锡',
	);

$gGlobalConfig['areaname'] =  '北仑';

$gGlobalConfig['bus_tab'] =  '1';

$gGlobalConfig['database_no_use'] =  'dev_gongjiao1';
?>