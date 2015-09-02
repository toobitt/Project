<?php
$gDBconfig = array(
	'host' => '10.0.1.31',
	'user' => 'root',
	'pass' => 'hogesoft',
	'database' => 'community_www',
	'charset' => 'utf8',
	'pconncet' => 0,
);
 
define('DB_PREFIX', 'liv_');
define('APP_UNIQUEID','community');//应用标识
define('GROUP_PLAN_SET_ID',86);
define('THREAD_PLAN_SET_ID',87);
$db_config = $gDBconfig;

//讨论区权限定义
define('MEMBER_JOIN', 0x00000001); //居民加入
define('NON_MEMBER_VIEW', 0x00000002); //非居民查看
define('VISITOR_POST', 0x00000004); //非居民回复
define('BLOG_CHECK', 0x00000008); //主题置顶

//管理权限定义
define('STICKY', 0x00000010);//置顶/取消置顶
define('QUINTESSENCE', 0x00000020); //加精/取消加精
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


$group_permission_arr = array(
	MEMBER_JOIN => '非本讨论区居民加入需要经管理员确认',
	NON_MEMBER_VIEW => '允许非本讨论区居民查看讨论区内容',
	VISITOR_POST => '允许非本讨论区居民在讨论区内发贴',
//	BLOG_CHECK => '推荐日记审核',
	UPLOAD_PICTURE => '允许居民上传照片',
);

$manage_permission_arr = array(
	STICKY => '置顶/取消',
	QUINTESSENCE => '加精/取消',
	OPEN => '关闭/开启',
	MOVE => '转移',
//	CHECK_BLOG=> '审核日记',
	CREATE_ALBUMS => '创建相册',
	DEL_ALBUMS => '删除相册',
	DEL_PICTURE => '删除图片',
//	SET_COVER=> '把图片设为封面',
	VERYFY_MEMBER => '认证会员',
	CREATE_CATEGORY => '创建分类',
	DEL_CATEGORY => '删除分类',
	THREAD_DEL => '删除主题',
	THREAD_COMPLETE_DEL => '彻底删除',
	BLACKLIST => '居民黑名单操作',
);

$gGlobalConfig['livime_upload_url'] = 'http://127.0.0.1/topic/uploads/';
$gGlobalConfig['user_grands_num'] = 20;
$gGlobalConfig['map_using_type'] = 1;//使用地图类型，1：百度，0：谷歌  谷歌地图有问题
$gGlobalConfig['map_key'] = 'abcdef';//google map key
$gGlobalConfig['map_center_point'] = '30.298X120.159';//默认中心点
$gGlobalConfig['app_url'] = 'http://localhost/community/';

$livime_configs = array(
	'livime_upload_url' => 'http://127.0.0.1/topic/uploads/',  //图片访问域名
	'livime_avatar_url' => 'http://10.0.1.80/topic/uploads/avatars/', //会员头像访问域名
	'livime_web_url' => 'http://127.0.0.1/topic/', //系统根目录访问域名
	'livime_web_url_g' => 'http://127.0.0.1/topic/group/',
	'livime_web_url_a' => 'http://127.0.0.1/topic/albums/',
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
$gGlobalConfig['group_status']=array(
	1 =>'全部状态',
	2 =>'待审核',
	3 =>'已审核',
);

//地盘节点类型
$gGlobalConfig['group_type'] = array(
  1 => "最新更新", 
  2 => "帖子最多", 
  3 => "活动最多", 
  4 => "居民最多"
);
//帖子节点类型
$gGlobalConfig['thread_type'] = array(
  1 => "最新更新", 
  2 => "最多评论", 
  3 => "最多举报", 
  4 => "置顶帖 "
);

//帖子是否有图片、视频
$gGlobalConfig['thread_img']=array(
	1 => '所有帖子',
	2 => '图片贴',
	3 => '视频贴',
);

//节点的属性
$gGlobalConfig['group_type_attr'] = array(
  1 => array('color' => '#4AA44C'), 
  2 => array('color' => '#0F9AB9'), 
  3 => array('color' => '#BF8144'), 
  4 => array('color' => '#7E4DCB')
);


$gGlobalConfig['attach_type'] = array(
	'img' => array(
		'host' => 'http://localhost/livsns/' ,
		'dir' => 'uploads/thread/attach/' ,
	),
	'doc' => array(
		'host' => 'http://localhost/livsns/' ,
		'dir' => 'uploads/thread/attach/' ,
	),
	'real' => array(
		'host' => 'http://localhost/livsns/' ,
		'dir' => 'uploads/thread/attach/' ,
	),
);
$gGlobalConfig['arrow_img']=array(
	'host' => 'http://localhost/livsns/' ,
	'dir' => 'uploads/thread/arrow/',
);

$gGlobalConfig['App_banword'] = array(
		'host' => 'localhost',
		'dir' => 'livsns/api/banword/',
);
