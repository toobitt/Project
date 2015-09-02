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
'database' => 'dev_weather',
'charset'  => 'utf8',
'pconnect' => '',
);
define('DB_PREFIX','liv_');//定义数据库表前缀
define('APP_UNIQUEID','weather');//应用标识
define('WEATHER_DAYS', 6);
define('IS_DEFAULT_CITY', 1);//是否使用默认城市 1-使用 0-不适用
define('COPY_SIX_DAY',1);
$gGlobalConfig['city'] = array(
1=>'省',
2=>'市',
);
//实时天气接口
$gGlobalConfig['weather_realtime'] =  array (
  'host' => 'www.weather.com.cn',
  'dir' => 'data/sk/',
);
//指数接口
$gGlobalConfig['weather_zs'] = array(
	'host'=>'m.weather.com.cn',
	'dir'=>'zs/'
);
$gGlobalConfig['default_city_id'] =  array (
  'id' => '2',
);
$gGlobalConfig['default_pm25_city'] =  ''; //没有pm25数据的城市调用
//pm25
define('PM25TOKEN', 'Ey2Mk9rvp7pidTRaqjHz');
define('CACHE_TIME', 3600);
$gGlobalConfig['pm25api'] = array(
	'cities'	=>'http://www.pm25.in/api/querys.json',
	'stations'	=>'http://www.pm25.in/api/querys/station_names.json',
	'pm25data'	=>'http://www.pm25.in/api/querys/pm2_5.json',
	'aqidata'	=>'http://www.pm25.in/api/querys/aqi_details.json'
);
//指数图片配置
$gGlobalConfig['zs_image'] =  array (
  'cy' => 
  array (
    'host' => 'http://img.dev.hogesoft.com:233/',
    'dir' => 'material/weather/img/',
    'filepath' => '2012/10/',
    'filename' => '20121023110710YZaT.gif',
  ),
  'xc' => '',
  'ls' => '',
  'uv' => '',
  'yd' => '',
  'ys' => '',
  'ac' => '',
  'ag' => '',
  'be' => '',
  'cl' => '',
  'co' => '',
  'dy' => '',
  'fs' => '',
  'gj' => '',
  'gm' => '',
  'gz' => '',
  'hc' => '',
  'jt' => '',
  'lk' => '',
  'mf' => '',
  'nl' => '',
  'pj' => '',
  'pk' => '',
  'pl' => '',
  'pp' => '',
  'sg' => '',
  'tr' => '',
  'xq' => '',
  'yh' => '',
  'zs' => '',
);
define('INITED_APP', true);
?>