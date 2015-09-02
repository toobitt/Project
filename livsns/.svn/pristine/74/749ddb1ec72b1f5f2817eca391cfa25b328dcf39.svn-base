<?php
/***************************************************************************
 * HOGE M2O
 *
 * @package     DingDone M2O API
 * @author      RDC3 - Zhoujiafei
 * @copyright   Copyright (c) 2013 - 2014, HOGE CO., LTD (http://hoge.cn/)
 * @since       Version 1.1.0
 * @date        2014-8-18
 * @encoding    UTF-8
 * @description 配置文件
 **************************************************************************/

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

//上架搜索
$gGlobalConfig['shelves_search'] = array(
		0 => '选择上架',
		1 => '未上架',
		2 => '已上架',
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
    '0'  => '打包中',
    '1'  => '打包成功',
    '2'  => '发布成功'
);

$gGlobalConfig['attr_type'] = array(
	'input'         => '单行文本',
	'textarea' 	    => '多行文本',
    'color'         => '拾色器',
	'radio'         => '单选框',
	'checkbox'      => '复选框',
	'select'        => '下拉框',
	'singlefile'    => '单个文件',
	'multiplefiles' => '多个文件',
    'mix'           => '混合',
    'range'         => '取值范围'
);

//APP图标尺寸(key:输给客户端用的key，thumb:upyun上定义的对应的缩略图)
$gGlobalConfig['icon_size'] =  array (
  'android' => 
array (
0 =>
array (
      'width'  => '144',
      'height' => '144',
      'key'    => 'drawable-xxhdpi',
      'thumb'  => 'iconxxhd',
),
1 =>
array (
      'width'  => '96',
      'height' => '96',
      'key'    => 'drawable-xhdpi',
      'thumb'  => 'iconxhd',
),
2 =>
array (
      'width'  => '72',
      'height' => '72',
      'key'    => 'drawable-hdpi',
      'thumb'  => 'iconhd',
),
3 =>
array (
      'width'  => '48',
      'height' => '48',
      'key'    => 'drawable-mdpi',
      'thumb'  => 'iconmd',
),
4 =>
array (
      'width'  => '36',
      'height' => '36',
      'key'    => 'drawable-ldpi',
      'thumb'  => 'iconld',
),
),
  'ios' => 
array (
0 =>
array (
      'width'  => '120',
      'height' => '120',
      'key'    => 'Icon-60@2x',
      'thumb'  => 'icon60x2',
),
1 =>
array (
      'width'  => '114',
      'height' => '114',
      'key'    => 'Icon@2x',
      'thumb'  => 'iconx2',
),
2 =>
array (
      'width'  => '57',
      'height' => '57',
      'key'    => 'Icon',
      'thumb'  => 'icon',
),
3 => array(
      'width'  => '180',
      'height' => '180',
      'key'    => 'Icon-60@3x',
      'thumb'  => 'icon60x3',
),
),
  'max_size' => 
array (
    'width'    => '1024',
    'height'   => '1024',
),
);

//APP启动画面尺寸
$gGlobalConfig['startup_size'] =  array (
  'android' => 
array (
0 =>
array (
      'width'  => '480',
      'height' => '800',
      'key'    => 'android',
      'thumb'  => 'androidbg',
),
),
  'ios' => 
array (
0 =>
array (
      'width'  => '640',
      'height' => '1136',
      'key'    => 'Default-568h@2x',
      'thumb'  => 'default568x2',
),
1 =>
array (
      'width'  => '640',
      'height' => '960',
      'key'    => 'Default@2x',
      'thumb'  => 'defaultx2',
),
2 =>
array (
      'width'  => '640',
      'height' => '1136',
      'key'    => 'Default-568h@2x-2',
      'thumb'  => 'default568x2',
),
3 =>
array (
      'width'  => '640',
      'height' => '960',
      'key'    => 'Default@2x-1',
      'thumb'  => 'defaultx2',
),
4 =>
array (
      'width'  => '750',
      'height' => '1334',
      'key'    => 'Default-667h@2x',
      'thumb'  => 'default667x2',
),
5 =>
array (
      'width'  => '1242',
      'height' => '2208',
      'key'    => 'Default-736h@3x',
      'thumb'  => 'default760x3',
),
6 =>
array (
      'width'  => '1242',
      'height' => '2208',
      'key'    => 'Default-736h@3x-1',
      'thumb'  => 'default760x3',
),
),
  'max_size' => 
array (
    'width'   => '1080',
    'height'  => '1920',
),
);

