<?php
/***************************************************************************
* LivCMS5.0
* (C)2004-2010 HOGE Software.
*
* $Id: functions.php 18528 2013-03-09 03:21:04Z lijiaying $
***************************************************************************/

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
function hg_set_stream_url($wowzaip, $app_name, $stream_name, $type='', $starttime='', $dvr='', $protocol = 'http://')
{
	$suffix = '';

	if ($type == 'flv')
	{
		$suffix = '/manifest.f4m';
	}
	else if ($type == 'm3u8')
	{
		$suffix = '/playlist.m3u8';
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
	
	
	return $url = $protocol . $wowzaip . '/' . $app_name . '/' . $stream_name . $suffix . $dvr . $starttime;
}

function hg_check_string($str)
{
	if (preg_match("/^[A-Za-z0-9_]+$/i", $str))
	{
		return true;
	}
	return false;
}

?>