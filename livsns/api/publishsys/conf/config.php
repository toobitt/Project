<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: config.php 6831 2012-05-29 00:55:16Z repheal $
***************************************************************************/
$gDBconfig = array(
	'host'     => 'db.dev.hogesoft.com',
	'user'     => 'root',
	'pass'     => 'hogesoft',
	'database' => 'dev_publish',
	'charset'  => 'utf8',
	'pconncet' => 0,
);

define('MK_DEBUG',false);
define('MAGIC_DEBUG',false);

define('APPID','55');
define('APPKEY','GLtPX7N7ijwb83wupXuIrEl1YvIeBbm7');

define('APP_UNIQUEID','publishsys');//应用标识
define('DB_PREFIX','liv_');//定义数据库表前缀
define('IP_REGULAR','/^(([1-9]|([1-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.)(([1-9]|([1-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.){2}([1-9]|([1-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))$/');//ip正则表达式
define('TEMP_CACHE_DIR', CUR_CONF_PATH . 'cache/template/');
define('MODE_CACHE_DIR', CUR_CONF_PATH . 'cache/mode/');
define('CSS_CACHE_DIR', CUR_CONF_PATH . 'cache/css/');
define('TEMPLATES_DIR',CUR_CONF_PATH.'data/template/');
define('MKPUBLISH_DIR',CUR_CONF_PATH.'cache/mkpublish/');
define('TEMP_TEMCACHE',CUR_CONF_PATH.'cache/temp_temcache/');//页面临时缓存文件
define('DATA_URL','http://api.dev.hogesoft.com:233/publishsys/data/');
define('DATA_URL_LOCAL','http://api.dev.hogesoft.com:233/publishsys/data/');
define('ICON_URL',DATA_URL . 'icon/');
define('CELL_DATA_CACHE',CUR_CONF_PATH.'cache/celldata/');//单元内容缓存

//是否支持shtml
$gGlobalConfig['is_support_shtml'] = 1;

$gGlobalConfig['template_image_url'] = DATA_URL . 'template';
$gGlobalConfig['mode_image_url'] = DATA_URL . 'mode';
$gGlobalConfig['layout_image_url'] = DATA_URL . 'layout';
//默认套系标识
$gGlobalConfig['tem_style_default'] = 'default';
//默认站点id
$gGlobalConfig['site_default'] = 1;

//数据源的缓存目录
$gGlobalConfig['data_source_dir'] = CUR_CONF_PATH . 'cache/datasource/';

//m2o数据源的缓存目录
$gGlobalConfig['m2o_data_source_dir'] = CUR_CONF_PATH . 'lib/m2o/include/';

//对于正文页是否需要生成阅读全文分页
$gGlobalConfig['need_show_all_pages'] = 1;

//权重
$gGlobalConfig['levelLabel'] = array(0, 1, 2, 3, 10, 20, 30, 40, 50, 60, 70, 80, 90);

$gGlobalConfig['status'] = array(
	'1'=>'启用',
	'2'=>'未启用',
	);
	
$gGlobalConfig['domain_type'] = array(
	'site' => 1,
	'col' => 2,
	);
	
$gGlobalConfig['is_not'] = array(
	'1'=>'是',
	'2'=>'否',
	);

$gGlobalConfig['update_type'] = array(
  0 => '手动',
  1 => '自动',
);

//栏目下的内容目录格式
$gGlobalConfig['column_folderformat'] = array(
	'Y-m-d'=>'2012-07-12',
	'Y-m'=>'2012-07',
	'Y_m_d'=>'2012_07_12',
	'Y_m'=>'2012_07',
	'Ymd'=>'20120712',
	'Ym'=>'201207',
	'Y'=>'2012',
	'Y/m'=>'2012/07',
	'Y/md'=>'2012/0712',
	'Y/m/d'=>'2012/07/12',
	);
//栏目下的内容命名格式
$gGlobalConfig['column_fileformat'] = array(
	'content{ID}' => 'content{ID} 字符+内容ID',
	'Y-m-d-{ID}' => '2012-07-12-ID 时间+内容ID',
	'Y_m_d_{ID}' => '2012_07_12_ID  时间+内容ID',
	'Ymd{ID}' => '20120712ID 时间+内容ID',
	'MD5({ID})' => 'MD5({ID}) MD5内容ID',
	);
//上传文件的格式
$gGlobalConfig['file_types'] = array(
	'.html',
	'.htm',
	);
//压缩包格式
$gGlobalConfig['compression_types'] = array(
	'.zip'
	);
//上传的图片格式
$gGlobalConfig['pic_types'] = array(
	'.jpeg',
	'.gif',
	'.bmp',
	'.jpg'
	);
//模板分类
$gGlobalConfig['template_types'] = array(
	'0'=>'不限类型',
	'1'=>'通用栏目页',
	'2'=>'通用内容页',
	'3'=>'通用子级模板',
	);
//站点或栏目对应的模板块
$gGlobalConfig['site_col_template'] = array(
	'0'   =>    '首页',
	'-1'  =>   '列表模板',
	);
