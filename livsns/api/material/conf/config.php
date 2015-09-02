<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: config.php 6705 2012-05-14 09:48:30Z wangleyuan $
***************************************************************************/

$gDBconfig = array(
'host'     => 'db.dev.hogesoft.com',
'user'     => 'root',
'pass'     => 'hogesoft',
'database' => 'dev_material',
'charset'  => 'utf8',
'pconncet' => 0,
);

define('DB_PREFIX','liv_');//定义数据库表前缀
define('IMG_DIR', '' . ROOT_PATH . 'uploads/');//附件绝对路径
define('IMG_URL', 'http://img.dev.hogesoft.com:233/');//附件访问路径
define('APP_UNIQUEID','material');//应用标识
define('CACHE_DIR', CUR_CONF_PATH . 'cache/');
define('WATER_PATH','material/water/img/');
define('MATERIAL_TMP_PATH','material/tmp/img/');

define('UPLOAD_FILE_LIMIT', 100);   //附件上传限制 M为单位
define('SKETCH_MAP_TIME',5);   //单位分钟

$gGlobalConfig['default_img'] =  'default/img/default.jpg';
$gGlobalConfig['realtime_refresh_cdn'] = 0;
$gGlobalConfig['imgdirs'] = array(
);
$gGlobalConfig['imgurls'] = array(
	'img1' => ''
);
$gGlobalConfig['curImgserver'] = 'img1';
$gGlobalConfig['default_size'] =  array (
  'label' => '100x75',
  'width' => '100',
  'height' => '75',
);
$gGlobalConfig['small_size']= array(
     'label' => '40x30',
	 'width' =>40,
	 'height' =>30,
);

$gGlobalConfig['default_index'] = array(
	'label' => '160x120',
	'width' => 160,
	'height' => 120,
);

$gGlobalConfig['material_style'] = array(
	-1    => '选择类型',
	'img' => '图片',
	'doc' => '文档',
	'real' => '视频',
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

//状态搜索
$gGlobalConfig['article_status']=array(
	1 =>'全部状态',
	2 =>'待审核',
	3 =>'已审核',
);


//类型搜索
$gGlobalConfig['pic_type']=array(
	1 => '全部类型',
	2 => 'jpg',
	3 => 'jpeg',
	4 => 'png',
	5 => 'gif',
);
//水印位置
$gGlobalConfig['water_position']=array(	
	0 => '选择位置',
	1 => '顶部居左',
	2 => '底部居左',
	3 => '顶部居右',
	4 => '底部居右',
	5 => '图片中心',
);


/*水印字体*/
$gGlobalConfig['water_font'] = array(
	  'arial.ttf' => '幼圆字体',
	  'simfang.ttf' => '仿宋字体',
	  'simhei.ttf' => '黑体',
	  'simkai.ttf' => '楷体',
	  'msyh.ttf' => '微软雅黑',
);

/*水印字体大小*/
$gGlobalConfig['water_angle'] = array(
	  1 => '水平',
	  2 => '垂直',
);


/*图片水印自适应大小比例*/
$gGlobalConfig['image_water_ratio'] = 5;

//是否删除缩略图
$gGlobalConfig['deletethumb'] = 0;

define('INITED_APP', true);
?>