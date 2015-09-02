<?php

//2013.06.19 scala
/*
 * @function debug export var
 * @param:$fname
 * @param:$var 
 * @param:$line which line calls the function
 * @param:$file which file calls the function
 * 
 */

function export_var($fname, $var, $line, $file, $flag = false)
{
    if (DEBUG_OPEN)
    {
        $path       = realpath($fname);
        $path_parts = pathinfo($path);
        $content    = $line . "\n" . $file . "\n" . var_export($var, 1) . "\n";

        if (@!file_put_contents($fname, $content) || !$flag)
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
    $phpself = explode("/", $_SERVER['PHP_SELF']);
    return substr($phpself[count($phpself) - 1], 0, -4);
}

function file_in($dir, $filename, $strings, $type = false, $cover = false)
{
    $path = rtrim($dir, '/');
    if (!is_dir($path))
    {
        @mkdir($path, CREATE_DIR_MODE, true);
    }
    if (!$cover)
    {
        if (file_exists($path . '/' . $filename))
        {
            return false;
        }
    }
    if ($type)
    {
        $r = @file_put_contents($path . '/' . $filename, $strings);
    }
    else
    {
        $r = @file_put_contents($path . '/' . $filename, $strings, FILE_APPEND);
    }
    if ($del_file)
    {
        @unlink($path . '/' . $del_file);
    }
    if ($r !== 'false')
    {
        return true;
    }
    else
    {
        return false;
    }
}

//多维数组合并 $array2覆盖$array1
function multi_array_merge($array1, $array2)
{
    if (is_array($array2) && count($array2))
    {
        //不是空数组的话  
        foreach ($array2 as $k => $v)
        {
            if (is_array($v) && count($v))
            {
                $array1[$k] = multi_array_merge($array1[$k], $v);
            }
            else
            {
                if (!empty($v))
                {
                    $array1[$k] = $v;
                }
            }
        }
    }
    else
    {
        $array1 = $array2;
    }
    return $array1;
}

//改变数组键值
function array_change_key($arr, $str = 'a', &$new_arr)
{
    foreach ($arr as $k => $v)
    {
        if (is_array($v))
        {
            array_change_key($v, $str, $new_arr[$str . $k]);
        }
        else
        {
            $new_arr[$str . $k] = $v;
        }
    }
}

/**
 * 解析单元信息
 *
 * @param string $content
 * @return array
 */
function parse_templatecell($content = "")
{
    $eregtag = '/<span[\s]+(?:id|class)="livcms_cell".+?>liv_([\\s\\S]+?(?=<\/span>))<\/span>/is';
    //$eregtag = '/<span[\s]+id="livcms_cell".+?[\s]+name="(.+?)">([\\s\\S]+?(?=<\/span>))<\/span>/is';
    preg_match_all($eregtag, $content, $match);
    return $match;
}

/**
 * 原目录，复制到的目录
 * */
function file_copy($from, $to, $filenamearr = array(),$exclude_suffix=array())
{
    $dir = opendir($from);
    if (!is_dir($to))
    {
        @mkdir($to, CREATE_DIR_MODE, true);
    }
    while (false !== ( $file = readdir($dir)))
    {
        if ($filenamearr)
        {
            if (in_array($file, $filenamearr))
            {
                continue;
            }
        }
        if($exclude_suffix)
        {
            $suffix = @pathinfo($file, PATHINFO_EXTENSION);
            if(in_array($suffix, $exclude_suffix))
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
                copy($from . '/' . $file, $to . '/' . $file);
            }
        }
    }
    closedir($dir);
}

/**
 * 计算路径相对位置
 * $path_a长 $path_b短
 * */
function compara_path($path_a, $path_b)
{
    $array_a = explode('/', $path_a);
    $array_b = explode('/', $path_b);
    $file_a  = array_pop($array_a);
    $file_b  = array_pop($array_b);
    $a_len   = count($array_a);
    $b_len   = count($array_b);
    for ($i = 0; $i < $a_len; $i++)
    {
        if ($array_a[$i] != $array_b[$i])
        {
            break;
        }
    }
    $com_path = "";
    for ($j = 0; $j < $a_len - $i; $j++)
    {
        $com_path .='../';
    }
    for ($i; $i < $b_len; $i++)
    {
        $com_path .=$array_b[$i] . '/';
    }
    $com_path .=$file_b;
    return $com_path;
}

/**
 * 取出该目录下文件
 * */
function get_files($dir)
{
    $files    = array();
    $handler  = opendir($dir);
    while (($filename = readdir($handler)) !== false)
    {
        if ($filename != "." && $filename != "..")
        {
            $files[] = $filename;
        }
    }
    closedir($handler);
    return $files;
}

/**
 * 取出该目录下所有文件包含子目录
 * */
function get_all_files($path, &$files)
{

    function get_allfiles($path, &$files)
    {
        if (is_dir($path))
        {
            $dp   = dir($path);
            while ($file = $dp->read())
            {
                if ($file != "." && $file != "..")
                {
                    get_allfiles($path . "/" . $file, $files);
                }
            }
            $dp->close();
        }
        if (is_file($path))
        {
            $files[] = $path;
        }
    }

}

function php_check_syntax($file_name, &$error_message = null)
{
    $file_content = file_get_contents($file_name);

    $check_code   = "return true; ?>";
    $file_content = $check_code . $file_content . "<?php ";

    if (!@eval($file_content))
    {
        $error_message = "<p>file: " . realpath($file_name) . " have syntax error</p>";
        return false;
    }
    return true;
}

/**
function deleteDir($path)
{
    if (is_dir($path))
    {
        $file_list = scandir($path);
        foreach ($file_list as $file)
        {
            if ($file != '.' && $file != '..')
            {
                deleteDir($path . '/' . $file);
            }
        }
        @rmdir($path);
    }
    else
    {
        @unlink($path);
    }
}
 * 
 */

function deleteDir($dirName)
{
    if(!file_exists($dirName))
    {
        return false;
    }
    if ($handle = opendir("$dirName"))
    {
        while (false !== ( $item = readdir($handle) ))
        {
            if ($item != "." && $item != "..")
            {
                if (is_dir("$dirName/$item"))
                {
                    deleteDir("$dirName/$item");
                }
                else
                {
                    unlink("$dirName/$item");
                }
            }
        }
        closedir($handle);
        rmdir($dirName);
    }
}

function get_site_temdir($site)
{
    $md = '';
    if ($site['tem_material_dir'])
    {
        if (strstr($site['tem_material_dir'], "/") === 0)
        {
            $md = rtrim($site['tem_material_dir'], '/') . '/';
        }
        else
        {
            $md = rtrim($site['site_dir'], '/') . '/' . rtrim($site['tem_material_dir'], '/') . '/';
        }
    }
    else
    {
        $md = rtrim($site['site_dir'], '/') . '/';
    }
    return $md;
}

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float) $usec + (float) $sec);
}

?>