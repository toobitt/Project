<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: global.conf.php 46749 2015-07-23 03:24:13Z hanwenbin $
***************************************************************************/


define('DEBUG_MODE', 1); //1 - 直接页面输出， 2 - 输出到文件 LOG_DIR + debug.txt
define('LOG_DIR', ROOT_PATH . 'uploads/log/');
define('CREATE_DIR_MODE', 0777);
define('DEVELOP_MODE', 1);
define('PUBLISH_DBPRE', '');

define('TIMEZONEOFFSET', -8);
define('MAX_ADMIN_TYPE', 3); //最大管理员类型

define('CACHE_DIR',CUR_CONF_PATH . 'cache/');
define('DATA_DIR',CUR_CONF_PATH . 'data/');
define('CONF_DIR',CUR_CONF_PATH . 'conf/');
define('CONF_FILE',CONF_DIR . 'config.php');
define('CUSTOM_APPID',29);
define('CUSTOM_APPKEY','OjEN52E9LieIe9yx8mfDZEpDlUnxuya9');

$gGlobalConfig['is_open_xs'] = 1;     //是否开启了迅搜
$gGlobalConfig['token_expired'] = 1800;
$gGlobalConfig['openlogincache'] = 1;

//outpush_id 推送对象id(对应视频,图集,文稿)
$gGlobalConfig['outpush_id'] = array(
    '1' => 'CRE',
);



//权重搜索
$gGlobalConfig['weight_search'] = array(
	0   => '0',
	1   => '1',
	2   => '2',
	3   => '3',
	4  => '10',
	5  => '20',
	6  => '30',
	7  => '40',
	8  => '50',
	9  => '60',
	10  => '70',
	11  => '80',
	12  => '90',
);

//状态颜色值配置
$gGlobalConfig['status_color'] = array(
	0 => "#8ea8c8",
	1 => "#17b202",
	2 => "#f8a6a6",
	'待审核' => "#8ea8c8",
	'已审核' => "#17b202",
	'已打回' => "#f8a6a6",
	'被打回' => "#f8a6a6",
	'录制中…' => '#f8a6a6',
	'等待录制' => '#8ea8c8',
	'录制成功' => '#17b202'
);

//默认状态值  状态搜查模板状态列表数据
$gGlobalConfig['state_search'] = array(  
    '0'   => '全部状态', 
    '1'   => '未审核',
    '2'   => '已审核',
    '3'   => '已打回',
);


//状态
$gGlobalConfig['status_show'] = array(	
	'0'   => '未审核',
	'1'   => '已审核',
	'2'   => '已打回',
);


$gGlobalConfig['date_search'] = array(
  1 => '所有时间段',
  2 => '昨天',
  3 => '今天',
  4 => '最近3天',
  5 => '最近7天',
  'other' => '自定义时间',
);

$gGlobalConfig['App_card'] =array(
	'host' => 'localhost',
	'dir' => 'livsns/api/card/',
);
$gGlobalConfig['App_rules'] =array(
	'host' => 'localhost',
	'dir' => 'livsns/api/rules/',
);

$gGlobalConfig['App_lottery'] =array(
	'host' => 'localhost',
	'dir' => 'livsns/api/lottery/',
);

$gGlobalConfig['App_hospital'] =array(
	'host' => 'localhost',
	'dir' => 'livsns/api/hospital/',
);

$gGlobalConfig['App_catalog'] =array(
	'host' => 'localhost',
	'dir' => 'livsns/api/catalog/',
	
);

$gGlobalConfig['App_constellation'] =array(
	'host' => 'localhost',
	'dir' => 'livsns/api/constellation/',
	
);

$gGlobalConfig['App_road'] =array(
	'host' => 'localhost',
	'dir' => 'livsns/api/road/',
	
);

$gGlobalConfig['App_tv_interact'] =array(
	'host' => 'localhost',
	'dir' => 'livsns/api/tv_interact/',
	
);

$gGlobalConfig['App_recycle'] =array(
	'host' => 'localhost',
	'dir' => 'livsns/api/recycle/',
	
);

$gGlobalConfig['App_material']=array(
	'host' => '10.0.1.40',
	'dir' => 'livsns/api/material/',
	
);

$gGlobalConfig['App_magazine'] = array(
	'host' => 'localhost',
	'dir' => 'livsns/api/magazine/',

);
$gGlobalConfig['App_cheapbuy'] = array(
	'host' => 'localhost',
	'dir' => 'livsns/api/cheapbuy/',

);

