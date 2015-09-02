<?php
/**
 * Created on 2013-4-7
 * 前端部署用到的基本函数
 */
 
 /**
  * 获取图片url
  * */
 function web_get_pic_url($data,$var_pic_width,$var_pic_height)
 {
 	if ($data)
	{
		$url = $data['host'] . $data['dir'];
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
function web_cutchars($chars, $var_char_limitlen = 12,$var_char_cut_suffix = '...', $doubletoone = false)
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
		if($more)
		{
			$text = mb_substr($text, 0, $limit, 'UTF-8');
		}
		return array($text, $more);
	}
	elseif (function_exists('iconv_substr') && !$doubletoone)
	{
		$more = (iconv_strlen($text, 'UTF-8') > $limit) ? TRUE : FALSE;
		if($more)
		{
			$text = iconv_substr($text, 0, $limit, 'UTF-8');
		}
		return array($text, $more);
	}
	else
	{
		preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $text, $ar);
		$len = 0;
		$more = false;
		$ar = $ar[0];
		if (count($ar) <= $limit)
		{
			return array($text, $more);
		}
		$new_ar = array();
		$temp = '';
		foreach ($ar AS $k => $v)
		{  
			if ($len >= $limit)
			{
				$more = true;
				break;
			}
			$sbit  =  ord($v);         
			if($sbit  <  128)  
			{
				$temp .= $v;
				if (strlen($temp) == 2)
				{
					$new_ar[$len] = $temp;
					$temp = '';
					$len++;
				}
			}
			elseif($sbit  >  223  &&  $sbit  <  240)  
			{   
				$new_ar[$len] = $temp . $v; 
				$temp = '';
				$len++;      
			}
		}
		$text = implode('', $new_ar);
		return array($text, $more);
	}
}

function web_showcontent($data)
{
		if(is_array($data))
		{
			return $data[0];
		}
		return $data;
}
 
?>
