<?php
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