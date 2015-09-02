<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: functions_ui.php 7899 2014-09-23 09:48:31Z jiyuting $
***************************************************************************/

/**
 * 在 Head 加载 JavaScript 和 CSS
 *
 * @param string $type js 表示 JavaScript 文件, css 表示 CSS 文件, js-c 表示 JS 脚本
 */
function hg_add_head_element($type = 'js', $filename = '',$extra = '')
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
				$sReturn .= "  <link ".$extra."type=\"text/css\" rel=\"stylesheet\" href=\"" . $filename . $rand . "\" />\n";
				break;
			case 'js-c' :
				$sReturn .= "  <script type=\"text/javascript\">\n//<![CDATA[\n" . $filename . "\n//]]>\n  </script>\n";
				break;
		}
	} 
	else
	{
		$return = $sReturn;
		$sReturn = '';
		return $return;
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
	} 
	else
	{
		$return = $sReturn;
		$sReturn = '';
		return $return;
	}
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
	$timeoptions = array (1 => 'Y-m-d', 2 => 'Y-m-d H:i:s', 3 => 'Y年m月d日  H:i', 4 => 'm月d日 H:i', 5 => 'H:i', 6 => 'm-d', 7 => 'Y年n月j日' );
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
function hg_build_pagelinks($data = array(),$pageno = 7,$firstpage = "|<",$lastpage = ">|",$pre = "<<",$next = ">>", $pagenumtag = '<a id="%s" href="%s" %s title="%s" class="page_bur">%s</a>',$curpagenumtag = '%s')
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
	if ($results['total_page'] < 1) 
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
			$prevlink = "<span class='page_next'><a id='prepage_".$start."' href='".hg_build_href_link($pagelink)."' {$onclick}  title='上一页'>".$pre."</a></span>";
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
			$nextlink = "<span class='page_next'><a id='nextpage_".$start."' href='".hg_build_href_link($pagelink)."' {$onclick} title='下一页'>".$next."</a></span>";
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
				//$curpage .=  "<span id=\"pagelink_{$pagenumber}\"><a href=\"{$urls}\" id=\"page_{$numberid}\"  title=\"{$pagenumber}\" {$onclick} class='pages_current'>{$pagenumber}</a></span>";
				$curpage .=  "<span id=\"pagelink_{$pagenumber}\" class=\"page_cur\">{$pagenumber}</span>";
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
				$curpage .= "<span id=\"pagelink_{$pagenumber}\">{$pagelink}</span>";
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

        /**扩展每页条数 start**/
        /****一并修改了program.class.php中： 对referto进行了urlencode处理 ***/
		$changeCountConfig = array(20, 40, 60, 80, 100);
        $changeCountHtml = '';
        foreach($changeCountConfig as $_count){
            $selected = $_count == $data['perpage'] ? 'selected' : '';
            if(preg_match('/(?:\&|\?)count=\d+/', $data['pagelink'])){
                $dataLink = preg_replace($data['pagelink'], '/(?:\&|\?)(count=)\d+/', '${1}' . $_count);
            }else{
                $dataLink = $data['pagelink'] . (substr($data['pagelink'], -1) == '&' ? '' : '&') .'count=' . $_count;
            }
            $_count = '每页' . $_count . '条';
            $changeCountHtml .= '<option value="' . $dataLink . '" ' . $selected . '>' . $_count . '</option>';
        }
        $changeCountHtml = '<select style="float:left;vertical-align:middle;margin:0 5px;" onchange="location.href=this.value;">' . $changeCountHtml . '</select>';
        /**扩展每页条数 end**/

		//$showpages_return_str = '<div align="center" class="hoge_page"><span class="page_all">共' . $showpages_return['showtotal_page'] . '页/计' . $data['totalpages'] . '条</span>' . $changeCountHtml . '<span class="page_next">' . $showpages_return['showfirstpage'] . '</span>' . $showpages_return['showprepage'] . '' . $showpages_return['showpage_nums'] . '' . $showpages_return['shownextpage'] . '<span class="page_next">' . $showpages_return['showlastpage'].'</span></div>';

        if($results['total_page']==1) 
        {
        	$showpages_return_str = '<div align="center" class="hoge_page"><span class="page_all">共' . $showpages_return['showtotal_page'] . '页/计' . $data['totalpages'] . '条</span>' . $changeCountHtml . '</div>';
        }
        elseif($results['total_page']!=1)
        {
        	$showpages_return_str = '<div align="center" class="hoge_page"><span class="page_all">共' . $showpages_return['showtotal_page'] . '页/计' . $data['totalpages'] . '条</span>' . $changeCountHtml . '<span class="page_next">' . $showpages_return['showfirstpage'] . '</span>' . $showpages_return['showprepage'] . '' . $showpages_return['showpage_nums'] . '' . $showpages_return['shownextpage'] . '<span class="page_next">' . $showpages_return['showlastpage'].'</span></div>';
        }


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

function hg_bulid_img($data,$width=0,$height=0)
{
	if(!empty($data))
	{
		$size = '';
		if($width)
		{
			$size = ($height ? $width . "x" . $height : $width . "x") . '/';
		}
		return $img = $data['host'] . $data['dir'] . $size . $data['filepath'] . $data['filename'] . '?' . TIMENOW;
	}
	return "";
}
function create_rgb_color($weight)
{
	$weight = 100 - $weight;
	$rgb = array(255, $weight * 2, $weight);
	return 'rgb(' . implode(',', $rgb) .')';
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
?>