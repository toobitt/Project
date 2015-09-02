<?php
class hgSocket
{
	private $socket;
	public function __construct()
	{
		$this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		//socket_set_option($this->socket ,SOL_SOCKET, SO_RCVTIMEO, array("sec"=>300, "usec"=>0)); 
	}
	
	public function __destruct()
	{
		$this->close();
	}

	private function close()
	{
		if ($this->socket)
		{
			socket_close($this->socket);
		}
	}
	
	public function connect($ip, $port)
	{
		$result = @socket_connect($this->socket, $ip, $port);
		if ($result < 0) 
		{
			$this->socket = false;
		}
		return $this->socket;
	}

	public function sendCmd($cmd)
	{
		if (!$this->socket)
		{
			return false;
		}
		if (!isset($cmd['charset']))
		{
			$cmd['charset'] = '';
		}
		$str = json_encode($cmd);
		if(socket_write($this->socket, $str, strlen($str)))
		{
			return true;
		}
		else 
		{
			return false;
		}
	}

	public function read($size = 256)
	{
		if (!$this->socket)
		{
			return false;
		}
		$out = socket_read($this->socket, $size);
		return $out;
	}

	public function readall()
	{
		if (!$this->socket)
		{
			return false;
		}
		$data = '';
		$size = 256;
		while ($out = $this->read($size))
		{
			$data .= $out;
			if (strlen($out) < $size)
			{
				break;
			}
		}
		return $data;
	}
}
?>