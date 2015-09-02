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
//配合array_filter使用,清空所有数组非数字值
function clean_array_num($v)
{
	if(is_numeric($v))return true;
	return false;
}

 function is_serialized( $data, $strict = true ) {
	if ( ! is_string( $data ) )
		return false;
	$data = trim( $data );
 	if ( 'N;' == $data )
		return true;
	$length = strlen( $data );
	if ( $length < 4 )
		return false;
	if ( ':' !== $data[1] )
		return false;
	if ( $strict ) {
		$lastc = $data[ $length - 1 ];
		if ( ';' !== $lastc && '}' !== $lastc )
			return false;
	} else {
		$semicolon = strpos( $data, ';' );
		$brace     = strpos( $data, '}' );
		if ( false === $semicolon && false === $brace )
			return false;
		if ( false !== $semicolon && $semicolon < 3 )
			return false;
		if ( false !== $brace && $brace < 4 )
			return false;
	}
	$token = $data[0];
	switch ( $token ) {
		case 's' :
			if ( $strict ) {
				if ( '"' !== $data[ $length - 2 ] )
					return false;
			} elseif ( false === strpos( $data, '"' ) ) {
				return false;
			}
		case 'a' :
		case 'O' :
			return (bool) preg_match( "/^{$token}:[0-9]+:/s", $data );
		case 'b' :
		case 'i' :
		case 'd' :
			$end = $strict ? '$' : '';
			return (bool) preg_match( "/^{$token}:[0-9.E-]+;$end/", $data );
	}
	return false;
}

function is_serialized_string( $data ) {
	if ( !is_string( $data ) )
		return false;
	$data = trim( $data );
	$length = strlen( $data );
	if ( $length < 4 )
		return false;
	elseif ( ':' !== $data[1] )
		return false;
	elseif ( ';' !== $data[$length-1] )
		return false;
	elseif ( $data[0] !== 's' )
		return false;
	elseif ( '"' !== $data[$length-2] )
		return false;
	else
		return true;
}

function maybe_serialize( $data ) {
	if ( is_array( $data ) || is_object( $data ) )
		return serialize( $data );
	if ( is_serialized( $data, false ) )
		return serialize( $data );
	return $data;
}

function maybe_unserialize( $original ) {
	if ( is_serialized( $original ) ) 
		return @unserialize( $original );
	return $original;
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

//json数据key不带引号转换成带引号的并解析
function ex_json_decode($json, $mode=false) {
	if(!$json) 
	{
		return false;
	}
    $find = array('attributes','m_name','mileage','tst_name','full_price','max_tickets','bus_code','sst_name','driving_time','plan_time','available_tickets','dst_name');
    $replace = array('"attributes"','"m_name"','"mileage"','"tst_name"','"full_price"','"max_tickets"','"bus_code"','"sst_name"','"driving_time"','"plan_time"','"available_tickets"','"dst_name"');
    $json=str_replace($find,$replace,$json);
	//if(preg_match('/\w:/', $json))
    //$json = preg_replace('/(\w+)\s{0,}:/is', '"$1":', $json);
    return json_decode($json, $mode);
}

	/**
	 *
	 * 编目前缀,添加或者删除 ...
	 * @param string $data
	 */
	function catalog_prefix($field,$prefix='add')
	{
		//为编目标识添加前缀catalog开始
		if($prefix=='add')
		{
			if(strcasecmp($field, 'catalog')==0)
			{
				$data=CATALOG_PREFIX.$field;
			}
			if (stripos($field, 'catalog') === false)
			{
				$data=CATALOG_PREFIX.$field;
			}
		}
		elseif($prefix=='del')
		{
			$data=trim(str_ireplace(CATALOG_PREFIX,'',$field));//去掉前缀
		}
		//为编目标识添加前缀catalog结束
		return $data;
	}
	
	//----------------------------------------------------------------------------------- 
// 函数名：CheckLengthBetween($C_char, $I_len1, $I_len2=100) 
// 作 用：判断是否为指定长度内字符串 
// 参 数：$C_char（待检测的字符串） 
// $I_len1 （目标字符串长度的下限） 
// $I_len2 （目标字符串长度的上限） 
// 返回值：布尔值 
// 备 注：无 
//----------------------------------------------------------------------------------- 
function CheckLengthBetween($C_cahr, $I_len1, $I_len2=100) 
{ 
	$C_cahr = trim($C_cahr); 
	if (strlen($C_cahr) < $I_len1) return false; 
	if (strlen($C_cahr) > $I_len2) return false; 
	return true; 
} 
	