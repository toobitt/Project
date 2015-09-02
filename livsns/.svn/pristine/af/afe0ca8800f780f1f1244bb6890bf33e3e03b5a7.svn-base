<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/

$gDBconfig = array('host' => 'db.dev.hogesoft.com',
'user' => 'root',
'pass' => 'hogesoft',
'database' => 'dev_ad',
'charset' => 'utf8',
'pconncet' => '0',
);
 
define('DB_PREFIX','liv_');//定义数据库表前缀
define('INITED_APP',true);
define('ADV_DATA_DIR',CUR_CONF_PATH . 'data/');
define('AD_DOMAIN', 'http://api.dev.hogesoft.com/adv/');
define('ADV_DATA_URL',AD_DOMAIN.'data/');
define('APP_UNIQUEID', 'adv');
//define('MANIFEST','manifest.m3u8');
define('SPLIT_FLAG', '-');
$gGlobalConfig['hg_ad_flag'] = array(
'liv_player_flag'=>'liv_player',
'vod_player_flag'=>'vod_player',
'mobile_ad_flag'=>'mobile',
'web_ad_flag'=>'website',
);
//允许上传的图片类型
$gGlobalConfig['allow_upload_types']['img'] = array(
'*.jpg',
'*.jpeg',
'*.png',
'*.swf',
'*.gif',
);
//允许上传的视频类型
$gGlobalConfig['allow_upload_types']['video'] = array(
'*.flv',
'*.3gp',
'*.mp4',
'*.mpg',
'*.avi',
'*.flv',
'*.asf',
'*.mkv',
'*.mov',
'*.mpeg',
'*.rmvb',
'*.wmv',
);
//admin
$gGlobalConfig['conditions'] = array(
'0'=>'请选择',
'&gt;'=>'大于',
'&lt;'=>'小于',
'&gt;='=>'大于等于',
'&lt;='=>'小于等于',
'=='=>'等于',
'like'=>'模糊匹配',
'!='=>'不等于',
'IN'=>'IN(以"，"分隔)',
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
$gGlobalConfig['status_search'] = array(
-1=>'全部状态',
1=>'有效',
2=>'已过期',
3=>'即将过期',
4=>'等待下个时间段',
6=>'下架'
);
$gGlobalConfig['status_color'] = array(
1=>'green',
2=>'red',
3=>'yellow',
6=>'orange',
4=>'blue'
);
$gGlobalConfig['priority'] = array('1'=>'独占','2'=>'标准', '3'=>'补余', '4'=>'内部');
$gGlobalConfig['adv_pos_type'] = array(
0=>'播放器',
1=>'浮动',
2=>'固定',
);
$gGlobalConfig['form_style'] = array(
1=>'普通输入框',
2=>'时间输入框', 
3=>'时间选择框', 
4=>'像素输入框',
5=>'缩放模式',
6=>'九宫位置',
7=>'四角位置',
8=>'填写次数',
);

$gGlobalConfig['video_upload_type'] = array(
  1 => "编辑上传", 
  2 => "网友上传", 
  3 => "直播归档", 
  4 => "标注归档"
);
//视频的状态
$gGlobalConfig['video_upload_status'] = array(
  -1 => "转码失败",
  0 => "转码中",
  1 => "待审核",
  2 => "已审核",
  3 => "被打回"
);
define('HG_FIXED_ADBOX', '<div hg_adbox="{$posid}" id="ad_{$posid}"></div>');
define('HG_FLOAT_ADBOX', '<div hg_adbox="{$posid}"></div>');
$gGlobalConfig['mtype']=array(
'flash'=>'动画',
'javascript'=>'代码',
'image'=>'图片',
'video'=>'视频',
'text'=>'文字',
);
?>