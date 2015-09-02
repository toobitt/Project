<?php
define('M2O_ROOT_PATH', '../');
@include_once(M2O_ROOT_PATH . 'conf/config.php');
if (!trim($_REQUEST['url']))
{
	exit;
}
if (defined('DRM_SALT') && DRM_SALT)
{
	$hash = $_REQUEST['hash'];
	$salt = md5(DRM_SALT . $_REQUEST['url'] . DRM_SALT);
	if ($hash != $salt)
	{
		$url = $_REQUEST['url'] . '?_upt=' . substr(md5(DRM_SALT), 0, 8) . (time() + 7200);
		echo $url;
		exit;
	}
}
if ($gGlobalConfig['open_cache']['drm'])
{
	$cache_file = 'drm_' . md5($_REQUEST['url']) . '.xml';
	$cache_dir = M2O_ROOT_PATH . '../cache/' . date('Ymd') . '/';
	if (!is_dir($cache_dir))
	{
		mkdir($cache_dir, 0777, 1);
	}
	$cache_file = $cache_dir . $cache_file;
	if (is_file($cache_file))
	{
		$filemtime = filemtime($cache_file);
		if (($filemtime + 600) < time())
		{
			$recache = true;
		}
		else
		{
			$content = @file_get_contents($cache_file);
			if (!$content)
			{
				$recache = true;
			}
			else
			{
				$recache = false;
			}
		}
	}
	else
	{
		$recache = true;
	}
	if ($recache)
	{
		$content = file_get_contents('http://' . $gGlobalConfig['App_live']['host'] . '/' . $gGlobalConfig['App_live']['dir'] . 'drm.php?url=' . addslashes($_REQUEST['url']) . '&refererurl=' . $refferurl);
		@file_put_contents($cache_file, $content);
	}
}
else
{
		$content = file_get_contents('http://' . $gGlobalConfig['App_live']['host'] . '/' . $gGlobalConfig['App_live']['dir'] . 'drm.php?url=' . addslashes($_REQUEST['url']) . '&refererurl=' . $refferurl);
}
echo $content;
?>