<?php
/***************************************************************************
* LivSNS 0.1
* (C)2009-2010 HOGE Software.
*
* $Id: error.conf.php 480 2010-12-14 02:08:50Z chengqing $
***************************************************************************/

$errorConf = array(
	UNKNOW => '未知错误',
	USENAME_REPEAT => '用户名已被使用',
	USENAME_NOLOGIN => '用户未登录',
	NOFOLLOWERS => '用户没有粉丝',
	NOBLACKLIST => '没有黑名单',
	OUTLIMIT => '超出操作数目限制',
	UNFOLLOW => '无法关注',
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
);
?>