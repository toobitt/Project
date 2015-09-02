<?php

/**
 * Created on 2013-4-7
 * 前端部署用到的基本函数
 */
function web_get_zh_num($num, $d0 = false)
{
    $ch = array(
        0 => '零',
        1 => '一',
        2 => '二',
        3 => '三',
        4 => '四',
        5 => '五',
        6 => '六',
        7 => '七',
        8 => '八',
        9 => '九',
        10 => '十',
        11 => '十一',
        12 => '十二',
    );
    if ($d0)
    {
        $ch[0] = '日';
    }
    if (!$ch[$num])
    {
        $ch[$num] = $num;
    }
    return $ch[$num];
}

/**
 * 获取图片url
 * */
function web_get_pic_url($data, $var_pic_width = '', $var_pic_height = '')
{
    if ($data)
    {
        $url = rtrim($data['host'], '/') . '/' . $data['dir'];
        if ($var_pic_width)
        {
            $url .= $var_pic_width . 'x' . $var_pic_height . '/';
        }
        return $url . $data['filepath'] . $data['filename'];
    }
    else
    {
        return '';
    }
}

/**
 * 按给定的字串长度截取原字符串
 * @param $chars 原字符串
 * @param $limitlen 指定的字串长度
 * @param $cut_suffix 截取后剩余部分替代值
 * @param $doubletoone 英文数字是否2个字符做1长度处理
 * @return 截取后的字符串
 */
function web_cutchars($chars, $var_char_limitlen = 12, $var_char_cut_suffix = '...', $doubletoone = false)
{
    //	global $var_char_limitlen,$var_char_cut_suffix;
    $val = web_csubstr($chars, $var_char_limitlen, $doubletoone);
    return $val[1] ? $val[0] . $var_char_cut_suffix : $val[0];
}

/**
 * 剪切字符
 *
 * @param string $text
 * @param int $limit
 * @return array
 */
function web_csubstr($text, $limit = 12, $doubletoone = false)
{
    if (function_exists('mb_substr') && !$doubletoone)
    {
        $more = (mb_strlen($text, 'UTF-8') > $limit) ? TRUE : FALSE;
        if ($more)
        {
            $text = mb_substr($text, 0, $limit, 'UTF-8');
        }
        return array($text, $more);
    }
    elseif (function_exists('iconv_substr') && !$doubletoone)
    {
        $more = (iconv_strlen($text, 'UTF-8') > $limit) ? TRUE : FALSE;
        if ($more)
        {
            $text = iconv_substr($text, 0, $limit, 'UTF-8');
        }
        return array($text, $more);
    }
    else
    {
        preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $text, $ar);
        $len  = 0;
        $more = false;
        $ar   = $ar[0];
        if (count($ar) <= $limit)
        {
            return array($text, $more);
        }
        $new_ar = array();
        $temp   = '';
        $h = '';
        foreach ($ar AS $k => $v)
        {
            if ($len >= $limit)
            {
                $more = true;
                break;
            }
            $sbit = ord($v);
            if ($sbit < 128)
            {
                $temp .= $v;
                $h .= $v;
                if (strlen($h) == 2)
                {
                    $new_ar[$len] = $temp;
                    $temp         = '';
                    $h            = '';
                    $len++;
                }
            }
            elseif ($sbit > 223 && $sbit < 240)
            {
                $new_ar[$len] = $temp . $v;
                $temp         = '';
                $len++;
            }
        }
        if(strlen($temp)>0)
        {
            $new_ar[] = $temp;
        }
        if (strlen($h) == 1 && $len>=$limit)
        {
            $i = end($new_ar);
            array_pop($new_ar);
            preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $i, $ar2);
            if(count($ar2[0]) > 1)
            {
                $new_ar[] = $ar2[0][0];
            }
        }
        $text = implode('', $new_ar);
        return array($text, $more);
    }
}

function web_showcontent($data)
{
    if (is_array($data))
    {
        return $data[0];
    }
    return $data;
}

/**
 * 时间格式
 * */
function web_date_set($time, $format)
{
    if ($format)
    {
        $time = strtotime($time);
        return date($format, $time);
    }
    else
    {
        return $time;
    }
}