$gGlobalConfig['App_logs']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/logs/',
	
);

$gGlobalConfig['App_news']=array(
    'name' => '文稿',
	'host' => 'localhost',
	'dir' => 'livsns/api/news/',
	
);
	
$gGlobalConfig['App_tuji']=array(
    'name' => '图集',
	'host' => 'localhost',
	'dir' => 'livsns/api/tuji/',
	
);

$gGlobalConfig['App_old_live']=array(
	'protocol' => 'http://',
	'host' => 'api.dev.hogesoft.com',
	'dir' => 'old_live/',
	
);

$gGlobalConfig['App_live']=array(
    'name'     => '频道',
	'protocol' => 'http://',
	'host' => 'localhost',
	'dir' => 'livsns/api/live/',
	
);

/*
$gGlobalConfig['App_live_takeover']=array(
	'protocol' => 'http://',
	'host' => 'localhost',
	'dir' => 'livsns/api/live_takeover/',
	
);
*/
$gGlobalConfig['App_live_control']=array(
	'protocol' => 'http://',
	'host' => 'api.dev.hogesoft.com',
	'dir' => 'live_control/',
	
);

$gGlobalConfig['App_schedule']=array(
	'protocol' => 'http://',
	'host' => 'api.dev.hogesoft.com',
	'dir' => 'schedule/',
	
);

$gGlobalConfig['App_program']=array(
	'protocol' => 'http://',
	'host' => 'localhost',
	'dir' => 'livsns/api/program/',
	
);

$gGlobalConfig['App_program_record']=array(
	'protocol' => 'http://',
	'host' => '10.0.1.40',
	'dir' => 'livsns/api/program_record/',
	
);

$gGlobalConfig['App_live_interactive']=array(
	'protocol' => 'http://',
	'host' => 'localhost',
	'dir' => 'livsns/api/live_interactive/',
	
);

$gGlobalConfig['App_livmedia']=array(
    'name' => '视频',
	'host' => 'localhost',
	'dir' => 'livsns/api/livmedia/admin/',
	
);
$gGlobalConfig['App_servermonitor']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/servermonitor/',
	
);

$gGlobalConfig['App_player'] = array(
	'protocol' => 'http://',
	'host'=>'api.dev.hogesoft.com',
	'dir'=>'player/',
	'demovideo'=>'latest',
	'demochannel'=>'latest',
);

$gGlobalConfig['App_mediaserver'] = array(
	'protocol' => 'http://',
	'host' => 'vapi1.dev.hogesoft.com',
	'dir' => '',
	'token' => 'aldkj12321aasd',
	'port' => '80',
);

$gGlobalConfig['App_auth']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/auth/',
);

$gGlobalConfig['App_statistics']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/statistics/',
);

$gGlobalConfig['App_access'] = array(
	'host' => 'localhost',
	'dir' => 'livsns/api/access/',
	
);

$gGlobalConfig['App_share']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/share/',
	
);

$gGlobalConfig['App_workbench']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/workbench/',
	
);
$gGlobalConfig['App_publishcontent']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/publishcontent/',
	
);
$gGlobalConfig['App_publishplan']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/publishplan/',
	
);
$gGlobalConfig['App_publishconfig']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/publishcontent/',
	
);
$gGlobalConfig['App_publishsys']=array(
	'host' => '10.0.1.40',
	'dir' => 'livsns/api/publishsys/',	
);
$gGlobalConfig['App_block']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/block/',
	
);
$gGlobalConfig['App_special']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/special/',
	
);

$gGlobalConfig['App_travel']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/travel/',
	
);
$gGlobalConfig['App_login']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/member/',
	
);
$gGlobalConfig['App_members']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/members/',
	
);
$gGlobalConfig['App_opinion']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/opinion/',
	
);

$gGlobalConfig['App_livcms']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/livcms/',
	
);
$gGlobalConfig['App_mk_publish_content']=array(
	'host' => '10.0.1.40',
	'dir' => 'hoge_2012/cp/mk/',
	
);
$gGlobalConfig['App_mkpublish']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/mkpublish/',
	
);
$gGlobalConfig['App_mark']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/mark/',
	
);
$gGlobalConfig['App_member']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/member/',
	
);

$gGlobalConfig['App_cp_status']=array(
	'host' => 'localhost',
	'dir' => 'livsns/admin_api/cp_status/',
	
);

$gGlobalConfig['App_status']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/statuses/',
	
);

$gGlobalConfig['App_queue']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/service/',
	
);

