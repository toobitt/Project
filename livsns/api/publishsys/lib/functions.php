<?php

function get_tablename($bundle_id, $module_id, $struct_id, $struct_ast_id = '')
{
    return strtolower($bundle_id . '_' . $module_id . '_' . $struct_id . (empty($struct_ast_id) ? '' : ('_' . $struct_ast_id)));
}

function file_in($dir, $filename, $strings, $type = false, $cover = false, $del_file = '')
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
        file_put_contents($path . '/' . $filename, $strings);
    }
    else
    {
        file_put_contents($path . '/' . $filename, $strings, FILE_APPEND);
    }
    if ($del_file)
    {
        @unlink($path . '/' . $del_file);
    }
    return true;
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
function file_copy($from, $to, $filenamearr = array())
{
    $status = true;
    $dir = opendir($from);
    if (!is_dir($to))
    {
        @mkdir($to, CREATE_DIR_MODE, true);
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

/**
 * 数据源参数处理
 * */
function datasource_param($param)
{
    $result = array();
    foreach ($param['ident'] as $k => $v)
    {
        $result[$v] = $param['value'][$k];
    }
    return $result;
}

/**
 * 模板标题关键字等的插入
 * */
function template_process($template, $site, $column, $is_content = false)
{
    $title       = '';
    if($column['shortname'])
    {
        $title       = $column['shortname'];
    }
    else
    {
        $title       = $column['name'];
        $title .= ($title ? '_' : '') . $site['site_name'];
    }
    
    $keywords    = empty($column['keywords']) ? $site['site_keywords'] : $column['keywords'];
    $description = empty($column['content']) ? $site['content'] : $column['content'];
    preg_match("/<title>(.*?)<\/title>/s", $template, $matches1);
    if ($is_content)
    {
        
        $title       = '<?php echo $__info[\'content\'][\'title\']?($__info[\'content\'][\'title\'].\'_'.$title.'\'):\'' . $title . '\'; ?>';
        $keywords    = '<?php echo $__info[\'content\'][\'keywords\']?$__info[\'content\'][\'keywords\']:\'' . $keywords . '\'; ?>';
        $description = '<?php echo $__info[\'content\'][\'brief\']?strip_tags($__info[\'content\'][\'brief\']):\'' . strip_tags($description) . '\'; ?>';
        /**
        $title       = '__content_detail.title';
        $keywords    = '__content_detail.keywords';
        $description = '__content_detail.description';
        * */
    }

    preg_match("/<meta([^>]*?)name=([\'\"]?)keywords([\'\"]?[^>]*?)content=([\'\"]?[^>].*?[\'\"]?[^>]*?)\/*>/is", $template, $matches2);
    preg_match("/<meta([^>]*?)name=([\'\"]?)description([\'\"]?[^>]*?)content=([\'\"]?[^>].*?[\'\"]?[^>]*?)\/*>/is", $template, $matches3);
    if ($matches1)
    {
        $f_arr[] = '/<title>(.*?)<\/title>/s';
        $r_arr[] = '<title>' . $title . '</title>';
    }
    else
    {
        $f_arr[] = '/<head>(.*?)<\/head>/s';
        $r_arr[] = '<head>\\1<title>' . $title . '</title></head>';
    }
    if ($matches2)
    {
        $f_arr[] = '/<meta([^>]*?)name=([\'\"]?)keywords([\'\"]?[^>]*?)content=([\'\"]?[^>].*?[\'\"]?[^>]*?)\/*>/is';
        $r_arr[] = '<meta\\1name=\\2keywords\\3content="' . $keywords . '" />';
    }
    else
    {
        $f_arr[] = '/<head>(.*)<\/head>/s';
        $r_arr[] = '<head>\\1<meta name="keywords" content="' . $keywords . '"></head>';
    }
    if ($matches3)
    {
        $f_arr[] = '/<meta([^>]*?)name=([\'\"]?)description([\'\"]?[^>]*?)content=([\'\"]?[^>].*?[\'\"]?[^>]*?)\/*>/is';
        $r_arr[] = '<meta\\1name=\\2description\\3content="' . $description . '">';
    }
    else
    {
        $f_arr[] = '/<head>(.*)<\/head>/s';
        $r_arr[] = '<head>\\1<meta name="description" content="' . $description . '"></head>';
    }
    $template = preg_replace($f_arr, $r_arr, $template);
    return $template;
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

function mk_site_url($site)
{
    $url = rtrim($site['sub_weburl'], '.');
    $url .= '.' . rtrim($site['domain'], '/');
    if ($site['site_dir'])
    {
        $url .= '/' . trim($site['site_dir'], '/');
    }
    return $site['sub_weburl'] . '.' . $site['domain'] . $site['site_dir'];
}

function debug_file($filename, $data, $is_array = true)
{
    if (!MK_DEBUG)
    {
        return false;
    }
    if ($is_array)
    {
        file_in('../cache/log/', $filename . '.txt', var_export($data, 1) . "\n", true, true);
    }
    else
    {
        file_in('../cache/log/', $filename . '.txt', $data . "\n", true, true);
    }
}

//数据源排序
function ds_maopao($arr, $ds_sign_id)
{
    $len = count($arr);
    for ($i = 1; $i < $len; $i++)//最多做n-1趟排序
    {
        $flag = false;    //本趟排序开始前，交换标志应为假
        for ($j = $len - 1; $j >= $i; $j--)
        {
            if (!$arr[$j]['value'] && $arr[$j - 1]['value'])
            {
                $x         = $arr[$j];
                $arr[$j]   = $arr[$j - 1];
                $arr[$j - 1] = $x;
            }
            if ($arr[$j]['value'] && $arr[$j - 1]['value'])
            {
                preg_match_all('/^([a-zA-Z0-9_-]+)\./', $arr[$j - 1]['value'], $mat1);
                preg_match_all('/,([a-zA-Z0-9_-]+)\./', $arr[$j - 1]['value'], $mat11);
                $mat1[1]  = is_array($mat1[1]) ? $mat1[1] : array();
                $mat11[1] = is_array($mat11[1]) ? $mat11[1] : array();
                $c1       = array_merge($mat1[1], $mat11[1]);
                foreach ($c1 as $kk => $vv)
                {
                    if (in_array($ds_sign_id[$arr[$j]['id']], $c1))
                    {
                        $x         = $arr[$j];
                        $arr[$j]   = $arr[$j - 1];
                        $arr[$j - 1] = $x;
                        break;
                    }
                }
            }
        }
    }
    return $arr;
}

//css合并，删除相同css
function comp_css($cssarr)
{
    if (!$cssarr)
    {
        return array();
    }
    foreach ($cssarr as $k => $v)
    {
        $newarr[md5($v)] = $v;
    }
    return $newarr;
}

/**
 *  将$arr = array(
 * 			0 => array('name' => '变量a','sign' => 'a','default' => '100'),
 * 			1 => array('name' => '变量b','sign' => 'b','default' => '200',),		
 * 	)形式的数组转变为
 * 	$arr = array(
 * 		'name' => array('变量a','变量b'),
 * 		'sign' => array('a','b'),
 * 		'default'=> array('100','200'),
 * 	)形式的数组
 * 
 * @author leo
 * @copyright hoge
 * @param array $arr 原始数组
 * @param array $key 需要提取的键值
 * @param string $prefix 格式化键值的前缀
 * @return array $ret 格式化后的数组
 */
function hg_format_array($arr, $key, $prefix = '')
{
    if (!$arr || !$key)
    {
        return array();
    }
    $ret = array();
    if (is_array($arr) && count($arr) > 0)
    {
        foreach ($arr as $k => $v)
        {
            foreach ($key as $kk => $vv)
            {
                $ret[$prefix . $vv][] = $v[$vv];
            }
        }
    }
    return $ret;
}

/**
 *  将$arr = array(
 * 		'name' => array('变量a','变量b'),
 * 		'sign' => array('a','b'),
 * 		'default'=> array('100','200'),
 * 	)形式的数组转变为
 * 	$arr = array(
 * 			0 => array('name' => '变量a','sign' => 'a','default' => '100'),
 * 			1 => array('name' => '变量b','sign' => 'b','default' => '200',),		
 * 	)形式的数组
 * 
 * @author leo
 * @copyright hoge
 * @param array $arr 原始数组
 * @param array $key 需要提取的键值
 * @param string $prefix 格式化键值的前缀
 * @return array $ret 格式化后的数组
 */
function hg_format_array_reverse($arr, $key, $prefix = '')
{
    if (!$arr || !$key)
    {
        return array();
    }
    $ret = array();
    if (is_array($arr[$key[0]]) && count($arr[$key[0]]) > 0)
    {
        foreach ($arr[$key[0]] as $k => $v)
        {
            foreach ($key as $kk => $vv)
            {
                $ret[$k][$vv] = $arr[$vv][$k];
            }
        }
    }
    return $ret;
}

/**
 * 将形如  Array('name' => aaa, 'value' => 100,'default_value' => 200)的数组 转换为
 * 'name=>aaa value=>100 default_value=>200'的字符串
 * 
 * @author leo
 * @copyright hoge
 * @param array $arr 需要转换的字符串
 * @param string $link  键值对之间的连接符
 * @param string $separate 值之间的间隔符
 * 
 * @return string $arr 格式化后的数组
 */
function hg_array_to_string($arr, $link = '=>', $separate = '#&33')
{
    if (!$arr)
    {
        return '';
    }
    $tmp = array();
    if (is_array($arr) && count($arr) > 0)
    {
        foreach ($arr as $key => $value)
        {
            $tmp[] = $key . $link . $value;
        }
        $tmp = implode($separate, $tmp);
    }
    return $tmp;
}

/**
 * 将形如  'name=>aaa value=>100 default_value=>200'的字符串转换为
 * Array('name' => aaa, 'value' => 100,'default_value' => 200) 的数组
 * 
 * @author leo
 * @copyright hoge
 * @param string $string 需要转换的字符串
 * @param string $link  键值对之间的连接符
 * @param string $separate 值之间的间隔符
 * 
 * @return array $ret 格式化后的数组
 */
function hg_string_to_array($string, $link = '=>', $separate = '#&33')
{
    if (!$string)
    {
        return array();
    }
    $string = html_entity_decode($string);
    $tmp    = array();
    $string = explode($separate, $string);
    if (is_array($string) && count($string) > 0)
    {
        foreach ($string as $key => $value)
        {
            $value          = explode($link, $value);
            $tmp[$value[0]] = $value[1];
        }
    }
    return $tmp;
}

function css_js_replace($str, $cell_idarr,$cell_suff)
{
    $a    = '';
    $flag = false;
    foreach (explode(',', $cell_idarr) as $v)
    {
        $a .= ($flag ? ',' : '') . $cell_suff . $v . '_' . $str;
        $flag = true;
    }
    return $a;
}

function array_export($arr)
{
    $pa = '/\$_POST\[|\$_GET\[|\$_REQUEST\[|\$_SESSTION|\$_COOKIE\[|\$__info|\$__cell_data/i';
    $h  = 'array(';
    if (!is_array($arr))
    {
        return $arr;
    }
    foreach ($arr as $k => $v)
    {
        $h .= '\'' . $k . '\'' . '=>';
        if (is_numeric($v))
        {
            $h .= $v;
        }
        else if (preg_match_all($pa, $v, $mat))
        {
            $h .= stripslashes($v);
        }
        else if (!$v)
        {
            $h .= '\'\'';
        }
        else
        {
            $h .= '\'' . addslashes($v) . '\'';
        }
        $h .= ',' . "\n";
    }
    $h .= ')';
    return $h;
}

function get_dir_files($dir)
{
    if (!is_dir($dir))
    {
        return false;
    }
    $hd   = opendir($dir);
    while (false !== ($file = readdir($hd)))
    {
        if ($file != '.' && $file != '..')
        {
            $path = $dir . '/' . $file;
            if (is_dir($path))
            {
                get_dir_files($path);
            }
            else
            {
                $arr   = array(
                    'real_url' => ICON_URL . $file,
                    'url' => '<MATEURL>' . $file,
                );
                $ret[] = $arr;
            }
        }
    }
    return $ret;
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

function get_site_temurl($site)
{
    if ($site['tem_material_url'])
    {
        $tmu = rtrim($site['tem_material_url'], '.') . '.' . $site['weburl'];
    }
    else
    {
        $tem_material_dir = trim($site['tem_material_dir'], '/');
        $tmu              = $site['site_info']['url'];
        if ($tem_material_dir)
        {
            $tmu .= '/' . $tem_material_dir;
        }
    }
    return 'http://' . $tmu;
}

function str_process($out_paramarr)
{
    $pregreplace  = array('&#032;', '<!--', '-->', '>', '<', '"', '!', "'", "\n", '$', "\r");
    $pregfind     = array(' ', '&#60;&#33;--', '--&#62;', '&gt;', '&lt;', '&quot;', '&#33;', '&#39;', "\n", '&#036;', '');
    $out_paramarr = str_replace($pregfind, $pregreplace, $out_paramarr);
    return $out_paramarr;
}

function get_pages_url($url, $num)
{
    $a = array();
    if (!$url)
    {
        return false;
    }
    /**
    $urlarr = explode('/', $url);
    if (!$urlarr)
    {
        return false;
    }
    $nurlarr           = array_reverse($urlarr);
    $filename          = $nurlarr[0];
    $filearr           = explode('.', $filename);
    $a['org_filename'] = $filename;
    $a['file']         = $filearr[0];
    $a['suffix']       = $filearr[1];
    $a['filedir']      = rtrim(str_replace($a['file'] . '.' . $a['suffix'], $a['file'] . '_' . $num . '.' . $a['suffix'], $url), '/');
    */
    $filearr = pathinfo($url);
    if(!is_array($filearr)||!$filearr)
    {
	    return false;
    }
    $a['org_filename'] = $filearr['basename'];
    $a['file']         = $filearr['filename'];
    $a['suffix']       = $filearr['extension'];
    $a['filedir']      = rtrim(str_replace($filearr['basename'], $filearr['filename'] . '_' . $num . '.' . $filearr['extension'], $url), '/');
    return $a;
}

function pub_addslashes($html)
{
    return str_replace('\'', '\\\'', $html);
}

function deleteDir($dirName)
{
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

?>