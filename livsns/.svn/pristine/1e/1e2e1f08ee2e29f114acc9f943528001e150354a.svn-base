<?php
//检测时移服务器的连通性
function check_shift_server($url)
{
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 2);
    curl_exec($ch);
	$head_info = curl_getinfo($ch);
    curl_close($ch);
	if ($head_info['http_code'] != 200)
	{
		return false;
	}
	return true;
}

function xml2Array($xml) 
{
	normalizeSimpleXML(simplexml_load_string($xml,null,LIBXML_NOCDATA), $result);
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

//记录错误日志
function writeErrorLog($info = '')
{
	$path_dir = CACHE_DIR . 'error/';
	if (!hg_mkdir($path_dir) || !is_writeable($path_dir))
	{
		return false;
	}
	$msg = "\n======================" . date('Y-m-d H:i:s',TIMENOW) . "============================\n";
	$msg.= $info;
	$msg.= "\n=====================================================================\n";
	file_put_contents($path_dir . 'error.log',$msg,FILE_APPEND);
}

?>