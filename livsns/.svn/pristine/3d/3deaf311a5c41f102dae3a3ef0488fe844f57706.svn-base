<?php
$gDBconfig = array(
	'host' => '10.0.1.31',
	'user' => 'root',
	'pass' => 'hogesoft',
	'database' => 'dev_sns_action',
	'charset' => 'utf8',
	'pconncet' => 0,
);
 
define('DB_PREFIX', 'liv_');
define('APP_UNIQUEID','community');//应用标识
define('GROUP_PLAN_SET_ID',86);
define('THREAD_PLAN_SET_ID',87);

$db_config = $gDBconfig;

//小组权限定义
define('VISITE_TEAM', 0x00000001);          //小组浏览
define('VISITE_TOPIC', 0x00000002);         //话题浏览
define('ADD_TOPIC', 0x00000004);            //话题发布
define('REPLY_TOPIC', 0x00000008);          //话题回复
define('SUPPLY_CREATER', 0x00000010);       //申请活动召集者
define('ADD_ACTIVITY', 0x00000020);         //活动发布
define('VISITE_ACTIVITY', 0x00000040);      //活动浏览
define('COMMENT_ACTIVITY', 0x00000080);     //活动评论
define('BLACK_LIST', 0x00000100);           //黑名单操作

define('PERMISSION_ALL', VISITE_TEAM+VISITE_TOPIC+ADD_TOPIC+REPLY_TOPIC+SUPPLY_CREATER+ADD_ACTIVITY+VISITE_ACTIVITY+COMMENT_ACTIVITY+BLACK_LIST);
/*
//管理权限定义
define('STICKY', 0x00000010);//置顶|取消置顶
define('QUINTESSENCE', 0x00000020); //加精|取消加精
define('MOVE', 0x00000040); //转移


define('CHECK_BLOG', 0x0000080); //审核日记

define('CREATE_ALBUMS', 0x0000100); //创建相册 编辑相册 编辑照片
define('DEL_ALBUMS', 0x00000200);    //删除相册
define('DEL_PICTURE', 0x00000400);   //删除图片和编辑图片
//define('SET_COVER', 0x00000800);     //把图片设为封面

define('VERYFY_MEMBER', 0x00001000); //认证会员
define('CREATE_CATEGORY', 0x00002000); //创建分类
define('DEL_CATEGORY', 0x00004000); //删除分类


define('THREAD_DEL', 0x00008000); //删除 
define('THREAD_COMPLETE_DEL', 0x00010000); //完全删除
define('BLACKLIST', 0x00020000); //完全删除
define('UPLOAD_PICTURE', 0x00040000); //上传照片
define('UPLOAD_PICTURE', 0x00080000); //上传照片
define('OPEN', 0x00100000); //'关闭/开启',
*/

$gGlobalConfig['team_permission'] = array(
	'VISITE_TEAM' => array(
		'name' => '小组浏览',
		'permission' => VISITE_TEAM,
	),
	'VISITE_TOPIC' => array(
		'name' => '话题浏览',
		'permission' => VISITE_TOPIC,
	),
	'ADD_TOPIC' => array(
		'name' => '话题发布',
		'permission' => ADD_TOPIC,
	),
	'REPLY_TOPIC' => array(
		'name' => '话题回复',
		'permission' => REPLY_TOPIC,
	),
	'SUPPLY_CREATER' => array(
		'name' => '申请活动召集者',
		'permission' => SUPPLY_CREATER,
	),
	'ADD_ACTIVITY' => array(
		'name' => '创建活动',
		'permission' => ADD_ACTIVITY,
	),
	'VISITE_ACTIVITY' => array(
		'name' => '活动浏览',
		'permission' => VISITE_ACTIVITY,
	),
	'COMMENT_ACTIVITY' => array(
		'name' => '活动评论',
		'permission' => COMMENT_ACTIVITY,
	),
	'BLACK_LIST' => array(
		'name' => '黑名单操作',
		'permission' => BLACK_LIST,
	),
);

