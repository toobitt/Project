<?php
	/** 
	* gps坐标转换百度 
	* params ： x 经度；y 纬度；; 
	*/ 
	function GpsToBaidu($x,$y)
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
	
	/**
	 * gps转百度
	 * Enter description here ...
	 * @param string $coords 坐标
	 * @param string $ak	密钥
	 */
	function FromGpsToBaidu($coords,$ak)
	{
	    $url = BAIDU_CONVERT_DOMAIN_GPS_TO_BAIDU . '&coords='  . $coords . '&ak=' .$ak;

	    $ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result  = curl_exec($ch);
		curl_close($ch);//关闭
		
	    $json = json_decode($result,1); 
	    //return $json;
	    if(!$json['status'])
	    {
	        $info['x'] = $json['result'][0]['x'];
	        $info['y'] = $json['result'][0]['y'];
	        return $info;
	    }
	    else
	    {
	    	return false;//转换失败
	    }
	}
	
	/**
	 * 谷歌转百度
	 * Enter description here ...
	 * @param string $coords	坐标
	 * @param string $ak		密钥
	 */
	function FromGoogleToBaidu($coords,$ak)
	{
	    $url = BAIDU_CONVERT_DOMAIN_GOOGLE_TO_BAIDU . '&coords='  . $coords . '&ak=' .$ak;

	    $ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result  = curl_exec($ch);
		curl_close($ch);//关闭
		
	    $json = json_decode($result,1); 
	    //return $json;
	    if(!$json['status'])
	    {
	        $info['x'] = $json['result'][0]['x'];
	        $info['y'] = $json['result'][0]['y'];
	        return $info;
	    }
	    else
	    {
	    	return false;//转换失败
	    }
	}
	/** 
	* 计算两组经纬度坐标 之间的距离 
	* params ：lat1 纬度1； lng1 经度1； lat2 纬度2； lng2 经度2； len_type （1:m or 2:km); 
	* $len_type 1=m  2=km
	* return m or km 
	*/ 
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
?>