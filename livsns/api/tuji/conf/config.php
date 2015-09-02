<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('APP_UNIQUEID','tuji');
$gDBconfig = array(
'host'     => '10.0.1.31',
'user'     => 'root',
'pass'     => 'hogesoft',
'database' => 'dev_tuji',
'charset'  => 'utf8',
'pconnect' => '',
);
 
define('DB_PREFIX','liv_');//定义数据库表前缀
define('PUBLISH_SET_ID',26);		//图集表发布计划配置ID
define('PUBLISH_SET_SECOND_ID',27);		//图片表发布计划配置ID
define('DESCRIPTION_TYPE', 1);		//图集描述类型，0为默认继承图集摘要，1为默认使用图片名称，2为人工编辑
define('APPLICATION_ID',15);//outpush appid
$gGlobalConfig['thumb'] = array(
	'prefix' => 'tuji_',
	'width'=>'95',
	'height'=>'95',
);

$gGlobalConfig['image_upload_status'] = array(
  0 => "全部状态",
  -1 => "待审核",
  1 => "已审核",
  2 => "已打回"
);

$gGlobalConfig['default_state'] =  '-1';//图集创建默认状态//优先根据权限判断，如果权限设置为根据系统默认此配置才有效

//时间搜索
$gGlobalConfig['date_search'] = array(
  1 => '所有时间段',
  2 => '昨天',
  3 => '今天',
  4 => '最近3天',
  5 => '最近7天',
  'other' => '自定义时间',
);

/*水印字体大小*/
$gGlobalConfig['water_font'] = array(
	  1 => '1号字体',
	  2 => '2号字体',
	  3 => '3号字体',
	  4 => '4号字体',
	  5 => '5号字体',
);

/*水印字体大小*/
$gGlobalConfig['water_angle'] = array(
	  1 => '水平',
	  2 => '垂直',
);


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
    'status' => array(
        'name'  => '状态',
        'key' => 'status',
        'data' => 'image_upload_status',
    ),  
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



$gGlobalConfig['publish_main_url'] = 0;     //发布到多个栏目只有一个链接
define('INITED_APP', true);



$gGlobalConfig['used_search_condition'] =  array (
);
?>