<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: global.php 4191 2011-07-25 08:28:38Z repheal $
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
define('TIMENOW', time());
require(ROOT_PATH . 'conf/global.conf.php');
if (DEBUG_MODE)
{
	define('STARTTIME', microtime());
	define('MEMORY_INIT', memory_get_usage());
	include(ROOT_PATH . 'lib/func/debug.php');
}
require(ROOT_PATH . 'lib/func/functions.php');
require(ROOT_PATH . 'frm/base_frm.php');

require(CUR_CONF_PATH . 'conf/config.php');
require(CUR_CONF_PATH . 'conf/code.conf.php');
if (DEVELOP_MODE)
{
}

if (!defined('WITHOUT_DB') || !WITHOUT_DB)
{
	include_once ROOT_PATH . 'lib/db/db_mysql.class.php';
	$gDB = new db();
	$gDB->connect($gDBconfig['host'], $gDBconfig['user'], $gDBconfig['pass'], $gDBconfig['database'], $gDBconfig['charset'], $gDBconfig['pconnect'], $gDBconfig['dbprefix']);
}
$_INPUT = hg_init_input();

date_default_timezone_set(TIMEZONE);
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