$gGlobalConfig['App_memcache']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/service/',
);

$gGlobalConfig['App_memcached']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/memcache/',
);

$gGlobalConfig['App_shorturl']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/shorturl/',
	
);

$gGlobalConfig['App_cp_albums']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/cp_albums/',
	
);

$gGlobalConfig['App_group']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/group/',
	
);

$gGlobalConfig['App_notify']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/notify/',
	
);

$gGlobalConfig['App_option']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/action/',
	
);

$gGlobalConfig['App_team']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/team/',
	
);

$gGlobalConfig['App_activity']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/activity/',
	
);

$gGlobalConfig['App_appstore']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/appstore/',
);

$gGlobalConfig['App_adv'] = array(
	'host' => 'api.dev.hogesoft.com',
	'dir' => 'adv/',
);
$gGlobalConfig['App_vote']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/vote/',
	
);

$gGlobalConfig['App_message'] = array(
	'protocol' => 'http://',
	'host' => 'localhost',
	'dir' => 'livsns/api/message/',
	'token' => 'aldkj12321aasd',
);

$gGlobalConfig['App_mobile'] = array(
	'protocol' => 'http://',
	'host' => 'localhost',
	'dir' => 'livsns/api/mobile/',
);

$gGlobalConfig['App_cheapbuy'] = array(
	'protocol' => 'http://',
	'host' => '10.0.1.40',
	'dir' => 'livsns/api/cheapbuy/',
);

$gGlobalConfig['App_banword']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/banword/',
	
);

$gGlobalConfig['App_message_received']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/message_received/',
	
);

$gGlobalConfig['App_settings']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/settings/',
	
);

$gGlobalConfig['App_photoedit']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/photoedit/',
	
);

$gGlobalConfig['App_webapp']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/webapp/',
	
);

$gGlobalConfig['App_payments']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/payments/',
	
);

$gGlobalConfig['App_video_split'] = array(
	'protocol' => 'http://',
	'host' => 'localhost',
	'dir' => 'livsns/api/video_split/',
);

$gGlobalConfig['App_video_fast_edit'] = array(
	'protocol' => 'http://',
	'host' => 'localhost',
	'dir' => 'livsns/api/video_fast_edit/',
);
$gGlobalConfig['App_qrcode'] = array(
	'protocol' => 'http://',
	'host' => 'localhost',
	'dir' => 'livsns/api/qrcode/',
);
$gGlobalConfig['App_screen3syn'] = array(
	'protocol' => 'http://',
	'host' => 'localhost',
	'dir' => 'livsns/api/screen3syn/',
);
$gGlobalConfig['App_contribute'] = array(
	'protocol' => 'http://',
	'host' => 'localhost',
	'dir' => 'livsns/api/contribute/',
);
$gGlobalConfig['App_ticket'] = array(
	'protocol' => 'http://',
	'host' => 'localhost',
	'dir' => 'livsns/api/ticket/',
);
$gGlobalConfig['App_textsearch'] = array(
	'protocol' => 'http://',
	'host' => 'localhost',
	'dir' => 'livsns/api/textsearch/',
);
$gGlobalConfig['App_archive'] = array(
	'protocol' => 'http://',
	'host' => 'localhost',
	'dir' => 'livsns/api/archive/',
);
$gGlobalConfig['App_weather'] = array(
	'protocol' => 'http://',
	'host' => 'localhost',
	'dir' => 'livsns/api/weather/',
);
$gGlobalConfig['App_carpark'] = array(
	'protocol' => 'http://',
	'host' => 'localhost',
	'dir' => 'livsns/api/carpark/',
);
$gGlobalConfig['App_live_time_shift'] = array(
	'protocol' => 'http://',
	'host' => 'api.dev.hogesoft.com',
	'dir' => 'live_time_shift/',
);
$gGlobalConfig['App_seekhelp'] = array(
	'protocol' => 'http://',
	'host' => 'localhost',
	'dir' => 'livsns/api/seekhelp/',
);

$gGlobalConfig['App_video_point'] = array(
	'protocol' => 'http://',
	'host' => 'localhost',
	'dir' => 'livsns/api/video_point/',
);

$gGlobalConfig['App_tv_play'] = array(
	'protocol' => 'http://',
	'host' => 'api.dev.hogesoft.com',
	'dir' => 'tv_play/',
);

$gGlobalConfig['App_albums_app']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/albums_app/',
);

$gGlobalConfig['App_dingdoneuser']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/dingdoneuser/',
);

$gGlobalConfig['App_app_plant']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/app_plant/',
);