/**
 * 分页函数
 * $base_url 完整的 URL 
 * $total_rows 分页的数据总行数
 * $per_page  每个页面中希望展示的项目数量
 * $num_links 当前页码的前面和后面的“数字”链接的数量
 * $full_tag_open 希望在整个分页周围围绕一些标签 把打开的标签放在所有结果的左侧
 * $full_tag_close 把关闭的标签放在所有结果的右侧
 * $first_link 分页的左边显示“第一页”链接的名字。如果你不希望显示，可以把它的值设为 FALSE
 * $first_tag_open “第一页”链接的打开标签
 * $first_tag_close “第一页”链接的关闭标签
 * $last_link 在分页的右边显示“最后一页”链接的名字。如果你不希望显示，可以把它的值设为 FALSE
 * $last_tag_open “最后一页”链接的打开标签
 * $last_tag_close “最后一页”链接的关闭标签
 * $next_link 分页中显示“下一页”链接的名字。如果你不希望显示，可以把它的值设为 FALSE
 * $next_tag_open “下一页”链接的打开标签
 * $next_tag_close “下一页”链接的关闭标签
 * $prev_link 在分页中显示“上一页”链接的名字。如果你不希望显示，可以把它的值设为 FALSE 
 * $prev_tag_open “上一页”链接的打开标签
 * $prev_tag_close “上一页”链接的关闭标签
 * $cur_tag_open “当前页”链接的打开标签
 * $cur_tag_close “当前页”链接的关闭标签
 * $num_tag_open “数字”链接的打开标签
 * $num_tag_close “数字”链接的关闭标签
 * $display_pages 如果你不想显示“数字”链接（比如只显示 “上一页” 和 “下一页”链接）FALSE
 * $anchor_class 如果你想要给每一个链接添加 CSS 类
 * */
