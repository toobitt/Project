<?php
/***************************************************************************
* LivCMS5.0
* (C)2004-2010 HOGE Software.
*
* $Id: functions.php 20122 2013-04-10 09:06:16Z zhuld $
***************************************************************************/

/**
 * 初始化用户输入
 */
function hg_init_input($data = array())
{	
	$return = array();
	if($data)
	{
		foreach ($data as $k => $v)
		{
			$k = hg_clean_key($k);
			if (is_array($v))
			{
				$return[$k] = hg_init_input($v);
			}
			else
			{
				$return[$k] = hg_clean_value($v);
			}
		}
	}
	else
	{
		foreach(array($_GET, $_POST) AS $type)
		{
			if (is_array($type))
			{
				foreach ($type as $k => $v)
				{
					$k = hg_clean_key($k);
					if (is_array($v))
					{
						$return[$k] = hg_init_input($v);
					}
					else
					{
						$return[$k] = hg_clean_value($v);
					}
				}
			}
		}
	}
	return $return;
}


/**
 * 清理用户输入数据
 * @param $key 指定需要清理的数据
 * @param $input 用户输入的数据，默认为$_INPUT
 * @return Array 清理后的数据
 */
function hg_clean_input($key = array(), $input = array())  
{
	if (!$input)
	{
		global $_INPUT;
		$input = $_INPUT;
	}
	foreach ($key AS $k)
	{
		if (!is_array($input[$k]) && $input[$k])
		{
			$input[$k] = hg_clean_value($input[$k]);
		}
	}
	return $input;
}

/**
 * 过滤数组索引
 */
function hg_clean_key($key)
{
	if (is_numeric($key))
	{
		return $key;
	}
	else if (empty($key))
	{
		return '';
	}

	if (strpos($key, '..') !== false)
	{
		$key = str_replace('..', '', $key);
	}

	if (strpos($key, '__') !== false)
	{
		$key = preg_replace('/__(?:.+?)__/', '', $key);
	}

	return preg_replace('/^([\w\.\-_]+)$/', '\\1', $key);
}

/**
 * 过滤输入的数据
 *
 * @param unknown_type $val
 * @return unknown
 */
function hg_clean_value($val)
{
	if (is_numeric($val))
	{
		return $val;
	}
	else if (empty($val))
	{
		return is_array($val) ? array() : '';
	}
	$val = preg_replace("/<script/i", "&#60;script", $val);

	if ($_REQUEST['html'])
	{
		//return $val;
	}
	$pregfind = array('&#032;', '<!--', '-->', '>', '<', '"', '!', "'", "\n", '$', "\r");
	$pregreplace = array(' ', '&#60;&#33;--', '--&#62;', '&gt;', '&lt;', '&quot;', '&#33;', '&#39;', "\n", '&#036;', '');
	$val = str_replace($pregfind, $pregreplace, $val);

	return preg_replace('/\\\(&amp;#|\?#)/', '&#092;', $val);
}

/**
 * 按给定的字串长度截取原字符串
 * @param $chars 原字符串
 * @param $limitlen 指定的字串长度
 * @param $cut_suffix 截取后剩余部分替代值
 * @param $doubletoone 英文数字是否2个字符做1长度处理
 * @return 截取后的字符串
 */
function hg_cutchars($chars, $limitlen = '6', $cut_suffix = '…', $doubletoone = false)
{
	$val = hg_csubstr($chars, $limitlen, $doubletoone);
	return $val[1] ? $val[0] . $cut_suffix : $val[0];
}

/**
 * 剪切字符
 *
 * @param string $text
 * @param int $limit
 * @return array
 */
function hg_csubstr($text, $limit = 12, $doubletoone = false)
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

/**
* 将utf8字符转换为unicode编码
* $c 需要转换的字符
* 返回转换后的编码
*/
function hg_utf8_unicode($c) 
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
 * 检查 Email 格式是否正确
 */
function hg_clean_email($email = '')
{
	$email = trim($email);
	$email = str_replace(' ', '', $email);
	$email = preg_replace('#[\;\#\n\r\*\'\"<>&\%\!\(\)\{\}\[\]\?\\/\s]#', '', $email);
	if (preg_match('/^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,4}|[0-9]{1,4})(\]?)$/', $email))
	{
		return $email;
	}
	return '';
}

/**
 * 生成用户加密干扰码
 *
 * @param intager $length 干扰码长度
 */
function hg_generate_user_salt($length = 5)
{
	$randstr = '0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
	$rlength = strlen($randstr);
	$salt = '';
	for ($i = 0; $i < $length; $i++)
	{
		$n = mt_rand(0, $rlength);
		$salt .= $randstr[$n];
	}
	return $salt;
}


/**
 * 生成随机数字
 *
 * @param intager $length 干扰码长度
 */
function hg_rand_num($length = 5)
{
	$randstr = '0123456789';
	$rlength = strlen($randstr);
	$salt = '';
	for ($i = 0; $i < $length; $i++)
	{
		$n = mt_rand(0, ($rlength-1));
		if(!$randstr[$n] && !$i)
		{
		 $randstr[$n] = '3';
		}
		$salt .= $randstr[$n];
	}
	return $salt;
}


/**
 * 写文件
 *
 * @return intager 写入数据的字节数
 */
function hg_file_write($filename, $content, $mode = 'rb+')
{
	$length = strlen($content);
	@touch($filename);
	if (!is_writeable($filename))
	{
		@chmod($filename, 0666);
	}

	if (($fp = @fopen($filename, $mode)) === false)
	{
		trigger_error('file_write() failed to open stream: ' . $filename . 'Permission denied', E_USER_WARNING);
		return false;
	}

	flock($fp, LOCK_EX | LOCK_NB);

	$bytes = 0;
	if (($bytes = @fwrite($fp, $content)) === false)
	{
		$errormsg = sprintf('file_write() Failed to write %d bytes to %s', $length, $filename);
		trigger_error($errormsg, E_USER_WARNING);
		return false;
	}

	if ($mode == 'rb+')
	{
		@ftruncate($fp, $length);
	}

	@fclose($fp);

	// 检查是否写入了所有的数据
	if ($bytes != $length)
	{
		$errormsg = sprintf('file_write() Only %d of %d bytes written, possibly out of free disk space.', $bytes, $length);
		trigger_error($errormsg, E_USER_WARNING);
		return false;
	}

	// 返回长度
	return $bytes;
}

