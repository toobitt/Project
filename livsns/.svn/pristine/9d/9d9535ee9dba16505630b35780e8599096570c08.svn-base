<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: channel.php 6066 2012-03-12 02:32:09Z develop_tong $
***************************************************************************/
class pushNotify
{
	var $mCert = '';
	private $mpass = '';
	private $mdevelopAPNS = 'ssl://gateway.sandbox.push.apple.com:2195';
	private $mpublishAPNS = 'ssl://gateway.push.apple.com:2195';
	private $mAPNS;
	private $mAPNsHost;
	function __construct()
	{
	}

	function __destruct()
	{
	}
	
	public function setCert($cert)
	{
		if (!is_file($cert))
		{
			return;
		}
		$this->mCert = $cert;
	}
	public function setAPNsHost($debug)
	{
		if ($debug)
		{
			$this->mAPNsHost = $this->mdevelopAPNS;
		}
		else
		{
			$this->mAPNsHost = $this->mpublishAPNS;
		}
	}
	public function connectToAPNS()
	{
		if (!$this->mAPNsHost || !$this->mCert)
		{
			return 0;
		}
		echo $this->mAPNsHost;
		$APNS = '';
		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', $this->mCert);
		stream_context_set_option($ctx, 'ssl', 'passphrase', $this->mpass);
		$this->mAPNS = @stream_socket_client($this->mAPNsHost, $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);
		if (!$this->mAPNS)
		{
			return 0;
		}
		else
		{
			return 1;
		}
	}

	public function closeConnections()
	{
		if (!$this->mAPNS)
		{
			return;
		}
		fclose($this->mAPNS);
	}

	public function send($deviceToken, $message)
	{
		if (strlen($deviceToken) < 60)
		{
			return 0;
		}
		if (!$this->mAPNS)
		{
			return 0;
		}
		$badge = (int)$message['badge'];
		if (!$badge)
		{
			$badge = 1;
		}
		$sound = $message['sound'];
		if (!$sound)
		{
			$sound = '';
		}
		$type = 'alert';

		$payload = '{"aps" : {"' . $type . '" : "' . addslashes($message['text']) . '", "type" : "' . $type . '", "badge" : "' . $badge . '", "sound" : "' . $sound . '"}, "module_id" : "' . $message['module_id'] . '", "id" : "' . $message['content_id'] . '"}';
		$msg = chr(0) . pack("n",32) . pack('H*', str_replace(' ', '', $deviceToken)) . pack("n",strlen($payload)) . $payload;
		$len = @fwrite($this->mAPNS, $msg);
		return $len;
	}
}

?>