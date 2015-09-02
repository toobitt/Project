<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: config.php 17931 2013-02-26 01:34:49Z lijiaying $
***************************************************************************/

$gDBconfig = array(
'host'     => 'db.dev.hogesoft.com',
'user'     => 'root',
'pass'     => 'hogesoft',
'database' => 'applant_application',
'charset'  => 'utf8',
'pconnect' => '',
);

define('APP_UNIQUEID', 'app_plant'); //应用标识

define('DB_PREFIX','liv_');//定义数据库表前缀

//时间搜索
$gGlobalConfig['date_search'] = array(
	1 => '所有时间段',
	2 => '昨天',
	3 => '今天',
	4 => '最近3天',
	5 => '最近7天',
	'other' => '自定义时间',
);

$gGlobalConfig['status'] = array(
	1 => '全部状态',
	2 => '打包中',
	3 => '打包成功',
	4 => '打包失败',
	5 => '发布成功',
	6 => '发布失败'
);

$gGlobalConfig['unpack'] = array(
    '-2' => '发布失败',
    '-1' => '打包失败',
    '0' => '打包中',
    '1' => '打包成功',
    '2' => '发布成功'
);

$gGlobalConfig['attr_type'] = array(
	'input' => '单行文本',
	'textarea' => '多行文本',
    'color' => '拾色器',
	'radio' => '单选框',
	'checkbox' => '复选框',
	'select' => '下拉框',
	'singlefile' => '单个文件',
	'multiplefiles' => '多个文件',
    'mix' => '混合',
    'range' => '取值范围'
);

//APP图标尺寸
$gGlobalConfig['icon_size'] =  array (
  'android' => 
  array (
    0 => 
    array (
      'width' => '144',
      'height' => '144',
    ),
    1 => 
    array (
      'width' => '96',
      'height' => '96',
    ),
    2 => 
    array (
      'width' => '72',
      'height' => '72',
    ),
    3 => 
    array (
      'width' => '48',
      'height' => '48',
    ),
    4 => 
    array (
      'width' => '36',
      'height' => '36',
    ),
  ),
  'ios' => 
  array (
    0 => 
    array (
      'width' => '120',
      'height' => '120',
    ),
    1 => 
    array (
      'width' => '114',
      'height' => '114',
    ),
    2 => 
    array (
      'width' => '57',
      'height' => '57',
    ),
  ),
  'max_size' => 
  array (
    'width' => '1024',
    'height' => '1024',
  ),
);

//APP启动画面尺寸
$gGlobalConfig['startup_size'] =  array (
  'android' => 
  array (
    0 => 
    array (
      'width' => '480',
      'height' => '800',
    ),
  ),
  'ios' => 
  array (
    0 => 
    array (
      'width' => '640',
      'height' => '1136',
    ),
    1 => 
    array (
      'width' => '640',
      'height' => '960',
    ),
  ),
  'max_size' => 
  array (
    'width' => '1080',
    'height' => '1920',
  ),
);

//APP引导图尺寸
$gGlobalConfig['guide_size'] =  array (
  'android' => 
  array (
    0 => 
    array (
      'width' => '480',
      'height' => '800',
    ),
  ),
  'ios' => 
  array (
    0 => 
    array (
      'width' => '640',
      'height' => '1136',
    ),
  ),
  'max_size' => 
  array (
    'width' => '1080',
    'height' => '1920',
  ),
);

//APP模块图标尺寸
$gGlobalConfig['module_size'] =  array (
  'android' => 
  array (
    0 => 
    array (
      'width' => '200',
      'height' => '200',
    ),
  ),
  'ios' => 
  array (
    0 => 
    array (
      'width' => '200',
      'height' => '200',
    ),
  ),
  'max_size' => 
  array (
    'width' => '200',
    'height' => '200',
  ),
);

//导航栏标题
$gGlobalConfig['navBarTitle_size'] =  array (
  'android' => 
  array (
    0 => 
    array (
      'width' => '60',
      'height' => '240',
    ),
  ),
  'ios' => 
  array (
    0 => 
    array (
      'width' => '60',
      'height' => '240',
    ),
  ),
  'max_size' => 
  array (
    'width' => '60',
    'height' => '240',
  ),
);