//用户权限
$gGlobalConfig['user_permission'] = array(
	//活动召集者
	'activity_creater' => array(
		'level' => 1,
		'permission' => array(
			'VISITE_TEAM' => true,
			'VISITE_TOPIC' => true,
			'ADD_TOPIC' => true,
			'REPLY_TOPIC' => true,
			'SUPPLY_CREATER' => true,
			'ADD_ACTIVITY' => true,
			'VISITE_ACTIVITY' => true,
			'COMMENT_ACTIVITY' => true,
			'BLACK_LIST' => false,
		),
	),
	//关注用户
	'attention_user' => array(
		'level' => 2,
		'permission' => array(
			'VISITE_TEAM' => true,
			'VISITE_TOPIC' => true,
			'ADD_TOPIC' => true,
			'REPLY_TOPIC' => true,
			'SUPPLY_CREATER' => true,
			'ADD_ACTIVITY' => false,
			'VISITE_ACTIVITY' => true,
			'COMMENT_ACTIVITY' => true,
			'BLACK_LIST' => false,
		),
	),
	//非关注用户(除游客外)
	'no_attention_user' => array(
		'level' => 3,
		'permission' => array(
			'VISITE_TEAM' => true,
			'VISITE_TOPIC' => true,
			'ADD_TOPIC' => true,
			'REPLY_TOPIC' => true,
			'SUPPLY_CREATER' => true,
			'ADD_ACTIVITY' => false,
			'VISITE_ACTIVITY' => true,
			'COMMENT_ACTIVITY' => false,
			'BLACK_LIST' => false,
		),
	),
	//黑名单用户
	'black_user' => array(
		'level' => 4,
		'permission' => array(
			'VISITE_TEAM' => true,
			'VISITE_TOPIC' => true,
			'ADD_TOPIC' => false,
			'REPLY_TOPIC' => false,
			'SUPPLY_CREATER' => false,
			'ADD_ACTIVITY' => false,
			'VISITE_ACTIVITY' => true,
			'COMMENT_ACTIVITY' => false,
			'BLACK_LIST' => false,
		),
	)
);

$gGlobalConfig['create_team_num'] = 20;
$gGlobalConfig['map_using_type'] = 1;//使用地图类型，1：百度，0：谷歌  谷歌地图有问题
$gGlobalConfig['map_key'] = 'abcdef';//google map key
$gGlobalConfig['map_center_point'] = '30.298X120.159';//默认中心点

//时间搜索
$gGlobalConfig['date_search'] = array(
	1 => '所有时间段',
	2 => '昨天',
	3 => '今天',
	4 => '最近3天',
	5 => '最近7天',
	'other' => '自定义时间',
);

$gGlobalConfig['action_category'] = array(
	'action' => '活动',
	'team'     => '小组',
	'topic'    => '讨论',
);

//状态搜索
$gGlobalConfig['team_status'] = array(
	1 =>'全部状态',
	2 =>'待审核',
	3 =>'已审核',
);

//分组节点类型
$gGlobalConfig['team_type'] = array(
	1 => '最新更新', 
	2 => '话题最多', 
	3 => '活动最多', 
	4 => '关注最多',
);

//话题节点类型
$gGlobalConfig['topic_type'] = array(
	1 => '最新更新', 
	2 => '最多评论', 
	3 => '最多举报', 
	4 => '置顶话题',
);

//话题是否有图片、视频
$gGlobalConfig['topic_img'] = array(
	1 => '所有话题',
	2 => '含图片话题',
	3 => '含视频话题',
);

//节点的属性
$gGlobalConfig['group_type_attr'] = array(
	1 => array('color' => '#4AA44C'), 
	2 => array('color' => '#0F9AB9'), 
	3 => array('color' => '#BF8144'), 
	4 => array('color' => '#7E4DCB'),
);

$gGlobalConfig['App_banword'] = array(
	'host' => 'localhost',
	'dir' => 'livsns/api/banword/',
);
