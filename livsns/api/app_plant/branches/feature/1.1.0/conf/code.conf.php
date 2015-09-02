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

?>