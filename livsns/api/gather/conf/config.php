<?php
//数据库配置
$gDBconfig = array(
	'host'     => 'db.dev.hogesoft.com',
	'user'     => 'root',
	'pass'     => 'hogesoft',
	'database' => 'dev_gather',
	'charset'  => 'utf8',
	'pconnect' => '0',
);
define('DB_PREFIX','liv_');//定义数据库表前缀
define('APP_UNIQUEID','gather');//应用标识
//时间搜索
$gGlobalConfig['date_search'] = array(
  1 => '所有时间段',
  2 => '昨天',
  3 => '今天',
  4 => '最近3天',
  5 => '最近7天',
  'other' => '自定义时间',
);
$gGlobalConfig['gather_dict'] = array(
	'title'		=> '标题',
	'brief'		=> '描述',
	'index_pic' => '索引图',
	'content'	=> '内容',
	'pic'	=> '图片',
	'video'		=> '视频',
	'author'	=> '作者',
	'source'	=> '来源',
	'material_ids'=>'附件',
	'org_id'	=>'用户组织',
	'user_id'	=>'用户ID',
	'user_name'	=> '用户名',
	'source_url'=> '链接',
	'keywords'	=> '关键字',
	'subtitle'	=> '副标题',
);
define('INITED_APP', true);
?>