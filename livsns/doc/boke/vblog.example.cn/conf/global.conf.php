<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: global.conf.php 4395 2011-08-15 01:28:48Z repheal $
***************************************************************************/

$gGlobalConfig = array(
	'sitename'  => '葫芦网_杭州网络电视_杭州电视台旗下城市社区平台',
	'cookie_prefix'  => 'liv_',
	'cookie_domain'  => '',
	'cookie_path'    => '/',
);

$gMysqlShorturlConfig = array(
	'host'   => 'api.example.cn',
	'apidir' => 'shorturl/',
);

$gUserApiConfig = array(
'host' => 'api.example.cn',
'apidir' => 'users/',
);

$gApiConfig = array(
'host' => 'api.example.cn',
'apidir' => 'api/',
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
 
define('BATCH_FETCH_LIMIT', 200); //批量获取数据数目限制

define('DEBUG_MODE', 1); //1 - 直接页面输出， 2 - 输出到文件 LOG_DIR + debug.txt
define('LOG_DIR', ROOT_PATH . 'log/user/');
define('CREATE_DIR_MODE', 0777);
define('DEVELOP_MODE', true);

define('PHOTO_SIZE1', 32);
define('PHOTO_SIZE2', 85);
define('PHOTO_SIZE3', 145);
define('PHOTO_SIZE4', 350);
define('PHOTO_SIZE5', 640);
define('PHOTO_SIZE6', 655);

define('VIDEO_IMG_WIDTH',144);//视频图片
define('VIDEO_IMG_HEIGHT',108);
define('VIDEO_IMG_MULTIPLE',2);//视频图片中大小图倍数

$gGlobalConfig['video_img_size'] = array(
	"larger" =>array("label"=>"l_","width"=>680,"height"=>275,),
	"small" =>array("label"=>"s_","width"=>144,"height"=>108,)
);

define('IMG_SIZE',2);//点滴内容的图片大小（M）
define('LOGO_SIZE_SMALL',235);//网台logo
define('NUM_IMG',10000);//每个目录所包含的图片数目
define('AVATAR_DEFAULT','0.jpg');

define('UPLOAD_DIR', 'opt/web/upload.example.cn/');//文件是存放路径
define('AVATAR_DIR', 'opt/web/upload.example.cn/avatars/');//头像默认是存放路径

define('VIDEO_DIR', 'video/');//视频默认存放路径
define('IMG_DIR', 'img/');//图片默认是存放路径

define('AVATAR_URL', 'http://upload.example.cn/avatars/');//头像默认默认
define('UPLOAD_URL', 'http://upload.example.cn/');//文件默认

define('LOGO_DIR', 'logo/');//频道logo默认是存放路径

define('SNS_MBLOG', 'http://vblog.example.cn');
define('SNS_UCENTER', 'http://vblog.example.cn');
define('SNS_VIDEO', 'http://vblog.example.cn');
define('SNS_TOPIC', 'http://vblog.example.cn');

//积分类型配置 	注：Ucenter中添加积分类型时，此处需添加定义。 eg ： define(名称  , ID);

define('REGISTER' , 1); 	 //注册
define('LOGIN' , 2); 		 //登录
define('SENT_STATUS' , 3); 	 //发送微博
define('CREATE_THEME' , 4);  //创建主题
define('REPLY_THEME' , 5);   //回复主题
define('CREATE_ALBUMS' , 6); //创建相册
define('UPLOAD_PHOTO' , 7);  //上传图片
define('CREATE_SPECIAL' , 8);//创建专辑
define('UPLOAD_VIDEO' , 9);  //上传视频
define('CREATE_STATION' , 10); //创建网台
define('BIND_STATUS' , 21);  //绑定微博
define('DELETE_STATUS' , 22);//删除微博
define('DELETE_PHOTO' , 24); //删除照片
define('DELETE_VIDEO' , 23); //删除视频

define('FACE_DIR', '/opt/web/vblog.example.cn/vui/res/img/smiles/');//表情存放路径
define('FACE_URL', 'http://vblog.example.cn/ui/res/img/smiles/');//表情读取路径

$gGlobalConfig['smile_face'] = array(
		'qq' => array(
					'dir' => FACE_DIR.'qq/',
					'url' => FACE_URL.'qq/'
				),
		'xn' => array(
					'dir' => FACE_DIR.'xn/',
					'url' => FACE_URL.'xn/'
				),
	);
		
	
$gGlobalConfig['smile_name'] = array(
		'qq' => 'QQ表情',
		'xn' => '人人表情'
	);

$gGlobalConfig['video_api'] = array('http://218.108.132.13/vod/');
$gGlobalConfig['follow_set'] = true; //用于设置关注用户时，是否同步关注用户频道 ，默认为false
$gGlobalConfig['qq_login'] = true;//qq用户的同步登陆 

//接口中对应标记配置
$gGlobalConfig['state'][0] = '待审核';
$gGlobalConfig['state'][1] = '通过';
$gGlobalConfig['state'][2] = '未通过';

$gGlobalConfig['video_state'][0] = '转码中...';
$gGlobalConfig['video_state'][1] = '转码完成';

$gGlobalConfig['video_type'][0] = '待审核';
$gGlobalConfig['video_type'][1] = '未通过';
$gGlobalConfig['video_type'][2] = '已发布';

$gGlobalConfig['video_copyright'][0] = '转载';
$gGlobalConfig['video_copyright'][1] = '原创';

define("IS_SHORTURL",false); //是否使用短url 

define('SECOND_NAV_URL', 'http://www.hoolo.tv/js/ce76aa06d57cf95d8f20ef65e073f745.php?&p=liv_cel_id%3A%3D%3A342%26lineheight%3A%3D%3A30px%26is_makestatic%3A%3D%3Ano');
define(ADVERT_XML,'http://www.hoolo.tv/video/video_ad.xml');

?>
