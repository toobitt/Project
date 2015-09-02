<?php 
$gDBconfig = array(
	'host' => '10.0.1.31',
	'user' => 'root',
	'pass' => 'hogesoft',
	'database' => 'dev_mark',
	'charset' => 'utf8',
	'pconncet' => 0,
);
 
define('DB_PREFIX', 'liv_');
$db_config = $gDBconfig;

$gGlobalConfig['cat'] = array(
	'topic' => '话题',
	'team' => '小组',
	'action'=>'活动'
);
define(CHINESE_EQUAL_ENGLISH, true);//一个中文等于一个英文
define(MARK_NAME_LIMIT, 10);//标签的长度限制
define(KIND_NAME_LIMIT, 10);//标签的长度限制
define(CODEING, 'utf-8');//编码
define(REPEAT_SET, true);//可以重复设置同一事件
define(VAILD, 1);//有效数据
define(BACKSTAGE_DELETE, 0);//后台删除数据
define(DELETE_SYSTEM, true);//删除到默认
define(CHECK_DATA,true);//核对数据
define(MARK_LIMIT_NUM, 6);//对象最大的添加标签数
?>