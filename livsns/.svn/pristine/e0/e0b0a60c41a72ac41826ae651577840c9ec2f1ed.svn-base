<?php

class PushFeedBack
{
	var $mCert = '';
	private $mpass = '';
	private $mDevelopFeedBack = 'ssl://feedback.sandbox.push.apple.com:2196';
	private $mPublishFeedBack = 'ssl://feedback.push.apple.com:2196';
	
	private $mFeedBack;
	private $mFeedBacksHost;
	
	function __construct()
	{
	}

	function __destruct()
	{
	}
	
	public function SetCert($cert)
	{
		if (!is_file($cert))
		{
			return;
		}
		$this->mCert = $cert;
	}
	
	public function SetFeedBackHost($debug='')
	{
		if ($debug)
		{
			$this->mFeedBacksHost = $this->mDevelopFeedBack;
		}
		else
		{
			$this->mFeedBacksHost = $this->mPublishFeedBack;
		}
	}
	
	public function ConnectToFeedBack()
	{
		if (!$this->mFeedBacksHost || !$this->mCert)
		{
			return false;
		}
		
		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', $this->mCert);
		stream_context_set_option($ctx, 'ssl', 'passphrase', $this->mpass);
		$this->mFeedBack = @stream_socket_client($this->mFeedBacksHost, $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);
		if (!$this->mFeedBack)
		{
			return 0;
		}
		else
		{
			$APNsFeedBack = array();
		 	while ($devcon = fread($this->mFeedBack, 38))  
		   	{  
		       	$arr = unpack("H*", $devcon);  
		       	$rawhex = trim(implode("", $arr));  
		       	$feedbackTime = hexdec(substr($rawhex, 0, 8));  
		       	$feedbackLen = hexdec(substr($rawhex, 8, 4));  
		       	$feedbackDeviceToken = substr($rawhex, 12, 64);  
		   		$arr_tmp['fb_time'] = $feedbackTime;
		   		$arr_tmp['fb_devi'] = $feedbackDeviceToken;
		   		$APNsFeedBack[] = $arr_tmp;
		   	}  
			return $APNsFeedBack;
		}
	}

	public function CloseConnections()
	{
		if (!$this->mFeedBack)
		{
			return;
		}
		fclose($this->mFeedBack);
	}

}

?>