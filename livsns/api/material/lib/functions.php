<?php
/***************************************************************************
* LivCMS5.0
* (C)2004-2010 HOGE Software.
*
* $Id: functions.php 6759 2012-05-17 08:34:42Z repheal $
***************************************************************************/
/**
 * 创建图片
 * @param $uploadedfile 需生成的图片路径
 * @param $name 生成后的返回的图片名称
 * @param $path 需要存放的图片上级目录
 * @param $size 缩略图的尺寸（array）
 * @param $max_pixel 尺寸的最大值（M）
 * @param $force 当文件存在时是否强制重新生成
 * return 生成后的图片名
 */
function hg_mk_images($uploadedfile,$name,$path,$size,$water,$max_pixel = 100,$force = 0)
{
	$special = '';
	if(!file_exists($uploadedfile) || !$name || !$path || !is_array($size))
	{
		return false;
	}
	include_once(ROOT_PATH . 'lib/class/gdimage.php');
	//源文件
	if((filesize($uploadedfile)/1024/1024) >= $max_pixel)
	{
		return false;
	}
	
	$image = getimagesize($uploadedfile);
	$width = $image[0];
	$height = $image[1];
    
    //从 JPEG 或 TIFF 文件中读取 EXIF 头信息 相机照片缩略图旋转问题
    if ($image[2] == 2) {
        
        @$exif = exif_read_data($uploadedfile);
        if(!empty($exif['Orientation'])) {
            switch($exif['Orientation']) {
                case 8:
                    $tmp = $width;
                    $width = $height;
                    $height = $tmp;
                    break;
                case 3:
                    break;
                case 6:
                    $tmp = $width;
                    $width = $height;
                    $height = $tmp;                
                    break;
            }
        }  
    }
    //从 JPEG 或 TIFF 文件中读取 EXIF 头信息 相机照片缩略图旋转问题   

	$file_name = $name;

	//目录
	$file_dir = $path;

	//文件路径
	$file_path = $uploadedfile;
	$img = new GDImage();

	if($size['label'])
	{
		$new_name = $file_name;
		$other_dir = "";
		if(empty($size['other_dir']))
		{
			$other_dir = date('Y') . '/' . date('m') . '/';
		}
		else 
		{
			$other_dir = $size['other_dir'];
		}
		hg_mkdir($file_dir .  $size['label'] . '/' . $other_dir);
		$save_file_path = $file_dir .  $size['label'] . '/' . $other_dir . $new_name;
	}
	else
	{
		hg_mkdir($file_dir);
		$save_file_path = $file_dir . $file_name;
	}
	
	if(!file_exists($save_file_path) || $force)
	{
		$img->init_setting($file_path , $save_file_path);
		if($size['height'] == 1)   //按长边等比缩放
		{
			if($width > $height)
			{
				if($size['width'])
				{
					$img->maxWidth = $width > $size['width'] ? $size['width'] : $width;
					$img->maxHeight = $height * ($img->maxWidth/$width);
				}
			}
			else
			{
				if($size['width'])
				{
					$img->maxHeight = $height > $size['width'] ? $size['width'] : $height;
					$img->maxWidth = $width * ($img->maxHeight/$height);
				}
			}
		}
		else if (empty($size['width']))  //宽度不存在，按高度
		{
			$img->maxHeight = $height > $size['height'] ? $size['height'] : $height;
			$img->maxWidth = $width * ($img->maxHeight/$height);
		}
		else
		{
			if($width > $height)
			{
				if($size['width'])
				{
					$img->maxWidth = $width > $size['width'] ? $size['width'] : $width;
					if($size['height'])
					{
						$img->maxHeight = $height > $size['height'] ? $size['height'] : $height;
					}
					else 
					{
						$img->maxHeight = $height * ($img->maxWidth/$width);
					}
				}
			}
			else 
			{
				if($size['height'])
				{
					if(($height/$width > 1.2) && ($size['width']/$size['height'] > 1.2))
					{
						$special = 1;
						//$img->maxHeight = $height > $size['height'] ? $size['height'] : $height;
						//$img->maxWidth = $width * ($img->maxHeight/$height);	
                        $img->maxHeight = $height > $size['height'] ? $size['height'] : $height;
                        $img->maxWidth = $width > $size['width'] ? $size['width'] : $width;						
					}
					else 
					{
						$img->maxHeight = $height > $size['height'] ? $size['height'] : $height;
						$img->maxWidth = $width > $size['width'] ? $size['width'] : $width;
					}
				}
				else
				{
					$img->maxWidth = $width > $size['width'] ? $size['width'] : $width;
					$img->maxHeight = $height * ($img->maxWidth/$width);
				}
			}
		}
		$isSucc = $img->makeThumb(3,FALSE);	
		if(!$isSucc)
		{
			return false;
		}
		if($special)
		{
			//hg_add_backgroud($save_file_path, $size);
		}
	}
	if(!empty($water))
	{
		if($water['type'] == 1)
		{
			$water['water_file_path'] = $water['filename'] ? hg_getimg_default_dir() . WATER_PATH . $water['filename'] : '';
			$img->waterimg($save_file_path,$water);
		}
		else
		{
			$water['water_font'] = $water['water_font'] ? CUR_CONF_PATH . 'font/' . $water['water_font'] : CUR_CONF_PATH . 'font/arial.ttf';
			$water['font_size'] = $water['font_size'] ? $water['font_size'] : 14;
			$img->waterstr($save_file_path, $water);
		}
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


function hg_file_search($dir,&$arr,$ext)
{ 
	 $arr[$dir] = array();
    $fh = opendir($dir);
    while(false !== ($folder = readdir($fh)))
    {
        if($folder == '.' || $folder == '..')
        {
            continue;
        }
        if(is_file($dir.'/'.$folder))
        {
			if(strstr($folder,$ext))
			{
			   $arr[$dir][] = $folder;
			}
        }
        else
        {
            hg_file_search($dir . '/'. $folder , $arr , $ext);
        }
    }
}

function hg_unlink_file($dir,$ext)
{
	$arr = array();
	hg_file_search($dir,$arr,$ext);
	if(!empty($arr))
	{
		foreach($arr as $key => $value)
		{
			if(!empty($value))
			{
				foreach($value as $k => $v)
				{
					if(is_file($key . '/' . $v))
					{
						@unlink($key . '/' . $v);
					}
				}
			}
		}
	}
}

function hg_delete_material($filepath,$filename)
{
	@unlink($filepath . $filename);
	$filename = preg_replace('/(.*?\.).*?/siU',"\\1json",$filename);
	if(file_exists($filepath . $filename))
	{
		$file_handle = fopen($filepath . $filename, "r");
		$content = "";
		while (!feof($file_handle)) {
		   $content .= fgets($file_handle);
		}
		fclose($file_handle);
		$info = json_decode($content,true);
		if(!empty($info['thumb']))
		{
			foreach($info['thumb'] as $k => $v)
			{
				if(is_file($v) && file_exists($v))
				{
					@unlink($v);
				}				
			}
		}
		@unlink($filepath . $filename);
	}
}

//重命名
function hg_editFalse_material($filepath,$filename)
{
	$file_json = preg_replace('/(.*?\.).*?/siU',"\\1json",$filename);
	if(file_exists($filepath . $file_json))
	{
		$file_handle = fopen($filepath . $file_json, "r");
		$content = "";
		while (!feof($file_handle)) {
		   $content .= fgets($file_handle);
		}
		fclose($file_handle);
		$info = json_decode($content,true);
		if(!empty($info['thumb']))
		{
			$arr = array();
			foreach($info['thumb'] as $k => $v)
			{
				if(is_file($v) && file_exists($v))
				{
					$arr[] = $n = str_replace($filename,'del_' . $filename,$v);
					@rename($v,$n); //修改缩略图文件名
				}				
			}
			unset($info['thumb']);
			$info['thumb'] = $arr;
			hg_file_write($filepath . $file_json ,json_encode($info)); //重写json内容中缩略图名
			@rename($filepath . $file_json,str_replace($file_json,'del_' . $file_json,$filepath . $file_json));//对json文件重命名
		}
		@rename($filepath . $filename,str_replace($filename,'del_' . $filename,$filepath . $filename)); //原图
	}
}

//恢复
function hg_editTrue_material($filepath,$filename)
{
	$file_json = preg_replace('/(.*?\.).*?/siU',"\\1json",$filename);
	if(file_exists($filepath . 'del_' . $file_json))
	{
		$file_handle = fopen($filepath .'del_' .  $file_json, "r");
		$content = "";
		while (!feof($file_handle)) {
		   $content .= fgets($file_handle);
		}
		fclose($file_handle);
		$info = json_decode($content,true);
		if(!empty($info['thumb']))
		{
			$arr = array();
			foreach($info['thumb'] as $k => $v)
			{
				if(is_file($v) && file_exists($v))
				{
					$arr[] = $n = str_replace('del_' . $filename,$filename,$v);
					@rename($v,$n); //修改缩略图文件名改回
				}				
			}
			unset($info['thumb']);
			$info['thumb'] = $arr;
			hg_file_write($filepath . 'del_' . $file_json,json_encode($info)); //重写json内容中缩略图名
			@rename($filepath . 'del_' . $file_json,$filepath . $file_json);//对json文件改回
		}
		@rename($filepath . 'del_' . $filename,$filepath . $filename); //原图
	}
	
}

function addslashes_vars(&$vars)
{
	if (is_array($vars))
	{
		foreach ($vars as $k => $v)
		{
			addslashes_vars($vars[$k]);
		}
	}
	else if (is_string($vars))
	{
		$vars = addslashes($vars);
	}
}


/**
*  判读远程图片(文件)是否存在
*  img_exists
*/

function hg_img_exists($url)
{
	$res=curl_init();
	curl_setopt($res,CURLOPT_URL,$url);
	curl_setopt($res,CURLOPT_NOBODY,1);
	curl_setopt($res,CURLOPT_FAILONERROR,1);
	curl_setopt($res,CURLOPT_RETURNTRANSFER,1);
	if(curl_exec($res)!==false)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function check_remote_file_exists($url) 
{ 
	$curl = curl_init($url); 
	// 不取回数据 
	curl_setopt($curl, CURLOPT_NOBODY, true); 
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
	// 发送请求 
	$result = curl_exec($curl); 
	$found = false; 
	// 如果请求没有发送失败 
	if ($result !== false) 
	{ 
		// 再检查http响应码是否为200 
		$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE); 
		if ($statusCode == 200) 
		{ 
			$found = true; 
		} 
	} 
	curl_close($curl); 
	return $found; 
} 

/***************************
*
* 旋转图片
* @param $file_path 文件路径
* @param $save_file_path 文件保存路径
* @param $direction 旋转方向 1 左旋转 2 右旋转 
*
****************************/
function hg_turn_img($file_path,$save_file_path,$direction)
{
	include_once(ROOT_PATH . 'lib/class/gdimage.php');
	$angle = '';
	switch($direction)
	{
		case 1:  //左旋转
			$angle = '90';
			break;
		case 2:	//右旋转
			$angle = '-90';
			break;
        case 3: //180度 掉头
            $angle = '180';
            break;    
		default:
			return false;
			break;
	}
	$img = new GDImage();
	$img->init_setting($file_path , $save_file_path, '', $angle);
	$img->turnImg();
	return true;
}



/***************************
 * 
 * 给图片加边框背景
 * @param $file_path 文件路径
 * 
 */

function hg_add_backgroud($file_path,$size)
{
	$image = getimagesize($file_path);
	$width = $image[0];
	$height = $image[1];
	$type = $image[2];
    switch ($type) 
    {   
        case 3:   
            $src_im = imagecreatefrompng($file_path);   
            break 1;   
        case 2:   
            $src_im = imagecreatefromjpeg($file_path);   
            break 1;   
        case 1:   
            $src_im = imagecreatefromgif($file_path);   
            break 1;   
         default:   
            return false;   
    }   
	if($size['width'] > $width || $size['height'] > $height)
	{
		$im = imagecreatetruecolor($size['width'], $size['height']);
		$color = imagecolorallocate($im,238,238,238);
		imagefill($im,0,0,$color);
		$new_width = $width;
		if($size['height'] > $height)
		{	
			$new_width = ($width/$height) * $size['height'];
		}
		imagecopyresized($im,$src_im,($size['width']-$new_width)/2,0,0,0,$new_width,$size['height'],$width,$height);
	    switch ($type) 
	    {   
	        case 3:   
	            imagepng($im,$file_path);
	            break 1;   
	        case 2:   
	            imagejpeg($im,$file_path);  
	            break 1;   
	        case 1:   
	            imagegif($im,$file_path); 
	            break 1;   
	         default:   
	            return false;   
	    }  		
	}
	return false;
}


function curl_local($url,$newfile)
{
	$curl = curl_init(); 
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_HEADER, 0);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$data = curl_exec($curl);
	curl_close($curl);
	$write = @fopen($newfile,"w");
	fwrite($write,$data);
	fclose($write);
	return TRUE;
}

function hg_file_get_contents($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_TIMEOUT, 60);
    $data = curl_exec($curl);
    $http_status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    $data = array('data' => $data, 'statusCode' => $http_status_code);
    return $data;
}