/**
 * 格式化数字
 *
 * @param boolean $bytesize 是否带字节单位
 */
function hg_fetch_number_format($number, $bytesize = false)
{
	$decimals = 0;
	$type = '';
	if ($bytesize)
	{
		if ($number >= 1073741824)
		{
			$decimals = 2;
			$number = round($number / 1073741824 * 100) / 100;
			$type = ' GB';
		}
		else if ($number >= 1048576)
		{
			$decimals = 2;
			$number = round($number / 1048576 * 100) / 100;
			$type = ' MB';
		}
		else if ($number >= 1024)
		{
			$decimals = 1;
			$number = round($number / 1024 * 100) / 100;
			$type = ' KB';
		}
		else
		{
			$decimals = 0;
			$type = ' Bytes';
		}
	}
	$number = str_replace('_', '&nbsp;', number_format($number , $decimals, '.', ','));
	return $number . $type;
}

/**
 * 创建目录函数
 *
 * @param $dir 需要创建的目录
 */
function hg_mkdir($dir)
{
	if (!is_dir($dir))
	{
		if(!@mkdir($dir, CREATE_DIR_MODE, 1))
		{
			return false;//创建目录失败
		}
	}
	return true;
}
/**
 * 创建随机生成字符串
 *
 * @param $length salt长度
 */
function hg_generate_salt( $length = 6 ) {
    // salt字符集，可任意添加你需要的字符
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&';
    $salt = '';
    for ( $i = 0; $i < $length; $i++ ) 
    {
        $salt .= $chars[ mt_rand(0, strlen($chars) - 1) ];
    }
    return $salt;
}
/**
* 检查IP地址是否正确。
*/
function hg_checkip ($ipaddres) 
{
	$preg="/\A((([0-9]?[0-9])|(1[0-9]{2})|(2[0-4][0-9])|(25[0-5]))\.){3}(([0-9]?[0-9])|(1[0-9]{2})|(2[0-4][0-9])|(25[0-5]))\Z/";
	if(preg_match($preg,$ipaddres))
	{
		return true;
	}
	return false;
}

/**
 * 获取IP
 *
 * @param none
 */
function hg_getip() 
{
	global $_INPUT;
	if ($_INPUT['lpip'])
	{
		if (hg_checkip($_INPUT['lpip']))
		{
			return $_INPUT['lpip'];
		}
	}
	if (isset($_SERVER)) 
	{
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			$realip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		elseif ($_SERVER['HTTP_X_REAL_IP'])
		{
			$realip = $_SERVER['HTTP_X_REAL_IP'];
		}
		elseif (isset($_SERVER['HTTP_CLIENT_IP'])) 
		{
			$realip = $_SERVER['HTTP_CLIENT_IP'];
		} 
		else 
		{
			$realip = $_SERVER['REMOTE_ADDR'];
		}
	} 
	else 
	{
		
		if (getenv("HTTP_X_FORWARDED_FOR")) 
		{
			$realip = getenv( "HTTP_X_FORWARDED_FOR");
		}
		elseif (getenv('HTTP_X_REAL_IP'))
		{
			$realip = getenv('HTTP_X_REAL_IP');
		} 
		elseif (getenv("HTTP_CLIENT_IP")) 
		{
			$realip = getenv("HTTP_CLIENT_IP");
		}
		else 
		{
			$realip = getenv("REMOTE_ADDR");
		}
	}
	$realip = explode(',', $realip);
	return $realip[0];
}

function hg_get_cookie($name)
{
	global $gGlobalConfig;
	$cookie_name = $gGlobalConfig['cookie_prefix'] . $name;

	return $_COOKIE[$cookie_name];
}

/**
* 创建Memcache服务连接$memcache
* 向队列中增加数据方法为 $memcache->set('名称', '值');
*/

/*function hg_ConnectMemcache()
{
	global $gMemcacheConfig, $gMemcache;
	if (!$gMemcache)
	{
		$gMemcache = new Memcache();
		$connect = @$gMemcache->connect($gMemcacheConfig['host'], $gMemcacheConfig['port']);			
		if (!$connect)
		{
			include_once(ROOT_PATH . 'lib/class/memcache.class.php');
			$gMemcache = new memcache();
		}
	}
	return $gMemcache;
}*/

function hg_check_cache($name, $recache = 'friends(1)')
{
	$gMemcache = hg_ConnectMemcache();
	$data = $gMemcache->get($name);
	
	if (!$data)
	{	
		include_once (ROOT_PATH . 'lib/class/recache.class.php');
		$cache = new recache();
		if ($recache)
		{
			$spec_reacache = explode('(', $recache);
			$recache = $spec_reacache[0] . '_recache';
			$args = array();
			if ($spec_reacache[1])
			{
				$arg = trim($spec_reacache[1]);
				$get_args = substr($arg, 0, strlen($arg) - 1);
				$args = explode(',', $get_args);
			}
			if (method_exists($cache, $recache))
			{
				$cache->$recache($args);
			}
		}
		else 
		{
			$recache = $name . '_recache';
			if (method_exists($cache, $recache))
			{
				$cache->$recache();
			}
		}
		$recache = $recache . '_recache';
		$data = $gMemcache->get($name);
	}
	return $data;
}

