<?php
/**
 * 
 * 时间轴
 * @param  int	$time	unix时间戳
 */
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