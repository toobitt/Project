<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: global.php 9067 2015-04-14 03:52:30Z develop_tong $
***************************************************************************/

header('Content-Type:text/html; charset=utf-8');
if(!defined('ROOT_DIR'))
{
	define('ROOT_DIR', './');
}
define('ROOT_PATH', ($dir = @realpath(ROOT_DIR)) ? $dir . '/' : ROOT_DIR);

// 防止 PHP 5.1.x 使用时间函数报错
if (function_exists('date_default_timezone_set'))
{
    date_default_timezone_set('PRC');
}

// PHP 6 以后不需要再执行下面的操作
if (PHP_VERSION < '6.0.0')
{
	@set_magic_quotes_runtime(0);

	define('MAGIC_QUOTES_GPC', @get_magic_quotes_gpc() ? true : false);
	if (MAGIC_QUOTES_GPC)
	{
		function stripslashes_vars(&$vars)
		{
			if (is_array($vars))
			{
				foreach ($vars as $k => $v)
				{
					stripslashes_vars($vars[$k]);
				}
			}
			else if (is_string($vars))
			{
				$vars = stripslashes($vars);
			}
		}

		if (is_array($_FILES))
		{
			foreach ($_FILES as $key => $val)
			{
				$_FILES[$key]['tmp_name'] = str_replace('\\', '\\\\', $val['tmp_name']);
			}
		}

		foreach (array('_REQUEST', '_GET', '_POST', '_COOKIE', '_FILES') as $v)
		{
			stripslashes_vars($$v);
		}
	}

	define('SAFE_MODE', (@ini_get('safe_mode') || @strtolower(ini_get('safe_mode')) == 'on') ? true : false);
}
else
{
	define('MAGIC_QUOTES_GPC', false);
	define('SAFE_MODE', false);
}
define('TIMENOW', isset($_SERVER['REQUEST_TIME']) ? (int) $_SERVER['REQUEST_TIME'] : time());


$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
$is_iphone = (strpos($agent, 'iphone')) ? true : false;
$is_ipad = (strpos($agent, 'ipad')) ? true : false;
$is_ipod = (strpos($agent, 'ipod')) ? true : false;
$is_android = (strpos($agent, 'android')) ? true : false;
define('ISIOS', ($is_iphone || $is_ipad || $is_ipod));
define('ISANDROID', $is_android);
require(ROOT_PATH . 'conf/global.conf.php');
if(SCRIPT_NAME != 'install' && !@include(ROOT_PATH . 'conf/config.php'))
{
	header('Location:./install/');
}
require(ROOT_PATH . 'conf/template.conf.php');
if (DEBUG_MODE)
{
	define('STARTTIME', microtime());
	define('MEMORY_INIT', memory_get_usage());
	include(ROOT_PATH . 'lib/func/debug.php');
}
require(ROOT_PATH . 'lib/func/functions.php');
require(ROOT_PATH . 'lib/func/functions_ui.php');
require(ROOT_PATH . 'lib/class/functions.class.php');

require(ROOT_PATH . 'lib/ui.base.php');
require(ROOT_PATH . 'lib/templates/template.php');
@include(ROOT_PATH . 'conf/proxy.conf.php');

$_SERVER['HTTP_REFERER'] = hg_clean_value($_SERVER['HTTP_REFERER']);
define('REFERRER', $_SERVER['HTTP_REFERER']);
$gTpl = new Templates($gProxyConf);
if (DEVELOP_MODE)
{
	$gTpl->setTemplateVersion('');
}
else
{
	$gTpl->setTemplateVersion(SOFTVAR . '/' . $gGlobalConfig['version']);
}
$gTpl->addVar('gProxyConf', $gProxyConf);
$gCache = new class_functions();
if (DEVELOP_MODE)
{
}

if (defined('WITH_DB') && WITH_DB)
{
	$gDB = hg_checkDB();
}
$_INPUT = hg_init_input();