function hg_authcode($string, $operation, $key = '') 
{
     $key = md5($key ? $key : $GLOBALS['auth_key']);
     $key_length = strlen($key);
     $string = $operation == 'DECODE' ? base64_decode($string) : substr(md5($string.$key), 0, 8).$string;
     $string_length = strlen($string);
     $rndkey = $box = array();
     $result = '';
     for($i = 0; $i <= 255; $i++)
	{
		$rndkey[$i] = ord($key[$i % $key_length]);
		$box[$i] = $i;
	}

	for($j = $i = 0; $i < 256; $i++) 
	{
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}
    for($a = $j = $i = 0; $i < $string_length; $i++)
	{
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
     }

    if($operation == 'DECODE') 
	{
		if(substr($result, 0, 8) == substr(md5(substr($result, 8).$key), 0, 8)) 
		{
			return substr($result, 8);
		}
		else 
		{
			return '';
		}
     } 
	else 
	{
		return str_replace('=', '', base64_encode($result));
	}
}
/**
 * 获取头像
 * @param $id 用户ID
 * @param $type 头像类型
 * @param $avatar 数据库头像名称
 * @return $avatar 结合类型之后的头像名称
 */
function hg_avatar($id,$type = 'middle',$avatar = '',$sort = 1)
{
	if($sort)
	{
		$prefix = AVATAR_URL . ceil($id/NUM_IMG);
		if(!$avatar || $avatar== AVATAR_DEFAULT)
		{
			return $avatar = AVATAR_URL .$type . '_' . $avatar;
		}
		else 
		{
			return $avatar = $prefix . '/' . $type . '_' . $avatar;
		}
	}
	else 
	{
		return $avatar = $avatar.$type;
	}
}

/**
 * 链接处理
 * @param $filename 文件名
 * @param $param 参数(array)
 * @return $string 链接的地址
 */

function hg_build_link($filename , $param = array())
{
	$url = '';	
	$url .= $filename;
	
	if(!empty($param))
	{
		$url .= '?';
		foreach($param as $k => $v)
		{
			$url .= $k . '=' . $v . '&';
		}
		
		$len = strlen($str); 
		$url = substr($url , 0 , $len-1);
	}
	
	return $url;	
}

/**
 * 格式化输出视频时长
 * @param $time 视频秒数
 */
function hg_get_video_toff($time)
{
	$h = floor($time / (60 * 60));
	$n = floor(($time - $h * (60 * 60))/ 60);
	$s = floor($time - $h * (60 * 60) - $n * 60);

	$format_time = $h . "小时 " . $n . "分 " . $s . "秒 ";
	return $format_time; 
}

/**
 * 获取视频
 * @param $link 路径
 * @return $info array
 */
