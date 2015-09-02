<?php
/***************************************************************************
* LivCMS5.0
* (C)2004-2010 HOGE Software.
*
* $Id: functions.php 46502 2015-07-03 04:15:21Z develop_tong $
***************************************************************************/
 /*
  * @function debug export var
  * @param:$fname
  * @param:$var 
  * @param:$line which line calls the function
  * @param:$file which file calls the function
  * 
  */
  
 function export_var($fname,$var,$line,$file,$flag=false)
 {
 	if(DEBUG_OPEN)
 	{
 		$path = realpath($fname);
 		$path_parts = pathinfo($path);
 		$content = $line."\n".$file."\n".var_export($var,1)."\n";
 		
 		if(@!file_put_contents($fname,$content)||!$flag)
 		{
 			echo "<div class='debug_export'>";
			echo $content;			
			echo "</div>";	
 		}//end if
 		
 	}//end if
 	
 }
 
 /*
  * 获取文件名称
  */
 function get_filename()
 {
 	 	$phpself = explode("/",$_SERVER['PHP_SELF']);
 		return substr($phpself[count($phpself)-1],0,-4);
 }
 
//时间格式化函数（传进来的时间是毫秒为单位）转化为诸如:10'23"
function time_format($time)
{
	$num = floor($time/1000);//求出总秒数
	$minute = floor($num/60);//求出分钟数
	$second = round($num%60);//求出秒数
	if($second < 10)
	{
		$second = '0'.$second;
	}
	return $minute."'".$second.'"';
}

//时间格式化函数(传过来的时间是毫秒为单位)转化为诸如:4小时28分36秒
function  hg_timeFormatChinese($time)
{
	$time = floor($time/1000);
	if($time < 0)
	{
		return;
	}
	else if($time >= 0 && $time < 60)
	{
		return $time.'秒';
	}
	else if($time >= 60 && $time < 3600)
	{
		$minute  = floor($time/60);
		$seconds = floor($time%60);
		if($seconds < 10)
		{
			$seconds = '0'.$seconds;
		}
	
		return $minute.'分'.$seconds.'秒';
		
	}else if($time >= 3600)
	{
		$hour   = floor($time/3600);
		$minute = floor(floor($time%3600)/60);
		$seconds= floor(floor($time%3600)%60);
		if($minute < 10)
		{
			$minute = '0'.$minute;
		}
		
		if($seconds < 10)
		{
			$seconds = '0'.$seconds;
		}
		
		return $hour.'时'.$minute.'分'.$seconds.'秒';
	}
}

/**
 * 解析xml成数组
 * Enter description here ...
 * @param unknown_type $xml
 */

function xml2Array($xml) 
{
	normalizeSimpleXML(simplexml_load_string($xml,null,LIBXML_NOCDATA), $result);
	return $result;
}
function normalizeSimpleXML($obj, &$result) 
{
	$data = $obj;
	if (is_object($data)) 
	{
		$data = get_object_vars($data);
	}
	if (is_array($data)) 
	{
		foreach ($data as $key => $value) 
		{
			$res = null;
			normalizeSimpleXML($value, $res);
			if (($key == '@attributes') && ($key)) 
			{
				$result = $res;
			}
			else 
			{
				$result[$key] = $res;
			}
		}
	}
	else
	{
		$result = $data;
	}
}

function hg_replace_stream_port($url)
{
	if (defined('OUTPUT_PORT') && OUTPUT_PORT)
	{
		$url = explode(':', $url);
		if (OUTPUT_PORT == 80)
		{
			$url = $url[0];
		}
		else
		{
			$url = $url[0] . ':' . OUTPUT_PORT;
		}
	}
	return $url;
}
/**
 * 拼接流地址
 * Enter description here ...
 * @param unknown_type $wowzaip
 * @param unknown_type $app_name
 * @param unknown_type $stream_name
 * @param unknown_type $type
 * @param unknown_type $starttime
 * @param unknown_type $dvr
 * @param unknown_type $protocol
 */
