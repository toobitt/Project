<?php
$gDBconfig = array(
'host'     => 'db.dev.hogesoft.com',
'user'     => 'root',
'pass'     => 'hogesoft',
'database' => 'dev_ios_info',
'charset'  => 'utf8',
'pconnect' => '',
);
define('DB_PREFIX','liv_');
define('APP_UNIQUEID','mobile');
define('IMG_DIR','material/mobile_module/img/');

//重复推送失败消息次数
define('REPEAT_PUSH_NUM', 3);

//证书目录
define('ZS_DIR', DATA_DIR.'certificate/');

//文件模板路径
define('MOBILE_API_TPL','../api/apitpl.php');

define('YOUMENG_KEY','');

//防盗链token
define('MOBILE_ANTI_LINK_TOKEN', '2e93bb13b1d7f81845fd963dd15929a1');

//清除更新时间少于规定天数前的接口缓存
define('CLEAR_API_CACHE_TIME', 0.5);
$gGlobalConfig['force_update_icon'] =  '1';
$gGlobalConfig['advice_status'] = array(
  -1=>"全部状态",
  1 => "待审核",
  2 => "已审核",
  3 => "已发送",
  4 => "未发送",
);

$gGlobalConfig['outlink_version_ctrl'] = 0;
$gGlobalConfig['weburl'] = array(
	0 => 'http://dev.hogesoft.com'
);
$gGlobalConfig['client_state'] = array(
  -1=>"全部状态",
  1 => "正常",
  2 => "已卸载",
  3 => '失活',
);
$gGlobalConfig['sound'] = array(
  -1=>"全部声音",
  1 => "声音1",
  2 => "声音2",
);
$gGlobalConfig['debug'] = array(
	-1=>"全部版本",
	0=>'发布版',
	1=>'开发版',
);
$gGlobalConfig['api_protocol'] = array(
1=>'HTTP',
2=>'HTTPS',
);
$gGlobalConfig['request_type'] = array(
1=>'GET',
2=>'POST',
);
$gGlobalConfig['data_format'] = array(
1=>'json',
2=>'xml',
3=>'str',
);
$gGlobalConfig['cache_update'] = array(
0=>'从不更新',
1=>'每次更新',
2=>'每天更新',
);
$gGlobalConfig['date_search'] = array(
  1 => '所有时间段',
  2 => '昨天',
  3 => '今天',
  4 => '最近3天',
  5 => '最近7天',
  'other' => '自定义时间',
);

$gGlobalConfig['mobile_module_status'] = array(
	 -1 => '所有状态',
	  0 => '待审核',
	  1 => '已审核',
);
//
$gGlobalConfig['module_type'] = array(
	  1 => '原生',
	  2 => 'WEB',
);

$gGlobalConfig['default_state'] = 0;

define('INITED_APP', true);


$gGlobalConfig['used_search_condition'] =  array (
);

/**无线荆州 E线折扣 start*/
define('JSE_IMAGE_HOST','http://exzk.net/static/');//图片路径host
define('JSE_GOODSDETAIL_URL','http://api.dev.hogesoft.com:233/mobile/data/exzk/e_discount/shopping-detail.html');//图片路径host
define('JSE_ORDERDETAIL_URL','http://api.dev.hogesoft.com:233/mobile/data/exzk/e_discount/order-detail.html');//图片路径host
define('JSE_COUPONDETAIL_URL','http://api.dev.hogesoft.com:233/mobile/data/exzk/e_discount/pingjia-submit.html');//图片路径host
/**无线荆州 E线折扣 end*/

?>