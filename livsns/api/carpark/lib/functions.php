<?php
//密钥转换
function pkcs8_to_pem($der) 
{
    static $BEGIN_MARKER = "-----BEGIN PRIVATE KEY-----";
    static $END_MARKER = "-----END PRIVATE KEY-----";


    $pem = $BEGIN_MARKER . "\n";
    $pem .= chunk_split($der, 64, "\n");
    $pem .= $END_MARKER . "\n";

    return $pem;
}

//rsa签名
function rsaSign($data, $priKey) 
{
    openssl_sign($data, $signature, $priKey, OPENSSL_ALGO_MD5);
	//base64编码
    $sign = base64_encode($signature);
    return $sign;
}
	
//xml转换成数组
function xml2Array($xml) 
{
	$xmlObj = simplexml_load_string($xml);
	if(!$xmlObj)
	{
		return false;
	}
	normalizeSimpleXML($xmlObj,$result);
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

//距离单位转换
function distance_change_unit($distance = 0)
{
	if($distance > 1000)
	{
		$distance /= 1000;
		$distance .= 'km'; 
	}
	else 
	{
		$distance .= 'm';
	}
	return $distance;
}