<?php
	function hg_line_info($row)
	{
		if ($row['start_time'])
		{
			$runtime = date('H:i', ($row['start_time']));
		}
		if ($row['stop_time'])
		{
			$runtime .= '-' . date('H:i', ($row['stop_time']));
		}
		if ($row['sub_start_time'])
		{
			$runtime1 = date('H:i', ($row['sub_start_time']));
		}
		if ($row['sub_stop_time'])
		{
			$runtime1 .= '-' . date('H:i', ($row['sub_stop_time']));
		}
		$extra = ' 全程' . $row['rlen'] . '公里，单程' . round($row['rlen1'], 1) . '公里，预计总运行时间' . $row['rtime'] . '分钟';
		return $stations[1][0] . $runtime . '  ' . $stations[2][0] . $runtime1 . $extra;
	}

	function get_tablename($bundle_id,$module_id,$struct_id,$struct_ast_id = '')
	{
		return strtolower($bundle_id.'_'.$module_id.'_'.$struct_id.(empty($struct_ast_id)?'':('_'.$struct_ast_id)));
	}
	
	function file_in($dir,$filename,$strings,$type=false)
	{  
		$path = trim($dir,'/');
	    if(!is_dir($path))
	    {
		    mkdir($path, 0777, true);
	    }
	    if(file_exists($path.'/'.$filename))
	    {
	    	return false;
	    }
        if ($type == false)
            file_put_contents($path.'/'.$filename, $strings, FILE_APPEND);
        else
            file_put_contents($path.'/'.$filename, $strings);
        return true;
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
	

	//百度坐标转换为GPS坐标
	function FromBaiduToGpsXY($x,$y)
	{
	    $url = BAIDU_CONVERT_DOMAIN . '&x='  . $x . '&y=' .$y;
	    
	    $ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result  = curl_exec($ch);
		curl_close($ch);//关闭
		
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
	* 计算两组经纬度坐标 之间的距离 
	* params ：lat1 纬度1； lng1 经度1； lat2 纬度2； lng2 经度2；
	* return m or km 
	*/
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
?>