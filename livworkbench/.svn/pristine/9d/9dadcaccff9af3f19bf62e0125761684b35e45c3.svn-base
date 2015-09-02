<?php
$dir = $argv[1];
$handle = dir($dir);

while ($file = $handle->read())
{
	$bdir = $dir . "/" . $file;
	if (is_file($bdir))
	{
		$file_type = strrchr($bdir, '.');
		if($file_type == '.zip')
		{
			echo $bdir = $dir . "/" . $file;
			$info = pathinfo($file);
			$filename = $info['filename'];
			$filename = md5($filename . 'cjjt');
			rename($bdir, $dir . $filename . '.zip');
		}
	}
}
?>