if (!defined('WITHOUT_LOGIN') || !WITHOUT_LOGIN)
{
	include(ROOT_PATH . 'lib/session.php');
	//用户登录session信息
	$session = new Session();
	//获取用户登录信息，这里将会存有用户的基础信息和权限部分
	$gUser = $session->LoadSession($_INPUT['access_token']);
	if (!$gUser['id'] && !in_array(SCRIPT_NAME, array('login', 'register')))
	{
		if (!$_INPUT['ajax'])
		{
			/*if ($_SERVER['query_string'])
			{
				$query_string = '?' . $_SERVER['query_string'];
			}*/
			if ($_SERVER['QUERY_STRING'])
            {
                $query_string = '?' . $_SERVER['QUERY_STRING'];
            }
			header('Location:' . ROOT_DIR . 'login.php' . $query_string);
			exit;
		}
		else
		{
			$data = array(
			    'login_error' => 1,
				'msg' => '请先登录',
				'callback' => "hg_ajax_post({href: 'login.php'}, '登录');",
			);
			echo json_encode($data);
			exit;
		}
	}
}
if (!$gGlobalConfig['App_auth'] && !in_array(SCRIPT_NAME, array('login', 'appstore')))
{
	header('Location:' . ROOT_DIR . 'appstore.php?app=auth');
}

register_shutdown_function('hg_done');

function hg_done()
{
	global $gDB;
	if ($gDB)
	{
		$gDB->close();
	}
}
//hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'global.js');
hg_add_head_element('js-c',"
var ROOT_PATH = '".ROOT_DIR."';
var RESOURCE_DIR = '".RESOURCE_DIR."';
var cookie_id = '" . $gGlobalConfig['cookie_prefix'] . "';
var cookie_path = '" . $gGlobalConfig['cookie_path'] . "';
var cookie_domain = '" . $gGlobalConfig['cookie_domain'] . "';
var TIME_OUT = '" . $gGlobalConfig['time_out'] . "';
var ACCESS_TOKEN = '" . $gUser['token'] . "';
var DEBUG_MODE = " . intval(DEBUG_MODE) . ";
var ISIOS = " . intval(ISIOS) . ";
var ISANDROID = " . intval(ISANDROID) . ";
var gAdmin = {group_type : '".$gUser['group_type']."',admin_id : '" . $gUser['id'] . "', admin_user : '" . $gUser['user_name']."', admin_pass : '" . $gUser['password'] . "'};
window.onbeforeunload = function(){hg_window_destruct();};
");

$gTpl->addHeaderCode(hg_add_head_element('echo'));
$gGlobalConfig['liv_client_info'] = hg_get_cookie('client_info'); 

if (!is_writeable(CACHE_DIR))
{
		$uiview = new uiview();
		$uiview->ReportError('请将缓存目录' . realpath(CACHE_DIR) . '设置为可写！');
}
if (!is_file(CACHE_DIR . 'expire.m2o'))
{
	$needUpAuth = true;
}
else
{
	$content = file_get_contents(CACHE_DIR . 'expire.m2o');
	if (!$content)
	{
		$needUpAuth = true;
	}
	else
	{
		$license = hoge_de($content);
		if (!$license['appid'])
		{
			$needUpAuth = true;
		}
		else
		{
			$needUpAuth = false;
		}
	}
}
if ($needUpAuth)
{	
	include(ROOT_PATH . 'lib/class/curl.class.php');
	$curl = new curl($gGlobalConfig['verify_custom_api']['host'], $gGlobalConfig['verify_custom_api']['dir']);
	$curl->setClient(CUSTOM_APPID, CUSTOM_APPKEY);
	$curl->setToken('');
	$curl->setErrorReturn('');
	$curl->setCurlTimeOut(10);
	$curl->mAutoInput = false;
	$curl->initPostData();
	$postdata = array(
		'useappkey'				=>	1,
	);
	foreach ($postdata as $k=>$v)
	{
		$curl->addRequestData($k, $v);
	}
	$content = $curl->request('Authorization.php');
	file_put_contents(CACHE_DIR . 'expire.m2o', $content);
	$license = hoge_de($content);
}
if ($license['domain'])
{
	$gGlobalConfig['license'] = $license['domain'];
}
$license['expire'] = @date('Y-m-d', $license['expire_time']);
$license['leftday'] = intval(($license['expire_time'] - TIMENOW) / 86400);
$gTpl->addVar('licenseInfo', $license);

function hg_checkDB()
{
	global $gDB;
	if (!$gDB)
	{
		global $gDBconfig;
		include_once ROOT_PATH . 'lib/db/db_mysql.class.php';
		$gDB = new db();
		$gDB->connect($gDBconfig['host'], $gDBconfig['user'], $gDBconfig['pass'], $gDBconfig['database'], $gDBconfig['charset'], $gDBconfig['pconnect'], $gDBconfig['dbprefix']);
	}
	return $gDB;
}
?>