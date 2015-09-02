<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: curl.class.php 44492 2015-03-05 09:40:13Z kangxiaoqiang $
***************************************************************************/

class curl
{
	var $mRequestType = 'http';
	var $mReturnType = 'json';
	var $mSubmitType = 'get';
	var $mUrlHost = 'localhost';
	var $mApiDir = 'livsns/api/';
	var $mAuthKey = 'aldkj12321aasd';
	var $mFile = '';
	var $mCharset = 'UTF-8';
	var $mCookies = array();
	var $mRequestData = array();
	var $mGetData = array();
	var $globalConfig = array();
	var $input = array();
	var $mErrorOut  = true;
	var $isSetTimeOut = 30;//设置curl超时时间
	function __construct($host = '', $apidir = '', $authkey = 'aldkj12321aasd', $stype = 'get', $request_type = 'http')
	{
		global $gGlobalConfig, $_INPUT;
		$this->globalConfig = $gGlobalConfig;
		$this->setUrlHost($host, $apidir);
		$this->setClient($authkey);
		$this->setRequestType($request_type);
		$this->setSubmitType($stype);
	}

	function __destruct()
	{
	}

	public function setCharset($charset)
	{
		if ($charset)
		{
			$this->mCharset = $charset;
		}
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

	public function setClient($authkey)
	{
		$this->mAuthKey = $authkey;
	}

	public function setAuthKey($authkey)
	{
		$this->mAuthKey = $authkey;
	}

	public function setRequestType($type)
	{
		$this->mRequestType = $type;
	}
	
	public function setErrorOut($type)
	{
		$this->mErrorOut = $type;
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
						if ($fname)
						{
							$this->mRequestData[$var . "[$k]"] = "@".$fname . ';type=' . $val['type'][$k] . ';filename=' . urlencode($val['name'][$k]);
						}
					}
				}
				else
				{
					if ($val['tmp_name'])
					{
							$this->mRequestData[$var] = "@".$val['tmp_name'] . ';type=' . $val['type'] . ';filename=' . urlencode($val['name']);
					}
				}
			}
		}
	}

	public function addRequestData($name, $value)
	{
	    //处理@符号的问题防止curl传递过程中与文件上传冲突
	    if(gettype($value) == 'string' && $value[0] == '@')
	    {
	        $value = ' ' . $value;
	    }
		$this->mRequestData[$name] = $value;//urlencode
	}
	
	/**
	 * 
	 * 配合addGetData方法使用，用于重置数据 ...
	 */
	public function initGetData()
	{
		$this->mGetData = array();
	}
	
	/**
	 * 
	 * 当$mSubmitType设置为post时，提交自定义get数据使用，如果$mSubmitType为get时，此方法使用无意义 ...
	 */
	public function addGetData($name, $value)
	{
		$this->mGetData[$name] = $value;//urlencode
	}
	

	public function mPostContentType($type)
	{
		$this->mPostContentType = $type;
	}
	
	//设置curl超时(秒)
	public function setCurlTimeOut($time = 0)
	{
		$this->isSetTimeOut = $time;
	}

    public function request($file)
    {
		$para = '';
		if ('get' == $this->mSubmitType||$this->mGetData)
		{
			$getData = array();
			if($this->mGetData)
			{
				$getData = $this->mGetData;
			}
			else if($this->mRequestData)
			{
				$getData = $this->mRequestData;
			}
			foreach ($getData AS $k => $v)
			{
				$para .= '&' . $k . '=' . ($v);
			}
		}
		if (strpos($file, '?'))
		{
			$pachar = '&';
		}
		else
		{
			$pachar = '?';
		}
		$header = array();

        $domain = $this->mUrlHost;

		$url = $this->mRequestType . '://' . $domain . '/' . $this->mApiDir . $file . $pachar . 'format=' . $this->mReturnType . $para;

		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
		//curl_setopt($ch,CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)");
		//curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
		curl_setopt($ch,CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		if ($this->mCookies)
		{
			$cookies = implode(';', $this->mCookies);

			curl_setopt($ch, CURLOPT_COOKIE, $cookies);
		}
		if($this->mRequestType == 'https')
		{
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		}
		if ('post' == $this->mSubmitType)
		{
			$header[] = 'Expect:';
			curl_setopt($ch, CURLOPT_POST, true);
			if ($this->mPostContentType == 'string')
			{	
				$postdata = '';
				foreach ($this->mRequestData AS $k => $v)
				{
					$postdata .= '&' . $k . '=' . $v;
				}
			}
			else
			{
				$postdata = $this->mRequestData;
			}
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
		}
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        if($this->isSetTimeOut)
        {
        	curl_setopt($ch, CURLOPT_TIMEOUT, $this->isSetTimeOut);
        }
        $ret = curl_exec($ch);      

		$head_info = curl_getinfo($ch);
		$i = 0;
		while ($head_info['http_code'] != 200 && $i < 1)
		{
			$i++;
			$ret = curl_exec($ch);
			$head_info = curl_getinfo($ch);
		}
                while (substr($ret, 0,3) == pack("CCC",0xef,0xbb,0xbf))  //去除utf8 bom头
		{
			$ret = substr($ret, 3);
		}
        curl_close($ch);
		if($head_info['http_code']!= 200)
		{
			return '';
		}

        if ($ret == 'null')
        {
        	return '';
        }
      //  if ($this->mCharset != 'UTF-8')
        {
        	//$ret = iconv($this->mCharset, 'UTF-8', $ret);
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
			if(in_array($ret['ErrorCode'], array('APP_AUTH_EXPIRED', 'APP_NEED_AUTH', 'NO_APP_INFO','NO_ACCESS_TOKEN')))
			{
				return $ret;
			}
			if ($this->mErrorOut&&$ret['ErrorCode'])
			{
				$ret = array();
			}
			return $ret;
		}
		else
		{
			return $json;
		}
    }
    
 //用于将数组直接用json的方式提交到某一个地址
    public function curl_json($url,$data)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, 'data='.json_encode($data));
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		$response  = curl_exec($ch);
		$head_info = curl_getinfo($ch);
		if($head_info['http_code']!= 200)
		{
			$error = array('return' =>'fail');
			return json_encode($error);
		}
		curl_close($ch);//关闭
		return $response;
	}
	
	//直接提交文件到某一地址
	public function post_files($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this->mRequestData);
		$response  = curl_exec($ch);
		curl_close($ch);//关闭
		return $response;
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