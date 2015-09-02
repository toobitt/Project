<?php
/***************************************************************************
* LivCMS5.0
* (C)2004-2010 HOGE Software.
*
* $Id: functions.php 8250 2012-07-23 07:34:59Z lijiaying $
***************************************************************************/
/**
 * 创建图片
 * @param $uploadedfile 需生成的图片路径
 * @param $name 生成后的返回的图片名称
 * @param $path 需要存放的图片上级目录
 * @param $size 缩略图的尺寸（array）
 * @param $max_pixel 尺寸的最大值（M）
 * return 生成后的图片名
 */
function hg_mk_images($uploadedfile,$name,$path,$size,$max_pixel = 2)
{
	if(!$uploadedfile || !$name || !$path || !is_array($size))
	{
		return OBJECT_NULL;
	}
	include_once(ROOT_PATH . 'lib/class/gdimage.php');
	//源文件
	if((filesize($uploadedfile)/1024/1024) >= $max_pixel)
	{
		return PIXEL_ERROR;
	}
	$image = getimagesize($uploadedfile);
	$width = $image[0];
	$height = $image[1];

	$file_name = $name;

	
	//目录
	$file_dir = $path;	

	//文件路径
	$file_path = $file_dir . $file_name;

	if(!hg_mkdir($file_dir))
	{
		return UPLOAD_ERR_NO_FILE;
	}
	
	if(!copy($uploadedfile, $file_path))
	{			
		return UPLOAD_ERR_NO_FILE;	
	}
	
	$img = new GDImage($file_path , $file_path , '');
	foreach($size as $key=>$value)
	{
		$new_name = $value['label'].$file_name;
		$save_file_path = $file_dir . $new_name;
		$img->init_setting($file_path , $save_file_path , '');
		
		$img->maxHeight = $value['height'];
		$img->maxWidth = $value['width'];
	/*
		if($width > $height)
		{
			$img->maxWidth = $width > $value['width']?$value['width'] : $width;
			$img->maxHeight = $height * ($img->maxWidth/$width);
		}
		else 
		{
			$img->maxHeight = $height > $value['height']?$value['height'] : $height;
			$img->maxWidth = $width * ($img->maxHeight/$height);
		}
	*/	
		$img->makeThumb(3);		
	}
	return $file_name;
}

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
 * @param $wowzaip
 * @param $appName
 * @param $streamName
 * @param $type
 * @param $starttime
 * @param $dvr
 * @param $protocol
 */
function hg_streamUrl($wowzaip, $appName, $streamName, $type='', $starttime='', $dvr='', $protocol = 'http://')
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
	
	
	return $url = $protocol . $wowzaip . '/' . $appName . '/' . $streamName . $suffix . $dvr . $starttime;
}

function hg_check_string($str)
{
	if (preg_match("/^[A-Za-z0-9_]+$/i", $str))
	{
		return true;
	}
	return false;
}

function hg_update_time($start,$week_day)
{
	$week_now = date('N',$start);
	$new_arr = array_flip($week_day);
	if(count($week_day) > ($new_arr[$week_now]+1))
	{
		$ks = $new_arr[$week_now] + 1;
	}
	else
	{
		$ks = 0;
	}
	$week_day = array_flip($new_arr);
	$next_week = ($week_day[$ks] - $week_now)>0?($week_day[$ks] - $week_now):($week_day[$ks] - $week_now + 7);
	$start_time = $start+($next_week*86400);
	if($start_time < TIMENOW)
	{
		return hg_update_time($start_time,$week_day);
	}
	else
	{
		return $start_time;
	}
}

/**
 *  获取拼音信息
 *
 * @access    public
 * @param     string  $str  字符串
 * @param     int  $ishead  是否为首字母
 * @param     int  $isclose  解析后是否释放资源
 * @return    string
 */
function hg_getPinyin($str,$ishead=0,$isclose=1)
{
	global $pinyins;
    $restr = '';
    $str = trim($str);
    $slen = strlen($str);
    if($slen < 2)
    {
        return $str;
    }
    if(count($pinyins) == 0)
    {
        $fp = fopen('pinyin.dat', 'r');
        while(!feof($fp))
        {
            $line = trim(fgets($fp));
            $pinyins[$line[0].$line[1]] = substr($line, 3, strlen($line)-3);
        }
        fclose($fp);
    }
    for($i=0; $i<$slen; $i++)
    {
        if(ord($str[$i])>0x80)
        {
            $c = $str[$i].$str[$i+1];
            $i++;
            if(isset($pinyins[$c]))
            {
                if($ishead==0)
                {
                    $restr .= $pinyins[$c];
                }
                else
                {
                    $restr .= $pinyins[$c][0];
                }
            }else
            {
                $restr .= "_";
            }
        }else if( preg_match("/[a-z0-9]/i", $str[$i]) )
        {
            $restr .= $str[$i];
        }
        else
        {
            $restr .= "_";
        }
    }
    if($isclose==0)
    {
        unset($pinyins);
    }
    return $restr;
}

function hg_utf82gb($utfstr)
{
    if(function_exists('iconv'))
    {
        return iconv('utf-8','gbk//ignore',$utfstr);
    }
    global $UC2GBTABLE;
    $okstr = "";
    if(trim($utfstr)=="")
    {
        return $utfstr;
    }
    if(empty($UC2GBTABLE))
    {
        $filename = "gb2312-utf8.dat";
        $fp = fopen($filename,"r");
        while($l = fgets($fp,15))
        {
            $UC2GBTABLE[hexdec(substr($l, 7, 6))] = hexdec(substr($l, 0, 6));
        }
        fclose($fp);
    }
    $okstr = "";
    $ulen = strlen($utfstr);
    for($i=0;$i<$ulen;$i++)
    {
        $c = $utfstr[$i];
        $cb = decbin(ord($utfstr[$i]));
        if(strlen($cb)==8)
        {
            $csize = strpos(decbin(ord($cb)),"0");
            for($j=0;$j < $csize;$j++)
            {
                $i++; $c .= $utfstr[$i];
            }
            $c = utf82u($c);
            if(isset($UC2GBTABLE[$c]))
            {
                $c = dechex($UC2GBTABLE[$c]+0x8080);
                $okstr .= chr(hexdec($c[0].$c[1])).chr(hexdec($c[2].$c[3]));
            }
            else
            {
                $okstr .= "&#".$c.";";
            }
        }
        else
        {
            $okstr .= $c;
        }
    }
    $okstr = trim($okstr);
    return $okstr;
}
?>