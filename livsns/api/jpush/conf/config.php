<?php
$gDBconfig = array('host' => 'db.dev.hogesoft.com',
'user' => 'root',
'pass' => 'hogesoft',
'database' => 'dev_jpush',
'charset' => 'utf8',
'pconncet' => '0',
);
define('DB_PREFIX', 'liv_');
define('APP_UNIQUEID','jpush');

$gGlobalConfig['notice_state'] = array(
  -1=>"全部状态",
  1 => "推送成功",
  2 => "推送失败",
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

$gGlobalConfig['date_search'] = array(
  1 => '所有时间段',
  2 => '昨天',
  3 => '今天',
  4 => '最近3天',
  5 => '最近7天',
  'other' => '自定义时间',
);
define('INITED_APP', true);
?>