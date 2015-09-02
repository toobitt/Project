<?php
$gDBconfig = array(
	'host' => 'db.dev.hogesoft.com',
	'user' => 'root',
	'pass' => 'hogesoft',
	'database' => 'dev_carpark',
	'charset' => 'utf8',
	'pconncet' => '0',
);
define('APP_UNIQUEID','carpark');//应用标识
define('DB_PREFIX','liv_');//定义数据库表前缀
define('BAIDU_AK','2d9c8c1bb23f3689ba05426d79ca011d');//百度地图密钥
define('BAIDU_GEOCODER_DOMAIN','http://api.map.baidu.com/geocoder/v2/?');
define('BAIDU_CONVERT_DOMAIN','http://api.map.baidu.com/ag/coord/convert?from=0&to=4');
define('EARTH_RADIUS', 6378.137);//地球半径 
define('PI', 3.1415926);//定义pi常量

//公告的状态
$gGlobalConfig['announcement_status'] = array(
 -1 => "所有状态",
  1 => "待审核",
  2 => "已审核",
  3 => "被打回",
);

//停车场状态
$gGlobalConfig['carpark_status'] = array(
 -1 => "所有状态",
  1 => "待审核",
  2 => "已审核",
  3 => "被打回",
);

//停车场服务时间类型
$gGlobalConfig['server_time_type'] = array(
 -1 => "所有时间",
  1 => "平时",
  2 => "工作日",
  3 => "周末",
  4 => "特定",
);

//停车场收费标准类型
$gGlobalConfig['collect_fees_type'] = array(
 -1 => "所有类型",
  1 => "人工收费",
  2 => "咪表收费",
  3 => "包月收费",
  4 => "免费",
);

//停车场计费单位
$gGlobalConfig['charge_unit'] = array(
 -1 => "所有单位",
  1 => "1小时",
  2 => "半小时",
  3 => "次",
  4 => "月",
  5 => "15分钟",
  6 => "自定义",
);

//停车场车型
$gGlobalConfig['car_type'] = array(
 -1 => "全部车型",
  1 => "大型",
  2 => "中型",
  3 => "小型",
);

//停车场结构类型
$gGlobalConfig['struct_type'] = array(
 -1 => "全部结构",
  1 => "露天",
  2 => "室内",
  3 => "地下",
  4 => "地上",
  5 => "机械式",
  6 => "坡道式",
  7 => "混凝土结构",
  8 => "钢结构",
  9 => "多层",
  10 => "其他",
);

//停车场结构类型
$gGlobalConfig['week'] = array(
  0 => "周日",
  1 => "周一",
  2 => "周二",
  3 => "周三",
  4 => "周四",
  5 => "周五",
  6 => "周六",
);

define('INITED_APP', true);
?>