<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
$gDBconfig = array(
'host'     => 'db.dev.hogesoft.com',
'user'     => 'root',
'pass'     => 'hogesoft',
'database' => 'dev_reporter',
'charset'  => 'utf8',
'pconncet' => 0,
);
define('DB_PREFIX','liv_');//定义数据库表前缀
define('APP_UNIQUEID','reporter');//应用标识
define('MANIFEST','manifest.f4m');//标注视频文件
define('CONTRIBUTE_PLAN_SET_ID',97);		//记者发稿内容发布计划配置ID
define('MATERIALS_PLAN_SET_ID',98);			//记者发稿素材发布计划配置ID
define('BOUNTY',true); //赏金开关
define('DEFAULT_POSITION', '南京');
define('INITED_APP', true);


//时间搜索
$gGlobalConfig['date_search'] = array(
  1 => '所有时间段',
  2 => '昨天',
  3 => '今天',
  4 => '最近3天',
  5 => '最近7天',
  'other' => '自定义时间',
);
$gGlobalConfig['contribute_audit'] = array(
1=>'未审核',
2=>'已审核',
3=>'被打回',
);

$gGlobalConfig['video_type'] = array(
	'0'=>'flv',
	'1'=>'3gp',
	'2'=>'mp4',
	'3'=>'mpg',
	'4'=>'avi',
	'5'=>'swf',
	'6'=>'asf',
	'7'=>'mkv',
	'8'=>'mov',
	'9'=>'mpeg',
	'10'=>'rmvb',
	'11'=>'wmv',
	'12'=>'f4v',

);
$gGlobalConfig['App_suobei'] = array(
	'is_open'=>0,       								//1是开启，0是关闭
	'ftp'=>array(
		'host'=>'10.0.1.52',
		'username'=>'dayang',
		'password'=>'123456',	
	),
	'display_name'=>'迁发索贝',
	'xmldir'=>ROOT_PATH .'uploads/sobey/',
	'xmlpath'=>'Z:/FTPUpload/',                       //视频路径前缀
);
$gGlobalConfig['bounty'] = array(
	0=>'未付费',
	1=>'已付费',
);

$gGlobalConfig['userinfo'] = array(
	'username'=>'姓名',
	'tel'=>'电话',
	'addr'=>'住址',
	'email'=>'邮件',
);
$gGlobalConfig['con_api_protocol'] = array(
1=>'HTTP',
2=>'HTTPS',
);
$gGlobalConfig['con_request_type'] = array(
1=>'GET',
2=>'POST',
);
$gGlobalConfig['con_dictionary'] = array(
	'0'=>'请选择',
	'title'=>'标题',
	'brief'=>'描述',
	'index_pic'=>'索引图',
	'longitude'=>'经度',
	'latitude'=>'纬度',
	'content'=>'内容',
	'picture'=>'图片',
	'video'=>'视频',
	'user_name'=>'报料人',
);
$gGlobalConfig['APP_livmedia'] = array(
	'host'=>'localhost',
	'dir'=>'livsns/api/livmedia/admin/'
);
$gGlobalConfig['reporter_sex'] = array(
	0=>'保密',
	1=>'男',
	2=>'女',
);
$gGlobalConfig['reporter_status'] = array(
	0=>'未审核',
	1=>'已审核',
	2=>'被打回',
);
?>