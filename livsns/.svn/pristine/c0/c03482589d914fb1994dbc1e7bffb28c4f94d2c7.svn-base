<?php
function hg_mc_sec2format($time)
{
	$mcr_time_sec = $time % 1000;
	$mcr_time_sec = str_pad($mcr_time_sec, 3, '0', STR_PAD_LEFT);
	$time = intval($time / 1000);
	$h = intval($time / 3600);
	$h = str_pad($h, 2, '0', STR_PAD_LEFT);
	$sec = $time % 3600;
	$m = intval($sec / 60);
	$m = str_pad($m, 2, '0', STR_PAD_LEFT);
	$sec = $sec % 60;
	$sec = str_pad($sec, 2, '0', STR_PAD_LEFT);
	return $dur = $h . ':' . $m . ':' . $sec . '.' . $mcr_time_sec;
}

function hg_snap($time, $snapdir, $width, $height, $source, $times = 0, $spec_fname = '')
{
	$maxtimes = 2;
	if (!$spec_fname)
	{
		$spec_fname = $time;
	}
	$file = $spec_fname . '.jpg';
	$jpg = $snapdir .$file;
	$time1 = hg_mc_sec2format($time);
	$cmd = FFMPEG_CMD . ' -ss ' . $time1 . ' -s ' . $width .'x' . $height . ' -y "' . $jpg . '" -i "' . $source . '" -vframes 1';
	exec($cmd, $out, $s);
	if (!is_file($snapdir . $file) && $times < $maxtimes)
	{
		$times = intval($times) + 1;
		$file = hg_snap($time - $times * 40, $snapdir, $width, $height, $source, $times, $spec_fname);
	}
	return $file;
}
function hg_get_video_id()
{
	$last_id = @file_get_contents('../autoid/last_upload.id');
	if (!$last_id)
	{
		$last_id = 1;
	}
	file_put_contents('../autoid/last_upload.id', ($last_id + 1));
	mt_srand($last_id);
	$last_id =  mt_rand(0, 99999999999) . mt_rand(0, 100000);
	return $last_id;
}

function output($data)
{
	$debug = $_REQUEST['debug'];
	if (!$debug)
	{
		header('Content-Type:text/plain; charset=utf-8');
		echo json_encode($data);
	}
	else
	{
		header('Content-Type:text/html; charset=utf-8');
			echo '<pre>';
			print_r($data);
			echo '</pre>';
	}
	exit;
}

function error_output($errorno, $errortext, $more = '')
{
	$error = array(
		'errorno' => $errorno,	
		'errortext' => $errortext,	
		'more' => $more,	
	);
	output($error);
}

/**
 * 创建目录函数
 *
 * @param $dir 需要创建的目录
 */
function hg_mkdir($dir)
{
	if (!is_dir($dir))
	{
		if(!@mkdir($dir, CREATE_DIR_MODE, 1))
		{
			return false;//创建目录失败
		}
		@chmod($dir, CREATE_DIR_MODE);
	}
	return true;
}

/**
 * 写文件
 *
 * @return intager 写入数据的字节数
 */
function hg_file_write($filename, $content, $mode = 'rb+')
{
	$length = strlen($content);
	@touch($filename);
	if (!is_writeable($filename))
	{
		@chmod($filename, 0666);
	}

	if (($fp = @fopen($filename, $mode)) === false)
	{
		trigger_error('hg_file_write() failed to open stream: Permission denied', E_USER_WARNING);
		return false;
	}

	flock($fp, LOCK_EX | LOCK_NB);

	$bytes = 0;
	if (($bytes = @fwrite($fp, $content)) === false)
	{
		$errormsg = sprintf('file_write() Failed to write %d bytes to %s', $length, $filename);
		trigger_error($errormsg, E_USER_WARNING);
		return false;
	}

	if ($mode == 'rb+')
	{
		@ftruncate($fp, $length);
	}

	@fclose($fp);

	// 检查是否写入了所有的数据
	if ($bytes != $length)
	{
		$errormsg = sprintf('file_write() Only %d of %d bytes written, possibly out of free disk space.', $bytes, $length);
		trigger_error($errormsg, E_USER_WARNING);
		return false;
	}

	// 返回长度
	return $bytes;
}

function hg_num2dir($num)
{
	$dir = number_format($num);
	$dir = explode(',', $dir);
	$dir[0] = str_pad($dir[0], 3, '0', STR_PAD_LEFT);
	$dir = implode('/', $dir) . '/';
	return $dir;
}

function get_real_size($size)
{
         $kb = 1024;         // Kilobyte
         $mb = 1024 * $kb;   // Megabyte
         $gb = 1024 * $mb;   // Gigabyte
         $tb = 1024 * $gb;   // Terabyte

         if($size < $kb) {
            return $size." B";
         }else if($size < $mb) {
            return round($size/$kb,2)." KB";
         }else if($size < $gb) {
            return round($size/$mb,2)." MB";
         }else if($size < $tb) {
            return round($size/$gb,2)." GB";
         }else {
            return round($size/$tb,2)." TB";
         }
}

function _authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) 
{
	$ckey_length = 4;

	$key = md5($key ? $key : CODE_KEY);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for($i = 0; $i <= 255; $i++) 
	{
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for($j = $i = 0; $i < 256; $i++) 
	{
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) 
	{
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE') 
	{
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) 
		{
			return substr($result, 26);
		}
		else 
		{
				return '';
			}
	} 
	else 
	{
		return $keyc.str_replace('=', '', base64_encode($result));
	}

}
/**
* 检查IP地址是否正确。
*/
function hg_checkip ($ipaddres) 
{
	$preg="/\A((([0-9]?[0-9])|(1[0-9]{2})|(2[0-4][0-9])|(25[0-5]))\.){3}(([0-9]?[0-9])|(1[0-9]{2})|(2[0-4][0-9])|(25[0-5]))\Z/";
	if(preg_match($preg,$ipaddres))
	{
		return true;
	}
	return false;
}
/**
 * 获取IP
 *
 * @param none
 */
function hg_getip() 
{
	global $_INPUT;
	if ($_INPUT['lpip'])
	{
		if (hg_checkip($_INPUT['lpip']))
		{
			return $_INPUT['lpip'];
		}
	}
	if (isset($_SERVER)) 
	{
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			$realip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		elseif ($_SERVER['HTTP_X_REAL_IP'])
		{
			$realip = $_SERVER['HTTP_X_REAL_IP'];
		}
		elseif (isset($_SERVER['HTTP_CLIENT_IP'])) 
		{
			$realip = $_SERVER['HTTP_CLIENT_IP'];
		} 
		else 
		{
			$realip = $_SERVER['REMOTE_ADDR'];
		}
	} 
	else 
	{
		
		if (getenv("HTTP_X_FORWARDED_FOR")) 
		{
			$realip = getenv( "HTTP_X_FORWARDED_FOR");
		}
		elseif (getenv('HTTP_X_REAL_IP'))
		{
			$realip = getenv('HTTP_X_REAL_IP');
		} 
		elseif (getenv("HTTP_CLIENT_IP")) 
		{
			$realip = getenv("HTTP_CLIENT_IP");
		}
		else 
		{
			$realip = getenv("REMOTE_ADDR");
		}
	}
	$realip = explode(',', $realip);
	return $realip[0];
}

function hg_get_cmd_file($pre = 'get_')
{
	$filename = 'cmd/' . $pre . time() . mt_rand(1, 99999);
	while (is_file($filename))
	{
		$filename = hg_get_cmd_file();
	}
	return $filename;
}
?>