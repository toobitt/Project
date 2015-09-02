<?php
	$gDBconfig = array(
	'host' => 'db.dev.hogesoft.com',
	'user' => 'root',
	'pass' => 'hogesoft',
	'database' => 'dev_cinema',
	'charset' => 'utf8',
	'pconncet' => '0',
);
define('APP_UNIQUEID','cinema');//应用标识
define('DB_PREFIX','liv_');//定义数据库表前缀
define('INITED_APP', true);
define('BAIDU_CONVERT_DOMAIN', 'http://api.map.baidu.com/ag/coord/convert?from=0&to=4');
define('BAIDU_AK','2d9c8c1bb23f3689ba05426d79ca011d');//百度地图密钥
define('BAIDU_GEOCODER_DOMAIN','http://api.map.baidu.com/geocoder/v2/?');
//define('EARTH_RADIUS', 6378.137);//地球半径 
//define('PI', 3.1415926);//定义pi常量


//时间搜索
$gGlobalConfig['date_search'] = array(
  1 => '所有时间段',
  2 => '昨天',
  3 => '今天',
  4 => '最近3天',
  5 => '最近7天',
  'other' => '自定义时间',
);

//地区类型
$gGlobalConfig['dimension'] = array(
  1 => '2D',
  2 => '3D',
  3 => '4D',
  4 => '5D',
);

//地区类型
$gGlobalConfig['area'] = array(
  1 => '内地',
  2 => '港台',
  3 => '日韩',
  4 => '欧美',
  5 => '其他',
);
    
//影片类型
$gGlobalConfig['movie_type'] = array(
  1 => '动作',
  2 => '喜剧',
  3 => '恐怖',
  4 => '悬疑',
  5 => '爱情',
  6 => '剧情',
  7 => '家庭',
  8 => '伦理',
  9 => '文艺',
  10 => '音乐',
  11 => '歌舞',
  12 => '科幻',
  13 => '西部',
  14 => '武侠',
  15 => '古装',
  16 => '惊悚',
  17 => '冒险',
  18 => '犯罪',
  19 => '纪录',
  20 => '战争',
  21 => '历史',
  22 => '传记',
  23 => '体育',
  24 => '科幻',
  24 => '魔幻',
  24 => '奇幻',
  25 => '动画',
  26 => '儿童',
);

?>