<?php

function mk_token()
{
	return md5(uniqid());
}

function check_token_time($token_addtime,$expires_in)
{
	$maxtime = $token_addtime+$expires_in;
	if($maxtime < TIMENOW)
	{
		return false;
	}
	else
	{
		return true;
	}
}

function file_in($dir,$filename,$strings,$type=false)
{  
		$path = trim($dir,'/');
	    if(!is_dir($path))
	    {
		    mkdir($path, 0777, true);
	    }
	    if(file_exists($path.'/'.$filename))
	    {
	    	return false;
	    }
        if (!$type)
            file_put_contents($path.'/'.$filename, $strings, FILE_APPEND);
        else
            file_put_contents($path.'/'.$filename, $strings);
        return true;
}

function create_image_dir($url,$filepath)
{
	$support_ext = array('.gif','.jpg','.png');
	if(empty($url))
	{
		return false;
	}
	$ext=strrchr($url,".");
	if(!in_array($ext,$support_ext)) 
	{
		return false;
	}
	$md5str = md5($url);
	return array('filepath'=>$filepath.'/'.substr($md5str,0,2).'/','filename'=>$md5str.$ext);
}

function upload_image($url,$file='')
{
	$img = file_get_contents($url);
		$path = trim($file['filepath'],'/');
	    if(!is_dir($path))
	    {
		    mkdir($path, 0777, true);
	    }
	file_put_contents($file['filepath'] . '/' . $file['filename'],$img);
	return   $file['filepath'].$file['filename'];
}

function url_format($url)
{
	$ret = stripos($url,'?');
	if(!$ret)
	{
		return $url;
	}
	return substr($url,0,$ret);
}


?>