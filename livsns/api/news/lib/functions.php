<?php
/***************************************************************************
* LivCMS5.0
* (C)2004-2010 HOGE Software.
*
* $Id: functions.php 45997 2015-06-02 08:30:56Z develop_tong $
***************************************************************************/

/**
 * 编译一段字符串中的img标签的src
 * @param string $str 字符串
 * @return array $arr img的集合
 */
function hg_encode_img($str,$filepath)
{
	if(empty($str))
	{
		return false;
	}
	$pregfind = array('#<img[^>]+src=(\'|")(.+)(\\1).*>#siU');
	$pregreplace = array('[img]\2[/img]'); 
	$text = preg_replace($pregfind, $pregreplace, $str); 
	preg_match_all ('#\[img\](.+)\[\/img\]#siU',$text,$out);
	$arr = array(
		'content' => $text,
		'oImg' => $out[1],	
	);
	if(!empty($out[1]))
	{
		$imgs = $imgtype = $nName = array();
		foreach($out[1] as $k => $v)
		{
			$imgType[$k] = "." . end(explode('.', end(explode('/',$v))));
			$nName[$k] = md5(hg_generate_user_salt(5)) . $imgType[$k];
			$imgs[$k] = $filepath . $nName[$k];
		}
		$arr['nImg'] = $imgs;
		$arr['imgType'] = $imgType;
		$arr['nName'] = $nName;
		$arr['content'] = str_replace($out[1], $imgs, $text);
	}
	return $arr;
}

/**
 * 编译一段字符串中的img标签的src
 * @param string $str 字符串
 * @return array $arr img的集合
 */
function hg_decode_img($str)
{
	if(empty($str))
	{
		return false;
	}
	$pregfind = array('#\[img\](.+)\[\/img\]#siU');
	$pregreplace = array('<img src="\1" />');
	$text = preg_replace($pregfind, $pregreplace, $str); 
	return $text;
}

/**
* 将一段字符串中的远程图片本地化
* @param  $str sting 字符串
*/
function hg_format_img($str)
{
	if(empty($str))
	{
		return false;
	}
	preg_match_all ('/<img.*?src=([\'|\"]?)([^>\"\'\s]*)(\\1).*?[\/]?>/i',$str,$out);
	$arr = array();
	
	if(!empty($out[2]))
	{
		foreach($out[2] as $k => $v)
		{   
			$arr['imgs'][$k] = $v;
		}
		$text= str_replace($out[2], $arr['imgs'], $str);
		$pregfind=array(
			'/<img[^>]*width=\"(\d*)\"[^>]*height=\"(\d*)\"[^>]*src=([\"|\']?)([^>\"\'\s]*)(\\3)[^>]*[\/]?>/si',
			'/<img[^>]*height=\"(\d*)\"[^>]*width=\"(\d*)\"[^>]*src=([\"|\']?)([^>\"\'\s]*)(\\3)[^>]*[\/]?>/si',
			'/<img[^>]*src=([\"|\']?)([^>\"\'\s]*)(\\1)[^>]*width=\"(\d*)\"[^>]*height=\"(\d*)\"[^>]*[\/]?>/si',
			'/<img[^>]*src=([\"|\']?)([^>\"\'\s]*)(\\1)[^>]*height=\"(\d*)\"[^>]*width=\"(\d*)\"[^>]*[\/]?>/si',
			'/<img[^>]*style=\"[^\"]*width:(\d*)px;[^\"]*height:(\d*)px;[^\"]*\"[^>]*src=([\"|\']?)([^>\"\'\s]*)(\\3)[^>]*[\/]?>/si',
			'/<img[^>]*style=\"[^\"]*height:(\d*)px;[^\"]*width:(\d*)px;[^\"]*\"[^>]*src=([\"|\']?)([^>\"\'\s]*)(\\3)[^>]*[\/]?>/si',
			'/<img[^>]*src=([\"|\']?)([^>\"\'\s]*)(\\1)[^>]*style=\"[^\"]*width:(\d*)px;[^\"]*height:(\d*)px;[^\"]*\"[^>]*[\/]?>/si',
			'/<img[^>]*src=([\"|\']?)([^>\"\'\s]*)(\\1)[^>]*style=\"[^\"]*height:(\d*)px;[^\"]*width:(\d*)px;[^\"]*\"[^>]*[\/]?>/si',
			'/<img[^>]*src=([\"|\']?)([^>\"\'\s]*)(\\1)[^>]*[\/]?>/si',
	     );
	    $pregreplace = array(
			'[img id="\\4#" width="\\1" height="\\2"]\\4[/img]',
			'[img id="\\4#" width="\\2" height="\\1"]\\4[/img]',
			'[img id="\\2#" width="\\4" height="\\5"]\\2[/img]',
			'[img id="\\2#" width="\\5" height="\\4"]\\2[/img]',
			'[img id="\\4#" width="\\1" height="\\2"]\\4[/img]',
			'[img id="\\4#" width="\\2" height="\\1"]\\4[/img]',
			'[img id="\\2#" width="\\4" height="\\5"]\\2[/img]',
			'[img id="\\2#" width="\\5" height="\\4"]\\2[/img]',
			'[img id="\\2#" width="" height=""]\\2[/img]',
		); 
		$arr['content'] = preg_replace($pregfind, $pregreplace, $text); 
	}
	else
	{
		$arr['content']=$str;
	}
	return $arr;
}

