<?php

$gDBconfig = array(
'host'     => 'db.dev.hogesoft.com',
'user'     => 'root',
'pass'     => 'hogesoft',
'database' => 'dev_subway',
'charset'  => 'utf8',
'pconnect' => '',
);

define('DB_PREFIX','liv_');			//定义数据库表前缀
define('APP_UNIQUEID','subway');	//应用标识
define('CONTENT_COUNT','5');		//内容条数
define('PUBLISH_SET_ID',123);		//发布计划配置ID


define('DISTANCE',10000); // 距离

$gGlobalConfig['city'] = array(
  'name' 	=> "南京",
);

//审核状态
$gGlobalConfig['status'] = array(
  -1=> '全部状态',
  0 => '未审核',
  1 => '已审核',
  2 => '已打回',
);

//审核状态
$gGlobalConfig['status'] = array(
  -1=> '全部状态',
  0 => '未审核',
  1 => '已审核',
  2 => '已打回',
);

//手机显示类型
$gGlobalConfig['type'] = array(
  'list' 	=> '列表',
  'pic'  	=> '图片',
);


//状态搜索
$gGlobalConfig['state'] =  array (
  1 => '全部状态',
  2 => '待审核',
  3 => '已审核',
  4 => '已打回',
);

$gGlobalConfig['maketype'] = array(
	'1'=>'静态生成',
	'2'=>'动态生成',
);

//状态颜色
$gGlobalConfig['state_color'] =  array (
  	0 => "#8ea8c8",
	1 => "#17b202",
	2 => "#f8a6a6",
);

$gGlobalConfig['bus'] =  array (
    'host' => 'http://app.wifiwx.com/',
    'dir'  => 'bus/',
  );
  
define('EARTH_RADIUS', 6378.137);//地球半径 
define('PI', 3.1415926); 
	
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


define('BAIDU_AK','2d9c8c1bb23f3689ba05426d79ca011d');//百度地图密钥
define('BAIDU_GEOCODER_DOMAIN','http://api.map.baidu.com/geocoder/v2/?');
define('BAIDU_CONVERT_DOMAIN','http://api.map.baidu.com/ag/coord/convert?from=0&to=4');

define('INITED_APP', true);

?>
