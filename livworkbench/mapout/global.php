<?php
header('Content-Type:text/html; charset=utf-8');
require('./func.php');
require('./config.php');
session_start();
$gUsers = $_SESSION['livauthuser'];
if (!$gUser && SCRIPT_NAME != 'login')
{
	//header('./Location:login.php');
}
$customer = $_REQUEST['c'];
$servers = @file_get_contents('db/server.' . $customer);
if ($servers)
{
	$servers = json_decode($servers, true);
}
else
{
	$servers = array();
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
?>