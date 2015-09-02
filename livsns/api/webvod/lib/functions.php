<?php
/***************************************************************************
* LivCMS5.0
* (C)2004-2010 HOGE Software.
*
* $Id: functions.php 8250 2012-07-23 07:34:59Z lijiaying $
***************************************************************************/

/**
 * 根据路径读取原图以及生成的缩略的访问路径
 * @param $filename 图片名称（一般是数据库存的数据）
 * @param $path 图片读取的前路径
 * @param $size 图片的大小规则（一般是config里定义的规则，按照一定规则）
 */
function hg_get_images($filename,$path,$size)
{
	if(!$filename || !$path || !is_array($size))
	{
		return array();
	}
	$info = array();
	$info['img'] = $path.$filename;
	foreach($size as $key => $value)
	{
		$info[$key] = $path.$value['label'].$filename;
	}
	return $info;
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
	return $minute."’".$second."”";
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
 * 拼接流地址
 * @param $server 服务器信息
 * $server = array(
		'client' => 'hoge',
		'outhost' => 'stream.dev.hogesoft.com',
			);
 * 拼接流地址
 * @param $data 频道和流信息
 * $data = array(
			'channel' => '',
			'stream_name' => '',
			'backtime' => '', //1232132144241,12124124124124
			);
 * @param $type 流类型（'live','channels','internal'）
 * @param $protocol  (http:// or tvie://)
 * return $stream_type 流类型 flv: or m3u8:
 */
function hg_get_stream_url($server, $data, $type = 'channels', $protocol = 'http://', $stream_type = 'flv:')
{
	if ($protocol == 'tvie://')
	{
		$stream_type = '';
		$type = 'live';
	}
	$suffix = $data['backtime'];
	if ($stream_type == 'm3u8:' && !$suffix)
	{
		$suffix = 'live.m3u8';
	}
	$host = $server['outhost'];
	if ($server['rand'] && $server['append_host'])
	{
		$server['append_host'] = explode(',', $server['append_host']);
		$server['append_host'][] = $host;
		$count = count($server['append_host']) - 1;
		$index = mt_rand(0, $count);
		$host = $server['append_host'][$index];
	}
	$url = $protocol . $host. '/' . $type . '/' . $server['client'] . '/' . $data['channel'] . '/' . $stream_type . $data['stream_name'] . '/' . $suffix; 
	return $url;
}

/*声道判断*/
 function check_str($search_L,$search_R,$string)
{
	$left = false;
	$right = false;
	if(strstr($string,$search_L))
	{
		$left = true;
	}
	
	if(strstr($string,$search_R))
	{
		$right = true;
	}
	
	if($left && $right)
	{
		return 3;
	}
	else if($left)
	{
		return 2;
	}
	else if($right)
	{
		return 1;
	}
	else 
	{
		return 0;
	}
			
}

function hg_array_sameItems($array1,$array2) {
	$i = 0;
	$j = count($array1);
	$result = array();
	while($i<$j)
	{
		if(in_array($array1[$i],$array2))
		{
			array_push($result,$array1[$i]);
		}
		$i++;
	}
	return $result ? $result:false;
}

function hg_clear_cache($cache_path)
{
	if(is_dir($cache_path))
	{
		$dir = @opendir($cache_path);
		while($con = @readdir($dir)) 
		{
			if($con != "." && $con != ".." && $con != "") 
			{
				$file = $cache_path . '/' . $con;
				if(is_dir($file)) {
					hg_clear_cache($file);
					@rmdir($file);
				}
				if(file_exists($file))
				{
					@unlink($file);
				}
			}
		}
		closedir($dir);
	}
	else
	{
		return true;
	}
}

/*
	xml解析array
*/
function xml2Array($xml) 
{
	normalizeSimpleXML(simplexml_load_string($xml), $result);
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

/*流地址拼接

频道输出流
http://wowzaip:1935/appName/streamName/manifest.f4m
http://wowzaip:1935/appName/streamName/playlist.m3n8

信号的输出流
rtmp://wowzaip/ch_name/streamname

*/
function hg_streamUrl($wowzaip, $appName, $streamName, $type, $protocol = 'http://')
{
	$suffix = '';

	if ($type == 'flv')
	{
		$suffix = '/manifest.f4m';//?DVR
	}
	else if ($type == 'm3u8')
	{
		$suffix = '/playlist.m3u8';//?DVR
	}
	else
	{
		$protocol = 'rtmp://';
	}

	return $url = $protocol . $wowzaip . '/' . $appName . '/' . $streamName . $suffix;
}
?>