function hg_get_video($link)
{
	$link = preg_replace("/(.*?)video-([0-9]+)\.html/i", "\\1video_play.php?id=\\2", $link);
	$link = preg_replace("/(.*?)station-([0-9]+)\.html(#[0-9]+){0,1}/i", "\\1station_play.php?sta_id=\\2\\3", $link);
	global $gGlobalConfig;
	$info = array("img" => "","link" => "","title" => "");
	$http = "http://";
	str_replace($http,"",$link,$hp);
	$type = $gGlobalConfig['video_type'];
	$i=0;
	foreach($type as $key =>$value)
	{
		$i +=1;
		str_replace($value,"",$link,$counts);
		if($counts)
		{
			$count = $i;
		}
	}

	if($count)
	{
		if(!$hp)
		{
			$link = $http.$link;
		}
	}
	else 
	{
		return $info;
	}


	$content = @file_get_contents($link);
	if(!$content)
	{
		return $info;
	}
	
	switch($count)
		{
			case 1:
					preg_match_all("/\+0800\|(.*?)\|\">/i",$content,$img);//优酷缩略图
					preg_match_all('/<input\s{1,}type="text"\s{1,}id="link2"\s{1,}value="(.*?)" \/>/i',$content,$url);//优酷swf地址
					preg_match_all('/<span\s{1,}class="name">(.+)<\/span>/Usi',$content,$title_first);
					$url = $url[1][0];
					$img = $img[1][0];
					$title = $title_first[1][0];
					if(!$title)
					{
						preg_match_all('/<title>(.+)<\/title>/Usi',$content,$title_second);
						$title = explode("-",$title_second[1][0]);
						$title = $title[0];
					}
					break;
			case 2:
					preg_match('/iid=(.*)&{0,1}/', $link,$iid);
					if(!$iid)
					{
						preg_match('/lid=(.*)&{0,1}/', $link,$lid);
						if(!$lid)
						{
							preg_match_all("/<script>(.+)<\/script>/Uis",$content,$con);
							preg_match_all("/\s{1,}icode\s{1,}=\s{1,}(.*),/Uis",str_replace("'", "",str_replace('"', "",$con[1][0])),$cod);
							$code = iconv("gb2312","utf-8",trim($cod[1][0]));
							if($code)
							{
								preg_match_all("/\s{1,}pic\s{1,}=\s{1,}(.*),/Uis",str_replace("'", "",str_replace('"', "",$con[1][0])),$im);
								$img = iconv("gb2312","utf-8",trim($im[1][0]));
								preg_match_all("/\s{1,}title\s{1,}=\s{1,}(.*),/Uis",str_replace("'", "",str_replace('"', "",$con[1][0])),$tit);
								$title = iconv("gb2312","utf-8",trim($tit[1][0]));
							}
							else
							{
								preg_match_all("/defaultIid\s{1,}=\s{1,}(.*)\s{1,},/Uis",str_replace("'", "",str_replace('"', "",$con[1][0])),$cod);
								$coid = $cod[1][0];
								preg_match_all("/icode\s{0,}:\s{0,}(.*)\s{0,},/Uis",str_replace("'", "",str_replace('"', "",$con[1][0])),$icode);
								preg_match_all("/title\s{0,}:\s{0,}(.*)\s{0,},/Uis",str_replace("'", "",str_replace('"', "",$con[1][0])),$title);
								preg_match_all("/pic\s{0,}:\s{0,}(.*)\s{0,},/Uis",str_replace("'", "",str_replace('"', "",$con[1][0])),$opic);
								preg_match_all("/iid\s{0,}:\s{0,}(.*)\s{0,},/Uis",str_replace("'", "",str_replace('"', "",$con[1][0])),$iid);
								$i = '';
								if($iid[1] && is_array($iid[1]))
								{
									foreach($iid[1] as $k=>$v)
									{
										if($coid == $v)
										{
										  $i = $k;
										}
									}
								}
								$code = $icode[1][$i];
								$img = $opic[1][$i];
								$title = iconv("gb2312","utf-8",trim($title[1][$i]));
							}
						}
						else
						{
							str_replace("&", "",  $lid, $numl);
							if($numl)
							{
								$lid = explode('&',$lid[1]);
								$lid = $lid[0];
							}
							else
							{	
								$lid =$lid[1];
							}
							preg_match_all("/<script>(.+)<\/script>/Uis",$content,$con);
							preg_match_all("/{(.+)}/Uis",$con[1][0],$im);
							$mv = $im[0][1];	
							$mv = explode(",",str_replace('"', "",  $mv));
							$code = iconv("gb2312","utf-8",trim(substr(strstr($mv[2], ':'),1)));
							$img = iconv("gb2312","utf-8",trim(substr(strstr($mv[8], ':'),1)));
							$title = iconv("gb2312","utf-8",trim(substr(strstr($mv[1], ':'),1)));
							if(!$code)
							{
								preg_match_all("/defaultIid\s{1,}=\s{1,}(.*)\s{1,},/Uis",$content,$other);
								$iid = $other[1][0];
								preg_match_all("/<script>(.+)<\/script>/Uis",$content,$con);
								preg_match_all("/{(.+)}/Uis",$con[1][0],$im);
								foreach($im[1] as $key => $value)
								{	
									str_replace($iid, "",  $value, $count);
									if($count)
									{
										$mv = $value;
									}
								}
								$mv = explode(",",str_replace('"', "",  $mv));
								$code = iconv("gb2312","utf-8",trim(substr(strstr($mv[2], ':'),1)));
								$img = iconv("gb2312","utf-8",trim(substr(strstr($mv[8], ':'),1)));
								$title = iconv("gb2312","utf-8",trim(substr(strstr($mv[1], ':'),1)));
							}
						}
					}
					else
					{
						str_replace("&", "",  $iid, $num);
						if($num)
						{
							$iid = explode('&',$iid[1]);
							$iid = $iid[0];
						}
						else
						{	
							$iid =$iid[1];
						}
						preg_match_all("/<script>(.+)<\/script>/Uis",$content,$con);
						preg_match_all("/{(.+)}/Uis",$con[1][0],$im);
						foreach($im[1] as $key => $value)
						{	
							str_replace($iid, "",  $value, $count);
							if($count)
							{
								$mv = $value;
							}
						}
						$mv = explode(",",str_replace('"', "",  $mv));
						$code = iconv("gb2312","utf-8",trim(substr(strstr($mv[2], ':'),1)));
						$img = iconv("gb2312","utf-8",trim(substr(strstr($mv[8], ':'),1)));
						$title = iconv("gb2312","utf-8",trim(substr(strstr($mv[1], ':'),1)));
					}
					if($code)
					{
						$url = "http://www.tudou.com/v/".$code."/";
					}
					break;
			case 3:				
					preg_match_all('/swfOutsideUrl:\'(.+)\',/Uis',$content,$url);
					$url = $url[1][0];
					preg_match_all('/title:\'(.+)\',/Uis',$content,$title);
					$title = $title[1][0];
					preg_match_all('/pic:\s*\'(.+)\',/Uis',$content,$img);
					$img = $img[1][0];
					break;
			case 4:
					preg_match_all('/<span\s{1,}class="s_pic">(.+)<\/span>/Uis',$content,$img);
					$img = iconv("gb2312","utf-8",$img[1][0]);
					preg_match_all('/<input\s{1,}id="outSideSwfCode"\s{1,}type="text"\s{1,}readonly="readonly"\s{1,}class="input"\s{1,}value="(.+)"\s{1,}onclick="App.swfCodeCopy\(true\);"\s{1,}\/>/Uis',$content,$url);
					$url = iconv("gb2312","utf-8",$url[1][0]);
					preg_match_all('/\<h1\s*class\s*=\s*["|\']cBlack["|\']\s*\>[\s\S]*\[.*\][\s\S]*(.*)\<\/h1\>/Uis',$content,$title);
					$title = iconv("gb2312","utf-8",trim($title[1][0]));
					break;
			case 5:
					str_replace("video_play","",$link,$cnt);
					if($cnt)
					{
						preg_match_all('/video_address="(.+)";/Uis',$content,$url);
						$url = $url[1][0];
						preg_match_all('/video_title="(.+)";/Uis',$content,$title);
						$title = $title[1][0];
						preg_match_all('/video_schematic="(.+)";/Uis',$content,$img);
						$img = $img[1][0];
					}
					else
					{
						$domain = substr(strstr($link, '#'),1);
						if($domain)
						{
							preg_match_all('/gTitles\['.$domain.'\]\s{1,}=\s{1,}"(.+)";/Uis',$content,$title);
							$title = $title[1][0];
							preg_match_all('/gMedias\['.$domain.'\]\s{1,}=\s{1,}\'(.+)\';/Uis',$content,$url);
						 	$url = $url[1][0];
							preg_match_all('/gSchematic\['.$domain.'\]\s{1,}=\s{1,}\'(.+)\';/Uis',$content,$img);
						 	$img = $img[1][0];
						}

						else
						{
							preg_match_all('/gFirstProgram\s{1,}=\s{1,}(.+);/Uis',$content,$num);
							$domain = $num[1][0];
							preg_match_all('/gTitles\['.$domain.'\]\s{1,}=\s{1,}"(.+)";/Uis',$content,$title);
							$title = $title[1][0];
							preg_match_all('/gMedias\['.$domain.'\]\s{1,}=\s{1,}\'(.+)\';/Uis',$content,$url);
						 	$url = $url[1][0];
							preg_match_all('/gSchematic\['.$domain.'\]\s{1,}=\s{1,}\'(.+)\';/Uis',$content,$img);
						 	$img = $img[1][0];
						}
					
					}
					
			default:
					break;
		}
		$info['img'] = $img;
		$info['link'] = $url;
		$info['title'] = $title;
		
		return $info;//为空就说明不支持此网站或者格式不正确
}

