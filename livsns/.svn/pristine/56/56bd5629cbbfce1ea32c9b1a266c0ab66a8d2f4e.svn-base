<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: del_file.php 284 2011-11-07 02:00:09Z develop_tong $
***************************************************************************/
set_time_limit(0);
function flushMsg($msg)
{
	echo $msg . str_repeat(' ', 4096) . '<br /><script type="text/javascript">window.scrollTo(0,10000);</script>';
	ob_flush();
}
function del_file($dir,$time, $limit = true)
{
	if(file_exists($dir))
	{
		$open = opendir($dir);
		while(($file = readdir($open)) !== false)
		{
			if($file != '.' && $file != '..')
			{			
				$pdir = $dir . $file;
				if(is_dir($pdir))//判断是否 目录
				{
					del_file($pdir . '/',$time, $limit);
				}
				else
				{
					//if(filemtime($pdir) < $time && !strpos($pdir, '.id') && !strpos($pdir, '.list'))
					if(filemtime($pdir) < $time)
					{
						if ($limit)
						{
							if (strpos($pdir, '.mp4'))
							{
								flushMsg( $pdir );
								unlink($pdir);
							//file_put_contents(UPLOAD_DIR . 'clear.list', $pdir, FILE_APPEND);
							}
						}
						else
						{
							flushMsg( $pdir );
							unlink($pdir);
						}
					}
				}
			}
		}
		closedir($open);
	}
	else
	{
		echo "no exists!";
	}
}

define('ROOT_DIR', '../');
require(ROOT_DIR . 'global.php');
$time = $_REQUEST['time']? $_REQUEST['time'] : (time() - 3600 * 24 * 7);

ob_start();
del_file(UPLOAD_DIR, $time, false);
del_file(TARGET_DIR, $time);
?>