<?php

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
//配合array_filter使用,清空所有数组非数字值
function clean_array_num_max0($v)
{
	if(is_numeric($v)&&$v>0)return true;
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

function passport_encrypt($txt, $key) {
	srand((double)microtime() * 1000000);
	$encrypt_key = md5(rand(0, 32000));
	$ctr = 0;
	$tmp = '';
	for($i = 0;$i < strlen($txt); $i++) {
		$ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
		$tmp .= $encrypt_key[$ctr].($txt[$i] ^ $encrypt_key[$ctr++]);
	}
	return str_replace('=', '',base64_encode(passport_key($tmp, $key)));
}

function passport_decrypt($txt, $key) {
	$txt = passport_key(base64_decode($txt), $key);
	$tmp = '';
	for($i = 0;$i < strlen($txt); $i++) {
		$md5 = $txt[$i];
		$tmp .= $txt[++$i] ^ $md5;
	}
	return $tmp;
}

function passport_key($txt, $encrypt_key) {
	$encrypt_key = md5($encrypt_key);
	$ctr = 0;
	$tmp = '';
	for($i = 0; $i < strlen($txt); $i++) {
		$ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
		$tmp .= $txt[$i] ^ $encrypt_key[$ctr++];
	}
	return $tmp;
}

function random($length, $numeric = 0) {
	$seed = base_convert(md5(microtime().$_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
	$seed = $numeric ? (str_replace('0', '', $seed).'012340567890') : ($seed.'zZ'.strtoupper($seed));
	if($numeric) {
		$hash = '';
	} else {
		$hash = chr(rand(1, 26) + rand(0, 1) * 32 + 64);
		$length--;
	}
	$max = strlen($seed) - 1;
	for($i = 0; $i < $length; $i++) {
		$hash .= $seed{mt_rand(0, $max)};
	}
	return $hash;
}

function is_url($str){
	return preg_match("/^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"])*$/", $str);
}

function trimall($str)//删除空格
{
	$qian=array(" ","　","\t","\n","\r");$hou=array("","","","","");
	return str_replace($qian,$hou,$str);
}

function makesingleinput($arguments = array(), $extra = '')
{
	if (!empty($arguments['css']))
	{
		$class = "class=\"{$arguments['css']}\"";
	}
	if (!empty($arguments['onclick']))
	{
		$onclick = "onclick=\"{$arguments['onclick']}\"";
	}

	if (!empty($arguments['maxlength']))
	{
		$maxlength = "maxlength=\"{$arguments['maxlength']}\"";
	}
	if (!empty($arguments['size']))
	{
		$size = "size=\"{$arguments['size']}\"";
	}
	if (empty($arguments['type']))
	{
		$arguments['type'] = 'submit';
	}
	if (!empty($arguments['cols']))
	{
		$arguments['cols'] = 'cols=' . $arguments['cols'];
	}
	if (!empty($arguments['rows']))
	{
		$arguments['rows'] = 'rows=' . $arguments['rows'];
	}
	if (!empty($arguments['checked']))
	{
		$checked = ' checked ';
	}

	$object = "<input type=\"{$arguments['type']}\" name=\"{$arguments['name']}\"  value=\"{$arguments['value']}\" {$arguments['extra']} $class $maxlength $size $checked {$arguments['cols']}  {$arguments['rows']} $onclick />" . $extra;
	if(empty($arguments['showit']))
	{
		echo $object;
	}
	else
	{
		return $object;
	}
}
function makesingleyesno($arguments = array())
{
	if(!$arguments['option'])
	{
		$arguments['option'] = array('1'=>'是','0'=>'否');
	}

	$tmp = '';
	foreach($arguments['option'] as $key=>$val)
	{
		if($arguments['selected']==$key){
			$checked = " checked";
		}
		else
		$checked = "";
		$tmp .= "<input type=\"radio\" name=\"$arguments[name]\" value=\"$key\" $checked $arguments[extra]>$val";
	}
	return $tmp;
}

function makesinglecheckbox($arguments = array())
{
	$tmp = '';
	foreach($arguments['option'] as $key=>$val)
	{
		$checked = "";
		if (is_array($arguments['selected']))
		{
			if (in_array($key,$arguments['selected']))
			{
				$checked = ' checked';
			}
		}
		else
		{
			if ($arguments['selected'] == $key)
			$checked = ' checked';
		}
		$tmp .=  "   <input type=\"checkbox\" name=\"{$arguments['name']}\" value=\"$key\" $checked {$arguments['extra']} />$val";
	}
	return $tmp;
}

function makesingletextarea($arguments = array())
{
	if (empty($arguments['cols']))
	{
		$arguments['cols'] = 40;
	}
	if (empty($arguments['rows']))
	{
		$arguments['rows'] = 7;
	}
	if (!empty($arguments['html']))
	{
		$arguments['value'] = htmlspecialchars($arguments['value']);
	}
	if (!empty($arguments['css']))
	{
		$class = "class=\"{$arguments['css']}\"";
	}
	$object = "<textarea type=\"text\" name=\"{$arguments['name']}\" cols=\"{$arguments['cols']}\" rows=\"{$arguments['rows']}\" {$arguments['extra']} {$class}>{$arguments['value']}</textarea>";
	if(empty($arguments['showit']))
	{
		echo $object;
	}
	else
	{
		return $object;
	}
}

function makesingleselect($arguments = array())
{
	global $object;
	if ($arguments['html'] == 1)
	{
		$value = htmlspecialchars($value);
	}
	if ($arguments['multiple'] == 1)
	{
		$multiple = ' multiple';
		if ($arguments['size'] > 0)
		{
			$size = "size={$arguments['size']}";
		}
	}

	if (!empty($arguments['id']))
	{
		$id = "id='" . $arguments['id'] . "'";
	}
	$object= "<select $id name=\"{$arguments['name']}\" $multiple {$arguments['extra']} $size>\n";
	if (is_array($arguments['option']))
	{
		foreach ($arguments['option'] AS $key=>$value)
		{
			if($arguments['optgroup'][$key])
			{
				$object .= "<optgroup label=\"$value\">";
			}
			else

			{
				if (!is_array($arguments['selected']))
				{
					if ($arguments['selected']==$key)
					{
						$object .= "<option value=\"$key\" selected=\"selected\" class=\"{$arguments['css'][$key]}\">$value</option>\n";
					}
					else
					{
						$object .= "<option value=\"$key\" class=\"{$arguments['css'][$key]}\">$value</option>\n";
					}

				}
				elseif (is_array($arguments['selected']))
				{

					if (in_array($key, $arguments['selected']))
					{
						$object .= "<option value=\"$key\"  selected=\"selected\" class=\"{$arguments['css'][$key]}\">$value</option>\n";
					}
					else
					{
						$object .= "<option value=\"$key\" class=\"{$arguments['css'][$key]}\">$value</option>\n";
					}
				}
			}
			if($arguments['optgroup'][$key])
			{
				$object .= "</optgroup>";
			}

		}
	}

	$object .= "</select>";
	if(empty($arguments['showit']))
	{
		echo $object;
	}
	else
	{
		return $object;
	}
}

function makesingleuploadimg($arguments = array())
{
	if($arguments['value'])
	{
		$class = '';
		if (!empty($arguments['css']))
		{
			$class = " class=\"{$arguments['css']}\"";
		}
		$img = '<img src="'.$arguments['value'].'"'.$class.'>';
	}
	return $img."<input type=\"file\" name=\"{$arguments['name']}\" {$arguments['extra']}  />";
}

/**
 *
 * 数据库表单值转换为数组 ...
 * @param unknown_type $type
 * @param unknown_type $value
 */
function outPutFormat($type,$value,$params = array())
{
	if ($type=='checkbox'&&$value)
	{
		return explode("\n", $value);
	}
	elseif($type=='img'&&$value)
	{
		$re = maybe_unserialize($value);
		if($params[$type])
		{
			return hg_fetchimgurl($re);
		}
		return $re;
	}
	return $value;
}

function fileRever($files)
{
	$_files = array();
	foreach ((array)$files AS $name => $f_value)
	{
		foreach ($f_value as $keys => $values)
		{
			$_files[$keys][$name]=$values;
		}
	}
	return $_files;
}
?>