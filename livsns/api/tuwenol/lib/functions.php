<?php
function shorturl($input) { 
	$base32 = array ( 
	'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h',
	'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p',
	'q', 'r', 's', 't', 'u', 'v', 'w', 'x',
	'y', 'z', '0', '1', '2', '3', '4', '5' ); 
	$hex = md5($input); $hexLen = strlen($hex); 
	$subHexLen = $hexLen / 8; 
	$output = array(); 
	for ($i = 0; $i < $subHexLen; $i++) 
	{ 
		$subHex = substr ($hex, $i * 8, 8); 
		$int = 0x3FFFFFFF & (1 * ('0x'.$subHex));
		$out = ''; for ($j = 0; $j < 6; $j++) 
		{ 
			$val = 0x0000001F & $int; $out .= $base32[$val]; $int = $int >> 5;
		} $output[] = $out;
	} 
	return $output;
}
function shorturl2($url='', $prefix='', $suffix='') {
	$base32 = array (
	'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h',
	'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p',
	'q', 'r', 's', 't', 'u', 'v', 'w', 'x',
	'y', 'z', '0', '1', '2', '3', '4', '5');
	
	$hex = md5($prefix.$url.$suffix);
	$hexLen = strlen($hex);
	$subHexLen = $hexLen / 8;
	$output = array();
	
	for ($i = 0; $i < $subHexLen; $i++) {
	$subHex = substr ($hex, $i * 8, 8);
	$int = 0x3FFFFFFF & (1 * ('0x'.$subHex));
	$out = '';
	for ($j = 0; $j < 6; $j++) {
	$val = 0x0000001F & $int;
	$out .= $base32[$val];
	$int = $int >> 5;
	}
	$output[] = $out;
	}
	return $output;
}
function FromBaiduToGpsXY($x,$y)
{
    $Baidu_Server = BAIDU_CONVERT_DOMAIN . '&x='  . $x . '&y=' .$y;
    $result = @file_get_contents($Baidu_Server);
    $json = json_decode($result);  
    if($json->error == 0)
    {
        $bx = base64_decode($json->x);     
        $by = base64_decode($json->y);  
        $GPS_x = 2 * $x - $bx;  
        $GPS_y = 2 * $y - $by;
        return array('x' => $GPS_x,'y' => $GPS_y);//经度,纬度
    }
    else
    {
    	return false;//转换失败
    }
}
//GPS坐标转换为百度坐标
function FromGpsToBaiduXY($x,$y)
{
	$url = BAIDU_CONVERT_DOMAIN . '&x='  . $x . '&y=' .$y;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$response  = curl_exec($ch);
	curl_close($ch);//关闭
	$info = json_decode($response,1);
	if($info && !$info['error'])
	{
		unset($info['error']);
		$info['x'] = base64_decode($info['x']);
		$info['y'] = base64_decode($info['y']);
		return $info;
	}
}
function GetDistance($lat1, $lng1, $lat2, $lng2, $decimal = 2) 
{ 
	$radLat1 = $lat1 * PI / 180.0; 
	$radLat2 = $lat2 * PI / 180.0; 
	$a = $radLat1 - $radLat2; 
	$b = ($lng1 * PI / 180.0) - ($lng2 * PI / 180.0); 
	$s = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1) * cos($radLat2) * pow(sin($b/2),2))); 
	$s = $s * EARTH_RADIUS; 
	$s = round($s * 1000); 
	if ($s > 1000) 
	{ 
		$s /= 1000; 
		$unit = '公里';
	}
	else
	{
		$unit = '米';
	}
	return round($s, $decimal) . $unit; 
}