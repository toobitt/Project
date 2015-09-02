<?php
function seekhelp_clean_value($val = '')
{
	$pregfind = array('&#60;&#33;--', '--&#62;', '&gt;', '&lt;', '&quot;', '&#33;', '&#39;', "\n", '&#036;', '');
	$pregreplace = array('<!--', '-->', '>', '<', '"', '!', "'", "\n", '$', "\r");
	$val = str_replace($pregfind, $pregreplace, $val);
	return $val;
}
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
function cdata($content)
{
	$regex = '/^<!\[CDATA\[(.*)\]\]>/isU';
	if(preg_match($regex, $content, $matches)){
    	$content = $matches[1];
	}
	return $content;
}