//using
function hg_format_img_call($text,$data)
{
	if(empty($text) || empty($data))
	{
		return false;
	}
	
	preg_match_all('/\[img id=\"(.*?)\".*?\](.+?)\[\/img\]/i',$text,$out);

	if(!empty($out))
	{
		foreach($out[2] as $k => $v)
		{
			foreach($data['ori_url'] as $key => $value)
			{
				if($value == $v)
				{
					$new = array($data['id'][$key],$data['url'][$key]);
					$ori = array($v . '#',$v);
					$text = str_replace($ori,$new,$text);
				}
			}
		}
		return $text;
	}
}

//using
function hg_encode2($arr)
{
	if(empty($arr))
	{
		return false;
	}
	$pattern=array(
		'/<a.*?id=\"(\d*)\"\s*><img\s*width=\"(\d*)\"\s*height=\"(\d*)\"\s*src=\"([^>\"\'\s]*)\"\s*[\/]?><\/a>/i',
		'/<a\s*href=\"\.\/getfile\.php\?id=(\d*)\"\s*><img\s*src=\"([^>\"\'\s]*)\"\s*[\/]?><\/a>/i',
		'/<object.*id=\"(\d*)\"\s*>.*<embed\s*src=\"([^>\"\'\s]*)\"\s*width=\"(\d*)\"\s*height=\"(\d*)\".*>\s*<\/object>/i',
		);
	$replace=array(
		'[img id="\\1" width="\\2" height="\\3"]\\4[/img]',
		'[doc id="\\1" width="" height=""]\\2[/doc]',
		'[real id="\\1" width="\\3" height="\\4"]\\2[/real]',
		);

	$out=preg_replace($pattern,$replace,$arr);
	return $out;
}


//using
/**
* $arr string需要解析的字符串
* $thump_path array 缩略图绝对路径和url 
* $water_img string水印路径
* $position string 水印位置
*/
function hg_decode($arr)
{
	if(empty($arr))
	{
		return false;
	}
	//匹配插入文章中的附件id
	preg_match_all('/\[img id=\"(\d+)\" width=\"(\d*)\" height=\"(\d*)\"\](.+?)\[\/img\]/i',$arr,$out1);
	preg_match_all('/\[doc id=\"(\d+)\".*?\](.+?)\[\/doc\]/i',$arr,$out2);
	preg_match_all('/\[real id=\"(\d+)\".*?\](.+?)\[\/real\]/i',$arr,$out3);
    
	
	//模式数组
	$pattern=array(
		'/\[img id=\"(\d*)\" width=\"(\d*)\" height=\"(\d*)\"\](.+?)\[\/img\]/i',
		'/\[doc id=\"(\d*)\" width=\"(\d*)\" height=\"(\d*)\"\](.+?)\[\/doc\]/i',
		'/\[real id=\"(\d*)\" width=\"(\d*)\" height=\"(\d*)\"\](.+?)\[\/real\]/i',
		);
	$replace=array(
		'<a href="\\4" id="mat_\\1_img"><img width="\\2" height="\\3" src="\\4" /></a>',
		'<a href="./getfile.php?id=\\1"><img src="\\4" /></a>',
		'<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"  width="\\2" height="\\3" codebase="http://active.macromedia.com/flash6/cabs/swflash.cab#version=6.0.0.0" id="\\1">   
			<param name="movie" value="\\4" />   
			<param name="play" value="true" />   
			<param name="loop" value="true" />	
			<param name="WMode" value="Opaque" />   
			<param name="quality" value="high" />   
			<param name="bgcolor" value="" />   
			<param name="align" value="" />   <embed src="\\4" width="\\2" height="3" play="true" loop="true" wmode="Opaque" quality="high" bgcolor="" align="" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash">   </object>',
		);
		$out['content']=preg_replace($pattern,$replace,$arr);
		$out['id']=array_merge($out1[1],$out2[1],$out3[1]);
		preg_match_all('/\[img id=\"(\d+)\" width=\"(\d*)\" height=\"(\d*)\"\](.+?)\[\/img\]/i',$arr,$out1);
		$out['img_url']=$out1[4];
		return $out;
}

