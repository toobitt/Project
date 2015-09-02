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
'database' => 'dev_contribute',
'charset'  => 'utf8',
'pconnect' => '',
);
define('DB_PREFIX','liv_');					//定义数据库表前缀
define('APP_UNIQUEID','contribute');		//应用标识
//define('MANIFEST', 'manifest.m3u8');			//标注视频文件
define('MANIFEST', 'manifest.m3u8');			//标注视频文件
define('PUBLISH_SET_ID',78);				//报料内容发布计划配置ID
define('PUBLISH_SET_SECOND_ID',79);			//报料素材发布计划配置ID
define('BOUNTY', 'true'); 						//赏金开关
define('DEFAULT_POSITION', '南京');			//地图默认城市
define('CLAIM', 0);						//认领开关
define('VOD_PIC_NUM',9);					//默认取几张视频截图
$gGlobalConfig['areaname'] =  '南京';			//百度地址默认显示位置
define('BAIDU_CONVERT_DOMAIN', 'http://api.map.baidu.com/ag/coord/convert?from=0&to=4');
define('IS_VERIFYCODE', 0); 			//是否开启验证码
define('IS_CREDITS', 1); 			//是否未审核增加积分
define('IS_EXTRA_CREDITS', 1); 			//是否审核通过增加额外积分

define('CONTRIBUTE_AUDIT', 1); 			//报料默认状态
//时间搜索
$gGlobalConfig['date_search'] = array(
	1 		=> '所有时间段',
	2 		=> '昨天',
	3 		=> '今天',
	4 		=> '最近3天',
	5 		=> '最近7天',
	'other' => '自定义时间',
);

$gGlobalConfig['contribute_audit'] = array(
	1 => '未审核',
	5 => '待审核',
	2 => '已审核',
	3 => '被打回',
);

$gGlobalConfig['contribute_audit_color'] = array(
	1 => '#FF7F00',//'未审核'
	2 => '#0000FF',//已审核
	3 => '#FF0000',//被打回
	4 => '#FF1CAE',
	5 => '#FF1CAE',//待审核
);


$gGlobalConfig['contribute_report'] = array(
	0 => '不搜索记者',
	1 => '搜索记者',
);
$gGlobalConfig['contribute_follow'] = array(
	0 => '不搜索跟踪',
	1 => '搜索跟踪',
);

$gGlobalConfig['contribute_follow_return'] = array(
	0 => '未跟进',
	1 => '记者已跟进',
);


$gGlobalConfig['App_suobei'] = array(
	'is_open'		=> 0,       					//1是开启，0是关闭
	'ftp'			=> array(
						'host'	   => '10.0.1.52',
						'username' => 'dayang',
						'password' => '123456',	
					),
	'display_name' => '迁发索贝',
	'xmldir'	   => ROOT_PATH .'uploads/sobey/',
	'xmlpath'	   => 'Z:/FTPUpload/',            	//视频路径前缀
);
$gGlobalConfig['bounty'] = array(
	0 => '未付费',
	1 => '已付费',
);

$gGlobalConfig['userinfo'] = array(
	'username' => '姓名',
	'tel'      => '电话',
	'addr'     => '住址',
	'email'	   => '邮件',
);
$gGlobalConfig['con_api_protocol'] = array(
	1 => 'HTTP',
	2 => 'HTTPS',
);
$gGlobalConfig['con_request_type'] = array(
	1 => 'GET',
	2 => 'POST',
);
$gGlobalConfig['con_dictionary'] = array(
	'0'			=> '请选择',
	'title'		=> '标题',
	'brief'		=> '描述',
	'index_pic' => '索引图',
	'longitude' => '经度',
	'latitude'	=> '纬度',
	'content'	=> '内容',
	'picture'	=> '图片',
	'video'		=> '视频',
	'user_name'	=> '报料人',
);
define('INITED_APP', true);

define('CONTRIBUTE_NEW_MEMBER', 1);

$gGlobalConfig['used_search_condition'] =  array (
);
//敏感词过滤规则
$gGlobalConfig['contribute_colation'] = array(
  1 => "禁止入库", 
  2 => "入库未审核",
  3 => "过滤敏感词"
 );
define('IS_BANWORD', 1);
define('BAIDU_GEOCODER_DOMAIN','http://api.map.baidu.com/geocoder/v2/?');
define('BAIDU_AK','2d9c8c1bb23f3689ba05426d79ca011d');//百度地图密钥
define('COLATION_TYPE', 3);

define('UPLOAD_IMG_NUM', 2);


?>