/**
 * 用户id或点滴id替换函数
 * @param $str 要替换的id
 * @param $type 替换方式：0：加密，1：解密（还原）
 * return string
 */
function hg_codeId($str,$type = 0)
{
	$str = (string)$str;
	$arr = array(
			'1' => 6,
			'2' => 7,
			'3' => 4,
			'4' => 1,
			'5' => 9,
			'6' => 3,
			'7' => 8,
			'8' => 5,
			'9' => 2,
		);


	if($type == 0)
	{
		for($i = 0;$i<strlen($str);$i++)
		{
			$str{$i}=$arr{$str{$i}};
			if($str{$i} == 0)
			{
				$str{$i} = 0;
			}
		}
	}
	else
	{
		for($i = 0;$i<strlen($str);$i++)
		{
			if($key = array_search($str{$i},$arr))
			{
				$str{$i} = $key;
			} 
		}
	} 
	return $str; 
}
/**
 * 返回时间格式
 * @param $total 时间总数 限制一天内
 * return string
 */
function hg_encode_time($total,$string=":",$type=2){
	$hou=$min=$sec=0;
	if(intval($total)>60)
	{
		if(intval($total)>3600)
		{
			$hou = floor(intval($total)/3600);
			$mins = intval($total) - $hou * 3600;
			$min = floor(intval($mins)/60);
			$sec = intval($mins) - $min * 60;
		}
		else
		{
			$min = floor(intval($total)/60);
			$sec = intval($total) - $min * 60;
		}
	}
	else
	{
		$sec = intval($total);
	}
	$hou = ($hou<10)?'0'.$hou:$hou;
	$min = ($min<10)?'0'.$min:$min;
	$sec = ($sec<10)?'0'.$sec:$sec;
	switch ($type)
	{
		case 1:
			
			break;
		case 2:
			return $hou.$string.$min;
			break;
		case 3:
			return $hou.$string.$min.$string.$sec;
			break;
		default:
			break;
	}
	
}

/**
 * 返回时间格式
 * @param $total 时间总数 限制一天内
 * return string
 */
function hg_decode_time($string){
	$total = 0;
	$arr = explode(":", $string);
	$count = count($arr);
	switch($count)
	{
		case 1:
			$total = $arr[0];
			break;
		case 2:
			$total = intval($arr[0])*3600+intval($arr[1])*60;
			break;
		case 3:
			$total = intval($arr[0])*3600+intval($arr[1])*60+intval($arr[2]);
			break;
		default:
			break;
	}
	return $total;
}

/**
 * 返回时间格式
 * @param $array 含有start_time和end_time的二维数组
 * @param $type 时间格式
 * return string
 */
function hg_check_time($array,$type="H:i")
{
	$nows =hg_decode_time(date($type,time()));
	if($array)
	{
		foreach($array as $key => $value)
		{
			if($nows>$value['start_time'] && $nows<$value['end_time'])
			{
				$array[$key]['play'] = 1; 	
			}
			else
			{
				$array[$key]['play'] = 0;
			}
		}
	}
	return $array;
}

/**
 * 返回时间时长
 * @param $start_time 
 * @param $end_time
 * return string
 */
function hg_toff_time($start_time,$end_time,$type=1)
{
	$toff = $end_time - $start_time;
	$hou=$min=$sec=0;
	if(intval($toff)>60)
	{
		if(intval($toff)>3600)
		{
			$hou = floor(intval($toff)/3600);
			$mins = intval($toff) - $hou * 3600;
			$min = floor(intval($mins)/60);
			$sec = intval($mins) - $min * 60;
		}
		else
		{
			$min = floor(intval($toff)/60);
			$sec = intval($toff) - $min * 60;
		}
	}
	else
	{
		$sec = intval($toff);
	}
	$hou = ($hou<10)?'0'.$hou:$hou;
	$min = ($min<10)?'0'.$min:$min;
	$sec = ($sec<10)?'0'.$sec:$sec;
	$ret = "";
	if($type)
	{
		$sec_t ="秒";
		$min_t ="分";
		$hou_t ="时";
	}
	else 
	{
		$sec_t ="";
		$min_t =":";
		$hou_t =":";
	}
	if($sec)
	{
		$ret =$sec.$sec_t;
		if($min)
		{
			$ret =$min.$min_t.$ret;
			if($hou)
			{
				if($hou<24)
				{
					$ret =$hou.$hou_t.$ret;
				}
			}
		}
	}
	return $ret;
}

/**
 * 返回无样式数据，用于测试
 * @param $obj 
 * @param $type 
 */
function hg_pre($obj,$type = 1)
{
	echo	$html = "<pre>";
	if(empty($obj))
	{
		var_dump($obj);
	}
	else 
	{
		print_r($obj);
	}
	echo "</pre>";
	if(!$type)
	{
		exit;
	}
}


function hg_convert_encoding($str, $from = '', $to = '')
{
	static $convert = null;
	if (is_null($convert))
	{
		include_once(ROOT_PATH . 'lib/class/encoding.class.php');
		$convert = new encoding();
	}
	return $convert->convert($str, $from, $to);
}


/**
 * 拼组视频图片
 * @param $obj
 * 返回数组 
 */
