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
	'database' => 'dev_publish_config',
	'charset'  => 'utf8',
	'pconncet' => 0,
);

define('DB_PREFIX','liv_');//定义数据库表前缀
define('IP_REGULAR','/^(([1-9]|([1-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.)(([1-9]|([1-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.){2}([1-9]|([1-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))$/');//ip正则表达式
define('APP_UNIQUEID', 'publishconfig');

//栏目默认的主目录
$gGlobalConfig['defalult_column_dir'] = 'folder';

//栏目默认首页文件名称
$gGlobalConfig['defalult_column_index_name'] = 'index';

$gGlobalConfig['status'] = array(
	'1'=>'启用',
	'2'=>'未启用',
	);
	
$gGlobalConfig['domain_type'] = array(
	'site' => 1,
	'column' => 2,
	);
	
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
	
$gGlobalConfig['column_suffix'] = array(
	'.php'=>'.php',
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
		'format'=>'Ymd_{ID}','brief'=>'20120712_ID 时间+内容ID',
	),
	5 => array(
		'format'=>'MD5({ID})','brief'=>'MD5({ID}) MD5内容ID',
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

define('INITED_APP', true);
?>