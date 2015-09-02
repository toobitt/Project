<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: config.php 4114 2011-06-27 06:36:51Z repheal $
***************************************************************************/

$gDBconfig = array(
	'host'     => '10.0.1.80',
	'user'     => 'root',
	'pass'     => 'hogesoft',
	'database' => 'sns_style',
	'charset'  => 'utf8',
	'pconncet' => 0,
);

$gGlobalConfig[] = '';
define('DB_PREFIX','liv_');//定义数据库表前缀

define('CACHE_DIR', ROOT_PATH . 'ui/cache/');
define('TEMPLATES_DIR', ROOT_PATH . 'ui/tpl/');
define('RESOURCE_DIR', 'http://localhost/livsns/ui/res/');

/*Ucenter 配置*/
define('UC_CONNECT', 'post');
define('UC_DBHOST', '10.0.1.80');
define('UC_DBUSER', 'root');
define('UC_DBPW', 'hogesoft');
define('UC_DBNAME', 'sns_ucenter');
define('UC_DBCHARSET', 'utf8');
define('UC_DBTABLEPRE', '`sns_ucenter`.liv_');
define('UC_DBCONNECT', '0');
define('UC_KEY', '17ccy1chR/9ePR0IQEJ4o/KpMVek4b4mkL6Y+n4');
define('UC_API', 'http://localhost/livsns/ucs');
define('UC_CHARSET', 'utf-8');
define('UC_IP', '127.0.0.1');
define('UC_APPID', '4');
define('UC_PPP', '20');


define('USER_URL','user.php');
define('TOPIC_URL','k.php');
define('SHOW_URL','show.php');

define( "WB_AKEY" , '2613888264');
define( "WB_SKEY" , '96a32662abb819e1ae64a03b470bb8cc');

//define( "WB_AKEY" , '2473706278');
//define( "WB_SKEY" , 'a113626eda457787b1911c59afe34863');


/*定义脚本名称*/
define('FINDPEOPLE' , '找人');

$gGlobalConfig['mblog_url'] = 'http://localhost/livsns/ui/';
$gGlobalConfig['main_nav'] = array(
	'index' => array('url' => 'http://localhost/topic/', 'name' => '葫芦网首页'),
	'H·Live' => array('url' => '#', 'name' => 'H·Live'),
	'H·City' => array('url' => 'http://localhost/topic/group/', 'name' => 'H·City'),
	'H·Channel' => array('url' => 'http://localhost/livsns/vui/', 'name' => 'H·Channel'),
);
$gGlobalConfig['menu'] = array(
'n'  => array('name' => '找人' , 'filename' => 'n.php'),
'mytemplate'  => array('name' => '模板' , 'filename' => 'mytemplate.php'),
'info'  => array('name' => '我的资料' , 'filename' => 'info.php'),
'index' => array('name' => '我的首页' , 'filename' => 'index.php'),
);

$gGlobalConfig['nav'] = array(
	'index' => array('name' => '我的首页' , 'filename' => 'index.php','class' => 'index'),
	'atme' => array('name' => '提到我的', 'filename' => 'atme.php','class' => 'notice'),
	'user'  => array('name' => '我的点滴' , 'filename' => 'user.php','class' => 'myblog'),	
	'all_comment'=> array('name' => '我的评论' , 'filename' => 'all_comment.php','class' => 'comments'),
	'favorites'=> array('name' => '我的收藏' , 'filename' => 'favorites.php','class' => 'favorites'),														
);

$gGlobalConfig['list'] = array(
'user'  => array('name' => '点滴' , 'filename' => 'user.php','class' => 'text-wb'),
'follow'  => array('name' => '关注的人' , 'filename' => 'follow.php','class' => 'text-gz'),	
'fans'  => array('name' => '粉丝' , 'filename' => 'fans.php','class' => 'text-fs'),
'favorites' => array('name' => '收藏' , 'filename' => 'favorites.php','class' => 'text-sc'),
);

/**
 * 个人设置 
 */
$gGlobalConfig['personset'] =array (
//'register' => array('name' => '用户注册' , 'filename' => 'register.php'),
'userprofile' => array('name' => '个人资料' , 'filename' => 'userprofile.php'),
'avatar' => array('name' => '上传头像' , 'filename' => 'avatar.php'),
'userprivacy' => array('name' => '隐私设置' , 'filename' => 'userprivacy.php'),
'bind' => array('name' => '绑定点滴' , 'filename' => 'bind.php'),
'editpasswd' => array('name' => '修改密码' , 'filename' => 'editpasswd.php')
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

/**
 * 用户模板的颜色设置项
 */
$gGlobalConfig['user_skin'] = array(
	'mainF_0' => array('name' => '主字体', 'color' => '#000','className'=>'*'),
	'mainF_1' => array('name' => '主链接', 'color' => '#0066CB','className'=>'.content-left ul li .subject a'),
	'mainF_2' => array('name' => '次文字', 'color' => '#7E7A7B','className'=>'.business dl dd'),
	'mainF_3' => array('name' => '次链接', 'color' => '#4B8ED1','className'=>'.topic li a'),
	'mainF_4' => array('name' => '内容背景','color' => '#fff','className'=>'.list'),
	'mainF_5' => array('name' => '右栏背景','color' => '#DEF2F9','className'=>'.pad-all')
);

/**
 * 是否同步到点滴
 */
$gGlobalConfig['default_sync'] = array(
	'comm_list'=>false,//点滴列表 评论
	'comm_main'=>true//点滴正文页评论
);


/*地图相关配置*/

define('MAP_KEY','ABQIAAAAtc3gOEMmMCkyAFxMhi-DBhQC0HV1r2nGXBS7h_3BjiuiIt4KzhTpTbhPLv-gb7vwxWzbgJuaduwRDg');
define('MARK_POINT_LIMIT', 300);
define('MAP_CENTER_POINT', '31.9991X118.7842');

/*评论设置*/
define('RESULT_MAX_NUM',50);//每页返回结果最多条数
define('DEFAULT_PAGE_NUM',0);//默认返回结果页（默认第一页）

/*上传图片的配置*/
define('BGIMG_MAX_SIZE',5000000);//上传的背景图片最大为5M
define('MAIN_URL','http://www.hoolo.tv/');
?>