function hg_get_materialid($text)
{
	preg_match_all('/\[([a-z]+) id=\"(.+?)\".*?\](.+?)\[\/([a-z]+)\]/i',$text,$out);
	if(!empty($out[2]))
	{
		$material_id = $space = "";
		foreach($out[2] as $k => $v)
		{
			if(intval($v))
			{
				$material_id .= $space . intval($v);
				$space = ',';
			}
		}
		return $material_id ? $material_id : false;
	}
	return false;
}


/**
* 检测一段字符串中是否含有图片，视频，文档
* @arr string要检测的字符串
*
* @return $return array 检测后返回的信息
*/
function hg_check_content($content)
{
	if(empty($content))
	{
		return false;
	}
	$return = array('is_img' => 0, 'is_video' => 0, 'is_tuji' => 0, 'is_vote' => 0);
	if(preg_match('/<img[^>]* src=([\"|\']?)([^>\"\'\s]*)(\\1)[^>]*[\/]?>/i',$content))
	{
		$return['is_img'] = 1;
	}
	if(preg_match('/<img[^>]*class=\"image-refer\" src=([\"|\']?)([^>\"\'\s]*)sketch_map\/tuji([^>\"\'\s]*)(\\1)[^>]*[\/]?>/i',$content))
	{
		$return['is_tuji'] = 1;		
	}
	if(preg_match('/<img[^>]*class=\"image-refer\" src=([\"|\']?)([^>\"\'\s]*)sketch_map\/media_channel([^>\"\'\s]*)(\\1)[^>]*[\/]?>/i',$content))
	{
		$return['is_video'] = 1;		
	}
	if(preg_match('/<img[^>]*class=\"image-refer\" src=([\"|\']?)([^>\"\'\s]*)sketch_map\/vote([^>\"\'\s]*)(\\1)[^>]*[\/]?>/i',$content))
	{
		$return['is_vote'] = 1;		
	}
	return $return;
}


/**
*  加密一段字符串
*  @param $arr string 字符串
*  @return $arr array 加密后的信息集合
*/
function hg_encode_affix($arr,$path,$width=95,$height=95)
{
	if(empty($arr) || empty($path))
	{
		return false;
	}
	$filename=hg_getname($path);
    
	//声明一个正则表达式的模式数组,将传递给preg_replace()函数的第一个参数
	$pattern=array(
		'/<img.*src\s*=\s*([\"|\']?)\s*([^>\"\'\s]*\s*)(\\1).*[\/]?>/i', 
		'/<object[^>]*>(.*?)<\/object>/si',	 
		'/<embed.*src\s*=\s*([\"|\']?)\s*([^>\"\']*\s*)(\\1).*[\/]?>/i',
		'/<a.*href\s*=\s*([\"|\']?)\s*([^>\"\']*\s*)(\\1).*>(.*?)<\/a>/',
		);

   //声明一个替换数组，将传递给preg_replace()函数的第二个数组，与上面模式数组的内容相应
   $replace=array(
	   '[img width="' . $width . '" height="' . $height . '"]' . $path . '[/img]',
	   '[object width="' . $width .'" height="' . $height . '"]' . $path . '[/object]',
	   '[embed width="' . $width . '" height="' . $height . '"]' . $path . '[/embed]',
	   '[a name="' . $filename . '"]' . $path . '[/a]',
	   );
   $text=preg_replace($pattern,$replace,$arr);
   return $text;
}

/**
* 解密一段字符串
* @param $arr string 字符串
* @return $text string 解密后的字符串
*/
function hg_decode_affix($arr)
{
	if(empty($arr))
	{
		return false;
	}
	//声明一个模式数组,传递给preg_replace()函数的第一个参数
	$pattern=array(
		'/\[img.*width\s*=\s*([\'|\"]?)(\d+)(\\1)\s*height=(\\1)(\d+)(\\1)\](.+?)\[\/img\]/i',
		'/\[object.*width\s*=\s*([\'|\"]?)(\d+)(\\1)\s*height=(\\1)(\d+)(\\1)\](.+?)\[\/object\]/',
		'/\[embed.*width\s*=\s*([\'|\"]?)(\d+)(\\1)\s*height=(\\1)(\d+)(\\1)\](.+?)\[\/embed\]/',
		'/\[a.*name=\s*([\'|\"]?)(.+?)(\\1)\](.*?)\[\/a\]/',
		);    
	//声明一个替换数组，传递给preg_replace()函数的第二个参数
	$replace=array(
		'<img width="\\2" height="\\5" src="\\7" style="margin-top:5px;"/>',
		'<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
         codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="\\2" height="\\5" style="margin-top:5px;">   
                <param name=movie value="\\7">
                <param name=quality value=high>
                <param name=wmode value=transparent>
         </object>',
	     '<embed pluginspage="http://www.microsoft.com/Windows/Downloads/Contents/MediaPlayer/" src="\\7" width="\\2" height="\\5"  type="application/x-mplayer2" autorewind="true" showdisplay="false" showstatusbar="false" showcontrols="true" autostart="false" style="margin-top:5px;"/>',
		'<a href="\\4" target="_blank">\\2 下载</a>',
		);
	   $text=preg_replace($pattern,$replace,$arr);
	   return $text;
}


