<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: functions.php 1621 2011-01-08 06:44:18Z repheal $
***************************************************************************/

/**
 * 载入模板
 *
 * @param string $template 模板名
 * @param string $tpl_dir 模板文件目录
 * @return string 模板缓存文件路径
 */
function hg_load_template($template, $tpl_dir = './tpl/')
{	
	$template_file = $template . '.tpl.php';
	$tpl_dir = TEMPLATES_DIR;
	if (DEVELOP_MODE)
	{
		return $tpl_dir . $template_file;
	}
	$cache_template_file = md5 ( $template_file . realpath ( $tpl_dir ) ) . '.php';
	$template_cache_file = CACHE_DIR . 'tpl/' . $cache_template_file;
	if (! is_file ( $template_cache_file ))
	{
		include_once ('./lib/template.class.php');
		$template = new Template ();
		$template->ParseTemplate ( $template_file );
	}
	return $template_cache_file;
}
/**
 * 在 Head 加载 JavaScript 和 CSS
 *
 * @param string $type js 表示 JavaScript 文件, css 表示 CSS 文件, js-c 表示 JS 脚本
 */
function hg_add_head_element($type = 'js', $filename = '')
{
	
	static $sReturn = '';
	$rand = '';
	if ($type != 'echo')
	{
		if (empty ( $filename ))
		{
			return;
		}
		switch ($type) {
			case 'js' :
				$sReturn .= "  <script type=\"text/javascript\" src=\"" . $filename . $rand . "\"></script>\n";
				break;
			case 'css' :
				$sReturn .= "  <link type=\"text/css\" rel=\"stylesheet\" href=\"" . $filename . $rand . "\" />\n";
				break;
			case 'js-c' :
				$sReturn .= "  <script type=\"text/javascript\">\n//<![CDATA[\n" . $filename . "\n//]]>\n  </script>\n";
				break;
		}
	} else
	{
		return $sReturn;
	}
}

/**
 * 在 foot 加载 JavaScript 和 CSS
 *
 * @param string $type js 表示 JavaScript 文件, css 表示 CSS 文件, js-c 表示 JS 脚本
 */
function hg_add_foot_element($type = 'js', $filename = '')
{
	static $sReturn = '';
	if ($type != 'echo')
	{
		if (empty ( $filename ))
		{
			return;
		}
		
		switch ($type) {
			case 'js' :
				$sReturn .= "  <script type=\"text/javascript\" src=\"" . $filename . "\"></script>\n";
				break;
			case 'css' :
				$sReturn .= "  <link type=\"text/css\" rel=\"stylesheet\" href=\"" . $filename . "\" />\n";
				break;
			case 'js-c' :
				$sReturn .= "  <script type=\"text/javascript\">\n//<![CDATA[\n" . $filename . "\n//]]>\n  </script>\n";
				break;
			case 'js-d' :
				$sReturn .= "  <script type=\"text/javascript\" defer='defer'>\n//<![CDATA[\n" . $filename . "\n//]]>\n  </script>\n";
				break;
		}
	} else
	{
		return $sReturn;
	}
}

function hg_get_time_offset()
{
	$r = 0;
	$timezoneoffset = TIMEZONEOFFSET;
	;
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
	$timeoptions = array (1 => 'Y-m-d', 2 => 'Y-m-d H:i:s', 3 => 'Y-m-d H:i', 4 => 'm-d H:i', 5 => 'H:i', 6 => 'm-d', 7 => 'Y年n月j日' );
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
function hg_get_date($date = 0, $method = 4, $type = 0)
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
	
	if ($minutes < 60)
	{
		if ($minutes < 1)
		{
			if ($seconds <= 10)
			{
				$showtime = '刚刚';
			} else
				$showtime = $seconds . '秒前';
		} else
		{
			$showtime = intval ( $minutes ) . '分钟前';
		}
	} 
	elseif ($minutes < 1440)
	{
		$showtime = intval ( $minutes / 60 ) . '小时前';
	} elseif ($minutes < 14400)
	{
		$showtime = intval ( $minutes / 1440 ) . '天前';
	} else
	{
		$showtime = hg_get_format_date ( $date, $method );
	}
	
	return $showtime;
}

/**
 * 设置cookie
 * @param $name cookie名
 * @param $value cookie值
 * @param $cookiedate cookie期限
 * @return unknown_type
 */
