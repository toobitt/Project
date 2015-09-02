<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: error.conf.php 2167 2011-02-22 07:30:12Z develop_tong $
***************************************************************************/

$errorConf = array(
	UNKNOW => '未知错误',
	USENAME_REPEAT => '用户名已被使用',
	USENAME_NOLOGIN => '用户未登录',
	OBJECT_NULL => '对象为空',
	EMAIL_ERROR => 'Email不符合规则',
	EMAIL_REPEAT => 'Email已被使用',
	PHOTO_ERROR => '图片不符合规则',
	UPLOAD_ERR_OK => '文件上传成功',
	UPLOAD_ERR_INI_SIZE => '文件超过了服务器指定的大小',
	UPLOAD_ERR_FORM_SIZE => '文件超过了系统指定的大小',
	UPLOAD_ERR_PARTIAL => '文件只有部分被上传',
	UPLOAD_ERR_NO_FILE => '没有文件被上传',
	PHOTO_TYPE => '图片不是系统规定类型',
	LOGIN_FAILED => '登录失败，请重新登录',	
	SUCCESS => '成功',
	FAILED => '失败',
	INPUT_ERROR => '用户id参数缺失',
	OUT_OF_LIMIT => '标注个数超限！',
	ERR_PASSWORD => '密码错误',
);
?>