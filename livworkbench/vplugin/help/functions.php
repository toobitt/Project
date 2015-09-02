<?php if(!defined('PLUGIN_PATH')) exit('Access Denied');
function import($class)
{
	if(!class_exists($class))
	{
		include(PLUGIN_PATH . 'lib/' . $class . '.class.php');
	}
}
//初始化环境
function initialize()
{
	//加载curl;
	global $_HOGE;
	import('curl');
	$_HOGE['curl'] = new curl();
	$_HOGE['input'] = array_merge($_GET,$_POST);
	$_HOGE['cookie'] = $_COOKIE;
	session_start();
	$_HOGE['session'] = $_SESSION;
	
	if($_HOGE['session']['access_token'])
	{
		$_HOGE['curl']->setUrlPrefix(AUTH);
		$parameters = array(
		'a'	=> 'access_token_expired',
		'access_token'=>$_HOGE['session']['access_token'],
		);
		$_HOGE['curl']->setRequestFile('get_app_info.php');
		$_HOGE['curl']->setRequestParameters($parameters);
		$isexpired = $_HOGE['curl']->request();
		$isexpired = $isexpired[0]['result'];
	}
	//获取access_token
	if($isexpired || !$_HOGE['session']['access_token'])
	{
		$_HOGE['curl']->setUrlPrefix(AUTH);
		$parameters = array(
		'a'	=> 'show',
		'username'=>USER_NAME,
		'password'=>PASSWORD,
		);
		$_HOGE['curl']->setRequestFile('get_access_token.php');
		$_HOGE['curl']->setRequestParameters($parameters);
		$access_token = $_HOGE['curl']->request();
		$_SESSION['access_token'] = $access_token[0]['token'];
		if(!$_SESSION['access_token'])
		{
			exit('Token Error!');
		}
	}
	if(is_array($_HOGE['input']) && $_HOGE['input'])
	{
		foreach($_HOGE['input'] as $key=>$val)
		{
			//
		}
	}
}
//
function array2img($img = array(), $width=160, $height=120)
{	
	$thumbnail = $width . 'x' . $height . '/';
	return $img['host'] . $img['dir'] . $thumbnail  . $img['filepath'] . $img['filename'];
}
?>