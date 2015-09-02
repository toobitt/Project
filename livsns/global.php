<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: global.php 43627 2015-01-19 01:50:23Z develop_tong $
***************************************************************************/

header('Content-Type:text/html; charset=utf-8');
if(!defined('ROOT_DIR'))
{
	define('ROOT_DIR', './');
}
if(!defined('CUR_CONF_PATH'))
{
	define('CUR_CONF_PATH', './');
}
define('ROOT_PATH', ($dir = @realpath(ROOT_DIR)) ? $dir . '/' : ROOT_DIR);
// 防止 PHP 5.1.x 使用时间函数报错
if (function_exists('date_default_timezone_set'))
{
    date_default_timezone_set('PRC');
}
define('TIMENOW', time());
$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
$is_iphone = (strpos($agent, 'iphone')) ? true : false;
$is_ipad = (strpos($agent, 'ipad')) ? true : false;
$is_ipod = (strpos($agent, 'ipod')) ? true : false;
$is_android = (strpos($agent, 'android')) ? true : false;
define('ISIOS', ($is_iphone || $is_ipad || $is_ipod));
define('ISANDROID', $is_android);
require(ROOT_PATH . 'conf/global.conf.php');
if (DEBUG_MODE)
{
	define('STARTTIME', microtime());
	define('MEMORY_INIT', memory_get_usage());
	include(ROOT_PATH . 'lib/func/debug.php');
}
require(ROOT_PATH . 'lib/func/functions.php');
require(ROOT_PATH . 'frm/base_frm.php');

@include(CUR_CONF_PATH . 'conf/config.php');
@include(CUR_CONF_PATH . 'conf/code.conf.php');

if (defined('PUBLISH_DBPRE') && PUBLISH_DBPRE)
{
	//$gDBconfig['database'] = PUBLISH_DBPRE . $gDBconfig['database'];
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

if (!defined('WITHOUT_DB') || !WITHOUT_DB)
{
	$gDB = hg_ConnectDB();
}

/*/用户输入安全过滤
foreach (array('_REQUEST', '_GET', '_POST', '_COOKIE', '_FILES', '_SERVER') as $v)
{
	$$v = hg_addslashes($$v);
}
*/
$_INPUT = hg_init_input();
register_shutdown_function('hg_done');

function hg_done()
{
	global $gDB;
	if ($gDB)
	{
		$gDB->close();
	}
}

/**
* 创建db
*/
function hg_ConnectDB()
{
	global $gDBconfig,$gDB;
	if (!$gDB && $gDBconfig['host'])
	{
		include_once ROOT_PATH . 'lib/db/db_mysql.class.php';
		$gDB = new db();
		$gDB->connect($gDBconfig['host'], $gDBconfig['user'], $gDBconfig['pass'], $gDBconfig['database'], $gDBconfig['charset'], $gDBconfig['pconnect']);
	}
	return $gDB;
}
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

function hg_addslashes($string) 
{
	//if(!MAGIC_QUOTES_GPC) 
	{
		if(is_array($string)) 
		{
			foreach($string as $key => $val) 
			{
				$string[$key] = hg_addslashes($val);
			}
		} 
		else 
		{
			$string = addslashes($string);
		}
	}
	return $string;
}
?>