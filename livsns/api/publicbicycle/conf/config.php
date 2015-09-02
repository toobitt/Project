<?php

$gDBconfig = array(
'host'     => 'db.dev.hogesoft.com',
'user'     => 'root',
'pass'     => 'hogesoft',
'database' => 'dev_public_bicycle',
'charset'  => 'utf8',
'pconnect' => '',
);
define('APP_UNIQUEID','publicbicycle');//定义应用
define('DB_PREFIX','liv_');//定义数据库表前缀

define('BAIDU_AK', '2d9c8c1bb23f3689ba05426d79ca011d');//百度地图密钥
define('BAIDU_GEOCODER_DOMAIN', 'http://api.map.baidu.com/geocoder/v2/?');
define('BAIDU_CONVERT_DOMAIN','http://api.map.baidu.com/ag/coord/convert?from=0&to=4');

define('BAIDU_CONVERT_DOMAIN_GOOGLE_TO_BAIDU', 'http://api.map.baidu.com/geoconv/v1/?from=3&to=5');
define('BAIDU_CONVERT_DOMAIN_GPS_TO_BAIDU', 'http://api.map.baidu.com/geoconv/v1/?from=1&to=5');
//时间搜索
$gGlobalConfig['date_search'] = array(
  1 => '所有时间段',
  2 => '昨天',
  3 => '今天',
  4 => '最近3天',
  5 => '最近7天',
  'other' => '自定义时间',
);
//状态
$gGlobalConfig['status'] = array(
	-1=>'所有状态',
	0=>'待审核',
	1=>'已审核',
	2=>'已打回'
);

//坐标转换
$gGlobalConfig['convert_set'] = array(
	0=>'不更新坐标',
	1=>'不处理坐标',
	2=>'GPS转百度',
	3=>'谷歌转百度',
	4=>'添加偏移量',
);
//接口字段映射
$gGlobalConfig['bike_filed_dict'] = array(
	'stationid'			=>'站点id',
	'station'			=>'站点名称',
	'totalnum'			=>'自行车总数',
	'currentnum'		=>'可借数量',
	'stationx'			=>'站点经度',
	'stationy'			=>'站点纬度',
	'address'			=>'站点地址',
);

//上传的图片格式
$gGlobalConfig['pic_types'] = array(
	'.jpeg',
	'.gif',
	'.bmp',
	'.jpg',
	'.png'
);
$gGlobalConfig['jwd'] = array(
	'wd' =>  array(
		'min' => '0',
		'max' => '90',
	),
	'jd' =>  array(
		'min' => '0',
		'max' => '180',
	),
);

define('CITY_NAME', '南京');//定义城市

define('EARTH_RADIUS', 6378.137);//地球半径 
define('PI', 3.1415926); 
	
define('INITED_APP', true);


$gGlobalConfig['used_search_condition'] =  array (
);
?>