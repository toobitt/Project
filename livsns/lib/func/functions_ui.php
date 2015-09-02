<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: functions_ui.php 16316 2013-01-05 01:52:52Z develop_tong $
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
		$ret = $sReturn;
		$sReturn = '';
		return $ret;
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
		$ret = $sReturn;
		$sReturn = '';
		return $ret;
	}
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
	$gGlobalConfig ['cookie_domain'] = $gGlobalConfig ['cookie_domain'] == '' ? '' : $gGlobalConfig ['cookie_domain'];
	
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
 * @param unknown_type $total_pos 总数的位置，1最后，0默认在前
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
function hg_build_pagelinks($data = array(),$total_pos = 0,$pageno = 5,$firstpage = "|<",$lastpage = ">|",$pre = "<<",$next = ">>", $pagenumtag = '<a id="%s" href="%s" %s title="%s">%s</a>',$curpagenumtag = '%s')
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
		if($total_pos)
		{
			$showpages_return_str = '<div class="pagelink"><ul class="pages"><li class="p_f">' . $showpages_return['showfirstpage'] . '</li>' . $showpages_return['showprepage'] . '' . $showpages_return['showpage_nums'] . '' . $showpages_return['shownextpage'] . '<li class="p_l">' . $showpages_return['showlastpage'].'</li><li class="page_total"><a href="#" style="cursor:default;" onclick="return false;" >共' . $showpages_return['showtotal_page'] . '页/计' . $data['totalpages'] . '条</a></li></ul></div>';
		}
		else 
		{
			$showpages_return_str = '<div class="pagelink"><ul class="pages"><li class="page_total"><a href="#" style="cursor:default;" onclick="return false;" >共' . $showpages_return['showtotal_page'] . '页/计' . $data['totalpages'] . '条</a></li><li class="p_f">' . $showpages_return['showfirstpage'] . '</li>' . $showpages_return['showprepage'] . '' . $showpages_return['showpage_nums'] . '' . $showpages_return['shownextpage'] . '<li class="p_l">' . $showpages_return['showlastpage'].'</li></ul></div>';
		}
		

		return $showpages_return_str;
	}
}	


function hg_build_href_link($link)
{
	return $link;
}


function hg_tags($text,$keywords,$extra = "search.php")
{
	$text = str_replace("，",",",$text);
	$arr = explode(",",$text);
	$text = "";
	$link = SNS_VIDEO.$extra."?k=";
	foreach($arr as $key => $value)
	{
		$text .= '<a href="'.$link.$value.'">'.$value.'</a>';
	}

	if(!$keywords)
	{
		$html = $text;
	}
	else 
	{
		$keywords = str_replace("/", "\/", $keywords);
		$ti = '<span style="color:red;">'.$keywords.'</span>';
		$keyP = "/($keywords)/siu";
		$matchs = hg_match_links($text);
		$arr_link = $matchs['link'];
		$arr_text = $matchs['content'];
	
		$match = $matchs['all'];
		if($match)
		{
			foreach($match as $key =>$value)
			{
				$key = "key_".$key;
				$text = str_replace($value,$key,$text);
			}	
			$texts = preg_replace($keyP,$ti,$arr_text);
	
			$html = "";
			$space ="";
			foreach($texts as $key =>$value)
			{
				$html .= $space.'<a href="'.$arr_link[$key].'">'.$value.'</a>';
				$space = ",";
			}
		}
	}
	return $html;
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
	$html = '<div class="error">
	<h2>' . $title . '</h2>
	<p>
		<img align="absmiddle" src="' . RESOURCE_DIR . 'img/error.gif" alt="" title="">' . $text;
	if(!$type)
	{
		$html .= '<a href="' . $url . '">返回上一页！</a>';	
	}
	$html .='</p></div>';
	return $html;
}

function hg_null_search($search_content)
{
	$html = '<div class="noinfo">
				<div class="hl-link">
					<dl>
						<dt>
							抱歉没有找到
							<span>' . $search_content . '</span>
							相关的结果
						</dt>
						<dd class="return_page">
							<a class="f14bbule" href="' . $_SERVER['HTTP_REFERER'] . '">返回上一页！</a>
						</dd>
						<dd class="pl150">
							<a class="f18bule" href="http://www.hoolo.tv/">www.hoolo.tv</a>
						</dd>
					</dl>
				</div>
			</div>';
	return $html;
	
}


function hg_move_face($text)
{
	global $gGlobalConfig;
	$faces = $gGlobalConfig['smile_face'];
	foreach($faces as $fk => $fv)
	{
		$f_pattern = "/\:em".$fk."_(.*)\:/Ui";
		preg_match_all($f_pattern,$text,$show);
		if($show[0])
		{
			foreach($show[1] as $key => $value)
			{
				$facename = "e".(99+$value).".gif";
				$face[] = '';
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



//获取相册是图片
function fetch_picture_path($arr, $size = PHOTO_SIZE2,$type = 'space/')
{
	global $gTopicConfig;
	
	return $gTopicConfig['topic_upload_url'] .$type. $size.'/' . $arr['cover_file_name'];
	
}

/**
 * 获取广告
 * @param $code
 */
function hg_advert($code)
{
	global $gApiConfig;
	include_once (ROOT_PATH . 'lib/class/curl.class.php');
	$curl = new curl($gApiConfig['host'], $gApiConfig['apidir']);
	$curl->setSubmitType('post');
	$curl->setReturnFormat('json');
	$curl->initPostData();
	$curl->addRequestData('a', 'get');
	$curl->addRequestData('mark', $code);
	$ret = $curl->request('video/advert.php');
	$advert = $ret[0];
	
	$content = '';
	if(trim($advert['mark']) == $code)
	{
		$content = $advert['content'];
	}
	return $content;
}

?>