function hg_video_image($id,$row,$type=1)
{
	global $gGlobalConfig;
	if($id)
	{
		$size = $gGlobalConfig['video_img_size'];
		if($type)
		{
			if(is_array($row))
			{
				if($row['images'])
				{
					foreach($size as $key=>$value)
					{
						$new_name = $value['label'].$row['images'];
						$row[$key] = UPLOAD_URL.VIDEO_DIR.ceil($id/NUM_IMG)."/".$new_name;
					}
				}
				else
				{
					str_replace($gGlobalConfig['video_api'],"",$row['schematic'],$cnt);
					if(!$cnt)
					{
						foreach($size as $key=>$value)
						{
							$new_name = $value['label'].$row['images'];
							$row[$key] = UPLOAD_URL.VIDEO_DIR.ceil($id/NUM_IMG)."/".$new_name;
						}
					}
				}
				
			}
		}
		else 
		{
			str_replace($gGlobalConfig['video_api'],"",$row,$cnt);
			if(!$cnt)
			{
				$new_name = $size["small"]['label'].$row;
				$row = UPLOAD_URL.VIDEO_DIR.ceil($id/NUM_IMG)."/".$new_name;
			}
		}
	}
	return $row;

}

/**
 * 替换或者过滤特殊字符串
 * @param $text
 * return $text
 */
function hg_filter_chars($text)
{
	$text = str_replace("'","’",$text);//单引号问题
	return $text;
}

/**
 * 读取某个目录下的所有文件名
 * @param $dir
 * return $dir array
 */
function hg_readdir($dir)   
{ 
	$list = array();
	$j = 1;
	for($i = 100;$i < 190; $i++)
	{
		$list[$j]= 'e' . $i . '.gif'; 
		$j++;
	}
	return   $list; 
}

/**
 * 获取用户的性别
 */
function hg_show_sex($sex)
{
	switch($sex)
	{
		case 0 : $s = '保密';break;
		case 1 : $s = '男';break;
		case 2 : $s = '女';break;
		default: break;
	}	
	return $s;
}


function hg_show_face($text)
{
	global $gGlobalConfig;
	$faces = $gGlobalConfig['smile_face'];
	if(!empty($faces))
	{
		foreach($faces as $fk => $fv)
		{
			$f_pattern = "/\:em".$fk."_(.*)\:/Ui";
			preg_match_all($f_pattern,$text,$show);
			if($show[0])
			{
				foreach($show[1] as $key => $value)
				{
					$facename = "e".(99+$value).".gif";
					$face[] = '<img alt="" src="'.$fv['url'].$facename.'"/>';
				}
				
				foreach($show[0] as $k=>$v)
				{
					$keys[] =  "/".$v."/";
				}
				$text = preg_replace($keys, $face,$text);
			}
		}
	}
	return $text;
}


	/**
	* 验证text中搜含有用户名，是否含有话题,链接地址,并替换标签
	* @param $text 点滴内容
	* @return $text
	*/
	function hg_verify($text)
	{
		$text = stripcslashes($text);
		$pattern = "/@([\x{4e00}-\x{9fa5}0-9A-Za-z_-]+)[\s:：,，.。\'‘’\"“”、！!]/iu";  //这里牵扯到用户名命名规则问题
		if(preg_match_all($pattern,$text." ",$username))
		{			
			foreach($username[1] as $value)
			{
				$nameH = '@'.$value;
				$names = '<a href="'.SNS_UCENTER.USER_URL.'?name='.urlencode($value).'" target="_blank">'.$nameH.'</a>';
				$text = str_replace($nameH,$names,$text);
			}		
		}
					
		$pattern = "/#([\x{4e00}-\x{9fa5}0-9A-Za-z_-\s‘’“”'\"!\?？，、,$%&:;！￥×\*\<\>\.。：；》《]+)[\s#]/iu";
		 //这里牵扯到话题规则问题
		if(preg_match_all($pattern,$text,$topic))
		{
			foreach ($topic[1] as $key => $value)
			{
				$nameH = '#'.$value.'#';
				$names = '<a target="_blank" href="'.SNS_MBLOG.TOPIC_URL.'?q='.urlencode($value).'">'.$nameH.'</a>';
				$text = str_replace($nameH,$names,$text);			
			}	
		}
		//超链接替换
		$pattern = "((((f|ht){1}tp|ftp|gopher|news|telnet|rtsp|mms)://|www\.)[-a-zA-Z0-9@:%_\+.~#?&//=]+)";
		$match = hg_match_links($text);
		if($match)
		{
			$match = $match['all'];
			foreach($match as $key =>$value)
			{
				$key = "key_".$key;
				$text = str_replace($value,$key,$text);
			}
			if(preg_match_all($pattern,$text,$url))
			{		
				$url[0] = array_unique($url[0]);
				foreach($url[0] as $value)
				{
					$urls = '<a href="'.$value.'" target="_blank">'.$value.'</a>';
					$text = str_replace($value,$urls,$text);
				}		
			}
			foreach($match as $key =>$value)
			{
				$key = "key_".$key;
				$text = str_replace($key,$value,$text);
			}
		}
		else
		{
			if(preg_match_all($pattern,$text,$url))
			{			
				foreach($url[0] as $value)
				{
					$urls = '<a href="'.$value.'" target="_blank">'.$value.'</a>';
					$text = str_replace($value,$urls,$text);
				}		
			}
		}
		
		global $gGlobalConfig;
		$faces = $gGlobalConfig['smile_face'];
		if(is_array($faces))
		{
			foreach($faces as $fk => $fv)
			{
				$f_pattern = "/\:em".$fk."_(.*)\:/Ui";
				preg_match_all($f_pattern,$text,$show);
				if($show[0])
				{
					foreach($show[1] as $key => $value)
					{
						$facename = "e".(99+$value).".gif";
						$face[] = '<img alt="" src="'.$fv['url'].$facename.'"/>';
					}
					
					foreach($show[0] as $k=>$v)
					{
						$keys[] =  "/".$v."/";
					}
					$text = preg_replace($keys, $face,$text);
				}
			}
		}
		return $text;		
	}

function hg_match_links($document) {    
    preg_match_all("'<\s*a\s.*?href\s*=\s*([\"\'])?(?(1)(.*?)\\1|([^\s\>]+))[^>]*>?(.*?)</a>'isx",$document,$links);  	   
		while(list($key,$val) = each($links[2])) {
        if(!empty($val))
            $match['link'][] = $val;
    }
    while(list($key,$val) = each($links[3])) {
        if(!empty($val))
            $match['link'][] = $val;
    }        
    while(list($key,$val) = each($links[4])) {
        if(!empty($val))
            $match['content'][] = $val;
    }
    while(list($key,$val) = each($links[0])) {
        if(!empty($val))
            $match['all'][] = $val;
    }                
    return $match;
}

