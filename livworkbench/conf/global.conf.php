<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: global.conf.php 3034 2013-05-21 03:31:05Z develop_tong $
***************************************************************************/
$gGlobalConfig['name'] = 'M2O新媒体综合运营平台';
$gGlobalConfig['enname'] = 'M2O';
$gGlobalConfig['corp_name'] = '南京厚建软件';
$gGlobalConfig['corp_en_name'] = 'HOGE Software Co., Ltd.';
$gGlobalConfig['officialsite'] = 'http://www.hoge.cn';
$gGlobalConfig['time_out'] = '30000';

$gGlobalConfig['group_types'] = array(
				3 => '普通用户',
				2 => '管理员',
				1 => '系统维护'
		);

$gGlobalConfig['verify_custom_api'] = array(
	'protocol' => 'http://',
	'host' => 'auth.hogesoft.com:233',
	'dir' => '',
    'port' => '233',
);
define('CACHE_DIR', ROOT_PATH . 'cache/');
define('RESOURCE_DIR', ROOT_DIR . 'res/');
define('MAX_ADMIN_TYPE', 3); //最大管理员类型
define('CREATE_DIR_MODE', 0777);
define('TIMEZONEOFFSET', -8);
$gGlobalConfig['max_admin_type'] = 3;
?>
