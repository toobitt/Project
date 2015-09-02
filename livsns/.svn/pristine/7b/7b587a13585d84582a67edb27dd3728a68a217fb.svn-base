<?php

function xml_filter($str)
{
    $str = preg_replace('/[\\x00-\\x08\\x0b-\\x0c\\x0e-\\x1f]/', '', $str);
    return $str;
}

function mk_site_url($site)
{
    $url = rtrim($site['sub_weburl'], '.');
    $url .= '.' . rtrim($site['weburl'], '/');
    if ($site['sub_wdir'])
    {
        $url .= '/' . trim($site['sub_wdir'], '/');
    }
    return $url;
}

function mk_column_url($row, $take_suffix = true,$need_filename = false)
{
    $result = '';
    if($row['linkurl'] && $row['is_outlink'])
    {
        return $row['linkurl'];
    }
    if ($row['father_domain'])
    {
        $result .= $row['father_domain'];
    }
    else
    {
        $result .= $row['sub_weburl'];
    }
    $result .= '.' . $row['weburl'];
    if ($row['relate_dir'])
    {
        $result .= '/' . trim($row['relate_dir'], '/');
    }
    $row['colindex'] = trim($row['colindex'], '.');
    $suffix = $row['maketype']==1?'.html':'.php';
    if($need_filename)
    {
        $result .= '/' . $row['colindex'] . $suffix;
    }
    else if ($take_suffix)
    {
        if ($row['colindex'] != 'index' && $row['colindex'])
        {
            $result .= '/' . $row['colindex'] . $suffix;
        }
    }

    return 'http://' . $result;
}

function get_tablename($bundle_id, $module_id, $struct_id, $struct_ast_id = '')
{
    return strtolower($bundle_id . '_' . $module_id . '_' . $struct_id . (empty($struct_ast_id) ? '' : ('_' . $struct_ast_id)));
}

function file_in($dir, $filename, $strings, $type = false)
{
    $path = trim($dir, '/');
    if (!is_dir($path))
    {
        mkdir($path, 0777, true);
    }
    if (file_exists($path . '/' . $filename))
    {
        return false;
    }
    if (!$type)
        file_put_contents($path . '/' . $filename, $strings, FILE_APPEND);
    else
        file_put_contents($path . '/' . $filename, $strings);
    return true;
}

function str_insert($str, $i, $substr)
{
    $startstr = $laststr  = '';
    for ($j = 0; $j < $i; $j++)
    {
        $startstr .= $str[$j];
    }

    for ($j = $i; $j < strlen($str); $j++)
    {
        $laststr .= $str[$j];
    }
    $str = ($startstr . $substr . $laststr);
    return $str;
}

function str_utf8_unicode($str)
{
    $newstr  = '';
    @mb_internal_encoding("UTF-8"); //this IS A MUST!! PHP has trouble with multibyte when no internal encoding is set!
    $dictLen = intval(@mb_strlen($str));
    for ($i = 0; $i < $dictLen; $i++)
    {
        $tcChar = mb_substr($str, $i, 1);
        if ($tcChar == ',' || $tcChar == '，')
        {
            $newstr .= ' ';
        }
        else
        {
            $newstr .= hg_utf8_unicode($tcChar);
        }
    }
    return $newstr;
}

function object_to_array($obj)
{
    $_arr = is_object($obj) ? get_object_vars($obj) : $obj;
    foreach ($_arr as $key => $val)
    {
        $val       = (is_array($val) || is_object($val)) ? object_to_array($val) : $val;
        $arr[$key] = $val;
    }
    return $arr;
}

function to_htmlspecialchars_decode($data)
{
    if (is_array($data))
    {
        foreach ($data as $k => $v)
        {
            $data[$k] = to_htmlspecialchars_decode($v);
        }
    }
    else
    {
        $data = htmlspecialchars_decode($data);
    }
    return $data;
}

function make_content_dir($content_id, $create_time, $folderformat, $fileformat, $custom_filename='')
{
    if ($custom_filename)
    {
        $contentname = trim($custom_filename,'/.');
    }
    else
    {
        $dir = date($folderformat ? $folderformat : 'Y-m-d', $create_time);
        switch ($fileformat)
        {
            case 2:
                $contentname = date('Y-m-d', $create_time) . '-' . $content_id;
                break;

            case 3:
                $contentname = date('Y_m_d', $create_time) . '_' . $content_id;
                break;

            case 4:
                $contentname = date('Ymd', $create_time) . $content_id;
                break;

            case 5:
                $contentname = md5($content_id);
                break;
                
            case 6:
                $contentname = date('Y_m_d', $create_time) . $content_id;
                break;
                
            case 7:
                $contentname = date('Ymd', $create_time) . '_' . $content_id;
                break;
                
            case 8:
                $contentname = date('Y-m-d', $create_time) . $content_id;
                break;
                

            default:
                $contentname = substr($fileformat, 2) . $content_id;
                break;
        }
    }
    
    return ltrim($dir . '/' . $contentname, '/');
}

