<?php if(!defined('PLUGIN_PATH')) exit('Access Denied');
class curl
{
	//GET请求url
	private $url_prefix;
	private $request_url;
	private $reFile;
	private $file;
	function __construct()
	{
		//
	}
	public function __destruct()
	{
		//
	}
	function setUrlPrefix($url_prefix = '')
	{
		$this->url_prefix = $url_prefix;
	}
	function setRequestFile($file = '')
	{
		$this->reFile = $file;
	}
	function setRequestParameters($data = array())
	{
		$parameters = $this->reFile . '?appid=' . APPID . '&appkey=' . APPKEY . '&access_token='.$_SESSION['access_token'];
		if($data)
		{
			foreach($data as $key=>$val)
			{
				$parameters .= '&' . $key . '=' . urlencode($val);
			}
		}
		$this->request_url = $this->url_prefix . $parameters;
	}
	public function request()
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		if($this->file)
		{
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $this->file);
		}
        curl_setopt($ch, CURLOPT_URL, $this->request_url);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       	$data = curl_exec($ch);
		curl_close($ch);
		$decoded = json_decode($data, true);
		if(!is_array($decoded))
		{
			echo "数据请求失败[URL:".$this->request_url."]";exit;
		}
		if($decoded['ErrorCode'] || $decoded['ErrorText'])
		{
			echo $decoded['ErrorText'] . $decoded['ErrorCode'];
			exit;
		}
		return $decoded;
	}
	public function postFile($file)
	{
		if(!isset($file))
		{
			return;
		}
		foreach ($file as $var => $val)
		{
			if (is_array($val['tmp_name']))
			{
				foreach ($val['tmp_name'] as $k=>$fname)
				{
					if ($fname)
					{
						$this->file[$var . "[$k]"] = "@".$fname . ';type=' . $val['type'][$k] . ';filename=' . urlencode($val['name'][$k]);
					}
				}
			}
			else
			{
				if ($val['tmp_name'])
				{
						$this->file[$var] = "@".$val['tmp_name'] . ';type=' . $val['type'] . ';filename=' . urlencode($val['name']);
				}
			}
		}
	}
}
?>