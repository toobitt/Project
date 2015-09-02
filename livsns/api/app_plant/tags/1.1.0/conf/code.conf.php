<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: code.conf.php 5351 2011-12-15 08:54:57Z zhuld $
***************************************************************************/

define('UNKNOW', 'Unknow');               //未知错误
define('OBJECT_NULL','0x0000');           //对象为空
define('PARAM_WRONG', '0x1000');          //参数错误
define('NAME_EXISTS', '0x2000');          //中文名重复
define('ENGLISH_EXISTS', '0x2100');       //英文名重复
define('NO_APPID', '0x3000');             //应用id不存在
define('TYPE_ERROR', '0x4000');           //类型错误
define('TYPE_ERROR1', '0x4100');          //引导图类型错误
define('TYPE_ERROR2', '0x4200');          //应用图标类型错误
define('TYPE_ERROR3', '0x4300');          //启动画面类型错误
define('TYPE_ERROR4', '0x4400');          //导航栏标题类型错误
define('TYPE_ERROR5', '0x4500');          //首页背景类型错误
define('SIZE_ERROR', '0x5000');           //尺寸错误
define('SIZE_ERROR1', '0x5100');          //引导图尺寸错误
define('SIZE_ERROR2', '0x5200');          //应用图标尺寸错误
define('SIZE_ERROR3', '0x5300');          //启动画面尺寸错误
define('SIZE_ERROR4', '0x5400');          //导航栏标题尺寸错误
define('SIZE_ERROR5', '0x5500');          //首页背景尺寸错误
define('OVER_LIMIT', '0x6000');           //超过限制个数
define('URL_NOT_VALID', '0x7000');        //URL地址无效
define('CHAR_OVER', '0x8000');            //超过限定字符长度
define('SUCCESS',true);                   //成功
define('FAILED',false);                   //失败
define('COLOR_ERROR', '0x9000');          //颜色值错误
define('FILE_TYPE_ERROR', '0x10000');     //模块上传图标压缩包类型有误
define('APP_ICON_ERROR', '0x11000');      //APP图标未上传或上传有误
define('APP_STARTPIC_ERROR', '0x12000');  //APP启动画面未上传或上传有误
define('MARK_EXISTS', '0x13000');         //标识重复
define('NAME_REPEAT', '0x14000');         //名称重复
define('NO_SOLIDIFY_ID', '0x15000');      //没有固化模块id
define('NO_USER_ID', '0x16000');          //没有用户id
define('NO_SOLIDIFY_PARAM', '0x17000');   //没有用户id
define('NO_CONFIG_ID', '0x18000');        //没有配置id
define('PROPERTY_AUTH_FAIL', '0X19000');  //属性验证失败
define('COLUMN_SORT_WRONG', '0x20000');   //栏目排序错误
define('NOID','0x21000');//没有id
define('APP_ID_EXISTS_ERROR', '0x22000');//所传应用id有误
define('CLIENT_INFO_NOT_EXISTS', '0x23000');//客户端信息不存在
define('APP_NOT_EXISTS', '0x24000');//该应用不存在
define('NO_CLIENT_TYPE', '0x25000');//没有客户端类型
define('NO_APP_ID', '0x26000');//没有应用id
define('CUR_VERSION_TOO_LOW', '0x27000');//当前版本小于上一个版本
define('NO_VERSION_INFO', '0x28000');//没有版本信息
define('NO_APP_ID_OR_CLIENT_TYPE', '0x29000');//没有应用id或者没有客户端类型
define('NO_VERSION_ID', '0x30000');//没有版本id
define('NO_QUEUE_ID', '0x31000');//没有队列id
define('NO_VERSION_ID_OR_QUEUE_ID', '0x32000');//没有版本id或者队列id
define('NO_VERSION_NUM', '0x33000');//没有版本号
define('VERSION_NUM_ERROR', '0x34000');//版本号有错
define('ERR_SHARE_DATA', '0x35000');//分享数据有误
define('NO_UUID', '0x36000');//没有uuid
define('NO_SYSTEM_ICON_URL', '0x37000');//系统图标下载地址不能为空
define('NO_NAME', '0x38000');//没有用户名
define('NO_DINGDONE_NAME', '0x39000');//没有叮当用户名
define('NO_DINGDONE_USER_ID', '0x40000');//没有叮当用户id
define('NO_TYPE', '0x41000');//没有申请类型
define('NO_IDENTITY_NUM', '0x42000');//没有证件号
define('NO_ID_OR_QUEUE_ID', '0x43000');//没有版本id或者queue_id
define('NO_TPL_NAME', '0x44000');//没有模板名称
define('NO_TPL_HTML', '0x45000');//没有正文html
define('NO_LOGIN', '0x46000');//未登陆
define('NO_STATUS', '0x47000');//没有状态
define('NO_PROVINCE_CODE', '0x48000');//没有省的地区码
define('NO_ACCOUNT_NAME', '0x49000');//没有账号名
define('NO_PASSWORD', '0x50000');//没有密码
define('NO_PLANT_TYPE', '0x51000');//没有平台类型
define('NO_CITY_CODE', '0x52000');//没有城市的地区码
define('THIS_USER_NOT_PUSH_API', '0x53000');//该用户还未配置推送接口
define('MSG_CAN_NOT_EMPTY', '0x54000');//推送的消息不能为空
define('NO_MODULE_ID', '0x55000');//没有模块id
define('NO_CONTENT_ID', '0x56000');//没有内容id
define('NO_MODULE_MARK', '0x57000');//没有模块标识
define('NO_PUSH_URL', '0x58000');//没有推送链接
define('NO_SELECT_OPEN_MODE', '0x59000');//没有选择打开模式
define('NO_SELECT_DEVICE_TYPE', '0x60000');//未选择终端类型
define('DEVICE_TYPE_ERR', '0x61000');//终端类型有误
define('IDENTITY_AUTH_HAS_EXISTS', '0x62000');//您的申请已存在
define('MSG_IS_TOO_LONG', '0x63000');//消息过长
define('GUIDE_PIC_ERROR', '0x64000');//引导图上传错误

?>