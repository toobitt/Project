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
'database' => 'dev_template_store',
'charset'  => 'utf8',
'pconnect' => '',
);

define('DB_PREFIX','liv_');//定义数据库表前缀
define('APP_UNIQUEID','template_store');//应用标识
define('INITED_APP', TRUE);


$gGlobalConfig['is_open_xs'] = 1;     //是否开启了迅搜


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
$gGlobalConfig['config_type'] = array(
'version'	=>'版本',
'style'		=>'风格',
'use'		=>'用途',
'color'		=>'色系',
);
$gGlobalConfig['template_status'] = array(
 -2 => '全部状态',
 -1 => "转码失败",
  0 => "转码中",
  1 => "待审核",
  2 => "已审核",
  3 => "被打回",
);

$gGlobalConfig['status_color'] = array(
	1 => "#8ea8c8",
	2 => "#17b202",
	3 => "#f8a6a6"
);

$gGlobalConfig['upload_settings'] = array(
'upload_url'=>$gGlobalConfig['App_mediaserver']['protocol'] . $gGlobalConfig['App_mediaserver']['host'] . $gGlobalConfig['App_mediaserver']['dir'] . '/admin/create.php',
'size_limit'=>'2048000000',
'file_types'=>'*.wmv;*.avi;*.dat;*.asf;*.rm;*.rmvb;*.ram;*.mpg;*.mpeg;*.3gp;*.mov;*.mp4;*.m4v;*.dvix;*.dv;*.dat;*.mkv;*.flv;*.vob;*.ram;*.qt;*.divx;*.cpk;*.fli;*.flc;*.mod;*.m4a;*.f4v;*.3ga;*.caf;*.mp3;*.vob;*.aac;*.amr;*.ts;'
);
?>