function web_build_page_link($total = '', $url_param = '')
{
    $need_page_info           = $GLOBALS['need_page_info'];
    unset($GLOBALS['need_page_info']);
    $_REQUEST['__page_total'] = $total ? $total : intval($_REQUEST['__page_total']);
    include_once(M2O_ROOT_PATH . 'lib/pagination.php');
    $pageconfig               = include(M2O_ROOT_PATH . 'conf/pagination.php');
    $page_style               = $page_style ? $page_style : 'default';
    $_REQUEST['pp']           = intval($_REQUEST['pp']);
    if (empty($pageconfig[$page_style]))
    {
        return false;
    }
    if ($_REQUEST['__page_count'])
    {
        $con['per_page'] = intval($_REQUEST['__page_count']);
    }
    $con[$pageconfig[$page_style]['query_string_segment']] = $_REQUEST[$pageconfig[$page_style]['query_string_segment']];

    //限制分页数
    if ($_REQUEST['__page_max_page'])
    {
        $max_total                = $con['per_page'] * $_REQUEST['__page_max_page'];
        $_REQUEST['__page_total'] = $_REQUEST['__page_total'] > $max_total ? $max_total : $_REQUEST['__page_total'];
    }

    $con['total_rows'] = $_REQUEST['__page_total'];
    $con['suffix']     = '.' . ltrim($need_page_info['suffix'], '.');
    $con               = $con + $pageconfig[$page_style];
    if ($need_page_info)
    {
        $con['use_page_numbers'] = true;
        $con['cur_page']         = floor($_REQUEST['pp'] / $con['per_page'] + 1);
    }
    if(empty($need_page_info['page_url']))
    {
        $_SERVER['REQUEST_URI']     = preg_replace('/(&|\?)'.$pageconfig[$page_style]['query_string_segment']. '=[^&]*/', '' , $_SERVER['REQUEST_URI']);
        if ($url_param)
        {
            $url_param_arr = explode(',', $url_param);
            foreach ($url_param_arr as $k => $v)
            {
                if ($v)
                {
                    $url_param_str .= $url_tag . $v . '=' . $_REQUEST[$v];
                    $url_tag       = '&';
                    $find_param[] = '/(&|\?)'.$v. '=[^&]*/';
                }
            }
            if ($url_param_str)
            {
                $_SERVER['REQUEST_URI']     = preg_replace($find_param, '' , $_SERVER['REQUEST_URI']);
                if (strstr($_SERVER['REQUEST_URI'], '?') !== false)
                {
                    $_SERVER['REQUEST_URI'] .= '&' . $url_param_str;
                }
                else
                {
                    $_SERVER['REQUEST_URI'] .= '?' . $url_param_str;
                }
            }
        }
        if($need_page_info)
        {
            $need_page_info['base_url'] = 'http://' . $_SERVER ['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        }
        else
        {
            $con['base_url'] = 'http://' . $_SERVER ['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            $con['cur_page'] = $_REQUEST['pp'];
        }
    }
    if ($_REQUEST['pp'] == -1)
    {
        return '';
    }
    else
    {
        $pageobj       = new pagination($con, $need_page_info);
        $m2o_page_link = $pageobj->create_links();
        if (($con['per_page'] + $_REQUEST['pp']) < $con['total_rows'] && $need_page_info['file_mktype'] == 1)
        {
            $_REQUEST['__next_plan'] = 1;
        }
    }
    
    $_REQUEST['__page_cur'] = $con['cur_page'];
    return $m2o_page_link;
}

function web_load_inner_content($content)
{
    if (!$content)
    {
        return '';
    }
    $gGlobalConfig = $GLOBALS['gGlobalConfig'];
    $__info  = $GLOBALS['__info'];
    $content = str_replace(' style="margin:0 auto;display:block;"', '', $content);
    if (!$gGlobalConfig['allow_domain'])
    {
		$weburl  = str_replace('\'', '\\\'', $__info['site']['weburl']);
		$column_id = intval($__info['column']['id']);
		$startnum = intval(stripos($weburl,':'));
		if($startnum>0)
		{
			$p_weburl = substr($weburl,0,$startnum);
		}
		else
		{
			$p_weburl = $weburl;
		}
    }
    else
    {
		$p_weburl = $gGlobalConfig['allow_domain'];
    }
    $content = preg_replace('/<img[\s]+class=\"image-refer\"[\s]+src=\".*?[' . $p_weburl . '].*?' . '\/.*?([a-zA-z]+)\/[a-zA-Z_]+_(\d+)\.png\"\s*\/*>/i', '<script type="text/javascript" src="http://' . $__info['site']['site_info']['url'] . '/m2o/\\1.php?id=\\2&colid='.$column_id.'"></script>', $content);
    
    return $content;
}

//获取区块内容
function web_get_block($block_id,$mateurl='')
{
    $gGlobalConfig = $GLOBALS['gGlobalConfig'];
    if (!$gGlobalConfig['App_block'])
    {
        return array();
    }
    $curl = new curl($gGlobalConfig['App_block']['host'], $gGlobalConfig['App_block']['dir']);
    $curl->setReturnFormat('json');
    $curl->initPostData();
    $curl->addRequestData('block_id', $block_id);
    $curl->addRequestData('a', 'get_block_data_and_line_info');
    $curl->addRequestData('url', $mateurl);
    $ret  = $curl->request('admin/block.php');
    if ($ret[0])
    {
        return $ret[0];
    }
    else
    {
        return array();
    }
}

function web_compare_cell_content($cell_id,$data=array())
{
    if(strstr($cell_id, '_') === 'false')
    {
        return $data;
    }
    if(empty($data))
    {
        return $data;
    }
    if(isset($data['total'])&&isset($data['data']))
    {
        $old_data = $data['data'];
    }
    else
    {
        $old_data = $data;
    }
    $new_data = $old_data;
    $gGlobalConfig = $GLOBALS['gGlobalConfig'];
    if (!$gGlobalConfig['App_publishsys'])
    {
        return array();
    }
    $curl = new curl($gGlobalConfig['App_publishsys']['host'], $gGlobalConfig['App_publishsys']['dir']);
    $curl->setReturnFormat('json');
    $curl->initPostData();
    $curl->addRequestData('cell_id', $cell_id);
    $curl->addRequestData('a', 'get_cell_data');
    $ret  = $curl->request('publishsys.php');
    if ($ret[0] && is_array($ret[0]))
    {
        foreach($ret[0] as $k=>$v)
        {
            $ret_p[$v['content_id']] = $v;
        }
        foreach($old_data as $k=>$v)
        {
            if($ret_p[$v['id']])
            {
                $new_data[$k]['title'] = $ret_p[$v['id']]['title'];
                $new_data[$k]['brief'] = $ret_p[$v['id']]['brief'];
                $new_data[$k]['content_url'] = $ret_p[$v['id']]['content_url'];
                $new_data[$k]['indexpic'] = $ret_p[$v['id']]['indexpic'];
            }
        }
    }
    if(isset($data['total'])&&isset($data['data']))
    {
        $data['data'] = $new_data;
    }
    else
    {
        $data = $new_data;
    }
    return $data;
}



/**
 * 将文章标题生成图片形式,返回图片地址
 * Enter description here ...
 * @param unknown_type $title 标题
 * @param unknown_type $width 图片宽
 * @param unknown_type $height 图片高
 * @param unknown_type $fontsize 字体大小
 * @param unknown_type $fontface 字体格式
 * @param unknown_type $fontcolor 字体颜色
 * @param unknown_type $bgcolor 图片背景颜色
 */
function web_get_title_pic($title = '',$width = '',$height = '',$fontsize = '',$fontface = '',$fontcolor = '',$bgcolor = '')
{
	if(!$title || !$width || !$height || !$fontsize || !$fontface || !$fontcolor || !$bgcolor)
	{
		return false;
	}
	//参数
	$title = trim($title);
	$fontcolor = explode(',',$fontcolor);
	$bgcolor = explode(',',$bgcolor);
	$angle = '0';
	$x = $fontsize;
	$y = $height-10;
	
	
	
	//生成图片
	$image = imagecreatetruecolor($width, $height);
	$bg_color = imagecolorallocate($image,$bgcolor[0],$bgcolor[1],$bgcolor[2]);
	imagefill($image,0,0,$bg_color);
	$color = imagecolorallocate($image, $fontcolor[0], $fontcolor[1], $fontcolor[2]);
	imagettftext($image,$fontsize,$angle,$x,$y,$color,$fontface,$title);
	
	//header("Content-Type:image/jpeg");
	$rand = rand(10000,99999);
	$dir = '../cache/'.$rand.'.jpg';
	imagejpeg($image,$dir);
	imagedestroy($image);
	
	//上传图片到图片服务器
	$file = file_get_contents($dir);
	include_once  '../../../lib/class/material.class.php';
	$material = new material();
	$re = $material->imgdata2pic(base64_encode($file),'jpg');
	
	//删除图片
	if($re)
	{
		unlink($dir);
	}
	
	//返回图片地址
	return $re;
}

function time_cover($msel = "")
{
    if($msel)
    {
        //$msel = $this->input['msel'];
        $msel = $msel;
    }
    $hour = floor($msel/3600000);   //小时
    $minute = floor(($msel-$hour*3600000)/60000);   //分钟
    $sec = floor(($msel-$hour*3600000-$minute*60000)/1000); //秒
    
    if($hour <= 0)
    {
        $hour = "00:";
    }
    else if($hour > 0 && $hour< 10)
    {
        $hour = "0" . $hour . ":";
    }
    else
    {
        $hour .= ":";
    }
    

    if($minute <= 0)
    {
        $minute = "00:";
    }
    else if($minute > 0 && $minute< 10)
    {
        $minute = "0" . $minute . ":";
    }
    else
    {
        $minute .= ":";
    }
    
    if($sec <= 0)
    {
        $sec = "00";
    }
    else if($sec > 0 && $sec< 10)
    {
        $sec = "0" . $sec;
    }
    $time = $hour.$minute.$sec; //格式为00:00:00
    return $time;
}

function get_content_detail($rid)
{
    $curl = new curl($GLOBALS['gGlobalConfig']['App_publishcontent']['host'], $GLOBALS['gGlobalConfig']['App_publishcontent']['dir']);
    $curl->setReturnFormat('json');
    $curl->initPostData();
    $curl->addRequestData('id', $rid);
    $curl->addRequestData('a', 'get_content_by_rid');
    $curl->addRequestData('not_need_content', 1);
    $ret  = $curl->request('content.php');
    $ret[0] = is_array($ret[0])?$ret[0]:array();
    return $ret[0];
}


?>
