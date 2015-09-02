<?php
if (!$_GET['build_page_cache'])
{
	$opencache_dir = array(M2O_ROOT_PATH.'cache/pagecache/');
	$opencache_file = array('index.php');
	$dir = $_SERVER['DOCUMENT_ROOT'];

	foreach ($opencache_dir AS $k => $v)
	{
		if (strstr($dir, $v))
		{
			$opencache = true;
			break;
		}
		else
		{
			$opencache = false;
		}
	}
	$filename = explode('/', $_SERVER['SCRIPT_NAME']);
	$filename = $filename[count($filename) - 1];
	if (in_array($filename,$opencache_file))
	{
		$opencache = true;
	}
	else
	{
		$opencache = false;
	}var_dump($opencache);
	if ($opencache && $_SERVER['REQUEST_METHOD'] == 'GET')
	{	
		$pagecache_dir = './pageche/';
		if (!is_dir($pagecache_dir))
		{
			mkdir($pagecache_dir);
		}
		$time = substr(time(), 0, 8) . '00';
		$last = substr(($time - 1), 0, 8) . '00';
		$cachename = $pagecache_dir . md5($filename . $_SERVER['QUERY_STRING']);
		@unlink($cachename . $last . '.page');
		$cachename .= $time . '.page';
		if (is_file($cachename))
		{
			$content = file_get_contents($cachename);
			if ($content)
			{
				$content = str_replace('build_page_cache=1', '', $content);
				$content = str_replace('?&amp;', '?', $content);
				$content = str_replace('&?', '?', $content);
				echo $content;
				exit;
			}
		}
		else
		{
			$url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			if (strpos($url, '?'))
			{
				$url .= '&build_page_cache=1';
			}
			else
			{
				$url .= '?build_page_cache=1';
			}
			$content = @file_get_contents($url);
			if ($content)
			{
				$content = str_replace('build_page_cache=1', '', $content);
				$content = str_replace('?&amp;', '?', $content);
				$content = str_replace('&?', '?', $content);
				echo $content;
				file_put_contents($cachename, $content);
				echo $content;
				exit;
			}
		}
	}
}

?>