//APP引导图尺寸
$gGlobalConfig['guide_size'] =  array (
  'android' => 
array (
0 =>
array (
      'width'  => '480',
      'height' => '800',
      'key'    => 'android',
      'thumb'  => 'androidbg',
      'effect2'=> 'guideeffect1',
),
),
  'ios' => 
array (
0 =>
array (
      'width'  => '640',
      'height' => '1136',
      'key'    => 'ios',
      'thumb'  => 'default568x2',
      'effect2'=> 'guideeffect2',
),
),
  'max_size' => 
array (
    'width'   => '1080',
    'height'  => '1920',
),
);

//APP模块图标尺寸
$gGlobalConfig['module_size'] =  array (
  'android' => 
array (
0 =>
array (
      'width'  => '200',
      'height' => '200',
      'key'    => 'android',
      'thumb'  => 'modulepic',
),
),
  'ios' => 
array (
0 =>
array (
      'width'  => '200',
      'height' => '200',
      'key'    => 'ios',
      'thumb'  => 'modulepic',
),
),
  'max_size' => 
array (
    'width'    => '200',
    'height'   => '200',
),
);

//导航栏标题
$gGlobalConfig['navBarTitle_size'] =  array (
  'android' => 
array (
0 =>
array (
      'width'  => '60',
      'height' => '240',
),
),
  'ios' => 
array (
0 =>
array (
      'width'  => '60',
      'height' => '240',
),
),
  'max_size' => 
array (
    'width'    => '60',
    'height'   => '240',
),
);

//杂志首页背景
$gGlobalConfig['magazine_size'] =  array (
  'android' => 
array (
0 =>
array (
      'width'  => '1080',
      'height' => '1920',
),
),
  'ios' => 
array (
0 =>
array (
      'width'  => '640',
      'height' => '1136',
),
1 =>
array (
      'width'  => '640',
      'height' => '960',
),
),
  'max_size' => 
array (
    'width'    => '1080',
    'height'   => '1920',
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
      'url'  => 'publish.php?a=content',
),
    'column' => 
array (
      'name' => '栏目地址',
      'url'  => 'publish.php?a=column',
),
    'vote' => 
array (
      'name' => '投票地址',
      'url'  => 'vote.php',
),
),
);

//设置包名
$gGlobalConfig['package'] = array(
    'android' => 'com.hoge.android.app',
    'ios'     => 'com.hoge.ios.app'
);

//设置包名
$gGlobalConfig['package_prefix'] = 'com.hoge.app';

//设置启动方式
$gGlobalConfig['app_effect'] =  array (
0 =>
array (
    'identifier' => 'zoomInFadeOut',
    'option'     => '图片放大并淡出',
    'value'      => '4',
    'default'    => 0,
),
1 =>
array (
    'identifier' => 'fadeOut',
    'option'     => '图片淡出',
    'value'      => '1',
    'default'    => 0,
),
2 =>
array (
    'identifier' => 'slideLeft',
    'option'     => '图片向左边滑出',
    'value'	     => '2',
    'default'    => 0,
),
3 =>
array (
    'identifier' => 'slideRight',
    'option'     => '图片向右边滑出',
    'value'      => '3',
    'default'    => 1,
),
);

//设置版权文字大小
$gGlobalConfig['cpTextSize'] =  array (
0 =>
array (
    'identifier' => 'small',
    'option'     => '小',
    'value'      => '12',
    'default'    => 0,
),
1 =>
array (
    'identifier' => 'medium',
    'option'     => '中',
    'value'      => '16',
    'default'    => 0,
),
2 =>
array (
    'identifier' => 'large',
    'option'     => '大',
    'value'      => '20',
    'default'    => 0,
),
3 =>
array (
    'identifier' => 'default',
    'option'     => '默认',
    'value'      => '14',
    'default'    => 1,
),
);

//设置版权文字颜色默认值
$gGlobalConfig['cpTextColor'] =  '#1f497d';

//设置引导图效果
$gGlobalConfig['guideEffect'] =  array (
0 =>
array (
    'identifier' => 'effect1',
    'option'     => '跟随手指滑动',
    'value'      => '1',
    'default'    => 1,
),
1 =>
array (
    'identifier' => 'effect2',
    'option'     => '1张背景缓动',
    'value'      => '2',
    'default'    => 0,
),
2 =>
array (
    'identifier' => 'effect3',
    'option'     => '0-6张淡入淡出',
    'value'      => '3',
    'default'    => 0,
),
);

//引导图效果与类型数字的对应关系
$gGlobalConfig['guide_effect_setting'] = array(
    'effect1' => 1,
    'effect2' => 2,
    'effect3' => 3,
);

