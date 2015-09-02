<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: code.conf.php 3676 2011-04-19 00:49:06Z develop_tong $
***************************************************************************/

define('UNKNOW', 'Unknow');               //未知错误
define('OBJECT_NULL','0x0000');           //对象为空
define('SUCCESS',true);           //成功
define('FAILED',false);           //失败

define('USENAME_REPEAT', '0x1000');       //用户名已经存在
define('USENAME_NOLOGIN' , '0x1100');     //用户未登录

define('EMAIL_ERROR', '0x2000');          //Email不符合规则
define('EMAIL_REPEAT', '0x2100');         //Email已被使用

define('PHOTO_ERROR', '0x3000');          //图片不符合规则
define('UPLOAD_ERR_OK', '0x3100');        //文件上传成功
define('UPLOAD_ERR_INI_SIZE', '0x3200');  //文件超过了服务器指定的大小
define('UPLOAD_ERR_FORM_SIZE', '0x3300'); //文件超过了系统指定的大小
define('UPLOAD_ERR_PARTIAL', '0x3400');   //文件只有部分被上传
define('UPLOAD_ERR_NO_FILE', '0x3500');   //没有文件被上传
define('PHOTO_TYPE', '0x3600');           //图片不是系统规定类型

define('LOGIN_FAILED','0x4000');			//登录失败，请重新登录
define('COUNT_FALES','false');			//减少点滴数失败
define('INPUT_ERROR','0x4100');			//用户id参数错误
define('OUT_OF_LIMIT','0x4200');			//用户标注位置个数超限
define('ERR_PASSWORD','0x4300');			//用户标注位置个数超限

define('NO_FRIEND_USER','0x5000');			//没有关注该用户
?>