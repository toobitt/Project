<?php
	$gDBconfig = array(
'host'     => 'db.dev.hogesoft.com',
'user'     => 'root',
'pass'     => 'hogesoft',
'database' => 'dev_cheapbuy',
'charset'  => 'utf8',
'pconnect' => '',
);
define('APP_UNIQUEID','cheapbuy');//应用标识
define('DB_PREFIX','liv_');//定义数据库表前缀
define('MANIFEST', 'manifest.f4m');	//标注视频文件
define('CHEAPBUY_LIVE_PORT', 80);	//直播端口

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
  -1=> '全部状态',
  0 => '待审核',
  1 => '已审核',
  2 => '已打回',
);
//组团方式
$gGlobalConfig['buy_type'] = array(
  1 => '团购',
  2 => '秒杀',
);
//库存计算方式
$gGlobalConfig['count_type'] = array(
  1 => '下单减库存',
);
//库存计算方式
$gGlobalConfig['execl_set'] = array(
	'订单号',
	'商品id',
	'用户名',
	'地址',
	'备注',
	'商品数量',
	'电话',
	'邮箱',
	'订单时间',
	'订单金额（包括邮费）',
	'邮费',
	'订单状态',
);
define('INITED_APP', true);
?>