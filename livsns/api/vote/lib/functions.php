<?php
/***************************************************************************
* LivCMS5.0
* (C)2004-2010 HOGE Software.
*
* $Id: functions.php
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
function hg_get_vote_status_text($start_time, $end_time)
{
	$status_code = 0;
	$status_flag = '';
	global $gGlobalConfig;
	if(!$start_time && !$end_time)
	{
		$status_code = 1; //无限期 
		$status_flag = 'ing';
	}
	elseif($start_time && !$end_time)
	{
		if($start_time >= TIMENOW)
		{
			if($start_time - TIMENOW > 3600*24)
			{
				$status_flag = 'will';
				$status_code = 5; //距离投票还有n天
			}
			else
			{
				$status_flag = 'will';
				$status_code = 3; //即将开始 12h以内
			}
		}
		else
		{
			$status_flag = 'ing';
			$status_code = 4; //正在进行
		}
	}
	elseif($end_time && !$start_time)
	{
		if(TIMENOW > $end_time)
		{
			$status_flag = 'over';
			$status_code = 2; //结束
		}
		else
		{
			$status_flag = 'ing';
			$status_code = 4; //正在进行
		}
	}
	else 
	{
		if($start_time <= TIMENOW && $end_time >=TIMENOW)
		{
			$status_flag = 'ing';
			$status_code = 4; //正在进行
		}
		if($start_time > TIMENOW)
		{
			if($start_time - TIMENOW > 3600*24)
			{
				$status_flag = 'will';
				$status_code = 5; //距离投票还有n天
			}
			else
			{
				$status_flag = 'will';
				$status_code = 3; //即将开始 12h以内
			}
		}
		if($end_time < TIMENOW)
		{
			$status_flag = 'over';
			$status_code = 2; //结束
		}
	}
	/*
	elseif($end_time && (TIMENOW > $end_time))
	{
		$status_flag = 'over';
		$status_code = 2; //结束
	}
	elseif($start_time && ($start_time - TIMENOW > 3600*12))
	{
		$status_flag = 'will';
		$status_code = 5; //距离投票还有n天
	}
	elseif($start_time && ($start_time - TIMENOW <= 3600*12))
	{
		$status_flag = 'will';
		$status_code = 3; //即将开始 12h以内
	}
	elseif($start_time <= TIMENOW && $end_time >=TIMENOW)
	{
		$status_flag = 'ing';
		$status_code = 4; //正在进行
	}
	*/
	$return = array();
	$return['status_flag'] = $status_flag;
	$return['status_text'] = $status_code == 5 ? str_replace('{$day}', floor(($start_time - TIMENOW)/(24*3600)), $gGlobalConfig['status_text'][$status_code]) : $gGlobalConfig['status_text'][$status_code];
	return $return;
}

/**
 *  10.0.0.0/8:10.0.0.0-10.255.255.255 
 *	172.16.0.0/12:172.16.0.0-172.31.255.255 
 *	192.168.0.0/16:192.168.0.0-192.168.255.255 
 * @param unknown_type $ip
 */
function is_reserverd_ip($ip)
{
	if(!$ip)
	{
		return false;
	}
	$arr = explode(',',$ip);
	if($arr[0] == 10)
	{
		if(($arr[1] >= 0 && $arr[1] <= 255) || ($arr[2] >= 0 && $arr[2] <= 255) || ($arr[3] >= 0 && $arr[3] <= 255))
		{
			return true;
		}
	}
	if($arr[0] == 172)
	{
		if(($arr[1] >= 16 && $arr[1] <= 31) || ($arr[2] >= 0 && $arr[2] <= 255) || ($arr[3] >= 0 && $arr[3] <= 255))
		{
			return true;
		}
	}
	if($arr[0] == 192 && $arr[1] == 168 )
	{
		if(($arr[2] >= 0 && $arr[2] <= 255) || ($arr[3] >= 0 && $arr[3] <= 255))
		{
			return true;
		}
	}
	
	return false;

}

function create_filename ($data,$pwd = '',$len = 13)//$pwd密钥　$data需加密字符串
{
	return substr(base64_encode(md5($data.$pwd)),0,$len);
}

/**
 * 原目录，复制到的目录
 * */
function file_copy($from, $to, $filenamearr = array())
{
    $status = true;
    $dir = @opendir($from);
    if (!is_dir($to))
    {
        @hg_mkdir($to);
    }
    while (false !== ( $file = readdir($dir)))
    {
        if ($filenamearr)
        {
            if (!in_array($file, $filenamearr))
            {
                continue;
            }
        }

        if (( $file != '.' ) && ( $file != '..' ))
        {
            if (is_dir($from . '/' . $file))
            {
                file_copy($from . '/' . $file, $to . '/' . $file, $filenamearr);
            }
            else
            {
                if(!@copy($from . '/' . $file, $to . '/' . $file))
                {
                    $status = false;
                    break;
                }
            }
        }
    }
    closedir($dir);
    return $status;
}
?>