function hg_num2dir($num)
{
	$dir = number_format($num);
	$dir = explode(',', $dir);
	$dir[0] = str_pad($dir[0], 3, '0', STR_PAD_LEFT);
	$dir = implode('/', $dir) . '/';
	return $dir;
}

function hg_hidden_ip($ip)
{
	$ip = explode('.', $ip);
	$ip[2] = '*';
	$ip[3] = '*';
	return implode('.', $ip);
}

function hg_material_link($host,$dir,$filepath,$filename,$sizelabel = "")
{
	return $host . $dir . $sizelabel . $filepath . $filename;
}

//xml特殊字符的转义函数
function xmlencode($tag)
{
	$tag = str_replace("&", "&amp;", $tag);
	$tag = str_replace("<", "&lt;", $tag);
	$tag = str_replace(">", "&gt;", $tag);
	$tag = str_replace("'", "&apos;", $tag);
	$tag = str_replace('"', '&quot;', $tag);
	return $tag;
}


/*
* 根据应用标识获取上传路径
*
* @return string 路径
*/
function app_to_dir($app_bundle,$type='img')
{
	if(empty($app_bundle))
	{
		return false;
	}
	$app_bundle = strtolower($app_bundle);
	global $gGlobalConfig;
	if ($gGlobalConfig['attach_serv'][$app_bundle])
	{
		$dir = $gGlobalConfig['attach_serv'][$app_bundle] . $type . '/';
	}
	else
	{
		$dir = 'material/' . $app_bundle.'/'.$type.'/';
	}
	return $dir;
}


/**
 * 
 * 时间轴
 * @param  int	$time	unix时间戳
 */
function hg_tran_time($time) 
{ 
    $rtime = date("m-d H:i",$time);  
    $htime = date("H:i",$time);  
      
    $time = time() - $time;  
  
    if($time < 60) 
    {  
        $str = '刚刚';  
    }  
    elseif($time < 60 * 60) 
    {  
        $min = floor($time/60);  
        $str = $min.'分钟前';  
    }  
    elseif($time < 60 * 60 * 24) 
    {  
        $h = floor($time/(60*60));  
        $str = $h.'小时前 '.$htime;  
    }  
    elseif($time < 60 * 60 * 24 * 3) 
    {  
        $d = floor($time/(60*60*24));  
        if($d==1)  
           $str = '昨天 '.$rtime;  
        else  
           $str = '前天 '.$rtime;  
    }  
    else 
    {  
        $str = $rtime;
    }  
    return $str;  
}


/**
* 转换字节数为其他单位
*
* @param	string	$filesize	字节大小
* @return	string	返回大小
*/
function hg_bytes_to_size($filesize) 
{
	if ($filesize >= 1073741824) 
	{
		$filesize = sprintf("%.2f",$filesize / 1073741824) .' GB';
	} 
	elseif ($filesize >= 1048576) 
	{
		$filesize = sprintf("%.2f",$filesize / 1048576) .' MB';
	} 
	elseif($filesize >= 1024) 
	{
		$filesize = sprintf("%.2f",$filesize / 1024) . ' KB';
	} 
	else 
	{
		$filesize = sprintf("%.2f",$filesize).' Bytes';
	}
	return $filesize;	
}
function hg_fetchimgurl($data, $width = '', $height = '', $default = '')
{
	if ($data)
	{
		$url = $data['host'] . $data['dir'];
		if ($width)
		{
			$url .= $width . 'x' . $height . '/';
		}
		return $url . $data['filepath'] . $data['filename'];
	}
	else
	{
		if ($default)
		{
			$default = $width . '_' . $default;
		}
		return $default;
	}
}
function hg_encript_str($str, $en = true)
{
	$salt = 'WssR$#QGsRT';
	if ($en)
	{
		$str = $str . $salt;
		$str = base64_encode($str);
	}
	else
	{
		$str = base64_decode($str);
		$str = str_replace($salt, '', $str);
	}
	return $str;
}
function hg_jwd_square($wd,$jd,$distance = 1)
{
	$r = 6.371229*1e6;
	$distance =$distance*1000;	
	$dx1 = ($distance*sin(0*pi()/180));
	$dy1 = ($distance*cos(0*pi()/180));
	$dx2 = ($distance*sin(90*pi()/180));
	$dy2 = ($distance*cos(90*pi()/180));
	$dx3 = ($distance*sin(180*pi()/180));
	$dy3 = ($distance*cos(180*pi()/180));
	$dx4 = ($distance*sin(270*pi()/180));
	$dy4 = ($distance*cos(270*pi()/180));
	//$dx1 = ($jd1-$jd)*pi()*$R*cos((($wd+$wd1)/2)*pi()/180)/180;
	//$dy1 = ($wd1-$wd)*pi()*$R/180;
	$wd1 = (180*$dy1/(pi()*$r))+$wd;
	$jd1 = 180*$dx1/(pi()*$r*cos((($wd+$wd1)/2)*pi()*180)) + $jd;
	$wd2 = (180*$dy2/(pi()*$r))+$wd;
	$jd2 = 180*$dx2/(pi()*$r*cos((($wd+$wd2)/2)*pi()*180)) + $jd;
	$wd3 = (180*$dy3/(pi()*$r))+$wd;
	$jd3 = 180*$dx3/(pi()*$r*cos((($wd+$wd3)/2)*pi()*180)) + $jd;
	$wd4 = (180*$dy4/(pi()*$r))+$wd;
	$jd4 = 180*$dx4/(pi()*$r*cos((($wd+$wd4)/2)*pi()*180)) + $jd;
	$return = array(
		'wd' => array(
			'max' => max($wd1,$wd2,$wd3,$wd4),
			'min' => min($wd1,$wd2,$wd3,$wd4),
 		 ),
 		'jd' => array(
 			'max' => max($jd1,$jd2,$jd3,$jd4),
 			'min' => min($jd1,$jd2,$jd3,$jd4),
 		 ), 
	);	
	return $return;	
}

