<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: global.conf.php 2774 2011-03-15 06:58:54Z wang $
***************************************************************************/

$gGlobalConfig = array(
	'cookie_prefix'  => 'liv_',
	'cookie_domain'  => '',
	'cookie_path'    => '/',
);
$gQueueConfig = array(

	1 => array('host'     => '10.0.1.80','port'     => '21201'),
);
$gMemcacheConfig = array(
	'host' => '127.0.0.1',
	'port' => '21201',
);
//对话接口配置
$gMessageConfig = array(
	'host' => '127.0.0.1',
	'apidir' => 'livsns/api/messages/'
);

/*提供mysql形式的队列和memcache服务配置*/
$gMysqlServiceConfig = array(
	'host'   => '127.0.0.1',
	'apidir' => 'livsns/api/service/',
);
$gMysqlShorturlConfig = array(
	'host'   => '127.0.0.1',
	'apidir' => 'livsns/api/shorturl/',
);
$gMysqlBanwordConfig = array(
	'host'   => '127.0.0.1',
	'apidir' => 'livsns/api/banword/',
);

$gMysqlfriendshipsConfig = array(
	'host' => '127.0.0.1',
	'apidir' => 'livsns/api/friendships/',
);

$gMysqlBlockConfig = array(
	'host' => '127.0.0.1',
	'apidir' => 'livsns/api/Blocks/',
);

$gMysqlpushConfig = array(
	'host' => '127.0.0.1',
	'apidir' => 'livsns/api/push/',
);


$gMysqlStatusesConfig = array(
	'host' => '127.0.0.1',
	'apidir' => 'livsns/api/statuses/',

);
$gUserApiConfig = array(
'host' => '127.0.0.1',
'apidir' => 'livsns/api/users/',
);
$gApiConfig = array(
'host' => '127.0.0.1',
'apidir' => 'livsns/api/',
);

$gfavoritesApiConfig = array(
'host' => '127.0.0.1',
'apidir' => 'livsns/api/favorites/',
);

//讨论区接口配置
$gGroupApiConfig = array(
'host' => '127.0.0.1',
'apidir' => 'topic/api/',
);

//相册接口配置
$gAlbumsApiConfig = array(
'host' => 'localhost',
'apidir' => 'topic/api/',
);

//积分接口配置
$gCreditApiConfig = array(
'host' => 'localhost',
'apidir' => 'livsns/api/'
);

//索引数字表示privacy字段的位数
$gPrivacyOrder = array(
'0' => "真实姓名",
'1' => "生日",
'2' => "email",
'3' => "qq",
'4' => "msn",
'5' => "mobile",


'19' => '加关注'
);


define('FRIENDS_MEM_PRE', 'friends'); //用户关注对象的memcache键前缀
define('FOLLOWERS_MEM_PRE', 'followers'); //用户粉丝的memcache键前缀
define('BLOCKERS_MEM_PRE', 'blockers');   //用户黑名单对象的memcache键前缀
define('INNERTRANSKEY', 'dsafqoi8w222343255');   //内部通行码

 
define('BATCH_FETCH_LIMIT', 200); //批量获取数据数目限制

define('DEBUG_MODE', 1); //1 - 直接页面输出， 2 - 输出到文件 LOG_DIR + debug.txt
define('LOG_DIR', ROOT_PATH . 'log/user/');
define('CREATE_DIR_MODE', 0777);
define('DEVELOP_MODE', true);
define('BATCH_FETCH_LIMIT', 200); //批量获取数据数目限制

define('LARGER_IMG_WIDTH',200);//头像缩略图
define('LARGER_IMG_HEIGHT',300);
define('MIDDLE_IMG_WIDTH',50);
define('MIDDLE_IMG_HEIGHT',50);
define('SMALL_IMG_WIDTH',30);
define('SMALL_IMG_HEIGHT',30);
define('IMG_SIZE_LARGER',440);//点滴内容的图片larger
define('IMG_SIZE_MIDDLE',200);//点滴内容的图片middle
define('IMG_SIZE_SMALL',120);//点滴内容的图片small
define('LOGO_SIZE_SMALL',235);//网台logo
define('NUM_IMG',10000);//每个目录所包含的图片数目
define('AVATAR_DEFAULT','0.jpg');
define('UPLOAD_DIR', ROOT_PATH . 'uploads/');//文件是存放路径
define('AVATAR_DIR', ROOT_PATH . 'uploads/avatars/');//头像默认是存放路径

define('VIDEO_DIR', 'video/');//视频默认存放路径
define('IMG_DIR', 'img/');//图片默认是存放路径
//define('UPLOAD_URL', 'http://127.0.0.1/livsns/uploads/');//文件默认

define('AVATAR_URL', 'http://10.0.1.80/livsns/uploads/avatars/');//头像默认默认
define('UPLOAD_URL', 'http://10.0.1.80/livsns/uploads/');//文件默认

define('LOGO_DIR', 'logo/');//网台logo默认是存放路径

define('PUBLISH_TO_MULTI_GROUPS',1);//点滴是否可以同时发布到多个讨论区：1：可以；0：不可以

define('SNS_MBLOG', 'http://localhost/livsns/ui/');//点滴访问
define('SNS_UCENTER', 'http://localhost/livsns/ucenter/');//用户中心访问
define('SNS_VIDEO', 'http://localhost/livsns/vui/');//网台访问
define('SNS_TOPIC', 'http://localhost/topic/group/');//话题访问

//积分类型配置 	注：Ucenter中添加积分类型时，此处需添加定义。 eg ： define(名称  , ID);
define('REGISTER_CREDIT' , 20); //注册积分

define('REGISTER' , 1); 	//注册
define('LOGIN' , 2); 		//登录
define('SENT_STATUS' , 3); 	//发送微博
define('UPLOAD_VIDEO' , 9); //上传视频
define('BIND_STATUS' , 21); //绑定微博
define('LIVIME_IMAGES_URL',"http://127.0.0.1/topic/img/");

?>
