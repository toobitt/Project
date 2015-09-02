<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: curl.class.php 85 2011-07-08 00:56:46Z develop_tong $
***************************************************************************/

set_time_limit(0);
class curl
{
	private $mRequestType = 'http';
	private $mReturnType = 'json';
	private $mSubmitType = 'post';
	private $mUrlHost = 'localhost';
	private $mApiDir = 'livsns/api/';
	private $mToken = 'aldkj12321aasd';
	private $mAppid = '1';
	private $mFile = '';
	private $access_token = '';
	private $mCookies = array();
	private $mRequestData = array();
	private $globalConfig = array();
	private $input = array();
	function __construct($host = '', $apidir = '', $token = 'aldkj12321aasd', $stype = 'post' , $request_type = 'http')
	{
		global $gGlobalConfig, $_INPUT, $gUser;
		$this->globalConfig = $gGlobalConfig;
		$this->input = $_INPUT;
		$this->setUrlHost($host, $apidir);
		$this->setToken($token);
		$this->access_token = $gUser['access_token'];
		$this->setRequestType($request_type);
		$this->setSubmitType($stype);
	}

	function __destruct()
	{
	}

	public function initPostData()
	{
		$this->mRequestData = array();
	}

	public function setReturnFormat($format)
	{
		if (!in_array($format, array('json', 'xml', 'str')))
		{
			$format = 'json';
		}
		$this->mReturnType = $format;
	}
	
	public function setUrlHost($host, $apidir)
	{
		if (!$host)
		{
			global $gApiConfig;
			$host = $gApiConfig['host'];
			$apidir = $gApiConfig['apidir'];
		}
		$this->mUrlHost = $host;
		$this->mApiDir = $apidir;
	}

	public function setClient($authkey, $appid = '1')
	{
		$this->mAuthKey = $authkey;
		$this->mAppid = $appid;
	}

	public function setToken($token)
	{
		$this->mToken = $token;
	}

	public function setRequestType($type)
	{
		$this->setRequestType = $type;
	}

	public function setSubmitType($type)
	{
		$this->mSubmitType = $type;
	}

	public function addCookie($name, $value)
	{
		$this->mCookies[] = $this->globalConfig['cookie_prefix'] . $name . '=' . $value;
	}

	public function addFile($file)
	{  
		if(isset($file))
		{
			foreach ($file as $var => $val)
			{
				if (is_array($val['tmp_name']))
				{
					foreach ($val['tmp_name'] as $k=>$fname)
					{
						$this->mRequestData[$var . "[$k]"] = "@".$fname . ';type=' . $val['type'][$k] . ';filename=' . $val['name'][$k];	
					}
				}
				else
				{
					$this->mRequestData[$var] = "@".$val['tmp_name'] . ';type=' . $val['type'] . ';filename=' . $val['name'];
				}
			}
		}
	}

	public function addRequestData($name, $value)
	{
		$this->mRequestData[$name] = urlencode($value);
	}

    public function request($file)
    {    	
		$para .= '&appid=' . APPID;
		$para .= '&appkey=' . APPKEY;
		$para .= '&lpip=' . hg_getip();
		
		if ($this->input)
		{
			foreach ($this->input AS $k => $v)
			{
				if (in_array($k, array('a', 'pp', 'mid', 'count', 'id', 'appid', 'appkey')))
				{
					continue;
				}
				if (is_array($v))
				{
					foreach ($v AS $kk => $vv)
					{
						$this->addRequestData($k . "[$kk]", urldecode($vv));
					}
				}
				else
				{
					$this->addRequestData($k, urldecode($v));
				}
			}
		}
		if ($this->input['html'])
		{
			$para .= '&html=' . $this->input['html'];
		}
		if ('get' == $this->mSubmitType && $this->mRequestData)
		{
			foreach ($this->mRequestData AS $k => $v)
			{
				$para .= '&' . $k . '=' . $v;
			}
		}
		$url = $this->mRequestType . '://' . $this->mUrlHost . '/' . $this->mApiDir . $file . '?format=' . $this->mReturnType . $para;
				
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
		//curl_setopt($ch,CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)"); 
		curl_setopt($ch,CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); 
		if ($this->mCookies)
		{
			$cookies = implode(';', $this->mCookies);

			curl_setopt($ch, CURLOPT_COOKIE, $cookies);
		}
		if ('post' == $this->mSubmitType)
		{
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $this->mRequestData);
		}
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         
        $ret = curl_exec($ch);
        curl_close($ch);
        $func = $this->mReturnType . 'ToArray';
        $ret = $this->$func($ret);
          
        return $ret;
    }
    private function jsonToArray($json)
    {
    	$ret = json_decode($json,true);
		if(is_array($ret))
		{
			unset($ret['Debug']);
			if ($ret['ErrorCode'])
			{
				exit($ret['ErrorCode'] . $ret['ErrorText']);
			}
			return $ret;
		}
		else 
		{
			return $json;
		}
    }

    private function xmlToArray($xml)
    {
    	return $xml;
    }
    
    private function strToArray($str)
    {
    	return $str;
    }
}
?>