<?php
function seekhelp_clean_value($val = '')
{
	$pregfind = array('&#60;&#33;--', '--&#62;', '&gt;', '&lt;', '&quot;', '&#33;', '&#39;', "\n", '&#036;', '');
	$pregreplace = array('<!--', '-->', '>', '<', '"', '!', "'", "\n", '$', "\r");
	$val = str_replace($pregfind, $pregreplace, $val);
	return $val;
}

function trimall($str)//删除空格
{
	$qian=array(" ","　","\t","\n","\r");$hou=array("","","","","");
	return str_replace($qian,$hou,$str);
}

//配合array_filter使用,清空所有数组空value值
function clean_array_null($v)
{
	$v=trimall($v);
	if(!empty($v))return true;
	return false;
}

function hg_jw_square($myLat, $myLng, $distance)
{
	$range = 180 / pi() * $distance / 6372.797; //里面的 $distance 就代表搜索 $distance 之内，单位km  
	$lngR = $range / cos($myLat * pi() / 180);  
	//echo $range;exit()
	$maxLat = $myLat + $range;//最大纬度  
	$minLat = $myLat - $range;//最小纬度  
	$maxLng = $myLng + $lngR;//最大经度  
	$minLng = $myLng - $lngR;//最小经度
	$arr = array(
		'maxLat'	=> $maxLat,
		'minLat'	=> $minLat,
		'maxLng'	=> $maxLng,
		'minLng'	=> $minLng,
	);
	return $arr;
}

	/**
	 * IsMobile函数:检测参数的值是否为正确的中国手机号码格式
	 * 返回值:是正确的手机号码返回手机号码,不是返回false
	 */
	Function IsMobile($Argv){
		$RegExp='/^(?:13|14|15|17|18)[0-9]{9}$/';
		return preg_match($RegExp,$Argv)?$Argv:false;
	}

	/**
	 * IsTel函数:检测参数的值是否为正取的中国电话号码格式包括区号
	 * 返回值:是正确的电话号码返回电话号码,不是返回false
	 */
	Function IsTel($Argv){
		$RegExp='/[0-9]{3,4}-[0-9]{7,8}$/';
		return preg_match($RegExp,$Argv)?$Argv:false;
	}
	
	function daddslashes($string, $force = 0) {
	if(!$GLOBALS['magic_quotes_gpc'] || $force) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = daddslashes($val, $force);
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
