<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: global.php 4332 2011-08-02 08:28:31Z repheal $
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
require(ROOT_PATH . 'lib/template/template.php');

if (DEBUG_MODE)
{
	define('STARTTIME', microtime());
	define('MEMORY_INIT', memory_get_usage());
	include(ROOT_PATH . 'lib/func/debug.php');
}
require(ROOT_PATH . 'lib/func/functions.php');
require(ROOT_PATH . 'lib/func/functions_ui.php');

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
$gUser = $session->LoadSession($_INPUT['uid'], $_INPUT['pass'], $_INPUT['sessionid']); 
date_default_timezone_set(TIMEZONE);

register_shutdown_function('hg_done');

function hg_done()
{
	/*global $gMemcache, $gDB;
	try 
	{
		if ($gDB)
		{
			$gDB->close();
			
			
		}
		if ($gMemcache)
		{
			@$gMemcache->close();
		}
	}
	catch (Exception $e) 
	{
	}*/
}
hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .'jquery.min.js');
hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .'new_tab.js');
hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'jquery.form.js');
hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'global.js');
hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'alertbox.min.js');
hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'alertbox.js');
hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'fa.js');
hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'notify.js');
hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'jQuery.equalHeights.js');
hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'excanvas.js');
hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'new_tab.js');
hg_add_head_element('js-c',"
var ROOT_PATH = '".ROOT_DIR."';
var RESOURCE_DIR = '".RESOURCE_DIR."';
var cookie_id = '" . $gGlobalConfig['cookie_prefix'] . "';
var cookie_path = '" . $gGlobalConfig['cookie_path'] . "';
var cookie_domain = '" . $gGlobalConfig['cookie_domain'] . "';
var TIME_OUT = 30000;
var SNS_MBLOG = '".SNS_MBLOG."';
var SNS_UCENTER = '".SNS_UCENTER."';
var SNS_VIDEO = '".SNS_VIDEO."';
var SNS_TOPIC = '".SNS_TOPIC."';
");

hg_add_head_element("js-c",'var now_user="'.$gUser['username'].'";' . "\n" . "\r\t\n" . "var sns_ui_url='" . SNS_MBLOG . "';" . "\r\t\n" .' var now_uid = "'.$gUser['id'].'";');
hg_add_head_element("js-c",'var user_avatars_url="'. AVATAR_URL .'";' );
hg_add_head_element('js',RESOURCE_DIR . 'scripts/chat_message.js');
hg_add_foot_element("js-c", "\r\t\n".'window.onload=function(){if(parseInt(now_uid,10)>0){setTimeout("check_new_msg()",5000);setTimeout("getnotify()",3000);}}');
hg_add_head_element('js',RESOURCE_DIR . 'scripts/pull_down.js');
$gTpl->addHeaderCode(hg_add_head_element('echo'));


/**
* 创建Memcache服务连接$memcache
* 向队列中增加数据方法为 $memcache->set('名称', '值');
*/
function hg_ConnectMemcache()
{
	global $gMemcacheConfig, $gMemcache;
	if (!$gMemcache)
	{
		$gMemcache = new Memcache();
		$connect = @$gMemcache->connect($gMemcacheConfig['host'], $gMemcacheConfig['port']);			
		if (!$connect)
		{
			include_once(ROOT_PATH . 'lib/class/memcache.class.php');
			$gMemcache = new memcache();
		}
	}
	return $gMemcache;
}
?>