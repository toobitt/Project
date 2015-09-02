<?php
function hg_sendCmd($cmd, $ip, $port)
{
	$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
	if ($socket < 0) 
	{
		return false;
	}
	$result = socket_connect($socket, $ip, $port);
	if ($result < 0) 
	{
		return false;
	}
	if (!isset($cmd['charset']))
	{
		$cmd['charset'] = '';
	}
	$str = json_encode($cmd);
	//echo ($str);
	//$str = base64_encode($str);
	socket_write($socket, $str, strlen($str));
	$data = '';
	while ($out = socket_read($socket, 256))
	{
		$data .= $out;
		if (strlen($out) < 256)
		{
			break;
		}
	}
	socket_close($socket);
	//$data = base64_decode($data);
	return $data;
}

?>