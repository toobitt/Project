<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: config.php 2101 2011-02-18 02:49:19Z repheal $
***************************************************************************/

$gDBconfig = array(
	'host'     => '10.0.1.80',
	'user'     => 'root',
	'pass'     => 'hogesoft',
	'database' => 'sns_ucenter',
	'charset'  => 'utf8',
	'pconncet' => 0,
);

$gGlobalConfig[] = '';
define('DB_PREFIX','liv_');//定义数据库表前缀

define('CACHE_DIR', ROOT_PATH . 'ucenter/cache/');
define('TEMPLATES_DIR', ROOT_PATH . 'ucenter/tpl/');
define('RESOURCE_DIR', 'http://localhost/livsns/ucenter/res/');

define('UC_CONNECT', 'post');		
define('UC_DBHOST', '10.0.1.80');
define('UC_DBUSER', 'root');
define('UC_DBPW', 'hogesoft');
define('UC_DBNAME', 'sns_ucenter');
define('UC_DBCHARSET', 'utf8');
define('UC_DBTABLEPRE', '`sns_ucenter`.liv_');
define('UC_DBCONNECT', '0');
define('UC_KEY', '9f14gIzpWqG9LJ8kPVKXZTAMmvRzJG6FnzqnofU');
define('UC_API', 'http://127.0.0.1/livsns/ucs');
define('UC_CHARSET', 'utf-8');
define('UC_IP', '127.0.0.1');
define('UC_APPID', '8');
define('UC_PPP', '20'); 
																	
define('USER_URL','user.php');
define('TOPIC_URL','k.php');
define('SHOW_URL','show.php');
define( "WB_AKEY" , '2613888264');
define( "WB_SKEY" , '96a32662abb819e1ae64a03b470bb8cc');
//是否在修改资料文件中显示所在地,1为显示，0为不显示
define('SHOW_LOCATION',0);

$gGlobalConfig['menu'] = array(
'set' => array('name' => '个人设置' , 'filename' => 'userprofile.php'),
'index' => array('name' => '我的首页' , 'filename' => 'index.php'),
);

$gGlobalConfig['mms_email'] = 'repheal@123.com';
/**
 * 个人设置 
 */
$gGlobalConfig['personset'] =array (
//'register' => array('name' => '用户注册' , 'filename' => 'register.php'),
'userprofile' => array('name' => '个人资料' , 'filename' => 'userprofile.php'),
'avatar' => array('name' => '上传头像' , 'filename' => 'avatar.php'),
'userprivacy' => array('name' => '隐私设置' , 'filename' => 'userprivacy.php'),
'bind' => array('name' => '绑定点滴' , 'filename' => 'bind.php'),
'editpasswd' => array('name' => '修改密码' , 'filename' => 'editpasswd.php'),
'cellphone' => array('name' => '手机绑定' , 'filename' => 'cellphone.php')
);


$gGlobalConfig['userprofile'] = array('birth' => array('3' =>'只显示星座','2' =>'只显示月日','1' =>'保密','0' =>'公开，完整显示'),
					  'other' => array('2' =>'所有人可见','1' =>'我关注的人可见','0' =>'仅自己可见'));


/**
 * 权限设置
 */
$gGlobalConfig['authority'] = array(
									 'visit_user_info' => array('0' => '所有人可访问' , '1' => '关注人可访问' , '2' => '所有人均不可访问'),
									 'follow' 		   => array('0' => '不需要通过审核' , '1' => '需要通过审核' , '2' => '任何人均无法关注'),
									 'comment'         => array('0' => '所有人可以评论' , '1' => '关注人可以评论' , '2' => '任何人均无法评论'),
									'search_true_name' => array('0' => '可以通过真实姓名搜索到我' , '1' => '不可以通过真实姓名搜索到我')	
);
$gGlobalConfig['nav'] = array(
	'index' => array('name' => '我的首页' , 'filename' => 'index.php','class' => 'index'),
	'atme' => array('name' => '提到我的', 'filename' => 'atme.php','class' => 'notice'),
	'user'  => array('name' => '我的点滴' , 'filename' => 'user.php','class' => 'myblog'),	
	'all_comment'=> array('name' => '我的评论' , 'filename' => 'all_comment.php','class' => 'comments'),
	'favorites'=> array('name' => '我的收藏' , 'filename' => 'favorites.php','class' => 'favorites'),														
);

$gGlobalConfig['umenu'] = array(
	'user' =>'',
	'group' =>'地盘',
	'follow'  => '关注',	
	'fans'=> '粉丝',
	'station'=> '频道',			
	'blacklist'=> '黑名单',													
);
/*地图相关配置*/

define('MAP_KEY','ABQIAAAAtc3gOEMmMCkyAFxMhi-DBhQC0HV1r2nGXBS7h_3BjiuiIt4KzhTpTbhPLv-gb7vwxWzbgJuaduwRDg');
define('MARK_POINT_LIMIT', 300);
define('MAP_CENTER_POINT', '30.30X120.15');
define('MAP_USING_TYPE',1);//加载地图的类型，0：谷歌地图（v2版），1：百度地图(v1.2版最新版)
/*评论设置*/
define('RESULT_MAX_NUM',50);//每页返回结果最多条数
define('DEFAULT_PAGE_NUM',0);//默认返回结果页（默认第一页）

/*上传图片的配置*/
define('BGIMG_MAX_SIZE',5000000);//上传的背景图片最大为5M


/**
 * 相册相关配置
 */

define('PHOTO_SIZE3', 145);
define('ALBUMS_URL' , 'http://localhost/topic/albums/');  //相册文件URL

$gTopicConfig = array(

	'topic_images_url' => 'http://localhost/topic/img/', 	   //图片资源访问域名
	'topic_upload_url' => 'http://localhost/topic/uploads/'    //图片访问域名
);
					
define('MAIN_URL','http://www.hoolo.tv/');
define('UCENTER_URL','http://sns.hcrt.cn/ucenter/');

/**
 * 是否同步到点滴
 */
$gGlobalConfig['default_sync'] = array(
	'comm_list'=>false,//点滴列表 评论
);
?>