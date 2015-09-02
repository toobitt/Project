<?php
/**
* 二元分词，且转换为unicode编码
* $arr 需要进行分词数组,arr['title'] 为要分词的字符串 arr['keywords'] 为 已分好词的字符串 当$arr为字符串时，则为要分词的字符串
* 返回经过转换后的分词词组
*/
function duality_word($arr,$return = 0)
{
	if(is_array($arr))
	{
		$str = $arr['title'];
		$keyword = $arr['keywords'];
	}
	else
	{
		$str = $arr;
	}
	$new_ar = array();

	if(function_exists("phpcws_split"))
	{
		$str = iconv("UTF-8", "GBK//IGNORE", $str);
		$result = phpcws_split($str,"default");
		$result = iconv("GBK", "UTF-8//IGNORE", $result);
		$new_ar = explode(" ",$result);
	}
	elseif($str)
	{

		$search = array(",", "/", "\\", ".", ";", ":", "\"", "!", "~", "`", "^", "(", ")", "?", "-", "\t", "\n", "'", "<", ">", "\r", "\r\n", "$", "&", "%", "#", "@", "+", "=", "{", "}", "[", "]", "：", "）", "（", "．", "。", "，", "！", "；", "“", "”", "‘", "’", "［", "］", "、", "—", "　", "《", "》", "－", "…", "【", "】","|"," ");
		//替换所有的分割符为空格
		$str = str_replace($search,' ',$str);
		//用正则匹配半角单个字符或者全角单个字符,存入数组$ar
//				preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/",$str,$ar);
//				$ar = $ar[0];

		$ar = utf8_str2a($str);
		//把连续的半角存成一个数组下标,或者全角的每2个字符存成一个数组的下标
		$i = -1;
		$can_add = true;

		foreach ($ar AS $k => $v)
		{
			$sbit  =  ord($v);
			if($sbit  <  128)
			{
				if (trim($v) || $v == 0)
				{
					if($can_add)
					{
						$i++;
						$can_add = false;
					}
					$new_ar[$i] .= $v;
				}
			}
			elseif($sbit  >  223  &&  $sbit  <  240)
			{

				$can_add = true;
				if (ord($ar[($k + 1)]) < 128)
				{
					//do nothing
				}
				else
				{
					$i++;
					$new_ar[$i] = $ar[$k] . $ar[($k + 1)];
				}
			}
		}
	}

	if($keyword)
	{
		$keyword = explode(" ",$keyword);
		$new_ar = array_merge($new_ar , $keyword);
	}

	if(!$return)
	{
		 $new_ar = duality_word_encode($new_ar);
	}

	return filter_arr_null_ele($new_ar);
}
/*
字符串转换成数组
*/
function utf8_str2a($str)
{

	  $len = strlen($str);
	  $arr = array();
	  for($i = 0;$i<$len;$i++)
	  {
	  	  if(ord($str[$i]) < 127)
	  	  {
	  	  	 $arr[] = $str[$i];
	  	  }
	  	  else
	  	  {

	  	  	$arr[] = $str[$i].$str[$i+1].$str[$i+2];
	  	  	$i +=2;
	  	  }
	  }
	  return $arr;
}
/*
编码字符串
*/
function duality_word_encode($arr)
{
	$len = count($arr);
	$new_arr = array();

	for($i = 0;$i<$len;$i++)
	{
		$tmp_arr = utf8_str2a($arr[$i]);
		$len1 = count($tmp_arr);
		for($k = 0;$k<$len1;$k++)
		{
			$new_arr[$i] .= utf8_unicode($tmp_arr[$k]);
		}
	}
	return $new_arr;
}
/**
* 将utf8字符转换为unicode编码
* $c 需要转换的字符
* 返回转换后的编码
*/
function utf8_unicode($c)
{
	switch(strlen($c))
	{
		case 1:
			$n = ord($c);
		break;
		case 2:
			$n = (ord($c[0]) & 0x3f) << 6;
			$n += ord($c[1]) & 0x3f;
		break;
		case 3:
			$n = (ord($c[0]) & 0x1f) << 12;
			$n += (ord($c[1]) & 0x3f) << 6;
			$n += ord($c[2]) & 0x3f;
		break;
		case 4:
			$n = (ord($c[0]) & 0x0f) << 18;
			$n += (ord($c[1]) & 0x3f) << 12;
			$n += (ord($c[2]) & 0x3f) << 6;
			$n += ord($c[3]) & 0x3f;
		break;
	}
	return dechex($n);
}
/**
 * 将数组中为空的数据过滤掉
 *
 * @param array $array
 * @return array
 */
function filter_arr_null_ele($array)
{
	return array_filter($array, 'array_filter_func');
}
function array_filter_func($var) //数组过滤
{
	return (trim($var) != '');
}

function build_content_dir($columninfo, $conntentid, $time = '',$siteinfo = array())
{
	$time = $time ? $time : time();
	$columndir = build_column_dir($columninfo, $siteinfo);
	$dir = date($columninfo['folderformat'], $time) . '/';
	if($columninfo['use_dateformat'] == 3)
	{
		$columninfo['fileformat'] = str_replace('MD5({ID})', md5($conntentid), $column['fileformat']);
	}
	else
	{
		$columninfo['fileformat'] = str_replace('{ID}', $conntentid, $columninfo['fileformat']);
	}

	if($columninfo['use_dateformat'] == 3)
	{
		$dir .= $columninfo['fileformat'];
	}
	elseif($columninfo['use_dateformat'] == 2)
	{
		$dir .= date($columninfo['fileformat'], $time);
	}
	else
	{
		$dir .= $columninfo['fileformat'];
	}
	if ($columninfo['content_maketype'] == 2 || $columninfo['maketype'] == 2)
	{
		$dir .= $var_cache['argsetting']['content_suffix'] ? $var_cache['argsetting']['content_suffix'] : '.html';
	}
	else
	{
		$dir .= $columninfo['suffix'];
	}
	return str_replace('//', '/', $dir);
}
function checkdir($dir, $layer = 0)
{
	$dir = str_replace(array('\\', '//'), array('/', '/'), $dir);
	$dir = explode('/', $dir);
	$check = '';
	$n = count($dir);
	$x = $n - $layer;
	for ($i = 0; $i < $n; $i++)
	{
		if (!$dir[$i] && $i > 0)
		{
			continue;
		}
		$check .= $dir[$i].'/';
		if (($layer && $i < $x) || strpos($dir[$i], '.') !== false || strpos($dir[$i], ':') !== false)
		{
			continue;
		}

		if (!is_dir($check))
		{
			if (substr($check, -1, 1) == '/')
			{
				$checkdir = substr($check, 0, -1);
			}
			if (!mkdir($checkdir, 0777))
			{
				exit("对不起，生成目录 $check 失败！");
			}
		}
	}
	return;
}
/**
 * 生成栏目目录路径
 */
function build_column_dir($columninfo, $siteinfo)
{
	$dir = $siteinfo['sitedir'] . $columninfo['coldir'] . '/';
	return str_replace('//', '/', $dir);
}
//echo plug_build_content_dir('1','10',time(),array('sitedir'=>'../../www/test1/'));