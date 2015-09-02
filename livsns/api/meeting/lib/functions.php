<?php
//计算两点之间的距离(GPS坐标)
function GetDistance($lat1, $lng1, $lat2, $lng2, $len_type = 1, $decimal = 2) 
{
	$radLat1 = $lat1 * PI / 180.0; 
	$radLat2 = $lat2 * PI / 180.0;
	$a = $radLat1 - $radLat2; 
	$b = ($lng1 * PI / 180.0) - ($lng2 * PI / 180.0); 
	$s = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1) * cos($radLat2) * pow(sin($b/2),2))); 
	$s = $s * EARTH_RADIUS; 
	$s = round($s * 1000); 
	if ($len_type > 1) 
	{ 
		$s /= 1000; 
	} 
	return round($s, $decimal); 
}

//百度坐标转换为GPS坐标
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
        return array('GPS_x' => $GPS_x,'GPS_y' => $GPS_y);//经度,纬度
    }
    else
    {
    	return false;//转换失败
    }
}

?>