/**
*  获取栏目分类树
*  @name hg_gettree
*  @access public
*  @category hogesoft
*  @copyright hogesoft
*  @param refence $tree 更改后的分类树
*  @param array $arr 取得的分类树
*  @param int $pid 父级分类id
*  @param int $x 
*/
function hg_gettree(&$tree,$arr,$pid=0,$x=0,$level=2)
{
	if(is_array($arr))
	{
		for($i=0;$i<count($arr);$i++)
		{
			if($arr[$i]['pid']==$pid)
			{
				$arr[$i]['name'] = str_repeat('&nbsp;',$x*3).'|-- ' .$arr[$i]['name'];
				$arr[$i]['create_time']=date("Y-m-d H:i:s",$arr[$i]['create_time']);
				$arr[$i]['update_time']=date("Y-m-d H:i:s",$arr[$i]['update_time']);
				$tree[]=$arr[$i];
				if($x< $level)
				{
					hg_gettree($tree,$arr,$arr[$i]['id'],$x+1);
				}
			}
		}
	}
}



function addslashes_vars(&$vars)
{
	if (is_array($vars))
	{
		foreach ($vars as $k => $v)
		{
			addslashes_vars($vars[$k]);
		}
	}
	else if (is_string($vars))
	{
		$vars = addslashes($vars);
	}
}


function hg_page_decode($content)
{
	$content = html_entity_decode($content);
	preg_match_all ('/<style text="text\/css">(.+?)<\/style>/s',$content,$out);
	$pattern = array(
		$out[0][0]
	);
	$replace = array('');
	$text = str_replace($pattern, $replace, $content);	
	$pattern_content = '/<div class="paging">\s{0,}<\/div>/s';
	preg_match_all($pattern_content,$text,$out);
	$page = 0;
	if(!empty($out[0]))
	{
		$title_arr = $content_arr = $replace = $info = array();
		$tmp_arr = explode($out[0][0],$text);
		$page = count($out[0]);
		for($i=1; $i <= $page;$i++)
		{
			$content_arr[$i] = $tmp_arr[$i];
		}//获取正确的分页
		if(!empty($content_arr))
		{
			$pattern_title = '/<span class="page_title">(.+?)<\/span>/s';
			$out_put = array();
			foreach($content_arr as $k => $v)
			{
				preg_match($pattern_title,$v,$out_put);
				$title_arr[$k] = strip_tags($out_put[0]);
				$content_arr[$k] = str_replace($out_put[0], '', $v);
			}
		}
		$info = array(
				'title' => $title_arr,
				'content' => $content_arr,
			);
		return $info;
	}
	else
	{
		return false;
	}
}

function hg_encode_page($content,$page_title,$title)
{
	if(empty($page_title))
	{
		return $content;
	}
	else
	{
		$content_arr = explode('###&###',$content);
		$page_arr = explode(',',$page_title);
		foreach($page_arr as $k => $v)
		{
			$str .= '<div class="paging"></div>';
			if($v != $title)
			{
				$str .= '<span class="page_title">
				<!--
				StartFragment
				-->
				<strong>' . $v . '</strong>
				<!--
				EndFragment
				-->
				</span>' . $content_arr[$k];
			}
			$str .= '<br/>';
		}
		$str .= '<style text="text/css"><!--
	.paging{border-top:1px dashed red;cursor:pointer;height:5px;}.page_title{border:1px solid;display:block;max-width:350px;height:30px;}
	--></style>';
		return $str;
	}
}

function hg_daddslashes($string, $force = 0) {
	if(!$GLOBALS['magic_quotes_gpc'] || $force) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = hg_daddslashes($val, $force);
			}
		} else {
			//如果魔术引用开启或$force为0
			//下面是一个三元操作符，如果$strip为true则执行stripslashes去掉反斜线字符，再执行addslashes
			//$strip为true的，也就是先去掉反斜线字符再进行转义的为$_GET,$_POST,$_COOKIE和$_REQUEST $_REQUEST数组包含了前三个数组的值
			//这里为什么要将＄string先去掉反斜线再进行转义呢，因为有的时候$string有可能有两个反斜线，stripslashes是将多余的反斜线过滤掉
			$string = addslashes($strip ? dstripslashes($string) : $string);
		}
	}
	return $string;
}

function dstripslashes($string)
{
if(is_array($string))
{
foreach($string as $key => $val)
{
$string[$key] = dstripslashes($val);
}
}
else
{
$string = stripslashes($string);
}
return $string;
}
?>