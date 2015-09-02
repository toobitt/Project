<?php
	$gDBconfig = array(
'host'     => 'db.dev.hogesoft.com',
'user'     => 'root',
'pass'     => 'hogesoft',
'database' => 'dev_xml',
'charset'  => 'utf8',
'pconnect' => '',
);
define('APP_UNIQUEID','xml');//应用标识
define('DB_PREFIX','liv_');//定义数据库表前缀
define('INITED_APP', true);
define('TARGET_DIR',''); //导出目录

$gGlobalConfig['export_count'] =  '3'; //每次导出条数

//时间搜索
$gGlobalConfig['date_search'] = array(
  1 => '所有时间段',
  2 => '昨天',
  3 => '今天',
  4 => '最近3天',
  5 => '最近7天',
  'other' => '自定义时间',
);

//时间搜索
$gGlobalConfig['date_search_config'] = array(
  1 => '所有时间段',
  'other' => '自定义时间',
);

//视频的状态
/*
$gGlobalConfig['video_upload_status'] = array(
 -2 => '全部状态',
 -1 => "转码失败",
  0 => "转码中",
  1 => "待审核",
  2 => "已审核",
  3 => "被打回",
  4 => "已暂停",
  5 => "已取消",
  6 => "下载出错",
);
*/
//搜索条件及对应小模板配置
//name 应用配置里显示名称  
//key  表单name值
//data 数据字段
//attr 其他属性  
$gGlobalConfig['search_condition'] = array(
    'key' => array(
        'name'      => '标题',   //显示名称
        'key'       => 'key', 	//表单name值
        'data'      => '',		//数据字段
        'attr'      => array(), //其他属性
    ),
     
    'pub_column_id' => array(
        'name'  => '栏目',
        'key' => 'pub_column_id',
    ),    
      
    'user_name' => array(
        'name'      => '添加人',
        'key'       => 'user_name',
        'data'      => '',
    ),
    /*
    'trans_status' => array(
        'name'  => '状态',
        'key' => 'trans_status',
        'data' => 'video_upload_status',
    ),
    */  
    'date_search' => array(
        'name'      => '时间',
        'key'       => 'date_search',
        'data'      => 'date_search',
        'attr'      => array(
            'start_time'        => 'start_time',
            'end_time'          => 'end_time',
        ),
    ),   
    'weight' => array(
        'name'      => '权重',
        'key'       => 'weight',
        'data'      => '',
        'attr'      => array(
        	'start_weight'     => 'start_weight',
        	'end_weight'       => 'end_weight',
		),
    ),                
);

define('LIMIT_NOT_SHOW', '');

$gGlobalConfig['used_search_condition'] =  array (
  0 => 'key',
  1 => 'pub_column_id',
  2 => 'user_name',
  3 => 'date_search',
  4 => 'weight',
);

$gGlobalConfig['count'] =  '1';
?>