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
'database' => 'dev_special',
'charset'  => 'utf8',
'pconnect' => '',
);

define('DB_PREFIX','liv_');//定义数据库表前缀
define('IP_REGULAR','/^(([1-9]|([1-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.)(([1-9]|([1-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.){2}([1-9]|([1-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))$/');//ip正则表达式
define('APP_UNIQUEID','special');//应用标识
define('CONTENT_COUNT','5');//内容条数
define('PUBLISH_SET_ID',113);		//专题发布计划配置ID
define('PUBLISH_SET_SECOND_ID',114);
define('PUBLISH_SET_THIRD_ID',115);

$gGlobalConfig['publish_main_url'] = 1; 

$gGlobalConfig['status'] = array(
	'1'=>'启用',
	'2'=>'未启用',
);
	
//状态搜索
$gGlobalConfig['state'] =  array (
  1 => '全部状态',
  2 => '待审核',
  3 => '已审核',
  4 => '已打回',
);

$gGlobalConfig['maketype'] = array(
	'1'=>'静态生成',
	'2'=>'动态生成',
);

$gGlobalConfig['column_file'] = array(
	'index'=>'门户',
	'list'=>'列表',
);
//状态颜色
$gGlobalConfig['state_color'] =  array (
  	0 => "#8ea8c8",
	1 => "#17b202",
	2 => "#f8a6a6",
);

//专题数据来源
$gGlobalConfig['data_source'] =  array (
  1 => '自定义添加',
  2 => '发布库',
);

//上传的图片格式
$gGlobalConfig['pic_types'] = array(
	'.jpeg',
	'.gif',
	'.bmp',
	'.jpg'
	);
	
//上传的附件格式
$gGlobalConfig['attachment'] = array(	
	'.txt',
	'.docx',
	'.doc',
	'.jpeg',
	'.gif',
	'.jpg',
	'.png'
);
	
$gGlobalConfig['video_upload_type'] = array(
  1 => "编辑上传", 
  2 => "网友上传", 
  3 => "直播归档", 
  4 => "标注归档"
);

//视频的状态
$gGlobalConfig['video_upload_status'] = array(
  -1 => "转码失败",
  0 => "转码中",
  1 => "待审核",
  2 => "已审核",
  3 => "被打回"
);

//生成方式
$gGlobalConfig['make_style'] = array(
  1 => "静态生成",
  2 => "动态生成",
);

//首页类型
$gGlobalConfig['index_type'] = array(
  'index' => "首页",
  'list' => "列表",
);

$gGlobalConfig['page_uniqueid'] = 'special';

define('INITED_APP', true);


define('DEFAULT_COLUMN', '默认栏目');
?>
