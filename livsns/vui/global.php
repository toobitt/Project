<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: global.php 4569 2011-09-23 09:40:16Z repheal $
***************************************************************************/
header('Content-Type:text/html; charset=utf-8');
define('WITHOUT_DB', true);
if(!defined('ROOT_DIR'))
{
	define('ROOT_DIR', './');
}
define('ROOT_PATH', ($dir = @realpath(ROOT_DIR)) ? $dir . '/' : ROOT_DIR);
define('TIMENOW', time());
define('REFERRER', isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');
require(ROOT_PATH . 'conf/global.conf.php');
require(ROOT_PATH . 'conf/nav.conf.php');

if (DEBUG_MODE)
{
	define('STARTTIME', microtime());
	define('MEMORY_INIT', memory_get_usage());
	include(ROOT_PATH . 'lib/func/debug.php');
}

require(ROOT_PATH . 'lib/func/functions.php');
require(ROOT_PATH . 'lib/func/functions_ui.php');
require(ROOT_PATH . 'lib/template/template.php');

require('conf/config.php');
require('conf/template.conf.php');
require('lib/ui.base.php');
require('lib/session.php');
if (DEVELOP_MODE)
{
}
$gTpl = new Templates();
if (!defined('WITHOUT_DB') || !WITHOUT_DB)
{
	include_once ROOT_PATH . 'lib/db/db_mysql.class.php';
	$gDB = new db();
	$gDB->connect($gDBconfig['host'], $gDBconfig['user'], $gDBconfig['pass'], $gDBconfig['database'], $gDBconfig['charset'], $gDBconfig['pconnect'], $gDBconfig['dbprefix']);
}
$_INPUT = hg_init_input();
//用户登录session信息
$session = new Session();
//获取用户登录信息，这里将会存有用户的基础信息和权限部分
$gUser = $session->LoadSession($_INPUT['user'], $_INPUT['pass'], $_INPUT['sessionid']);

date_default_timezone_set(TIMEZONE);

//register_shutdown_function('hg_done');

hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .'jquery.min.js');
hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'alertbox.min.js');
hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'alertbox.js');
hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'global.js');
hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'new_tab.js');
hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'notify.js');
//hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'jQuery.equalHeights.js');
hg_add_head_element('js-c',"
var ROOT_PATH = '".ROOT_DIR."';
var RESOURCE_DIR = '".RESOURCE_DIR."';
var cookie_id = '" . $gGlobalConfig['cookie_prefix'] . "';
var cookie_path = '" . $gGlobalConfig['cookie_path'] . "';
var cookie_domain = '" . $gGlobalConfig['cookie_domain'] . "';
var SNS_UCENTER = '".SNS_UCENTER."';
var SNS_VIDEO = '".SNS_VIDEO."';
var TIME_OUT = 30000;
var REWRITE = '" . $gGlobalConfig['rewrite'] . "';
");
hg_add_head_element("js-c", "var sns_ui_url='" . SNS_MBLOG . "';" . "\r\t\n" . ' var now_uid = ' . $gUser['id'] .';');
hg_add_head_element('js',SNS_MBLOG . 'res/scripts/chat_message.js');
hg_add_head_element("js-c", "\r\t\n".'window.onload=function(){if(parseInt(now_uid,10)>0){setTimeout("getnotify()",3000);}}'); 
hg_add_head_element('js',RESOURCE_DIR . 'scripts/pull_down.js');
$gTpl->addHeaderCode(hg_add_head_element('echo'));
?>