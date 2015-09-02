<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: debug.php 10239 2012-09-01 03:45:50Z develop_tong $
***************************************************************************/
define('LOG_DIR', ROOT_PATH . 'uploads/log/');
function hg_debug_tofile($str = '',$is_arr = 0,$dir = '', $filename = 'log.txt', $op_type = 'a+', $tofile = true)
{	
	if($is_arr)
	{
		$str = var_export($str,true);  
	}
	if($op_type == "a" || $op_type=="a+")
	{
		if ($tofile)
		{
			$entersplit = "\r\n";
		}
		else
		{
			$entersplit = "<br />";
		}
	}
	
	$tmp_info = debug_backtrace();
	$str .= $entersplit;

	$debug_tree = "";
	$max = count($tmp_info);
	$i = 1;
	
	foreach ($tmp_info as $debug_info)
	{
		$space  = str_repeat('&nbsp;&nbsp;',$max - $i); 
		$debug_tree =  $entersplit . $space.$debug_info['file'] . " on line " . $debug_info['line'] . ":" . $debug_tree;  
		$i++;
	}
	$str = $entersplit . '[' . date('Y-m-d H:i:s') . ']' . $debug_tree.$str;
	
	if ($tofile)
	{
		$filename = $filename ? $filename : "log.txt"; 
		$filenamedir = explode('/', $filename);
		unset($filenamedir[count($filenamedir) - 1]);
		hg_mkdir(LOG_DIR . $dir . implode('/', $filenamedir));
		hg_file_write(LOG_DIR . $dir . $filename, $str, $op_type);
	}
	else
	{
		echo $str;
	}
}

function hg_page_debug() 
{
	global $gDB;
	$mtime = explode(' ', microtime());
	$starttime = explode(' ', STARTTIME);
	$totaltime = sprintf('%.6f', ($mtime[1] + $mtime[0] - $starttime[1] - $starttime[0]));
	$run = 'Processed in ' . $totaltime . ' second(s), ';
	$memory = memory_get_usage() - MEMORY_INIT;
	$memory = 'Memory:' . hg_fetch_number_format($memory, 1);
	return $run . $memory;
}

?>