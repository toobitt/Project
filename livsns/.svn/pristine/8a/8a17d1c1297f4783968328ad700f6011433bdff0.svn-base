<?php
header('Content-Type: text/xml; charset=UTF-8');
define('M2O_ROOT_PATH', '../');
@include_once(M2O_ROOT_PATH . 'conf/config.php');
if ($gGlobalConfig['open_cache']['program'])
{
	$fname = md5(intval($_REQUEST['channel_id']) . '_' . date('Ymd', $_REQUEST['time']));
	$cache_file = 'program_' . $fname . '.xml';
	$cache_dir = M2O_ROOT_PATH . '../cache/' . date('Ymd') . '/';
	if (!is_dir($cache_dir))
	{
		mkdir($cache_dir, 0777, 1);
	}
	$cache_file = $cache_dir . $cache_file;
	if (is_file($cache_file))
	{
		$filemtime = filemtime($cache_file);
		if (($filemtime + 120) < time())
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
		$content = file_get_contents('http://' . $gGlobalConfig['App_player']['host'] . '/' . $gGlobalConfig['App_player']['dir'] . 'live/program_xml.php?channel_id=' . addslashes($_REQUEST['channel_id']) . '&time=' . addslashes($_REQUEST['time']));
		file_put_contents($cache_file, $content);
	}
}
else
{
		$content = file_get_contents('http://' . $gGlobalConfig['App_player']['host'] . '/' . $gGlobalConfig['App_player']['dir'] . 'live/program_xml.php?channel_id=' . addslashes($_REQUEST['channel_id']) . '&time=' . addslashes($_REQUEST['time']));
}
echo $content;
?>