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
'database' => 'dev_interview_old',
'charset' => 'utf8',
'pconncet' => '0',
);
define('DB_PREFIX','liv_');//定义数据库表前缀
define('QQBIAOQING_DIR',ROOT_DIR .'../livtemplates/tpl/lib/images/biaoqing/');
define('IMG_URL','http://img.dev.hogesoft.com:83/');//应用标识
define('APP_UNIQUEID','interview_old');//应用标识
define('UPLOAD','upload');
define('HOST','http://10.0.1.166/interview/');
$gGlobalConfig['file_type'] = array('gif','jpg','png');//允许上传文件格式
$gGlobalConfig['object_type'] =  array(
			'0' => '标准访谈',
			'1' => '新闻发布会',
		);

$gGlobalConfig['roles'] =  array(
			'0' => '游客',
			'1' => '管理员',
			'2' => '主持人',
			'3' => '嘉宾',
			'4' => '书记员',
			'5' => '记者',
			'6' => '普通用户',
		);
$gGlobalConfig['qqbiaoqing'] =  array(
			'num' => 60,
			'dir' => 'img/biaoqing/',
			'type' => '.gif'
);
$gGlobalConfig['roleoption'] = array(
		1 => array(1,2,3,4), //管理员 1审核，2忽略,3回复，4撤消
		2 => array(1,2,3,4),
		3 => array(3),
		4 => array(),
		6 => array(),
		0 => array(),
		);
define('INITED_APP', true);
?>