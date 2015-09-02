<?php
	$gDBconfig = array(
	'host' => 'db.dev.hogesoft.com',
	'user' => 'root',
	'pass' => 'hogesoft',
	'database' => 'dev_supermarket',
	'charset' => 'utf8',
	'pconncet' => '0',
);
define('APP_UNIQUEID','supermarket');//应用标识
define('DB_PREFIX','liv_');//定义数据库表前缀
define('BAIDU_CONVERT_DOMAIN','http://api.map.baidu.com/ag/coord/convert?from=0&to=4');
define('EARTH_RADIUS', 6378.137);//地球半径 
define('PI', 3.1415926);//定义pi常量
define('NEED_DISABLE_SORT',8);//输出商品数据时需要去除的某一分类下的id
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
//商家超市状态
$gGlobalConfig['market_status'] = array(
 -1 => "所有状态",
  1 => "待审核",
  2 => "已审核",
  3 => "已打回",
);

//商家超市状态
$gGlobalConfig['status_color'] = array(
  1 => "#8ea8c8",
  2 => "#17b202",
  3 => "#f8a6a6",
);

//特惠活动状态
$gGlobalConfig['activity_status'] = array(
 -1 => "所有状态",
  1 => "即将开始",
  2 => "活动进行中",
  3 => "已经结束",
);

//特惠商品状态
$gGlobalConfig['product_recommend'] = array(
 -1 => "所有商品",
  1 => "未推荐商品",
  2 => "推荐商品",
);

//商超会员状态
$gGlobalConfig['member_status'] = array(
 -1 => "所有状态",
  1 => "未绑定",
  2 => "已绑定",
);

//消息通知发送给的对象的范围
$gGlobalConfig['message_scope'] = array(
  1 => "所有用户",
  2 => "特定用户",
  3 => "指定用户",
);

//消息通知的状态
$gGlobalConfig['message_status'] = array(
 -1 => "所有状态",
  1 => "未发送",
  2 => "已发送",
);

//消息通知的状态
$gGlobalConfig['product_status'] = array(
 -1 => "所有状态",
  1 => "待审核",
  2 => "已审核",
  3 => "已打回",
);


//星座配置
$gGlobalConfig['constellation'] = array(
  1 	=> "摩羯",
  2 	=> "水瓶",
  3 	=> "双鱼",
  4		=> "白羊",
  5 	=> "金牛",
  6 	=> "双子",
  7 	=> "巨蟹",
  8 	=> "狮子",
  9 	=> "处女",
  10 	=> "天秤",
  11 	=> "天蝎",
  12 	=> "射手",
);

//终端设备
$gGlobalConfig['device'] = array(
  1 	=> "IOS",
  2 	=> "Android",
);

//生日消息体
$gGlobalConfig['birthday_message'] = array(
  'title' 	=> "生日快乐",
  'content' => "今天是您的生日,祝您生日快乐！身体健康,阖家幸福！",
);

//生日消息体
$gGlobalConfig['city'] = array(
  'name' 	=> "无锡",
);

?>