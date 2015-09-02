<?php
header('Content-Type: text/xml; charset=UTF-8');
define('M2O_ROOT_PATH', '../');
@include_once(M2O_ROOT_PATH . 'conf/config.php');
$cache_file = M2O_ROOT_PATH . '../cache/channel_' . intval($_REQUEST['id']) . '_xml.xml';
if ($gGlobalConfig['open_cache']['channel'])
{
	if (!is_dir(M2O_ROOT_PATH . '../cache/'))
	{
		mkdir(M2O_ROOT_PATH . '../cache/');
	}
	if (is_file($cache_file))
	{
		$filemtime = filemtime($cache_file);
		if (($filemtime + 300) < time())
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
		$content = file_get_contents('http://' . $gGlobalConfig['App_player']['host'] . '/' . $gGlobalConfig['App_player']['dir'] . 'live/channel_xml.php?extend=' . addslashes($_REQUEST['extend']) . '&id=' . addslashes($_REQUEST['id']) . '&time=' . addslashes($_REQUEST['time']) . '&first=' . addslashes($_REQUEST['first']) . '&url=' . addslashes($_REQUEST['url']));
		file_put_contents($cache_file, $content);
	}
}
else
{		
	$content = file_get_contents('http://' . $gGlobalConfig['App_player']['host'] . '/' . $gGlobalConfig['App_player']['dir'] . 'live/channel_xml.php?extend=' . addslashes($_REQUEST['extend']) . '&id=' . addslashes($_REQUEST['id']) . '&time=' . addslashes($_REQUEST['time']) . '&first=' . addslashes($_REQUEST['first']) . '&url=' . addslashes($_REQUEST['url']));

}
echo $content;
?>