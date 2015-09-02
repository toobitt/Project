<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: config.php 9348 2012-08-15 05:41:26Z wangleyuan $
***************************************************************************/

$gDBconfig = array(
'host'     => 'db.dev.hogesoft.com',
'user'     => 'root',
'pass'     => 'hogesoft',
'database' => 'dev_rules',
'charset'  => 'utf8',
'pconnect' => '',
);

define('DB_PREFIX','liv_');//定义数据库表前缀
define('APP_UNIQUEID','rules');//应用标识

define('PUBLISH_SET_ID',13);		//文章表发布计划配置ID
define('PUBLISH_SET_SECOND_ID',14);		//素材表发布计划配置ID

$gGlobalConfig['is_open_xs'] = 1;     //是否开启了迅搜

$gGlobalConfig['publish_main_url'] = 0;     //发布到多个栏目只有一个链接

$gGlobalConfig['default_size'] = array(
	'label' => '100x75',
	'width' => 100,
	'height' => 75,
);
$gGlobalConfig['small_size']=array(
     'label' => '40x30',
	 'width' =>40,
	 'height' =>30 ,
);

$gGlobalConfig['default_index'] = array(
	'label' => '160x120',
	'width' => 160,
	'height' => 120,
);


//引用素材模块
$gGlobalConfig['refer_module'] =  array (
		'tuji' 		=> '图集库',
		'vote' 		=> '投票',
		'livmedia'  => '视频库',
);

$gGlobalConfig['default_state'] = 0;


$gGlobalConfig['clear_config'] =  array (
  'clear_date' => '30',
  'clear_sort' => '139',
);

define('INITED_APP', true);


//搜索条件及对应小模板配置
$gGlobalConfig['search_condition'] = array(
    'key' => array(
        'name'      => '标题',   //显示名称
        'key'       => 'key', 	//表单name值
        'data'      => '',		//数据字段
        'attr'      => array(), //其他属性
    ),
    'author' => array(
        'name'      => '作者',
        'key'       => 'author',
        'data'      => '',
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
        'data' => 'state_search',
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

$gGlobalConfig['used_search_condition'] =  array (
);

$gGlobalConfig['maxpicsize'] =  '640x640';

$gGlobalConfig['autoSaveDraft'] =  '0';//是否开启自动草稿功能
$gGlobalConfig['grouptype'] =  '1';//管理员是否是编辑身份

$groupType = array(1);
?>