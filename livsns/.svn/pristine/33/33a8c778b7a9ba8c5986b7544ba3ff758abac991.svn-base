<?php
class pushNotify
{
	private $mCert = '';//证书存放位置
	private $mpass = 'hogewake';//证书的密码
	private $mdevelopAPNS = 'ssl://gateway.sandbox.push.apple.com:2195';//开发版提交苹果服务器的地址
	private $mpublishAPNS = 'ssl://gateway.push.apple.com:2195';//发布版提交苹果服务器的地址
	private $mAPNS;
	private $mAPNsHost;
	public function __construct()
	{
		$this->setCert(CERT_PATH);//设置证书的存放位置
		if(defined('IS_APP_PUBLISHED') && IS_APP_PUBLISHED)//设置提交的苹果的地址
		{
			$this->mAPNsHost = $this->mpublishAPNS;
		}
		else 
		{
			$this->mAPNsHost = $this->mdevelopAPNS;
		}
	}
	
	//设置证书
	private function setCert($cert)
	{
		if (!is_file($cert))
		{
			return;
		}
		$this->mCert = $cert;
	}
	
	//连接苹果服务器
	public function connectToAPNS()
	{
		if (!$this->mAPNsHost || !$this->mCert)
		{
			return 0;
		}
		$APNS = '';
		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', $this->mCert);
		stream_context_set_option($ctx, 'ssl', 'passphrase', $this->mpass);
		$this->mAPNS = @stream_socket_client($this->mAPNsHost, $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
		if (!$this->mAPNS)
		{
			return 0;
		}
		else
		{
			return 1;
		}
	}

	//关闭连接
	public function closeConnections()
	{
		if (!$this->mAPNS)
		{
			return;
		}
		fclose($this->mAPNS);
	}

	//发消息
	public function send($deviceToken, $message)
	{
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

		$payload = '{"aps" : {"' . $type . '" : "' . addslashes($message['text']) . '", "type" : "' . $type . '", "badge" : "' . $badge . '", "sound" : "' . $sound . '"},"user_info":{"exchange_id":"' .$message['exchange_id']. '","title":"' .$message['title']. '"}}';
		$msg = chr(0) . pack("n",32) . pack('H*', str_replace(' ', '', $deviceToken)) . pack("n",strlen($payload)) . $payload;
		$len = @fwrite($this->mAPNS, $msg);
		return $len;
	}
}
?>