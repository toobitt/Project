<?php
/**
 * 原目录，复制到的目录
 * */
function file_copy($from, $to, $filenamearr = array())
{
    $dir = opendir($from);
    if (!is_dir($to))
    {
        @mkdir($to, CREATE_DIR_MODE, true);
    }
    while (false !== ( $file = readdir($dir)))
    {
    	if(file_exists($to . '/' . $file))
    	{
    		//continue;
    	}
        if ($filenamearr)
        {
            if (!in_array($file, $filenamearr))
            {
                continue;
            }
        }

        if (( $file != '.' ) && ( $file != '..' ))
        {
            if (is_dir($from . '/' . $file))
            {
                file_copy($from . '/' . $file, $to . '/' . $file, $filenamearr);
            }
            else
            {
                copy($from . '/' . $file, $to . '/' . $file);
            }
        }
    }
    closedir($dir);
}
/**
 * 生成兑换码
 */
function generateExchangeCode()
{
    return substr(TIMENOW,-4).substr(microtime(),2,4).hg_rand_num(2);
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
function get_rand($proArr,$sum) 
{ 
    $result = ''; 
 
    //概率数组循环 
    foreach ($proArr as $key => $proCur) 
    { 
    	$proSum = '';
    	$proSum = $sum[$key];
    	
    	if(!$proSum)
    	{
    		continue;
    	}
    	
        $randNum = mt_rand(1, $proSum); 
        if ($randNum <= $proCur) 
        { 
            $result = $key; 
            break; 
        }
    } 
    unset ($proArr); 
 
    $result = $result ? $result : 0;
    return $result; 
}
	
function hg_tran_time_tv($time) 
{ 
    $time = time() - $time;  
  
    if($time < 60) 
    {  
        $str = '刚刚';  
    }  
    elseif($time < 60 * 60) 
    {  
        $min = floor($time/60);  
        $str = $min.'分钟前';  
    }  
    elseif($time < 60 * 60 * 24) 
    {  
        $h = floor($time/(60*60));  
        $str = $h.'小时前';  
    }  
    elseif($time < 60 * 60 * 24 * 8) 
    {  
        $d = floor($time/(60*60*24));  
        if($d==1)  
           $str = '昨天';  
        else if($d==2)
        { 
           $str = '前天';
        }
        elseif ($d>2&&$d<8)
        {
        	$str=$d.'天前';
        }  
    }  
    else if($time < 60 * 60 * 24 * 365)
    {  
        $str = date("m-d",$time);
    }  
    else
    {
    	$str = date("Y-m-d",$time);
    }
    return $str;  
}

?>