function mk_content_url($site_datas, $column_datas, $v)
{
	if (defined('TEMP_URL') && TEMP_URL)
	{
		$link = sprintf(TEMP_URL, $v['id']);
		return $link;
	}
    $result = '';
    if ($site_datas['custom_content_dir'])
    {
        $custom_content_dir = $site_datas['custom_content_dir'];
    }
    else
    {
        $custom_content_dir = $column_datas['custom_content_dir'];
    }
    if($v['file_domain'])
    {
        $result .= $v['file_domain'];
    }
    else if ($column_datas['father_domain'] && $v['file_name'])
    {
        $result .= $column_datas['father_domain'];
    }
    else
    {
        $result .= $site_datas['sub_weburl'] ? $site_datas['sub_weburl'] : '';
    }
    $result = rtrim($result, '.') . '.' . $site_datas['weburl'];
    if ($v['file_name'])
    {
        if (intval($column_datas['col_con_maketype']) == 2 && $v['bundle_id']!='special')
        {
            //动态
            if ($custom_content_dir)
            {
                $result .= '/' . trim($custom_content_dir, '/');
            }
            else if ($column_datas['relate_dir'])
            {
                $result .= '/' . trim($column_datas['relate_dir'], '/');
            }
            $result .= '/' . $v['bundle_id'] . '.php?rid=' . $v['id'];
        }
        else
        {
            //静态
            if($v['file_domain'])
            {
                //if($v['file_custom_filename'])
                //{
                //    $result .= '/' . $v['file_custom_filename'];
                //}
                //else
                {
                    $v['file_name'] = substr($v['file_name'],strrpos($v['file_name'],'/')+1);
                    $result .= '/' . ltrim($v['file_name'], '/');
                }
            }
            else
            {
                if ($custom_content_dir)
                {
                    $result .= '/' . trim($custom_content_dir, '/');
                }
                else if ($column_datas['relate_dir'])
                {
                    $result .= '/' . trim($column_datas['relate_dir'], '/');
                }
                /**
                if($v['file_custom_filename'])
                {
                    $findsuf = strrpos($v['file_name'],'/');
                    if($findsuf!==false)
                    {
                        $v['file_name'] = substr($v['file_name'],0,strrpos($v['file_name'],'/')+1).$v['file_custom_filename'];
                    }
                    else
                    {
                        $v['file_name'] = $v['file_custom_filename'];
                    }
                }echo $v['file_name'];exit;
                 * 
                 */
                $result .= '/' . ltrim($v['file_name'], '/');
            }
        }
    }
    else
    {
        $result .= '/' . $v['struct_id'] . '/' . $v['id'] . '.html';
    }
    return 'http://' . $result;
}

function arrpreg($a, $b)
{
    $arr = array();
    if (is_array($a))
    {
        foreach ($a as $k => $r)
        {
            if (is_array($r))
            {
                foreach ($r as $k1 => $r1)
                {
                    $arr[$k][$k1] = $r1;
                }
            }
        }
    }
    if (is_array($b))
    {
        foreach ($b as $k => $r)
        {
            if (is_array($r))
            {
                foreach ($r as $k1 => $r1)
                {
                    $arr[$k][] = $r1;
                }
            }
        }
    }
    return $arr;
}

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

function html_decode($str)
{
    $pregreplace = array(' ', '<!--', '-->', '>', '<', '"', '!', "'", "\n", '$', "\r");
    $pregfind = array('&#032;', '&#60;&#33;--', '--&#62;', '&gt;', '&lt;', '&quot;', '&#33;', '&#39;', "\n", '&#036;', '');
    $str = str_replace($pregfind, $pregreplace, $str);
    return $str;
}
if (!function_exists('hg_filter_ids'))
{
	function hg_filter_ids($id, $split = ',')
	{
		$pattern = '/^[\d]+(\\'.$split.'\d+){0,}$/';
		if(!preg_match($pattern, $id))
		{
			return -1;
		}
		return $id;
	}
}
function get_spell_title($title)
{
    include(CUR_CONF_PATH . 'lib/pinyin.class.php');
    $title_pinyin_result = hanzi_to_pinyin($title, false, 0);
    $title_pinyin_str    = implode('', $title_pinyin_result['first_word']);
    //$title_pinyin_str .= ' ' . implode('', $title_pinyin_result['word']);
    return $title_pinyin_str;
}

?>