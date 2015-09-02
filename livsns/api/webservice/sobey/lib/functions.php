<?php 

//解析提交过来的xml
function para_xml($xml = '')
{
    $xmlobj = @simplexml_load_string($xml);
    if(!$xmlobj)
	{
		return false;
	}
	
    $video = array();
    $video['ContentID'] = strval($xmlobj->ContentID);
    foreach($xmlobj->EntityData->children() AS $k => $v)
	{
		if($k != 'AttributeItem')
		{
			continue;
		}
		
		$video[strval($v->children()->ItemCode)] = strval($v->children()->Value);
	}
	
	foreach($xmlobj->ContentFile->children()->FileItem->children() AS $k => $v)
	{
		$video[$k] = strval($v);
	}
	return $video;
}

function hg_getip() 
{
	global $_INPUT;
	if ($_INPUT['lpip'])
	{
		if (hg_checkip($_INPUT['lpip']))
		{
			return $_INPUT['lpip'];
		}
	}
	if (isset($_SERVER)) 
	{
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			$realip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		elseif ($_SERVER['HTTP_X_REAL_IP'])
		{
			$realip = $_SERVER['HTTP_X_REAL_IP'];
		}
		elseif (isset($_SERVER['HTTP_CLIENT_IP'])) 
		{
			$realip = $_SERVER['HTTP_CLIENT_IP'];
		} 
		else 
		{
			$realip = $_SERVER['REMOTE_ADDR'];
		}
	} 
	else 
	{
		
		if (getenv("HTTP_X_FORWARDED_FOR")) 
		{
			$realip = getenv( "HTTP_X_FORWARDED_FOR");
		}
		elseif (getenv('HTTP_X_REAL_IP'))
		{
			$realip = getenv('HTTP_X_REAL_IP');
		} 
		elseif (getenv("HTTP_CLIENT_IP")) 
		{
			$realip = getenv("HTTP_CLIENT_IP");
		}
		else 
		{
			$realip = getenv("REMOTE_ADDR");
		}
	}
	$realip = explode(',', $realip);
	return $realip[0];
}


?>