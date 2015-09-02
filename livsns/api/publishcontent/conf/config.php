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
'database' => 'dev_publishcontent_new',
'charset'  => 'utf8',
'pconnect' => '',
);



define('DB_PREFIX','liv_');//定义数据库表前缀

define('APP_UNIQUEID','publishcontent');

define('IMG_URL', 'http://img.dev.hogesoft.com:233/');

$gGlobalConfig['is_need_audit'] =  '1';  //发布库里的内容是否需要审核

$gGlobalConfig['is_open_memcache'] = 0;  //是否开启memcache缓存

$gGlobalConfig['is_open_contentcache'] = 0;  //是否开启本地缓存

$gGlobalConfig['is_support_update_weight'] = 0;  //是否支持更新权重

$gGlobalConfig['publish_app'] = 'news,tuji,media_channel,livmedia,live,webvod,contribute';

$gGlobalConfig['is_open_xs'] = 1;     //是否开启了迅搜

$gGlobalConfig['html_tags'] = '<p><br><a><m2o_mark><h1><h2><h3><strong><b><ul><ol><li><u><del><blockquote>';     //配值保留的HTML标签


$gGlobalConfig['memcache_max_offset'] = 100; //memcache最大偏移量下进行缓存
$gGlobalConfig['memcache_max_count'] = 50;	//memcache最大取数量下进行缓存
$gGlobalConfig['memcache_groupname'] = 'pubcontent';	//memcache最大取数量下进行缓存

$gGlobalConfig['status'] = array(
	'1'=>'启用',
	'2'=>'未启用',
	);
	
$gGlobalConfig['client'] = array(
	'1'=>'网站',
	'2'=>'手机',
	'3'=>'机顶盒',
	);
$gGlobalConfig['default_client_id'] = 2;	
$gGlobalConfig['domain_type'] = array(
	'site' => 1,
	'column' => 2,
	);

$gGlobalConfig['order_field'] = array(
	'id-a' => 'ID-增',
	'id-d' => 'ID-减',
	'weight-a' => '权重-增',
	'weight-d' => '权重-减',
	'create_time-a' => '创建时间-增',
	'create_time-d' => '创建时间-减',
	'publish_time-a' => '发布时间-增',
	'publish_time-d' => '发布时间-减',
	);	
	
//时间搜索
$gGlobalConfig['create_date_search'] = array(
  -1 => '内容创建的所有时间段',
  2 => '昨天',
  3 => '今天',
  4 => '最近3天',
  5 => '最近7天',
  'other' => '自定义时间',
);
$gGlobalConfig['publish_date_search'] = array(
  -1 => '内容发布的所有时间段',
  2 => '昨天',
  3 => '今天',
  4 => '最近3天',
  5 => '最近7天',
  'other' => '自定义时间',
);

//生成内容静态页面的文件
$gGlobalConfig['make_content_file_suffix'] = '.html';
//默认的时间类型
$gGlobalConfig['default_time_format'] = 'Y-m-d H:i:s';
//取各个模块内容的api路径
$gGlobalConfig['get_content_api_path'] = CUR_CONF_PATH . 'data/api/';
//生成取内容api后缀
$gGlobalConfig['get_content_api_suffix'] = '.php';
//默认的取内容数
$gGlobalConfig['default_count'] = 1000;
//默认的网站终端的id
$gGlobalConfig['defalut_bundle_client_id'] = 2;
//content表各个字段集合
$gGlobalConfig['content_field'] = array('id','bundle_id','module_id',
										'struct_id','site_id','expand_id',
										'content_fromid','title','subtitle','brief',
										'keywords','indexpic','is_have_indexpic',
										'video','is_have_video',
										'outlink','share_num','comment_num','click_num',
										'child_num','weight','ip',
										'appid','appname','verify_time','verify_user','publish_time',
										'publish_user','create_user','create_time',
										'is_complete','template_sign'
										);
$gGlobalConfig['content_relation_field'] = array('id','bundle_id','module_id',
										'struct_id','site_id','expand_id',
										'content_fromid','title','subtitle','brief',
										'keywords','indexpic','is_have_indexpic',
										'video','is_have_video',
										'outlink','share_num','comment_num','click_num',
										'child_num','weight','ip',
										'appid','appname','verify_time','verify_user','publish_time',
										'publish_user','create_user','create_time',
										'is_complete','template_sign'
										);
$gGlobalConfig['sort_keyword'] = array('asc','desc','ASC','DESC');

$gGlobalConfig['update_type'] = array(
  1 => '手动',
  2 => '自动',
);

//默认的循环体
$gGlobalConfig['default_loop_body'] = "<li>{\$title}</li>";
//默认的父标签
$gGlobalConfig['default_father_tag'] = "ul";

$gGlobalConfig['get_page_regex'] = '/<img[\s]+class=\"pagebg\"[\s]+src=\".*?\.png\"[\s]+_title=\"(.*?)\"[\s]*\/*>/i';

