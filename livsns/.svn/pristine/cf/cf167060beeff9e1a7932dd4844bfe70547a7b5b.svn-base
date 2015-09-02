<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: config.php 4355 2011-08-08 07:46:38Z repheal $
***************************************************************************/

$gDBconfig = array(
	'host'     => '10.0.1.80',
	'user'     => 'root',
	'pass'     => 'hogesoft',
	'database' => 'video',
	'charset'  => 'utf8',
	'pconncet' => 0,
);

$gGlobalConfig[] = '';
define('DB_PREFIX','liv_');//定义数据库表前缀

define('CACHE_DIR', ROOT_PATH . 'vui/cache/');   //缓存路径

/*Ucenter 配置*/
define('UC_CONNECT', 'post');
define('UC_DBHOST', '10.0.1.80');
define('UC_DBUSER', 'root');
define('UC_DBPW', 'hogesoft');
define('UC_DBNAME', 'sns_ucenter');
define('UC_DBCHARSET', 'utf8');
define('UC_DBTABLEPRE', '`sns_ucenter`.liv_');
define('UC_DBCONNECT', '0');


define('UC_KEY', '2de6rb9yEMampJj1K82F7IwTdB4qROZMWHPV/XM');
define('UC_API', 'http://bbs.ywcity.cn/uc_server/');
define('UC_CHARSET', 'utf-8');
define('UC_IP', '127.0.0.1');
define('UC_APPID', '9');
define('UC_PPP', '20');

define('USER_URL','user.php');
define('TOPIC_URL','k.php');
define('SHOW_URL','show.php');
define('HOT_TOPIC_URL','http://www.hoolo.tv/js/47b430220d3067a8a9123631eb1a82c3.php?&p=liv_cel_id%3A%3D%3A1186%26columnid%3A%3D%3A194%26rows%3A%3D%3A10%26cutlength%3A%3D%3A7%26curClass%3A%3D%3Aditu_list_ul%26is_makestatic%3A%3D%3Ayes');

/* 视频上传处理文件路径  */
define('VIDEO_UPLOAD', 'http://localhost/livsns/vui/upload.php'); //注：文件提交后将域名改为10.0.1.66

/**站点目录,这个变量用于视频分享时传递的**/
define('WEB_SITE_NAME','http://http://localhost');


$gGlobalConfig['user_menu'] = array(
'user' => array('name' => '我的主页' , 'filename' => 'user.php'),//某人页面
'user_station'  => array('name' => '我的频道' , 'filename' => 'user_station.php'),
'user_video'  => array('name' => '我的视频' , 'filename' => 'user_video.php'),
'user_album'  => array('name' => '我的专辑' , 'filename' => 'user_album.php'),
'user_favorites'  => array('name' => '我的收藏' , 'filename' => 'user_favorites.php')
);

$gGlobalConfig['nav_menu'] = array(
	array("name" => "首页", "url" => "http://www.hoolo.tv/", "last" => 0),
	array("name" => "个人空间", "url" => SNS_UCENTER, "last" => 0),
	array("name" => "我的频道", "url" => SNS_VIDEO, "last" => 0),
);
$gGlobalConfig['nav'] = array(
	'index' => array('name' => '频道首页' , 'filename' => 'index.php','class' => 'index'),
	'my_program' => array('name' => '节目单', 'filename' => 'my_program.php','class' => 'program'),
	'my_video'  => array('name' => '我的视频' , 'filename' => 'my_video.php','class' => 'videos'),	
	'my_album'=> array('name' => '我的专辑' , 'filename' => 'my_album.php','class' => 'albums'),
	'my_favorites'=> array('name' => '我的收藏' , 'filename' => 'my_favorites.php','class' => 'favorites'),	
	'my_comments'=> array('name' => '我的评论' , 'filename' => 'my_comments.php','class' => 'comments'),														
);
define('ALLOW_PROGRAME',false);
if(!ALLOW_PROGRAME)
{
	unset($gGlobalConfig['nav']['my_program']);
	unset($gGlobalConfig['nav']['index']);
}

