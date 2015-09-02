<?php
$gDBconfig = array(
	'host' => 'db.dev.hogesoft.com',
	'user' => 'root',
	'pass' => 'hogesoft',
	'database' => 'dev_meeting',
	'charset' => 'utf8',
	'pconncet' => '0',
);
define('APP_UNIQUEID','meeting');//应用标识
define('APP_NAME','会议');//会议应用名称,在推送消息的时候可以使用
define('DB_PREFIX','liv_');//定义数据库表前缀
define('APPID', '6');
define('APPKEY', '3ntzQET0v4rHzrGFDPIW1ScMP4yFHMGA');
define('VCARD_DIR',DATA_DIR . 'vcard/');//vcard二维码图片存放目录
define('INITED_APP', true);

/*************************地理相关配置*************************/
define('EARTH_RADIUS', 6378.137);//地球半径 
define('PI', 3.1415926);//定义pi常量
define('MEETING_DISTANCE',1000);//定义开会地点与参会人在哪个距离范围内属于“附近”的概念,单位是米
define('BAIDU_CONVERT_DOMAIN','http://api.map.baidu.com/ag/coord/convert?from=0&to=4');
/*************************地理相关配置*************************/

/*************************会议相关配置*************************/
define('SIGN_STIME','2014-02-13 08:00:00');//签到开始时间
define('SIGN_ETIME','2014-04-13 17:00:00');//签到结束时间
define('LIVE_STIME','2014-04-11 09:00:00');//现场直播的开始时间
define('IS_VERIFY_GPS',1);//定义是否验证GPS坐标
/*************************会议相关配置*************************/

/****************************ios推送相关配置*******************/
define('IS_APP_PUBLISHED',0);//定义该应用是否是发布版本
define('IOS_PEMS','push.pems');//定义ios推送用到的证书
/****************************ios推送相关配置*******************/

/****************************极光推送相关配置*******************************/
define('JPUSH_APP_KEY', '0adcaaa3cd9a3c1bc09237da');//在极光推送上注册的应用标识
define('MASTER_SECRET', 'b806b77844b268318c9cb970');//API MasterSecret
/****************************极光推送相关配置*******************************/

//时间搜索
$gGlobalConfig['date_search'] = array(
  1 => '所有时间段',
  2 => '昨天',
  3 => '今天',
  4 => '最近3天',
  5 => '最近7天',
  'other' => '自定义时间',
);

//直播流地址
$gGlobalConfig['live_stream'] = array(
	'status' 		=> 'success',
	'stream_url' 	=> 'http://nlive.dev.hogesoft.com/testpush/playlist.m3u8',
);

//开会地点的经纬度
$gGlobalConfig['meeting_pos'] = array(
	'x' 	=> '118.7758636474609',//经度
	'y' 	=> '31.98172760009766',//纬度
);

//会场大屏的id号
$gGlobalConfig['screen_ids'] = array(
	0 => 'LED_1',
	1 => 'LED_2',
	2 => 'LED_3',
	3 => 'LED_4',
);

//嘉宾类型
$gGlobalConfig['guest_type'] = array(
	0 => '所有嘉宾',
	1 => '场外嘉宾',
	2 => '场内嘉宾',
	3 => '演讲嘉宾',
	4 => '媒体嘉宾',
);

?>