$gGlobalConfig['pic_regex'] = '/<img[\s]+.*class=\"image\".*?src=\"(.*?\/)?(material\/.*?img\/)([0-9]*[x|-][0-9]*)\/(\d{0,4}\/\d{0,2}\/)(.*?)\".*?[\/]
?>/mi';

$gGlobalConfig['get_pic_regex'] = '/<img[\s]+class=\"image-refer\"[\s]+src=\"' . IMG_URL . '.*?\/([a-zA-z]+)\/[a-zA-Z_]+_(\d+)\.png\"\/*>/ie';

$gGlobalConfig['p_style_preg'] = '#<p[^>]*>#i';

$gGlobalConfig['levelLabel'] = array(0, 1, 2, 3, 10, 20, 30, 40, 50, 60, 70, 80, 90);

//ip正则表达式
define('IP_REGULAR','/^(([1-9]|([1-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.)(([1-9]|([1-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.){2}([1-9]|([1-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))$/');

//栏目默认的主目录
$gGlobalConfig['defalult_column_dir'] = 'folder';

//栏目默认首页文件名称
$gGlobalConfig['defalult_column_index_name'] = 'index';
	
$gGlobalConfig['is_not'] = array(
	'1'=>'是',
	'2'=>'否',
	);

$gGlobalConfig['site_produce_format'] = array(
	'1'=>'静态生成',
	'2'=>'动态生成',
	);

$gGlobalConfig['site_suffix'] = array(
	'.html'=>'.html',
	'.htm'=>'.htm',
	);

$gGlobalConfig['site_material_fmt'] = array(
	'0'=>'默认',
	'Y/m'=>'年/月',
	'Y/m/d'=>'年/月/日',
	);
	
$gGlobalConfig['column_produce_format'] = array(
	'1'=>'静态生成',
	'2'=>'动态生成',
	);
	
$gGlobalConfig['column_file'] = array(
	'index'=>'门户',
	'list'=>'列表',
);
$gGlobalConfig['column_suffix'] = array(
	'0'=>'.php',
	'1'=>'.html',
	);
//引用文章生成方式
$gGlobalConfig['article_maketype'] = array(
	'1'=>'实链接引用',
	'2'=>'虚链接引用',
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
//	1 => array(
//		'format'=>'content{ID}','brief'=>'content{ID} 字符+内容ID',
//	),
	2 => array(
		'format'=>'Y-m-d-{ID}','brief'=>'2012-07-12-ID 时间+内容ID',
	),
	3 => array(
		'format'=>'Y_m_d_{ID}','brief'=>'2012_07_12_ID  时间+内容ID',
	),
	4 => array(
		'format'=>'Ymd{ID}','brief'=>'20120712ID 时间+内容ID',
	),
	5 => array(
		'format'=>'MD5({ID})','brief'=>'MD5({ID}) MD5内容ID',
	),
	6 => array(
		'format'=>'Y_m_d_{ID}','brief'=>'2012_07_12ID  时间+内容ID',
	),
	7 => array(
		'format'=>'Ymd_{ID}','brief'=>'20120712_ID 时间+内容ID',
	),
	8 => array(
		'format'=>'Y-m-d{ID}','brief'=>'2012-07-12ID 时间+内容ID',
	),
	);
//上传文件的格式
$gGlobalConfig['file_types'] = array(
	'.zip',
	'.rar',
	'.html',
	'.htm',
	'.jpeg',
	'.gif',
	'.bmp',
	'.jpg'
	);
//压缩包格式
$gGlobalConfig['compression_types'] = array(
	'.zip',
	'.rar'
	);
//上传的图片格式
$gGlobalConfig['pic_types'] = array(
	'.jpeg',
	'.gif',
	'.bmp',
	'.jpg'
	);
//上传模板来源
$gGlobalConfig['template_sources'] = array(
	'1'=>'网页模板',
	'2'=>'WAP模板',
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
	'self'=>'本身模板',
	'child'=>'子级模板',
	'content'=>'内容模板',
	);

//借口协议
$gGlobalConfig['api_protocol'] = array(
	1=>'HTTP',
	2=>'HTTPS',
);
//请求方式
$gGlobalConfig['request_type'] = array(
	1=>'GET',
	2=>'POST',
);
//数据格式
$gGlobalConfig['data_format'] = array(
	1=>'JSON',
	2=>'XML',
	3=>'HTML',
	4=>'TEXT',
);

/**发布库是否同步到云端发布库**/

$gGlobalConfig['is_syn_clouds'] = 0;//是否开启云端同步
$gGlobalConfig['App_applant']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/livcms/',
);
$gGlobalConfig['publishcontent_cloud']=array(
	'host' => 'publishcontent_api.hogesoft.com',
	'dir' => 'publishcontent/',
);
/****/


$gGlobalConfig['is_open_seo'] = 0;//是否seo 针对8684

define('INITED_APP', true);

$gGlobalConfig['used_search_condition'] =  array (
);

$gGlobalConfig['content_cache_time'] = 600; //正文内容缓存时间，秒
?>