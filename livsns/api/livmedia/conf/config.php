<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: config.php 5165 2011-11-26 08:45:05Z repheal $
***************************************************************************/

$gDBconfig = array(
'host'     => 'db.dev.hogesoft.com',
'user'     => 'root',
'pass'     => 'hogesoft',
'database' => 'dev_media',
'charset'  => 'utf8',
'pconnect' => '',
);
define('APP_UNIQUEID','livmedia');//应用标识
define('APPLICATION_ID',76);
define('DB_PREFIX','liv_');//定义数据库表前缀
define('MAINFEST_F4M','manifest.m3u8');//标注视频文件
define('LIMIT_NOT_SHOW', 'live_split_data');//不显示来自于哪些应用的视频
define('PUBLISH_SET_ID',25);

//技术审核转台
$gGlobalConfig['technical_review'] = array(
  -1 => "技审失败",
  1  => "待技审",
  2  => "技审中",
  3  => "技审完成",
);

//视频来源的频道
$gGlobalConfig['video_channel'] = array(
  0 => "新闻综合频道",
  1 => "体育综合频道",
  2 => "娱乐综合频道",
  3 => "经济综合频道"
);

//视频点播类型
$gGlobalConfig['video_upload_type'] = array(
  1 => "编辑上传", 
  2 => "网友上传", 
  3 => "直播归档", 
  4 => "标注归档"
);

//节点的属性
$gGlobalConfig['video_upload_type_attr'] = array(
  1 => array('color' => '#4AA44C'), 
  2 => array('color' => '#0F9AB9'), 
  3 => array('color' => '#BF8144'), 
  4 => array('color' => '#7E4DCB')
);

//码流的十种颜色值
$gGlobalConfig['bitrate_color'] = array(
  0 => '#b2c5e5', 
  1 => '#b2c5e5',
  2 => '#b2c5e5',
  3 => '#7e9cc2',
  4 => '#4e6c9e',
  5 => '#3f5d8f',
  6 => '#2f4974',
  7 => '#2f4974',
  8 => '#2f4974',
  9 => '#2f4974'
);

//时间搜索
$gGlobalConfig['date_search'] = array(
  1 => '所有时间段',
  2 => '昨天',
  3 => '今天',
  4 => '最近3天',
  5 => '最近7天',
  'other' => '自定义时间',
);

//视频的状态
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

//视频片段的类型
$gGlobalConfig['vcr_type'] = array(
 -1 => "选择片段",
  1 => '片头',
  2 => '片花',
  3 => '片尾',
  4 => '普通',
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
    'trans_status' => array(
        'name'  => '状态',
        'key' => 'trans_status',
        'data' => 'video_upload_status',
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
$gGlobalConfig['is_cloud']  = "";
$gGlobalConfig['is_link']  = "链接上传";


define('INITED_APP', true);
$gGlobalConfig['cloud_video'] =  array (
  'open' => '2',//1 只打开云 2只打开上传 3只打开is_cloud 4全部打开
  'user' => 'yun2',
  'pass' => '456',
  'client_id' => '1fa267b202f663587f70273a552ceb55',
  'cloud_video_oauth' => 'http://localhost/cloudvideo/index.php/oauth/get_access_token',
  'userspaceapi' => 'http://localhost/livsns/api/userSpace',
);
?>