function hg_set_stream_url($wowzaip, $app_name, $stream_name, $type='',$servertype ='', $starttime='', $dvr='', $protocol = 'http://', $psuffix = '/playlist.m3u8')
{	
	global $gGlobalConfig;
	$suffix = '';

	if ($type == 'flv')
	{
		$suffix = '/manifest.f4m';
		$wowzaip = hg_replace_stream_port($wowzaip);
	}
	else if ($type == 'm3u8')
	{
		$suffix = $psuffix;
		$wowzaip = hg_replace_stream_port($wowzaip);
	}
	else if ($type == 'manifest.m3u8')
	{
		$suffix = '/manifest.m3u8';
		$wowzaip = hg_replace_stream_port($wowzaip);
	}
	else
	{
		$protocol = 'rtmp://';
	}
	if ($dvr == 'dvr')
	{
		$dvr = '?dvr';
	}

	if ($starttime)
	{
		$starttime = '&starttime=' . $starttime;
	}
	if ($app_name)
	{
		$app_name = '/' . $app_name;
	}
	if($servertype=='nginx')
	{
		if($protocol == 'rtmp://')
		{
			return $url = $protocol . $wowzaip . $app_name . '/' . $stream_name . $suffix . $dvr . $starttime;
		}
		$path = '/' . $stream_name . $suffix . $dvr . $starttime;
		$sign = hg_sign_uri($path, $gGlobalConfig['live_expire'], $gGlobalConfig['sign_type']);
		return $url = $protocol . $wowzaip . $path . $sign[0];
	}
	
	$path = $app_name . '/' . $stream_name . $suffix . $dvr . $starttime;
	if($protocol == 'http://')
	{
		$sign = hg_sign_uri($path, $gGlobalConfig['live_expire'], $gGlobalConfig['sign_type']);
	}
	return $url = $protocol . $wowzaip . $path . $sign[0];
}

function hg_check_string($str)
{
	if (preg_match("/^[A-Za-z0-9]+$/i", $str))
	{
		return true;
	}
	return false;
}
function hg_merge_ts($ts, $ts1)
{
	$time_zone = array();
	$last_source = 0;
	$livets = array();
	$last_end_time = 0;
	$i = 0;
	foreach ($ts1 AS $v)
	{
		$source = $v['source'];
		if ($source != $last_source)
		{
			$last_source = $source;
			if ($v['start_time'] == $last_end_time)
			{
				$i--;
				$time_zone[$i]['end_time'] = $time_zone[$i]['end_time'] + $v['file_left_time'];
			}
			else
			{
				$last_end_time = $v['start_time'] + $v['file_left_time'];
				$time_zone[$i] = array(
					'start_time' => $v['file_start_time'],	
					'end_time' => $last_end_time,	
				);
			}
			$i++;
		}
		$livets[$v['start_time']] = $v;
	}
	foreach ($ts AS $v)
	{
		$stime = $v['start_time'];
		$etime = $v['start_time'] + $v['duration'];
		$go = false;
		if ($time_zone)
		{
			foreach ($time_zone AS $zone)
			{
				if ($stime >= $zone['start_time'] && $stime <= $zone['end_time'])
				{
					$go = true;
					break;
				}
			}
		}
		if ($go)
		{
			continue;
		}
		$livets[$stime] = $v;
	}
	ksort($livets);
	return $livets;
}
function hg_clear_m3u8($dir)
{
	if (is_dir($dir))
	{
		$handle = dir($dir);
		while ($file = $handle->read())
		{
			if($file == '.' || $file == '..')
			{
				continue;
			}
			$bdir = $dir . "/" . $file;
			if (is_dir($bdir))
			{
				hg_clear_m3u8($bdir);
			}
			else
			{
				$file_type = strrchr($bdir, '.');
				if($file_type == '.m3u8' && $file != 'live.m3u8')
				{
					unlink($bdir);
				}
			}
		}
	}
}
function build_nginx_stream_name($channel_code, $stream_code)
{
	return $channel_code . '_' . $stream_code;
}
function build_push_stream_url($stream_name, $server)
{
	if(!$stream_name || !$server)
	{
		return '';
	}
	if(!is_array($server))
	{
		return '';
	}
	return $server['host'] . '/' . $server['output_dir'] . '/' . $stream_name;
}
if (!function_exists('hg_filter_ids'))
{
	function hg_filter_ids($id, $split = ',')
	{
		$pattern = '/^[\d]+(\\'.$split.'\d+){0,}$/';
		if(!preg_match($pattern, $id))
		{
			return -1;
		}
		return $id;
	}
}
/*
 * 挑选dvr表
 */
function select_table($tables = array(), $dvr = 'dvr')
{
	if(in_array($dvr,$tables))
	{
		$num = trim($dvr,'dvr')+1;
		$dvr = 'dvr'.$num;
		if($dvr == 'dvr1')
		{
			$dvr = 'dvr2';
		}
		$dvr = select_table($tables, $dvr);
	}
	return $dvr;
}
?>