$gGlobalConfig['App_supermarket']=array(
	'host' => 'api.dev.hogesoft.com',
	'dir' => 'supermarket/',
);
$gGlobalConfig['App_epaper']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/epaper/',
);
$gGlobalConfig['App_cdn']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/cdn/',
);
$gGlobalConfig['App_lbs']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/lbs/',
);
$gGlobalConfig['App_gather']=array(
	'host' => 'localhost',
	'dir' => 'livsns/api/gather/',
);
$gGlobalConfig['App_mood']=array(
    'protocol' => 'http://',
	'host' => 'localhost',
	'dir' => 'livsns/api/mood/', 
);
$gGlobalConfig['App_verifycode'] = array(
	'protocol' => 'http://',
	'host' => '10.0.1.40',
	'dir' => 'livsns/api/verifycode/',
);
$gGlobalConfig['App_grade'] = array(
	'protocol' => 'http://',
	'host' => 'localhost',
	'dir' => 'livsns/api/grade/',
);
$gGlobalConfig['App_survey'] = array(
	'protocol' => 'http://',
	'host' => 'localhost',
	'dir' => 'livsns/api/survey/',
);

$gGlobalConfig['App_company'] = array(
	'protocol' => 'http://',
	'host' => 'localhost',
	'dir' => 'livsns/api/company/',
);

$gGlobalConfig['App_meeting'] = array(
	'protocol' => 'http://',
	'host' => '10.0.1.40',
	'dir' => 'livsns/api/meeting/',
);

$gGlobalConfig['App_searchtag'] = array(
    'protocol' => 'http://',
    'host' => 'localhost',
    'dir' => 'livsns/api/searchtag/',
);

$gGlobalConfig['App_feedback'] = array(
	'protocol' => 'http://',
	'host' => 'localhost',
	'dir' => 'livsns/api/feedback/',
);
$gGlobalConfig['App_jf_mall'] = array(
	'protocol' => 'http://',
	'host' => 'localhost',
	'dir' => 'livsns/api/jf_mall/',
);
$gGlobalConfig['App_dingdonestatistics'] = array(
	'protocol' => 'http://',
	'host' => 'localhost',
	'dir' => 'livsns/api/dingdonestatistics/',
);
$gGlobalConfig['App_dingdoneuser'] = array(
	'protocol' => 'http://',
	'host' => 'localhost',
	'dir' => 'livsns/api/dingdoneuser/',
);
$gGlobalConfig['App_gatherapi'] = array(
		'protocol' => 'http://',
		'host' => 'localhost',
		'dir' => 'livsns/api/gatherapi/',
);
$gGlobalConfig['App_im'] = array(
    'protocol' => 'http://',
    'host' => 'localhost',
    'dir' => 'livsns/api/im/',
);
$gGlobalConfig['App_userSpace'] = array(
    'protocol' => 'http://',
    'host' => 'localhost',
    'dir' => 'livsns/api/userSpace/',
);
$gGlobalConfig['App_live_interactive_new'] = array(
    'protocol' => 'http://',
    'host' => 'localhost',
    'dir' => 'livsns/api/live_interactive_new/',
);

$gGlobalConfig['App_qcon'] = array(
    'protocol' => 'http://',
    'host' => '10.0.1.40',
    'dir' => 'livsns/api/qcon/',
);

$gGlobalConfig['App_cinema'] = array(
    'protocol' => 'http://',
    'host' => 'localhost',
    'dir' => 'livsns/api/cinema/',
);

$gGlobalConfig['App_email'] = array(
    'protocol' => 'http://',
    'host' => 'localhost',
    'dir' => 'livsns/api/email/',
);

$gGlobalConfig['App_hogeorder'] = array(
    'protocol' => 'http://',
    'host' => 'localhost',
    'dir' => 'livsns/api/hogeorder/',
);
$gGlobalConfig['App_yc_bus'] = array(
    'protocol' => 'http://',
    'host' => 'localhost',
    'dir' => 'livsns/api/hogeorder/Middleware/',
);
$gGlobalConfig['App_gongjiao'] = array(
    'protocol' => 'http://',
    'host' => 'localhost',
    'dir' => 'livsns/api/gongjiao/',
);
$gGlobalConfig['App_praise'] = array(
		'host' => 'localhost',
		'dir' => 'livsns/api/praise/',
);
$gGlobalConfig['App_outpush'] = array(
    'host' => 'localhost',
    'dir'  => 'livsns/api/outpush/admin/',
);
?>