//设置引导图动画
$gGlobalConfig['guideAnimation'] =  array (
  'none' => 
array (
    'identifier' => 'none',
    'option'     => '无动画',
    'value'      => '0',
    'default'    => 1,
),
  'fadeIn' => 
array (
    'identifier' => 'fadeIn',
    'option'     => '淡入淡出',
    'value'      => '1',
    'default'    => 0,
),
  'smallDisapper' => 
array (
    'identifier' => 'smallDisapper',
    'option'     => '向后缩小消失',
    'value'      => '2',
    'default'    => 0,
),
  'accordion' => 
array (
    'identifier' => 'accordion',
    'option'     => '手风琴',
    'value'      => '3',
    'default'    => 0,
),
);

//引导图页脚标记
$gGlobalConfig['shapeSign'] =  array (
0 =>
array (
    'sign'     => '●',
    'default'  => 1,
),
1 =>
array (
    'sign'     => '★',
    'default'  => 0,
),
2 =>
array (
    'sign'     => '♪',
    'default'  => 0,
),
3 =>
array (
    'sign'     => '■',
    'default'  => 0,
),
);

//引导图标记默认色
$gGlobalConfig['signDefaultColor']  =  '#c0504d';

//引导图标记选中色
$gGlobalConfig['signSelectedColor'] =  '#8064a2';

//默认风格
define('DEFAULT_STYLE', 9);//默认经典风格

//默认界面
define('DEFAULT_UI', 13);//默认左右图

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

define('INITED_APP', true);

//每个应用的可上传的总的背景图数目
define('TOTAL_BACKGROUND_PIC_MUN',12);

//二维码URL
$gGlobalConfig['qrcode_url'] =  'http://applant.dev.hogesoft.com:233/qrcode.php';

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

