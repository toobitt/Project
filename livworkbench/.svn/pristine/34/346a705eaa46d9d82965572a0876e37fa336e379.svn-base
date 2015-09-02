<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: curl.class.php 1879 2012-12-05 04:10:21Z develop_tong $
***************************************************************************/

class curl
{
	private $mRequestType = 'http';
	private $mReturnType = 'json';
	private $mSubmitType = 'post';
	private $mUrlHost = 'localhost';
	private $mApiDir = 'livsns/api/';
	private $mToken = '';
	private $mErrorReturn = 'exit';
	private $mAppid = '';
	private $mAppkey = '';
	private $mFile = '';
	private $mAuth = '';
	private $mCookies = array();
	private $mRequestData = array();
	private $globalConfig = array();
	private $input = array();
	private $user = array();
	function __construct($host = '', $apidir = '', $token='',$stype = 'post' , $request_type = 'http')
	{
		$this->mAuth = $token;
		$this->setUrlHost($host, $apidir);
		$this->setToken();
		$this->setClient();
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

	public function setClient()
	{
		$this->mAppid = $this->input['appid'];
		$this->mAppkey = $this->input['appkey'];
	}

	public function setErrorReturn($type = 'exit')
	{
		$this->mErrorReturn = $type;
	}

	public function setToken()
	{
		$this->mToken = $this->user['token'];
	}

	public function setRequestType($type)
	{
		$this->mRequestType = $type;
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
						if($fname)
						{
							$this->mRequestData[$var . "[$k]"] = "@".$fname . ';type=' . $val['type'][$k] . ';filename=' . $val['name'][$k];
						}
					}
				}
				else
				{
					if ($val['tmp_name'])
					{
						$this->mRequestData[$var] = "@".$val['tmp_name'] . ';type=' . $val['type'] . ';filename=' . $val['name'];
					}
				}
			}
		}
	}

	public function addRequestData($name, $value)
	{
		$this->mRequestData[$name] = $value;
	}

    public function request($file)
    {
		/*
		 * 接口基类方法verifyToken根据token获取登录用户信息
		 * 或者根据客户端ID和客户端KEY也可以获取虚拟用户信息（超级用户权限）
		 */
		$para = '&appid=' . APPID;
		$para .= '&appkey=' . APPKEY;
		if($this->mToken)
		{
			$para .= '&access_token=' . $this->mToken;
		}

		$para .= '&auth=' . $this->mAuth;
		$para .= '&token=' . $this->mAuth;

		if ($this->input)
		{
			foreach ($this->input AS $k => $v)
			{
				if (in_array($k, array('a', 'pp', 'mid', 'count', 'id')))
				{
					continue;
				}
				if (is_array($v))
				{
					foreach ($v AS $kk => $vv)
					{
						//二维数组
						if(is_array($vv))
						{
							foreach($vv as $kkk=>$vvv)
							{
								$this->addRequestData($k . "[$kk]" . "[$kkk]", $vvv);
							}
						}
						else
						{
							$this->addRequestData($k . "[$kk]", $vv);
						}
					}
				}
				else
				{
					$this->addRequestData($k, $v);
				}
			}
		}

		if ($_FILES)
		{
			$this->addFile($_FILES);
		}
		if ($this->input['html'])
		{
			$para .= '&html=' . $this->input['html'];
		}
		if ('get' == $this->mSubmitType && $this->mRequestData)
		{
			foreach ($this->mRequestData AS $k => $v)
			{
				$para .= '&' . $k . '=' . urlencode($v);
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
		$head_info = curl_getinfo($ch);
        curl_close($ch);
		if($head_info['http_code']!= 200)
		{
			exit('服务器访问接口[' . $url . ']异常,错误:' . $head_info['http_code']);
		}
        if($ret == 'null')
        {
        	return '';
        }
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