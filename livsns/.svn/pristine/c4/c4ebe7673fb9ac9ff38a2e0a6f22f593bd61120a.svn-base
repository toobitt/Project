<?php
/***************************************************************************
 * LivCMS5.0
 * (C)2004-2010 HOGE Software.
 *
 * $Id: functions.php
 ***************************************************************************/
function hg_set_verifycode($length = 6, $chars = '0123456789')
{
	$salt = '';
	for ( $i = 0; $i < $length; $i++ )
	{
		$salt .= $chars[ mt_rand(0, strlen($chars) - 1) ];
	}
	return $salt;
}
/**
 *
 * 判断手机号码运营商
 * @param int $mobile
 */
function hg_checkOperators($_mobile){
	if(hg_verify_mobile($_mobile)){
		$special = array('134','170');
		$segment = substr($_mobile,0,3);
		in_array($segment,$special)&&($segment = substr($_mobile,0,4));
		$telecom = array(133,153,177,180,181,189,1700);
		$mobile = array(135,136,137,138,139,150,151,152,158,159,182,183,184,157,187,188,147,178,1340,1341,1342,1343,1344,1345,1346,1347,1348,1705);
		$unicom = array(130,131,132,155,156,145,185,186,176,1709);
		$Satellite = array(1349);
		if(in_array($segment, $mobile)){
			return 1;//移动（含向虚拟运营商专收号段170）
		}elseif (in_array($segment,$telecom)){
			return 2;//电信（含向虚拟运营商专收号段170）
		}elseif (in_array($segment, $unicom)){
			return 3;//联通（含向虚拟运营商专收号段170）
		}elseif(in_array($segment, $Satellite)){
			return 4;//卫星通讯
		}else {
			return -1;//未知
		}
	}
	return 0;//手机号码不正确
}

/**
 * hg_verify_mobile函数:检测参数的值是否为正确的中国手机号码格式
 * 返回值:是正确的手机号码返回手机号码,不是返回false
 */
Function hg_verify_mobile($Argv){
	$RegExp='/^(?:13|14|15|17|18)[0-9]{9}$/';
	return preg_match($RegExp,$Argv)?$Argv:false;
}

/**
 *
 * 隐藏手机号中间部分
 * @param string $mobile
 */
function hg_hide_mobile($mobile)
{
	if(hg_verify_mobile($mobile))
	{
		$pattern = "/(1\d{1,2})\d\d(\d{0,3})/";
		$replacement = "\$1****\$3";
		return  preg_replace($pattern, $replacement, $mobile);
	}
	return $mobile;
}

function hg_check_email_format($email)
{
	return strlen($email) > 6 && strlen($email) <= 128 && preg_match("/^([A-Za-z0-9\-_.+]+)@([A-Za-z0-9\-]+[.][A-Za-z0-9\-.]+)$/", $email);
}
function is_in_today($time = '')
{
	if(!$time)
	{
		return false;
	}
	return (boolean)($time > strtotime(date('Y-m-d')) && $time < strtotime(date('Y-m-d',strtotime('+1 day'))));
}
function hg_mermber2members_compatible($map = array(), $input = array(), $multi = true)
{
	global $_INPUT, $gGlobalConfig;

	if(!($_INPUT['version'] == CLIENT_VERSION))
	{
		return $input;
	}
	$data = array();
	if(!$multi)
	{
		$data[0] = $input;
	}
	if($data && is_array($map) && !empty($map))
	{
		foreach($data as $key=>$value)
		{
			foreach($map as $from=>$to)
			{
				$to_array = explode('|', $to);
				$to = $to_array[0];
				$config = $to_array[1];
				if($config)
				{
					$data[$key][$to] = $gGlobalConfig[$config][$data[$key][$from]];
				}
				else
				{
					$data[$key][$to] = $data[$key][$from];
				}
				unset($data[$key][$from]);
			}
		}
	}
	return $multi ? $data : $data[0];
}

/**
 * 计算用户等级 ...
 * @param INT $showstars 星星基数
 * @param INT $num  星星数量
 * @param Array $starimg 星星图标资源
 * @param INT $width 图片宽
 * @param INT $height 图片高
 */
function showstars($showstars,$num,$starimg,$width='16',$height='16')
{
	$return = '';
	$alt = 'alt="Rank: '.$num.'"';
	$style=$width&&$height?' width='.$width.' height='.$height:'';
	if(empty($showstars))
	{
		for($i = 0; $i < $num; $i++)
		{
			$return .= '<img src="'.$starimg[1].'" '.$alt.' />';
		}
	}
	else
	{
		for($i = 3; $i > 0; $i--) {
			$numlevel = intval($num / pow($showstars, ($i - 1)));
			$num = ($num % pow($showstars, ($i - 1)));
			for($j = 0; $j < $numlevel; $j++)
			{
				$return .= '<img src="'.$starimg[$i].'" '.$alt.$style.' />';
			}
		}
	}
	return $return;
}