function hg_get_time_offset()
{
	$r = 0;
	$timezoneoffset = TIMEZONEOFFSET;
	$r = $timezoneoffset * 3600;
	
	return $r;
}

function hg_get_time($date, $method = 'h:i A')
{
	$timeofset = hg_get_time_offset ();
	return gmdate ( $method, $date - $timeofset );
}

function hg_mk_weekday($time)
{
	$w = date ( 'w', $time );
	switch ($w) {
		case 0 :
			return '星期日';
		case 1 :
			return '星期一';
		case 2 :
			return '星期二';
		case 3 :
			return '星期三';
		case 4 :
			return '星期四';
		case 5 :
			return '星期五';
		case 6 :
			return '星期六';
	}
}

function hg_mk_time($hour, $minute, $second, $month, $day, $year)
{
	return gmmktime ( $hour, $minute, $second, $month, $day, $year );
}

function hg_get_format_date($date, $method)
{
	$timeoptions = array (1 => 'Y-m-d', 2 => 'Y-m-d H:i:s', 3 => 'Y年m月d日  H:i', 4 => 'Y-m-d H:i', 8 => 'm月d日 H:i', 5 => 'H:i', 6 => 'm-d', 7 => 'Y年n月j日' );
	if (empty ( $method ))
	{
		$method = 2;
	}
	return hg_get_time ( $date, $timeoptions [$method] );
}

function hg_get_time_forbidden_last($time = 0)
{
	if ($time <= 0)
		return '';
	$minutes = $time / 60;
	if ($minutes < 60)
	{
		if ($minutes < 1)
		{
			$showtime = '' . $time . '秒内';
		} else
		{
			$showtime = '' . intval ( $minutes ) . '分钟内';
		}
	} elseif ($minutes < 1440)
	{
		$showtime = '' . intval ( $minutes / 60 ) . '小时内';
	} elseif ($minutes < 14400)
	{
		$showtime = '' . intval ( $minutes / 1440 ) . '天内';
	}
	return $showtime;
}

function hg_get_time_last($date = 0)
{
	$seconds = $date - TIMENOW;
	$minutes = $seconds / 60;
	if ($seconds <= 0)
		return '';
	if ($minutes < 60)
	{
		if ($minutes < 1)
		{
			$showtime = '还剩' . $seconds . '秒';
		} else
		{
			$showtime = '还剩' . intval ( $minutes ) . '分钟';
		}
	} elseif ($minutes < 1440)
	{
		$showtime = '还剩' . intval ( $minutes / 60 ) . '小时';
	} elseif ($minutes < 14400)
	{
		$showtime = '还剩' . intval ( $minutes / 1440 ) . '天';
	}
	return $showtime;
}

/**
 * 格式化时间输出
 * @param $date unix时间戳
 * @param $method 显示格式
 * @param $type 是否强制格式化输出
 * @return unknown_type
 */
function hg_get_date($date = 0, $method = 4, $type = 1)
{
	if (! $date)
	{
		return '';
	}
	if ($type)
	{
		return hg_get_format_date ( $date, $method );
	}
	$seconds = TIMENOW - $date;
	$minutes = $seconds / 60;
	$d = date('d', $date);
	$cd = date('d');
	$m = date('m', $date);
	$cm = date('m');
	$y = date('Y', $date);
	$cy = date('Y');
	
	if ($minutes < 60)
	{
		if ($minutes < 1)
		{
			$showtime = $seconds . '秒前';
		} 
		else
		{
			$showtime = intval ( $minutes ) . '分钟前';
		}
	} 
	elseif ($cd == $d && $cm == $m && $cy == $y)
	{
		//$showtime = intval ( $minutes / 60 ) . '小时前';
		$showtime = '今天 ' . hg_get_format_date($date , 5);
	} 
	elseif ($cy == $y)
	{
		//$showtime = intval ( $minutes / 1440 ) . '天前';
		$showtime = hg_get_format_date ( $date, $method );
	}
	else
	{
		$showtime = hg_get_format_date ( $date, 3 );
	} 
	
	return $showtime;
}
/*
 * 求交集 差集 用于更新删除和新增数据
 */
function insert_update_delete($parameter1 = array(), $parameter2 = array(), $para_type="array", $spliter = ',' ,$return_type = 'array')
{
	if($para_type != 'array')
	{
		$parameter1 = $parameter1 ? explode($spliter, $parameter1) : array();
		$parameter2 = $parameter2 ? explode($spliter, $parameter2) : array();
	}
	$result = array();
	/*
	if(!is_array($parameter1) || empty($parameter11))
	{
		return $result;
	}
	if(!is_array($parameter2) || empty($parameter12))
	{
		return $result;
	}
	*/
	$insert = array_diff($parameter1, $parameter2);
	$update = array_intersect($parameter1, $parameter2);
	$delete = array_diff($parameter2, $parameter1);
	if($insert)
	{
		$result['insert'] = $insert;
	}
	if($update)
	{
		$result['update'] = $update;
	}
	if($delete)
	{
		$result['delete'] = $delete;
	}
	if($return_type != 'array' && $result)
	{
		foreach ($result as $item=>$value)
		{
			$result[$item] = implode(',', $value);
		}
	}
	return $result;
}

function hg_split_url($url)
{
	$a = array();
	if(!$url)
	{
		return false;
	}
	$urlarr = explode('/',$url);
	if(!$urlarr)
	{
		return false;
	}
	$nurlarr = array_reverse($urlarr);
	$filename = $nurlarr[0];
	$filearr = explode('.',$filename);
	$a['file'] = $filearr[0];
	$a['suffix'] = $filearr[1];
	$a['filedir'] = rtrim(str_replace($a['file'].'.'.$a['suffix'],'',$url),'/');
	return $a;
}
?>