//图片归属
$gGlobalConfig['pic'] = array(
	'0'=>'-请选择-',
	'1'=>'模板图片',
	'2'=>'模板示意图',
	);
$gGlobalConfig['api_protocol'] = array(
	1=>'HTTP',
	2=>'HTTPS',
);
$gGlobalConfig['request_type'] = array(
	1=>'GET',
	2=>'POST',
);
$gGlobalConfig['data_format'] = array(
	1=>'JSON',
	2=>'XML',
    3=>'Str',
);
$gGlobalConfig['cache_update'] = array(
	1=>'无缓存',
	2=>'设定时间',
);
$gGlobalConfig['page_type'] = array(
	0=>'请选择',
	1=>'栏目',
	2=>'专题',
);

 //图片格式
$gGlobalConfig['pic_config'] = array(
	'jpg',
	'jpeg',
	'gif',
	'bmp',
	'png',
    'swf', //临时放此处
	);
	
//css格式
$gGlobalConfig['css_config'] = array(
	'css'
	);
	
//js格式
$gGlobalConfig['js_config'] = array(
	'js'
	);
	
//模板格式
$gGlobalConfig['html_config'] = array(
	'html',
	'htm'
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
$gGlobalConfig['cell_type'] = array(
  	0=>'静态',
  	1=>'动态',
  	2=>'JS调用',
  	3=>'自定义',
  	4=>'shtml', 
);  

$gGlobalConfig['fuctions'] = array(
  	1=>'get_name',
  	2=>'get_info',
  	3=>'get_type',
);  


$gGlobalConfig['default_argument'] = array(
  	'name'			=> 	array('名称','标识'),
  	'sign'			=>	array('name','sign'),
  	'default_value'	=>	array('name','default_value'),
  	'select'		=>	array('slect','slect'),
  	
  	
); 

$gGlobalConfig['separator'] = '_';

//生成正文每次取内容条数
$gGlobalConfig['content_num_time'] = 10;

//前端php框架
$gGlobalConfig['frame_filename'] = 'm2o';

//前端生成数据源目录
$gGlobalConfig['m2o_include'] = 'm2o/include/';

//前端生成样式目录名称
$gGlobalConfig['template_name'] = 't';

//前端生成样式目录名称
$gGlobalConfig['mode_name'] = 'm';

//样式类型
$gGlobalConfig['mode_type'] = array(
	'0'=>'其他',
	'1'=>'正文',
	);


//样式参数类型
$gGlobalConfig['mode_data_type'] = array(
	'text'		=>	'输入框',
	'select'	=>	'下拉框',
	'pic'		=>	'图片',
	'bgpic'		=>  '背景图片',
	'column'	=>	'栏目',
	'color'		=>	'颜色',
	'date'		=>	'日期',
	'textarea'  =>  '文本框',
	);

//数据源返回参数
$gGlobalConfig['data_source_out_argument'] = array(
	'name'			=>  array('title','subtitle','brief','keyword','indexpic'),
	'title'     	=>	array('标题','副标题','描述','关键字','索引图'),
	'value'			=>  array('title','subtitle','brief','keyword','indexpic'),
	'column_url'	=>	'栏目链接',
	'content_url'	=>	'内容链接',
	);
//the template default data configure
$gGlobalConfig['defaultdata'] = array(
	'name'			=>  array('title','brief','content','author','index_pic'),
	'title'     	=>	array('标题','简介','内容','作者','索引图'),
	);
	
	
$gGlobalConfig['dynpro_type'] = array(
	'1' => '直接输出',
	'2' => 'JS调用'
);

$gGlobalConfig['special_template'] = array(
	'site_id'   => -1,
	'sort_id'   => -1,
);

$gGlobalConfig['maketype'] = array(
	'1'=>'静态生成',
	'2'=>'动态生成',
);

$gGlobalConfig['column_file'] = array(
	'index'=>'门户',
	'list'=>'列表',
);

$gGlobalConfig['header_dom'] = array(
	'layout' => '<div class="clearfix"><div class="m2o-layout-title"><div class="layout-title"><h2 class="layout-title-left">{$header_text}</h2><div class="layout-title-right"></div></div><a href="{$more_href}">{$more_text}</a></div></div>',
	'cell'	 => '<div class="clearfix"><div class="m2o-cell-title"><div class="cell-title"><h2  class="cell-title-left">{$header_text}</h2><div class="cell-title-right"></div></div><a href="{$more_href}">{$more_text}</a></div></div>',	
);
	
$gGlobalConfig['special_uniqueid'] = 'special';

$gGlobalConfig['default_special_column_tem'] = 'moban_list';
	
$gGlobalConfig['publishsys_node'] = array(
	'template'				=>	'模板',
	'template_classify'		=>	'模板分类',
	'mode'					=>	'样式',
	'mode_sort'				=>	'样式分类',
	'data_source'			=>	'数据源',
	'page_manage'			=>	'页面管理',
	'template_style'		=>	'模板套系',
	'layout'				=>'布局',
	'dynpro'				=>'创建程序',
	'publishdefaultdata_cate_data'=>'默认数据',
	'deploy_tem'			=>'部署',
);

define('INITED_APP', true);
?>