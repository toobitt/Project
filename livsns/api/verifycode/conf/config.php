<?php
$gDBconfig = array(
'host'     => 'db.dev.hogesoft.com',
'user'     => 'root',
'pass'     => 'hogesoft',
'database' => 'dev_verify_code',
'charset'  => 'utf8',
'pconnect' => '',
);
define('DB_PREFIX','liv_');//定义数据库表前缀
define('APP_UNIQUEID','verifycode');//应用标识
define('IS_BINARY', true);//$binary
$gGlobalConfig['max_size'] = ini_get('upload_max_filesize'); //文件上传限制大小
//验证码默认长度
//$gGlobalConfig['verify_code_length'] = 6;
//验证码有效期(秒)
$gGlobalConfig['verify_code_valid'] =  '30';
//验证码背景图片缩放后尺寸
$gGlobalConfig['width'] =  '120';
$gGlobalConfig['height'] =  '35';
//短信验证码所采用的字符串
$gGlobalConfig['verify_message_str'] = '0123456789'; //'abcdefghijklmnopqrstuvwxyz';
//时间搜索
$gGlobalConfig['date_search'] = array(
  1 => '所有时间段',
  2 => '昨天',
  3 => '今天',
  4 => '最近3天',
  5 => '最近7天',
  'other' => '自定义时间',
);
$gGlobalConfig['status'] = array(
  -1=> '全部状态',
  0 => '待审核',
  1 => '已审核',
  2 => '已打回',
);

$gGlobalConfig['verify_type'] = array(
  1 => '纯数字',
  2 => '纯字母',
  3 => '数字和字母',
  4 => '汉字',
  5 => '算术',
);

$gGlobalConfig['operation'] = array(
  1 => '加法',
  2 => '减法',
  3 => '乘法',
  4 => '除法',
  5 => '随机',
);

define('INITED_APP', true);



















$gGlobalConfig['used_search_condition'] =  array (
);
?>