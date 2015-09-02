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
	
	//格式化素材
	function format_ad_material($mat, $mtype='',$imgwidth='', $imgheight='')
	{
		//素材路径
		$murl = '';
		//素材原始数组
		if (!is_array($mat))
		{
			$isserial = unserialize($mat);
		}
		if($isserial)
		{
			$mat = $isserial;
		}
		switch($mtype)
		{
			case 'image':
				{
					$murl = hg_fetchimgurl($mat, $imgwidth, $imgheight);
					break;
				}
			case 'flash':
				{
					$murl = hg_fetchimgurl($mat);
					break;
				}
			case 'video':
				{
					$murl = hg_fetchimgurl($mat['img'], $imgwidth, $imgheight);
					break;
				}
			case 'text':
			case 'javascript':
				{
					return $mat;
				}
			default:
				{
					return '';
				}
		}
		return $murl;
	}

?>