$gGlobalConfig['user_video_nav'] = array(
'最新视频' => 1,
'播放次数' => 2,
'评论次数' => 3			
);


$gGlobalConfig['my_menu'] = array(
//'my' => array('name' => '我的主页' , 'filename' => 'my.php','classname' => 'my_page'),//某人页面
'my_station'  => array('name' => '我的频道' , 'filename' => 'my_station.php','classname' => 'my_web_tv'),
'my_program'  => array('name' => '节目单' , 'filename' => 'my_program.php','classname' => 'my_epg'),
'my_video'  => array('name' => '我的视频' , 'filename' => 'my_video.php','classname' => 'my_video'),
'my_album'  => array('name' => '我的专辑' , 'filename' => 'my_album.php','classname' => 'my_album'),
'my_favorites'  => array('name' => '我的收藏' , 'filename' => 'my_favorites.php','classname' => 'my_fav'),
'my_comments'  => array('name' => '我的评论' , 'filename' => 'my_comments.php','classname' => 'my_rec'),

'video_upload'  => array('name' => '视频上传' , 'filename' => 'upload.php','classname' => 'my_upload')

//'my_set'  => array('name' => '个人设置' , 'filename' => 'my_set.php','classname' => 'my_rec'),

);


$gGlobalConfig['album'] = array(
	1 => array('id' => 1,'name' => '资讯','checked' => 'checked',),
	2 => array('id' => 2,'name' => '原创','checked' => '',),
	3 => array('id' => 3,'name' => '电视','checked' => '',),
	4 => array('id' => 4,'name' => '娱乐','checked' => '',),
	5 => array('id' => 5,'name' => '电影','checked' => '',),
	6 => array('id' => 6,'name' => '体育','checked' => '',),
	7 => array('id' => 7,'name' => '音乐','checked' => '',),
	8 => array('id' => 8,'name' => '游戏','checked' => '',),
	9 => array('id' => 9,'name' => '动漫','checked' => '',),
	10 => array('id' => 10,'name' => '时尚','checked' => '',),
	11 => array('id' => 11,'name' => '母婴','checked' => '',),
	12 => array('id' => 12,'name' => '汽车','checked' => '',),
	13 => array('id' => 13,'name' => '旅游','checked' => '',),
	14 => array('id' => 14,'name' => '科技','checked' => '',),
	15 => array('id' => 15,'name' => '教育','checked' => '',),
	16 => array('id' => 16,'name' => '生活','checked' => '',),
	17 => array('id' => 17,'name' => '搞笑','checked' => '',),
	18 => array('id' => 18,'name' => '广告','checked' => '',),
	19 => array('id' => 19,'name' => '其他','checked' => '',),
);

$gGlobalConfig['mblog_url'] = "/livsns/ui/";


//视频上传支持的格式
$gGlobalConfig['video_layout'] = array(1 => 'wmv',
									   2 => 'avi',
									   3 => 'dat',
									   4 => 'asf',
									   5 => 'rm',
									   6 => 'rmvb',
									   7 => 'ram',
									   8 => 'mpg',
									   9 => 'mpeg',
									   10 => '3gp',
									   11 => 'mov',
									   12 => 'mp4',
									   13 => 'm4v',
									   14 => 'dvix',
									   15 => 'dv',
									   16 => 'dat',
									   17 => 'mkv',
									   18 => 'flv',
									   19 => 'vob',
									   20 => 'ram',
									   21 => 'qt',
									   22 => 'divx',
									   23 => 'cpk',
									   24 => 'fli',
									   25 => 'flc',
									   26 => 'mod');
/**
 * 是否同步到点滴
 */
$gGlobalConfig['default_sync'] = array(
	'comm'=>false,//评论列表 
);

$gGlobalConfig['default_program_user'] = array(
	'user_id' => 1,
); //推荐用户的节目单
?>
