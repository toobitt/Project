<?php
	$gDBconfig = array(
'host'     => 'db.dev.hogesoft.com',
'user'     => 'root',
'pass'     => 'hogesoft',
'database' => 'dev_lottery',
'charset'  => 'utf8',
'pconnect' => '',
);
define('APP_UNIQUEID','lottery');//应用标识
define('DB_PREFIX','liv_');//定义数据库表前缀
define('INITED_APP', true);
define('BAIDU_CONVERT_DOMAIN', 'http://api.map.baidu.com/ag/coord/convert?from=0&to=4');
define('BAIDU_AK','2d9c8c1bb23f3689ba05426d79ca011d');//百度地图密钥
define('BAIDU_GEOCODER_DOMAIN','http://api.map.baidu.com/geocoder/v2/?');
define('EARTH_RADIUS', 6378.137);//地球半径 
define('PI', 3.1415926);//定义pi常量
$gGlobalConfig['areaname'] =  '南京';//百度地址默认显示位置

define('LOTTERY_DOMAIN', 'http://10.0.2.247/livsns/api/lottery/data/');
define('CLEAR_WIN_INFO_TIME', 1);

//计划任务缓存抽奖开关
$gGlobalConfig['lottery_filter'] =  '1';

//云抽奖配置
define('CORE_DIR', CUR_CONF_PATH.'core/');//定义模板目录
define('DATA_DIR', CUR_CONF_PATH.'data/');//定义h5缓存目录
define('APPID', '98');
define('APPKEY', 'nepUOH76IqirMNOwvxITEMnAA39O7bPl');
define('PASSWORD', '888888');
$gGlobalConfig['db_bind'] = array( //会员绑定数据库
 	'host'	=> 'db.dev.hogesoft.com',
	'user'     => 'root',
	'pass'     => 'hogesoft',
	'database' => 'dev_member_bind',
);
$gGlobalConfig['sign'] = array(
	'guaguaka'		=> 1,
	'dazhuanpan'	=> 2
);
//中奖信息筛选
$gGlobalConfig['win_status'] = array(
  0 => '所有参与',
  1 => '中奖者',
  2 => '未中奖',
);
//抽奖类型
$gGlobalConfig['lottery_type'] = array(
  1 => '刮刮卡',
  2 => '大转盘',
);

//奖品类型
$gGlobalConfig['prize_type'] = array(
  1 => '虚拟物品',
  2 => '实物',
);

//时间搜索
$gGlobalConfig['date_search'] = array(
  1 => '所有时间段',
  2 => '昨天',
  3 => '今天',
  4 => '最近3天',
  5 => '最近7天',
  'other' => '自定义时间',
);

$gGlobalConfig['state_search'] = array(
 '-1'  => '全部状态',
 '0'   => '待审核',
 '1'   => '已审核',
 '2'   => '已打回',
);

//库存锁开关
$gGlobalConfig['lock_stock'] =  '1';

$gGlobalConfig['winlist'] =  '1';

$gGlobalConfig['winlist_url'] =  '../tv_interact/index.html';

$gGlobalConfig['notstartdesc'] =  '活动尚未开始， 敬请期待';

$gGlobalConfig['finish_desc'] =  '活动已结束， 敬请期待下次活动.';

$gGlobalConfig['clear_un_win_info'] =  '0';

$gGlobalConfig['lottery_limit_tip'] =  '您已中奖,谢谢参与！';

$gGlobalConfig['lottery_win_info'] =  '0';

$gGlobalConfig['exchange_url'] =  'http://10.0.2.247/livsns/api/lottery/data/tv_interact/exchange.html';
?>