//杂志首页背景
$gGlobalConfig['magazine_size'] =  array (
  'android' => 
  array (
    0 => 
    array (
      'width' => '1080',
      'height' => '1920',
    ),
  ),
  'ios' => 
  array (
    0 => 
    array (
      'width' => '640',
      'height' => '1136',
    ),
    1 => 
    array (
      'width' => '640',
      'height' => '960',
    ),
  ),
  'max_size' => 
  array (
    'width' => '1080',
    'height' => '1920',
  ),
);

//允许上传的图片类型
$gGlobalConfig['pic_type'] =  array (
  0 => '3',
);

//上传图片类型
$gGlobalConfig['image_type'] = array(
    1 => 'GIF',
    2 => 'JPG',
    3 => 'PNG',
    4 => 'SWF',
    5 => 'PSD',
    6 => 'BMP',
    7 => 'TIFF(intel byte order)',
    8 => 'TIFF(motorola byte order)',
    9 => 'JPC',
    10 => 'JP2',
    11 => 'JPX',
    12 => 'JB2',
    13 => 'SWC',
    14 => 'IFF',
    15 => 'WBMP',
    16 => 'XBM'
);

//APP数据地址配置
$gGlobalConfig['data_url'] =  array (
  'path' => 'http://applant.dev.hogesoft.com:233/data/',
  'file' => 
  array (
    'content' => 
    array (
      'name' => '内容地址',
      'url' => 'publish.php?a=content',
    ),
    'column' => 
    array (
      'name' => '栏目地址',
      'url' => 'publish.php?a=column',
    ),
    'vote' => 
    array (
      'name' => '投票地址',
      'url' => 'vote.php',
    ),
  ),
);

//设置包名
$gGlobalConfig['package'] = array(
    'android' => 'com.hoge.android.app',
    'ios' => 'com.hoge.ios.app'
);

//设置启动方式
$gGlobalConfig['app_effect'] =  array (
  0 => 
  array (
    'identifier' => 'zoomInFadeOut',
    'option' => '图片放大并淡出',
    'value' => '4',
    'default' => 0,
  ),
  1 => 
  array (
    'identifier' => 'fadeOut',
    'option' => '图片淡出',
    'value' => '1',
    'default' => 0,
  ),
  2 => 
  array (
    'identifier' => 'slideLeft',
    'option' => '图片向左边滑出',
    'value' => '2',
    'default' => 0,
  ),
  3 => 
  array (
    'identifier' => 'slideRight',
    'option' => '图片向右边滑出',
    'value' => '3',
    'default' => 1,
  ),
);

//设置版权文字大小
$gGlobalConfig['cpTextSize'] =  array (
  0 => 
  array (
    'identifier' => 'small',
    'option' => '小',
    'value' => '12',
    'default' => 0,
  ),
  1 => 
  array (
    'identifier' => 'medium',
    'option' => '中',
    'value' => '16',
    'default' => 0,
  ),
  2 => 
  array (
    'identifier' => 'large',
    'option' => '大',
    'value' => '20',
    'default' => 0,
  ),
  3 => 
  array (
    'identifier' => 'default',
    'option' => '默认',
    'value' => '14',
    'default' => 1,
  ),
);

//设置版权文字颜色默认值
$gGlobalConfig['cpTextColor'] =  '#1f497d';

//设置引导图效果
$gGlobalConfig['guideEffect'] =  array (
  0 => 
  array (
    'identifier' => 'effect1',
    'option' => '跟随手指滑动',
    'value' => '1',
    'default' => 1,
  ),
  1 => 
  array (
    'identifier' => 'effect2',
    'option' => '1张背景缓动',
    'value' => '2',
    'default' => 0,
  ),
  2 => 
  array (
    'identifier' => 'effect3',
    'option' => '0-6张淡入淡出',
    'value' => '3',
    'default' => 0,
  ),
);

//设置引导图动画
$gGlobalConfig['guideAnimation'] =  array (
  'none' => 
  array (
    'identifier' => 'none',
    'option' => '无动画',
    'value' => '0',
    'default' => 1,
  ),
  'fadeIn' => 
  array (
    'identifier' => 'fadeIn',
    'option' => '淡入淡出',
    'value' => '1',
    'default' => 0,
  ),
  'smallDisapper' => 
  array (
    'identifier' => 'smallDisapper',
    'option' => '向后缩小消失',
    'value' => '2',
    'default' => 0,
  ),
  'accordion' => 
  array (
    'identifier' => 'accordion',
    'option' => '手风琴',
    'value' => '3',
    'default' => 0,
  ),
);

