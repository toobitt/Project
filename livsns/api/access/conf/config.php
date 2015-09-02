<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: config.php 7642 2012-07-06 09:13:47Z wangleyuan $
***************************************************************************/

$gDBconfig = array(
'host'     => 'db.dev.hogesoft.com',
'user'     => 'root',
'pass'     => 'hogesoft',
'database' => 'dev_access',
'charset'  => 'utf8',
'pconnect' => '',
);

define('DB_PREFIX','liv_');		//定义数据库表前缀
define('SYNC_SPACE',1);			//同步的时间间隔 小时为单位
define('APP_UNIQUEID','access');
define('CACHE_DIR',CUR_CONF_PATH . 'cache/');
$gGlobalConfig['access_type'] = array(
	0 => 'click_num',   //点击次数
	1 => 'comm_num',	//评论次数
	2 => 'share_num',	//分享次数
	3 => 'down_num',	//下载次数
);

$gGlobalConfig['status'] = array(	
	0 => '未审核',
	1 => '已审核',
	2 => '被打回',
);

//时间搜索
$gGlobalConfig['date_search'] = array(
  1 => '所有时间段',
  2 => '昨天',
  3 => '今天',
  4 => '最近3天',
  5 => '最近7天',
  //'other' => '自定义时间',
);


//内容列表缓存过期时间  值为0时不适用缓存
$gGlobalConfig['cache_expire_time'] =  '360';

//基数范围
$gGlobalConfig['default_number'] =  '100,200';


define('INITED_APP', true);
?>