function hg_set_cookie($name, $value = '', $cookiedate = 0)
{
	global $gGlobalConfig;
	$expires = ($cookiedate > 0) ? TIMENOW + $cookiedate : 0;
	$gGlobalConfig ['cookie_domain'] = $gGlobalConfig ['cookie_domain'] == '' ? '' : $gGlobalConfig ['cookiedomain'];
	
	$gGlobalConfig ['cookie_path'] = $gGlobalConfig ['cookie_path'] == '' ? '/' : $gGlobalConfig ['cookie_path'];
	
	$name = $gGlobalConfig ['cookie_prefix'] . $name;
	$value = rawurlencode ( $value );
	setcookie ( $name, $value, $expires, $gGlobalConfig ['cookie_path'], $gGlobalConfig['cookie_domain'] );
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
				$names = '<a href="'.SNS_UCENTER.USER_URL.'?name='.$value.'">'.$nameH.'</a>';
				$text = str_replace($nameH,$names,$text);
			}		
		}
					
		$pattern = "/#([\x{4e00}-\x{9fa5}0-9A-Za-z_-‘’“”'\"]+)[\s#]/iu";
		 //这里牵扯到话题规则问题
		if(preg_match_all($pattern,$text,$topic))
		{
			foreach ($topic[1] as $key => $value)
			{
				$nameH = '#'.$value.'#';
				$names = '<a href="'.SNS_MBLOG.TOPIC_URL.'?q='.$value.'">'.$nameH.'</a>';
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
		foreach($faces as $fk => $fv)
		{
			$f_pattern = "/\:em".$fk."_(.*)\:/Ui";
			preg_match_all($f_pattern,$text,$show);
			$facelist = hg_readdir($fv['dir']);
			if($show[0])
			{
				foreach($show[1] as $key => $value)
				{
					$face[] = '<img alt="" src="'.$fv['url'].$facelist[$value].'"/>';
				}
				
				foreach($show[0] as $k=>$v)
				{
					$keys[] =  "/".$v."/";
				}
				$text = preg_replace($keys, $face,$text);
			}
		}
		
		return $text;
	}
	
/**
 * 
	$data['totalpages']   = $total['total'];
	$data['perpage'] = $perpage;
	$data['curpage'] = intval($_INPUT['pp']);
	$showpages = hg_build_pagelinks($data);
 *
 * @param unknown_type $data
 * @param unknown_type $pageno
 * @param unknown_type $firstpage
 * @param unknown_type $lastpage
 * @param unknown_type $pre
 * @param unknown_type $next
 * @param unknown_type $pagenumtag
 * @param unknown_type $curpagenumtag
 * @return unknown
 * @param string type $data['click'] eg $data['onclick'] = 'onclick="return loadingPage(\'module=bulletin&pp=%s\', \'正在加载留言页面\',\'bulletin_content\');"';
 */
function hg_build_pagelinks($data = array(),$pageno = 5,$firstpage = "|<",$lastpage = ">|",$pre = "<<",$next = ">>", $pagenumtag = '<a id="%s" href="%s" %s title="%s">%s</a>',$curpagenumtag = '%s')
{
	if (!trim($pagenumtag))
	{
		$pagenumtag = '<a id="%s" href="%s" %s title="%s">%s</a>';
	}
	if (!trim($curpagenumtag))
	{
		$curpagenumtag = '%s';
	}
    $results['pages'] = ceil( $data['totalpages'] / $data['perpage'] );		
	$results['total_page'] = $results['pages'] ? $results['pages'] : 1;
	$results['current_page'] = $data['curpage'] > 0 ? intval($data['curpage'] / $data['perpage']) + 1 : 1;
	$prevlink = "";
	$nextlink = "";		
	$showpages_return = array();
	if ($results['total_page'] <= 1) 
	{
		return '';
	}
	else 
	{		
		$data['pagelink'] = str_replace('?&amp;', '?', $data['pagelink']);

		$last_char = substr($data['pagelink'],-1);		
		switch ($last_char) 
		{
			case '':
				$data['pagelink'] .= $_SERVER['PHP_SELF'].'?';
				break;	
			case '?':
				break;		
			default:
				$data['pagelink'] .= '&';
				break;
		}

		$formatpagelink = $data['pagelink']."pp=";
		$showpages_return['showtotal_page'] = $results['total_page'];
		if ( $results['current_page'] > 1 ) 
		{
			$start = $data['curpage'] - $data['perpage'];
			$pagelink = $formatpagelink.$start;
			
			if($data['onclick'])
			{
				$onclick = sprintf($data['onclick'],$start);
				$pagelink = 'javascript:void(0);';
			}
			$prevlink = "<li class='p_p'><a id='prepage_".$start."' href='".hg_build_href_link($pagelink)."' {$onclick}  title='上一页'>".$pre."</a></li>";
			$showpages_return['showprepage'] = $prevlink;
		}
		if ( $results['current_page'] < $results['total_page'] ) 
		{
			$start = $data['curpage'] + $data['perpage'];
			$pagelink = $formatpagelink.$start;
			if($data['onclick'])
			{
				$onclick = sprintf($data['onclick'],$start);
				$pagelink = 'javascript:void(0);';
			}
			$nextlink = "<li class='p_n'><a id='nextpage_".$start."' href='".hg_build_href_link($pagelink)."' {$onclick} title='下一页'>".$next."</a></li>";
			$showpages_return['shownextpage'] = $nextlink;
		}
		$minpage = intval(ceil($results['current_page'] - $pageno/2)); 
		$maxpage = floor($results['current_page'] + $pageno/2);   
		$minpage = $minpage < 0 ? 0 : $minpage;
		$maxpage = $minpage?$maxpage:$pageno; 
		$maxpage = $maxpage > $results['total_page'] ? $results['total_page'] : $maxpage;
		$minpage = $maxpage == $pageno ? 0 : $minpage; 
		$minpage = ($maxpage == $maxpage && $results['total_page'] > $pageno)?$maxpage-$pageno:$minpage; 
		for( $i = $minpage; $i < $maxpage; ++$i) 
		{
			$numberid = $i * $data['perpage'];
			if($data['onclick'])
			{
				$onclick = sprintf($data['onclick'],$numberid); 
			}
			$pagenumber = $i+1;
			if ($numberid == $data['curpage']) 
			{
				$pagenumber = sprintf($curpagenumtag,$pagenumber);
				$onclick = sprintf($data['onclick'],$numberid); 
				$urls = "#";
				if($onclick)
				{
					$urls = 'javascript:void(0);';
				}
				$curpage .=  "<li id=\"pagelink_{$pagenumber}\"><a href=\"{$urls}\" id=\"page_{$numberid}\" title=\"{$pagenumber}\" {$onclick} class='pages_current'>{$pagenumber}</a></li>";
			}
			else 
			{
				if (1) 
				{
					$url = $formatpagelink."0"; 
					
					if($data['onclick'])
					{
						$zero= 0;
						$onclick = sprintf($data['onclick'],$zero); 
						$url = 'javascript:void(0);';
					}
					$firstlink = "<a id='firstpage_".$zero."' href='".hg_build_href_link($url)."' {$onclick} title='第一页'>$firstpage</a>";
					$showpages_return['showfirstpage'] = $firstlink;
				}
				if (1) 
				{
					$url = $formatpagelink.($results['total_page']-1) * $data['perpage'];
					if($data['onclick'])
					{
						$last = ($results['total_page']-1) * $data['perpage'];
						$onclick = sprintf($data['onclick'],$last); 
						$url = 'javascript:void(0);';
					}
					$lastlink = "<a id='lastpage_".$last."' href='".hg_build_href_link($url)."' {$onclick}  title='最后一页'>".$lastpage."</a>";
					$showpages_return['showlastpage'] = $lastlink;
				}
				$onclick = sprintf($data['onclick'],$numberid); 
				$pagelink = $formatpagelink.$numberid;
				if($onclick)
				{
					$pagelink = 'javascript:void(0);';
				}
				$pagelink = sprintf($pagenumtag,"page_".$numberid,hg_build_href_link($pagelink),$onclick, $pagenumber, $pagenumber);
				$curpage .= "<li id=\"pagelink_{$pagenumber}\">{$pagelink}</li>";
			}
		}
				
		if($results['current_page'] == 1)
		{
			unset($showpages_return['showfirstpage']);
		}
		else if($results['current_page'] == $results['total_page'])
		{
			unset($showpages_return['showlastpage']);
		}
		$showpages_return['showpage_nums'] = $curpage;
		$showpages_return_str = '<div class="pagelink"><ul class="pages"><li class="page_total"><a href="#" style="cursor:default;" onclick="return false;" >共' . $showpages_return['showtotal_page'] . '页/计' . $data['totalpages'] . '条</a></li><li class="p_f">' . $showpages_return['showfirstpage'] . '</li>' . $showpages_return['showprepage'] . '' . $showpages_return['showpage_nums'] . '' . $showpages_return['shownextpage'] . '<li class="p_l">' . $showpages_return['showlastpage'].'</li></ul></div>';

		return $showpages_return_str;
	}
}	

function hg_build_href_link($link)
{
	return $link;
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

function hg_match_red($text,$keywords)
{
	$keywords = str_replace("/", "\/", $keywords);
	$ti = '<span style="color:red;">'.$keywords.'</span>';
	$keyP = "/($keywords)/siu";
	$match = hg_match_links($text);
	$match = $match['all'];
	if($match)
	{
		foreach($match as $key =>$value)
		{
			$key = "key_".$key;
			$text = str_replace($value,$key,$text);
		}	
		$text = preg_replace($keyP,$ti,$text);
		foreach($match as $key =>$value)
		{
			$key = "key_".$key;
			$text = str_replace($key,$value,$text);
		}
	}
	else
	{
		$text = preg_replace($keyP,$ti,$text);
	}
	return $text;
}

function hg_show_null($title,$text,$type=0)
{
	if(!$title)
	{
		$title = 'sorry!!!';
	}
	$url = $_SERVER['HTTP_REFERER'];
	include hg_load_template('null');
}

//获取相册是图片
function fetch_picture_path($arr, $size = PHOTO_SIZE2,$type = 'space/')
{
	echo 'test';
	global $gTopicConfig;
	
	return $gTopicConfig['topic_upload_url'] .$type. $size.'/' . $arr['cover_file_name'];
	
}


?>