function trimall($str)//删除空格
{
	$qian=array(" ","　","\t","\n","\r");$hou=array("","","","","");
	return str_replace($qian,$hou,$str);
}
function trims($str)//删除空格
{
	if(is_array($str))
	{
		return $str;
	}
	return trim($str);
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
//配合array_filter使用,清空所有数组非数字值
function clean_array_num_max0($v)
{
	if(is_numeric($v)&&$v>0)return true;
	return false;
}
//配合array_filter使用,清空所有数组纯数字值
function clean_array_string($v)
{
	if(is_numeric($v))return false;
	return true;
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

function daddslashes($string, $force = 0, $strip = FALSE) {
	if(!$GLOBALS['magic_quotes_gpc'] || $force) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = daddslashes($val, $force, $strip);
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
/**
 * 颜色值与16进制形式的转换
 *
 */
function color($color)
{
	$color=strtoupper($color);
	$trueColors = array(
'ANTIQUE WHITE' => '#FAEBD7',
'AQUA' => '#00FFFF',
'AQUAMARINE' => '#7FFFD4',
'AZURE' => '#F0FFFF',
'BEIGE' => '#F5F5DC',
'BISQUE' => '#FFE4C4',
'BLACK' => '#000000',
'BLANCHED ALMOND' => '#FFEBCD',
'BLUE' => '#0000FF',
'BLUE VIOLET' => '#8A2BE2',
'BROWN' => '#A52A2A',
'BURLY WOOD' => '#DEB887',
'CADET BLUE' => '#5F9EA0',
'CHARTREUSE' => '#7FFF00',
'CHOCOLATE' => '#D2691E',
'CORAL' => '#FF7F50',
'CORN FLOWER BLUE' => '#6495ED',
'CORN SILK' => '#FFF8DC',
'CRIMSON' => '#DC143C',
'CYAN' => '#00FFFF',
'DARK BLUE' => '#00008B',
'DARK CYAN' => '#008B8B',
'DARK GOLDENROD' => '#B8860B',
'DARK GRAY' => '#A9A9A9',
'DARK GREEN' => '#006400',
'DARK KHAKI' => '#BDB76B',
'DARK MAGENTA' => '#8B008B',
'DARK OLIVE GREEN' => '#556B2F',
'DARK ORANGE' => '#FF8C00',
'DARK ORCHID' => '#9932CC',
'DARK RED' => '#8B0000',
'DARK SALMON' => '#E9967A',
'DARK SEA GREEN' => '#8FBC8F',
'DARK SLATE BLUE' => '#483D8B',
'DARK SLATE GRAY' => '#2F4F4F',
'DARK TURQUOISE' => '#00CED1',
'DARK VIOLET' => '#9400D4',
'DEEP PINK' => '#FF1493',
'DEEP SKY BLUE' => '#00BFFF',
'DULL GRAY' => '#696969',
'DODGER BLUE' => '#1E90FF',
'FIRE BRICK' => '#B22222',
'FLORAL WHITE' => '#FFFAF0',
'FOREST GREEN' => '#228B22',
'FUCHSIA' => '#FF00FF',
'GAINSBORO' => '#DCDCDC',
'GHOST WHITE' => '#F8F8FF',
'GOLD' => '#FFD700',
'GOLDENROD' => '#DAA520',
'GRAY' => '#808080',
'GREEN' => '#008000',
'GREEN YELLOW' => '#ADFF2F',
'HONEYDEW' => '#F0FFF0',
'HOT PINK' => '#FF69B4',
'INDIAN RED' => '#CD5C5C',
'INDIGO' => '#4B0082',
'IVORY' => '#FFFFF0',
'KHAKI' => '#F0E68C',
'LAVENDER' => '#E6E6FA',
'LAVENDER BLUSH' => '#FFF0F5',
'LEAF' => '    #6B8E23',
'LEMON CHIFFON' => '#FFFACD',
'LIGHT BLUE' => '#ADD8E6',
'LIGHT CORAL' => '#F08080',
'LIGHT CYAN' => '#E0FFFF',
'LIGHT GOLDENROD YELLOW' => '#FAFAD2',
'LIGHT GREEN' => '#90EE90',
'LIGHT GRAY' => '#D3D3D3',
'LIGHT PINK' => '#FF86C1',
'LIGHT SALMON' => '#FFA07A',
'LIGHT SEA GREEN' => '#20B2AA',
'LIGHT SKY BLUE' => '#87CEFA',
'LIGHT STEEL BLUE' => '#778899',
'LIGHT YELLOW' => '#FFFFE0',
'LIME' => '#00FF00',
'LIME GREEN' => '#32CD32',
'LINEN' => '#FAF0E6',
'MAGENTA' => '#FF00FF',
'MAROON' => '#800000',
'MEDIUM AQUA MARINE' => '#66CDAA',
'MEDIUM BLUE' => '#0000CD',
'MEDIUM ORCHID' => '#BA55D3',
'MEDIUM PURPLE' => '#9370DB',
'MEDIUM SEA GREEN' => '#3CB371',
'MEDIUM SLATE BLUE' => '#7B68EE',
'MEDIUM SPRING BLUE' => '#00FA9A',
'MEDIUM TURQUOISE' => '#48D1CC',
'MEDIUM VIOLET RED' => '#C71585',
'MIDNIGHT BLUE' => '#191970',
'MINT CREAM' => '#F5FFFA',
'MISTY ROSE' => '#FFE4E1',
'NAVAJO WHITE' => '#FFDEAD',
'NAVY' => '#000080',
'OLD LACE' => '#FDF5E6',
'OLIVE' => '#808000',
'ORANGE' => '#FFA500',
'ORANGE RED' => '#FF4500',
'ORCHID' => '#DA70D6',
'PALE GOLDENROD' => '#EEE8AA',
'PALE GREEN' => '#98FB98',
'PALE TURQUOISE' => '#AFEEEE',
'PALE VIOLET RED' => '#DB7093',
'PAPAYAWHIP' => '#FFEFD5',
'PEACH PUFF' => '#FFDAB9',
'PERU' => '#CD853F',
'PINK' => '#FFC0CB',
'PLUM' => '#DDA0DD',
'POWDER BLUE' => '#B0E0E6',
'PURPLE' => '#800080',
'RED' => '#FF0000',
'ROSY BROWN' => '#BC8F8F',
'ROYAL BLUE' => '#4169E1',
'SADDLE BROWN' => '#8B4513',
'SALMON' => '#FA8072',
'SANDY BROWN' => '#F4A460',
'SEA GREEN' => '#2E8B57',
'SEASHELL' => '#FFF5EE',
'SIENNA' => '#A0522D',
'SILVER' => '#C0C0C0',
'SKY BLUE' => '#87CEEB',
'SLATE BLUE' => '#6A5ACD',
'SLATE GRAY' => '#708090',
'SNOW' => '#FFFAFA',
'SPRING GREEN' => '#00FF7F',
'STEEL BLUE' => '#4682B4',
'TAN' => '#D2B48C',
'TEAL' => '#008080',
'THISTLE' => '#D88FD8',
'TOMATO' => '#FF6347',
'TURQUOISE' => '#40E0D0',
'VIOLET' => '#EE82EE',
'WHEAT' => '#F5DEB3',
'WHITE' => '#FFFFFF',
'WHITE SMOKE' => '#F5F5F5',
'YELLOW' => '#FFFF00',
'YELLOW GREEN' => '#9ACD32');
	return empty($trueColors[$color])?'#000000':$trueColors[$color];
}
// 时间合法检测
function validateDate($date, $format = 'Y-m-d H:i:s')
{
	if ( class_exists("DateTime", false ) && method_exists("DateTime", "createFromFormat" ) )
	{
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}
	else
	{
		$unixtime=strtotime(trim($date));
		return $unixtime&&(date($format,$unixtime)==$date);
	}
}
//日期合法检测
function datecheck($ymd, $sep='-') {
	if(!empty($ymd)) {
		$datearr=explode($sep, $ymd);
		if(count($datearr)==3)
		{
			list($year,$month,$day) = $datearr;
			if($year>0&&$month>0&&$day>0)
			{
				return checkdate(intval($month), intval($day), intval($year));
			}
		}
		return false;
	} else {
		return false;
	}
}
/**
 *
 * Enter description here ...
 * @param unknown_type $value 值
 * @param unknown_type 正确值类型
 */
function valueTypeChange($value,$changeInfo)
{
	$type = $changeInfo['type'];
	if($type == 'string')
	{
		return (string)$value;
	}
	if($type == 'int')
	{
		return (int)$value;
	}
	return $value;
}

function abs_num($n)
{
	$n=intval($n);
	if($n < 0){
		$n = abs($n);
	}
	return $n;
}
function neg_num($n)
{
	$n=intval($n);
	if($n > 0){
		$n = -$n;
	}
	return $n;
}

/**
 *
 * 增加积分文案处理 ...
 * @param unknown_type $credits
 */
function copywriting_credit($credits,$updatecredit=false,$credit_type = array())
{
	if($credits&&is_array($credits))
	{
		foreach ($credits as $k => $v)
		{
			if(is_array($v)&&($updatecredit?$updatecredit:$v['updatecredit']))
			{
				if(empty($credit_type))
				{
					$credit_type = $v['credit_type'];
				}
				if($credit_type&&is_array($credit_type))
				{
					foreach ($credit_type as $kk => $vv)
					{
						$$kk +=intval($v[$kk]);
					}
				}
				else return '';
			}
		}
		if($credit_type&&is_array($credit_type))
		{

			foreach ($credit_type as $k => $v)
			{

				if($$k>0)
				{
					$copywriting_credit_add .='+'.$$k.$v['title'].',';
				}
				elseif($$k<0) {
					$copywriting_credit_sub .=$$k.$v['title'].',';
				}
			}
			return trim($copywriting_credit_add.ltrim($copywriting_credit_sub,','),',');
		}
		return '';
	}
	else
	{
		return '';
	}
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

function xml2Array($xml)
{
	$xmlObj = simplexml_load_string($xml);
	if (!$xmlObj)
	{
		return false;
	}
	normalizeSimpleXML($xmlObj, $result);
	return $result;
}

function normalizeSimpleXML($obj, &$result)
{
	$data = $obj;
	if (is_object($data))
	{
		$data = get_object_vars($data);
	}
	if (is_array($data))
	{
		foreach ($data as $key => $value)
		{
			$res = null;
			normalizeSimpleXML($value, $res);
			if (($key == '@attributes') && ($key))
			{
				$result = $res;
			}
			else
			{
				$result[$key] = $res;
			}
		}
	}
	else
	{
		$result = $data;
	}
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

/**
 * 是否需要完善用户信息判断, 使用此函数返回的参数一定要详细判断，并作不同处理
 */
function isUserComplete($type)
{
	if($type =='m2o'||$type =='uc')
	{
		$isComplete = 0;//肯定不需要完善
	}
	else if ($type == 'sina')
	{
		$isComplete = 1;
	}
	else if($type == 'qq')
	{
		$isComplete = 2;
	}
	elseif($type == 'shouji') {
		$isComplete = 3;
	}
	elseif($type == 'email')
	{
		$isComplete = 4;
	}
	return $isComplete;
}
/**
 *
 * explode多次转换 ...
 * @param unknown_type $row
 * @param unknown_type $delimiter
 */
function explodes($row,$delimiter)
{
	if(is_array($delimiter))
	{
		foreach ($delimiter as $v)
		{
			$row = explodes($row,$v);
		}
	}
	else
	{
		if(is_array($row))
		{
			$_row = array();
			foreach ($row as $vv)
			{
				$expval = $vv?explode($delimiter, $vv):array();
				$expval && $_row[$expval[0]] = $expval[1];
			}
			$row = $_row;
		}
		elseif (is_string($row))
		{
			$row = $row?explode($delimiter, $row):array();
		}
	}
	return $row;
}

/**
 *
 * 内容替换函数 ...
 * @param String $replacePrefix 标签占位符前缀
 * @param Array $replaceData 对应占位符下标数组
 * @param String $content 需要替换的内容
 * @param int $i 替换下标，一般从0开始
 */
function replaceContent($replacePrefix,$replaceData,$content,$i = 0)
{
	if($replacePrefix&&$content&&is_array($replaceData)&&$replaceData)
	{
		$val = array_shift($replaceData);
		$content = replaceContent($replacePrefix,$replaceData,str_replace('{$'.$replacePrefix.$i.'}',$val,$content),++$i);
	}
	return $content;
}

/**
 * 
 * 逗号分割字符串转换数组 ... ...
 * @param string $string 需要转换字符串
 * @param Int $type 0为不过滤，1为过滤纯数字值，大于等于2过滤非数字值，3为过滤小于等于0的值
 * @param 
 */
function dexplode($string,$type = 0,$split = ',')
{
	if($string)
	{
		$arrStr = explode($split, trim(urldecode($string)));
		if($type > 0)
		{
		  $arrStr = array_filter($arrStr,"clean_array_null");
		}
		if($type == 1)
		{
		 	$arrStr = array_filter($arrStr,"clean_array_string");	
		}
		else if($type >= 2)
		{
		    $arrStr = array_filter($arrStr,"clean_array_num");	
		}
		if($type == 3)
		{
			 $arrStr = array_filter($arrStr,"clean_array_num_max0");
		}
		return $arrStr;//转为数组方便字符串转换
	}
	return array();
}

function roundToPercent($float)
{
	return ($float*100).'%';
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

?>