//正文模板状态，方便后台控制
$gGlobalConfig['domain_audit_status'] =  array (
0=>  '初始状态',
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

/****************************upyun相关配置*******************************/
$gGlobalConfig['upyun'] = array(
    'bucket'  	=> 'imagedingdone',
    'username'  => 'imagedingdone',
	'password'  => 'hoge@dingdone',
	'host'		=> 'http://upimg.dingdone.com/',
);
/****************************upyun相关配置*******************************/


/****************************UI类型*******************************/
$gGlobalConfig['ui_type'] = array(
    0 => '选择类型',
    1 => 'MAIN-UI',
    2 => 'LIST-UI',
);
/****************************UI类型*******************************/

/****************************角色类型*******************************/
$gGlobalConfig['role_type'] = array(
    0 => '选择角色',
   -1 => '适合所有角色',
    1 => '普通用户',
    2 => '开发者',
);
/****************************角色类型*******************************/

/****************************属性类型*******************************/
$gGlobalConfig['attribute_type'] = array(
    0 => '选择属性类型',
    1 => array(
                  'name'     => '文本框',
                  'uniqueid' => 'textbox',
              ),
    2 => array(
                  'name'     => '文本域',
                  'uniqueid' => 'textfield',
              ),
    3 => array(
                  'name'     => '单选',
                  'uniqueid' => 'single_choice',
              ),
    4 => array(
                  'name'     => '勾选',
                  'uniqueid' => 'check',
              ),
    5 => array(
                  'name'     => '取值范围',
                  'uniqueid' => 'span',
              ),
    6 => array(
                  'name'     => '图片单选',
                  'uniqueid' => 'pic_radio',
              ),
    7 => array(
                  'name'     => '图片上传+单选',
                  'uniqueid' => 'pic_upload_radio',
              ),
    8 => array(
                  'name'     => '多选',
                  'uniqueid' => 'multiple_choice',
              ),
    9 => array(
                  'name'     => '拾色器',
                  'uniqueid' => 'color_picker',
              ),
    10 => array(
                  'name'     => '高级拾色器（色+透明度）',
                  'uniqueid' => 'advanced_color_picker',
              ),
    11 => array(
                  'name'     => '配色方案',
                  'uniqueid' => 'color_schemes',
              ),
    12 => array(
                  'name'     => '高级配色方案',
                  'uniqueid' => 'advanced_color_schemes',
              ),
    13 => array(
                  'name'     => '高级背景设置',
                  'uniqueid' => 'advanced_background_set',
              ),
    14 => array(
                  'name'     => '高级文字设置',
                  'uniqueid' => 'advanced_character_set',
              ),
);
/****************************属性类型*******************************/

/****************************应用的额外全局配置*****************************/
$gGlobalConfig['app_extras'] = array(
    'weatherInSetting'   => FALSE,
    'uCenterInSetting'   => FALSE,
    'favoritesInSetting' => FALSE,
    'bgManagerInSetting' => FALSE,
    'uCenterInMenu'      => FALSE,
    'favoritesInMenu'    => FALSE,
);
/****************************应用的额外全局配置*****************************/

/****************************默认城市*****************************/
$gGlobalConfig['default_city'] = array(
    'city' => '南京',
);
/****************************默认城市*****************************/

/*************************扩展字段表现样式类型******************************/
$gGlobalConfig['extend_field_type'] = array(
    1 => '图标+名称+数值',
    2 => '图标+数值',
    3 => '名称+数值',
    4 => '数值',
);
/*************************扩展字段表现样式类型******************************/

/*****************************扩展字段与icon对应关系************************/
$gGlobalConfig['extend_field_icon'] = array(
    'author'     => 'author',
    'source'     => 'source',
    'clickNum'   => 'click',
    'commentNum' => 'comment',
    'keywords'   => 'keywords',
    'duration'   => 'duration',
    'totalPicNum'=> 'totalpicnum',
    'custom1'    => '',
    'label1'     => '',
    'date1'      => 'time',
    'col1'       => '',
);
/*****************************扩展字段与icon对应关系************************/

/****************************角标的位置************************/
$gGlobalConfig['corner_pos'] = array(
    1 => '左上角',
    2 => '右上角',
    3 => '左下角',
    4 => '右下角',
);
/****************************角标的位置************************/

/****************************角标的文字方向************************/
$gGlobalConfig['corner_text_direction'] = array(
    0 => '横向显示',
    1 => '竖向显示',
);
/****************************角标的文字方向************************/

/***********************由前台属性设置后台属性的方式******************/                                  	
$gGlobalConfig['set_value_type'] = array(
    1 => '对关联属性统一设值',
    2 => '对关联属性分别设置',
);
/***********************由前台属性设置后台属性的方式******************/

/***********************模块本身等一些默认数据***********************/
$gGlobalConfig['module_default'] = array(
    'text_nor_bg'      => '#ffffff',//模块文字默认色
    'text_pre_bg'      => '#f1f1fa1',//模块文字点击色
    'layout_pre_bg'    => '#e06666',//模块点击色
    'layout_pre_alpha' => 1,//模块点击透明度
    'layout_nor_bg'	   => '#ff0000',//模块默认背景色
    'layout_nor_alpha' => 1,//模块默认透明度
    'main_color'	   => '#219cdf',//模块主色
    'navbar'		   => array(
                            'isBlur' => FALSE,
                            'height' => 50,
                            'bg'     => '#ff0000',
                            'titleContent' => '创想叮当',
                        ),
    'ui_bg'			   => '#fafafa',
);

/***********************模块本身等一些默认数据***********************/

/**************************开发者申请相关**************************/
//开发者申请技术要求
$gGlobalConfig['developer_tech'] = array(
    0 => '无研发能力',
    1 => 'HTML5制作能力',
    2 => '设计能力',
    3 => 'PHP后端开发',
    4 => 'IOS原生开发',
    5 => '安卓原生开发',
    6 => '其他',
);

//开发者申请营销要求
$gGlobalConfig['marketing'] = array(
    0 => '无市场营销能力',
    1 => '校园类',
    2 => '企业类',
    3 => '服务行业',
    4 => '房产行业',
    5 => '其它行业',
);

/**************************开发者申请相关**************************/

/**************************属性控件默认宽与高***********************/
$gGlobalConfig['attr_pic_set'] = array(
    'width' => 120,
    'height'=> 213,
);
/**************************属性控件默认宽与高***********************/

/**************************客户端预览推送的key等参数*****************/
$gGlobalConfig['preview_push'] = array(
    'app_id' 		     => 'huu4knmff7axbkrpenifoordrr8tyv6ngszia6dvluoshwjt',
    'app_key'            => 'hfmww7l37zns7ei0rup3tz6aux4a0gb9g1f363tqwtnctq7j',
    'master_key'         => '8qnui2w3p1apq784f8cznt8ld2x1zbgw3swzxcsf00fvh0iu',
    'channel'            => 'preview_',//通道设置为预览
);

/**************************客户端预览推送的key等参数*****************/

/**************************商业授权相关参数*****************/
//商业授权状态
$gGlobalConfig['business_auth_status'] =  array(
    0 => '全部状态',
    1 => '待开通',
    2 => '已开通',
    3 => '被打回',
);

//商业授权类型
$gGlobalConfig['business_auth_type'] =  array (
    0 => '全部类型',
    1 => '个人',
    2 => '企业',
);

define('BUSINESS_DUTATION',365 * 24 * 3600);//商业授权时限 1年
define('PAY_MONEY', 2000);//每年付款的金额

//付款事由
$gGlobalConfig['business_pay_reason'] =  array(
    1 => '首次申请',
    2 => '续费',
);

//支付类型
$gGlobalConfig['pay_type'] =  array(
    1 => '手动转账',
    2 => '支付宝',
);

//用户转账银行的配置
$gGlobalConfig['banks'] = array(
    1 => '中国交通银行',
    2 => '中国工商银行',
    3 => '中国农业银行银行',
    4 => '中国建设银行',
    5 => '南京银行',
    6 => '中国招商银行',
);

//发票申请的状态
$gGlobalConfig['invoice_status'] = array(
    1 => '待审核',
    2 => '已审核',
    3 => '被打回',
    4 => '已发货',
);

//票据类型
$gGlobalConfig['invoice_type'] = array(
    1 => '收据',
    2 => '普通发票',
    3 => '增值税发票',
);

/**************************商业授权相关参数*****************/

/**************************LISTUI组件化相关配置*****************/
//列出可以供组件绑定的组件标识
$gGlobalConfig['comp_list_ui'] = array(
    'ListUI1',
    'ListUI2',
    'ListUI3',
    'ListUI4',
    'ListUI5',
    'ListUI6',
    'ListUI7',
    'ListUI8',
    'ListUI9',
);

/**************************LISTUI组件化相关配置*****************/

/**************************JSSDK相关配置***********************/
$gGlobalConfig['jssdk'] = array(
    'expire_in' => 7200,//jssdk的api的过期时间
);
/**************************JSSDK相关配置***********************/
    
/**************************角标相关配置*************************/
$gGlobalConfig['superscript'] = array(
    'field_type'=> array(
                        'author'     => array('filter' => array(7 => '包含',8 => '排除'),'value_style' => 'input','name' => '作者'),
                        'source'     => array('filter' => array(7 => '包含',8 => '排除'),'value_style' => 'input','name' => '来源'),
                        'clickNum'   => array(
                        					'filter'      => array(1 => '等于',2 => '不等于',3 => '大于',4 => '小于',5 => '大于等于',6 => '小于等于'),
                        					'value_style' => 'input',
                                            'name'		  => '点击数',
                                        ),
                        'commentNum' => array(
                        					'filter'      => array(1 => '等于',2 => '不等于',3 => '大于',4 => '小于',5 => '大于等于',6 => '小于等于'),
                        					'value_style' => 'input',
                                            'name'		  => '评论数',
                                        ),
                        'keywords'   => array('filter' => array(7 => '包含',8 => '排除'),'value_style' => 'input','name' => '关键字'),
                        'duration'   => array(
                        					'filter'      => array(1 => '等于',2 => '不等于',3 => '大于',4 => '小于',5 => '大于等于',6 => '小于等于'),
                        					'value_style' => 'input',
                                            'name'		  => '时长',
                                        ),
                        'totalPicNum'=> array(
                        					'filter'      => array(1 => '等于',2 => '不等于',3 => '大于',4 => '小于',5 => '大于等于',6 => '小于等于'),
                        					'value_style' => 'input',
                                            'name'		  => '图片总数',
                                        ),
//                         'label1'     => array('filter' => array(7 => '包含',8 => '排除'),'value_style' => 'input','name' => '标签'),
                        'script_date'      => array('filter' => 11,'value_style' => 'date','name' => '日期'),
                ),
    'condition' => array(
                    1 => '满足以下全部条件',
                    2 => '满足以下任一条件',
                ),
     'filter'	=> array(
                    0 => '过滤条件',
                    1 => '等于',
                    2 => '不等于',
                    3 => '大于',
                    4 => '小于',
                    5 => '大于等于',
                    6 => '小于等于',
                    7 => '包含',
                    8 => '排除',
                    9 => '是',
                   10 => '否',
     				11 => 'time',
                ),
     'corner_list_ui' => 'Corner',
     'max_num'		  => 4,//每个模块最多可以使用角标的个数
     'show_type' => array(
                    1 => '重叠',
                    2 => '错开',
                ),
     'save_path' => 'app_icon/corners/',//角标系统图标保存位置
);
/**************************角标相关配置*************************/

/**************************leancloud开放平台配置*************************/
define("LEANCLOUD_CLIENT_ID","1a4f976c3622468bad5696b40e54b357");//client_id
define("LEANCLOUD_CLIENT_SECRET","54a8a8ccd8e211e4b9d61681e6b88ec1");//client_secret
/**************************leancloud开放平台配置end*************************/

//用于访问前台叮当地址
$gGlobalConfig['base_url'] = "http://applant.dev.hogesoft.com:233/";



