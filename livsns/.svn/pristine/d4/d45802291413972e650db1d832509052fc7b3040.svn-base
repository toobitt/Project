<?php
function hg_check_email_format($email)
{
	return strlen($email) > 6 && strlen($email) <= 256 && preg_match("/^([A-Za-z0-9\-_.+]+)@([A-Za-z0-9\-]+[.][A-Za-z0-9\-.]+)$/", $email);
}

function hg_verify_mobile_fb($Argv)
{
	$regxArr = array(  
	  'sj'  =>  '/^(\+?86-?)?(18|15|13|17)[0-9]{9}$/',  
	  'tel' =>  '/^(010|02\d{1}|0[3-9]\d{2})-?\d{7,9}(-\d+)?$/',  
	  '400' =>  '/^(400|800)(-?\d{3,4}){2}$/',  
	  );  
	  foreach($regxArr as $regx)  
	  {  
	    if(preg_match($regx, $Argv ))  
	    {  
	      return true;  
	    }  
	  }  
	  return false;  
}

function deldir($dir) {
	  //先删除目录下的文件：
	$dh=opendir($dir);
  	while ($file=readdir($dh))
  	{
   		if($file!="." && $file!="..")
    	{
      		$fullpath=$dir."/".$file;
      		if(!is_dir($fullpath))
      		{
          		unlink($fullpath);
      		}
      		else
      		{
        		deldir($fullpath);
      		}
    	}
  	}
  	closedir($dh);
  	//删除当前文件夹：
  	if(rmdir($dir))
  	{
  		return true;
  	}
  	else
  	{
    	return false;
  	}
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
function create_filename ($data,$pwd = '',$len = 13)//$pwd密钥　$data需加密字符串
{
	return substr(base64_encode(md5($data)),0,$len);
}
?>