//引导图页脚标记
$gGlobalConfig['shapeSign'] =  array (
  0 => 
  array (
    'sign' => '●',
    'default' => 1,
  ),
  1 => 
  array (
    'sign' => '★',
    'default' => 0,
  ),
  2 => 
  array (
    'sign' => '♪',
    'default' => 0,
  ),
  3 => 
  array (
    'sign' => '■',
    'default' => 0,
  ),
);

//引导图标记默认色
$gGlobalConfig['signDefaultColor'] =  '#c0504d';

//引导图标记选中色
$gGlobalConfig['signSelectedColor'] =  '#8064a2';

//默认风格
define('DEFAULT_STYLE', 1);

//默认界面
define('DEFAULT_UI', 5);

//正文模板默认
define('DEFAULT_BODY_TPL', 25);

//引导图上传个数的上限
define('GUIDE_LIMIT', 6);

//APP模块中文名称字符限制
define('MODULE_NAME_LIMIT', 4);

//APP模块英文名称字符限制
define('MODULE_ENGLISH_LIMIT', 12);

//APP创建个数限制
define('APP_LIMIT_NUM', 1);

//APP模块创建个数限制
define('MODULE_LIMIT_NUM', 6);

define('REPLACE_IMG_DOMAIN', 'http://img.liv.cn/');

define('IS_REPLACE', 0);

//天气接口
define('WEATHER_API', 'http://weather.dingdone.com/');

//统计接口
define('STATISTICS_API', 'http://10.0.1.40/livsns/api/mobile/data/dingdonestatistics/');

//会员接口
define('MEMBER_API', 'http://10.0.1.40/livsns/api/mobile/data/members/');

//互助接口
define('SEEKHELP_API', 'http://10.0.1.40/livsns/api/mobile/data/seekhelp/');

//二维码URL
$gGlobalConfig['qrcode_url'] =  'http://applant.dev.hogesoft.com:233/qrcode.php';

define('INITED_APP', true);

$gGlobalConfig['used_search_condition'] =  array (
);

define('USE_EFFECT', 0);

//定义打包服务器是否可以使用
define('IS_BAG_SERVER_OK', 1);

$gGlobalConfig['effect_default'] =  array (
  0 => '1',
);

$gGlobalConfig['vip_user'] =  array (
  0 => 'echuzhou',
  1 => 'songzhi2',
);

/*****************************************正文模板相关配置**********************************************/
//正文模板状态，方便后台控制
$gGlobalConfig['body_tpl_status'] =  array (
  0 => '全部状态',
  1 => '待审核',
  2 => '已审核',
  3 => '被打回',
);

//正文模板类型
$gGlobalConfig['body_tpl_type'] =  array (
  0 => '全部类型',
  1 => '系统模板',
  2 => '自定义',
);
/*****************************************正文模板相关配置**********************************************/

/*****************************************申请认证类型*************************************************/
$gGlobalConfig['identity_auth_type'] =  array (
  0 => '全部类型',
  1 => '个人开发者',
  2 => '企业开发者',
);
/*****************************************申请认证类型*************************************************/

/*****************************************证件类型****************************************************/
$gGlobalConfig['identity_type'] =  array (
  0 => '全部类型',
  1 => '身份证',
  2 => '营业执照',
);
/*****************************************证件类型****************************************************/

/*****************************************认证审核状态*************************************************/
$gGlobalConfig['identity_auth_status'] =  array (
  0 => '所有状态',
  1 => '待审核',
  2 => '已审核',
  3 => '被打回',
);
/*****************************************认证审核状态*************************************************/

/*****************************************推送账号相关配置**********************************************/
$gGlobalConfig['push_plant'] =  array (
  0 => '所有平台',
  1 => 'AVOS平台',
  2 => '信鸽平台',
);

//打开方式
$gGlobalConfig['open_mode'] =  array (
  1 => '打开模块',
  2 => '打开内容',
  3 => '打开链接',
);

//终端类别
$gGlobalConfig['terminal_type'] =  array (
  1 => 'iOS设备',
  2 => 'Android设备',
);

/*****************************************推送账号相关配置**********************************************/

/*****************************************推送消息相关配置**********************************************/
//推送消息状态
$gGlobalConfig['push_msg_status'] =  array (
	  0 => '所有状态',
	  1 => '推送成功',
	  2 => '推送失败',
);

/*****************************************推送消息相关配置**********************************************/




?>