/**
 * 将类似 #U7490#U7490 的字符串转换成中文
 * 压缩包目录中含有中文会导致文件名变为#U7490#U7490
 * @param $str
 * @return mixed|string
 */
function changeU($str)
{
    if (!$str)
    {
        return $str;
    }
    if (strpos($str, '#U') !== FALSE)
    {
        $str = '"'.$str.'"';
        $str1 = str_replace('#U', '\u', $str);
        $str2 = json_decode($str1);
        if($str1 != $str2)
        {
            $str = $str2;
        }
    }
    return $str;
}

function hg_getimg_bs() {
    global $gGlobalConfig;
    return $gGlobalConfig["curImgserver"];
}

function hg_getimg_dir($cond = "", $type = "bs"){
    global $gGlobalConfig;
    if($type == "bs"){
        $bs = $cond ? $cond : hg_getimg_bs();
        $dir = $gGlobalConfig["imgdirs"][$gGlobalConfig["imgurls"][$bs]];
        if($dir){
            return $dir;
        }
    } else {
        if(strpos($cond, "http://") !== false){
            $cond = 'http://'. $cond;
        }
        $dir = $gGlobalConfig["imgdirs"][$cond];
        if($dir){
            return $dir;
        }
    }
    return IMG_DIR;
}

function hg_getimg_host($bs = ""){
    global $gGlobalConfig;
    if(!$bs){
        $bs = hg_getimg_bs();
    }
    $host = $gGlobalConfig["imgurls"][$bs];
    if($host){
        return $host;
    }
    return IMG_URL;
}

function hg_getimg_default_dir(){
    return hg_getimg_dir("img1");
}

function hg_getimg_default_host(){
    return hg_getimg_host("img1");
}

?>
