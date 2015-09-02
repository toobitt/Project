<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: curl.class.php 8466 2014-11-06 07:33:24Z youzhenghuan $
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
	public $mAutoInput = true;
	public $mNotInitedNeedExit = true;
	private $isSetTimeOut = 30;//设置curl超时时间
    private $mReponseHeader = false;
    private $mCurlInfo = array();
	function __construct($host = '', $apidir = '', $token='',$stype = 'post' , $request_type = 'http')
	{
		global $gGlobalConfig, $_INPUT, $gUser;
		$this->globalConfig = $gGlobalConfig;
		$this->input = $_INPUT;
		$this->user = $gUser;
		$this->setUrlHost($host, $apidir);
		$this->setToken($this->user['token']);
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

	public function setToken($token)
	{
		$this->mToken = $token;
	}
	public function setClient($appid = APPID, $appkey = APPKEY)
	{
		$this->mAppid = $appid;
		$this->mAppkey = $appkey;
	}

	public function setErrorReturn($type = 'exit')
	{
		$this->mErrorReturn = $type;
	}

	public function setRequestType($type)
	{
		$this->mRequestType = $type;
	}

	public function setSubmitType($type)
	{
		$this->mSubmitType = $type;
	}
	public function setmAutoInput($type)
	{
		$this->mAutoInput = $type;
	}
    public function setReponseHeader($type = false)
    {
        $this->mReponseHeader = $type;
    }

    public function getInfo($opt)
    {
        if ($opt)
        {
            if (isset($this->mCurlInfo[$opt]))
            {
                return $this->mCurlInfo[$opt];
            }
        }
        else
        {
            return $this->mCurlInfo;
        }
        return ;
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
						if($fname&&is_array($fname))
						{
							foreach ($fname as $fname_k => $fname_v)
							{
								if($fname_v)
								{
									$this->mRequestData[$var . "[$k]"."[$fname_k]"] = "@".$fname_v . ';type=' . $val['type'][$k][$fname_k] . ';filename=' . $val['name'][$k][$fname_k];
								}
							}
						}
						elseif($fname) {
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

	public function array_to_add($str, $data)
	{
		$str = $str ? $str : 'data';
		if (is_array($data))
		{
			foreach ($data AS $kk => $vv)
			{
				if (is_array($vv))
				{
					$this->array_to_add($str . "[$kk]" , $vv);
				}
				else
				{
					$this->addRequestData($str . "[$kk]", $vv);
				}
			}
		}
		else
		{
			$this->addRequestData($str, $data);
		}
	}
	
	public function addRequestData($name, $value)
	{
		$this->mRequestData[$name] = $value;
	}

	//设置curl超时(秒)
	public function setCurlTimeOut($time = 0)
	{
		$this->isSetTimeOut = $time;
	}
    public function request($file)
    {
		/*
		 * 接口基类方法verifyToken根据token获取登录用户信息
		 * 或者根据客户端ID和客户端KEY也可以获取虚拟用户信息（超级用户权限）
		 */
		$para = '&appid=' . $this->mAppid;
		$para .= '&appkey=' . $this->mAppkey;
		if($this->mToken)
		{
			$para .= '&access_token=' . $this->mToken;
		}
		$para .= '&lpip=' . hg_getip();

		if ($this->input && $this->mAutoInput)
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
								//防止用户提交的内容的字符开头是@符
								if($vvv && $vvv[0] == '@')
								{
									$vvv = ' ' . $vvv;
								}
								$this->addRequestData($k . "[$kk]" . "[$kkk]", $vvv);
							}
						}
						else
						{
							//防止用户提交的内容的字符开头是@符
							if($vv && $vv[0] == '@')
							{
								$vv = ' ' . $vv;
							}
							$this->addRequestData($k . "[$kk]", $vv);
						}
					}
				}
				else
				{
					//防止用户提交的内容的字符开头是@符
					if($v && $v[0] == '@')
					{
						$v = ' ' . $v;
					}
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
		if($file=='news.php'&&$this->mRequestData['a']=='show')
		{
	//	hg_pre($url);
		}
		if (DEBUG_MODE)
		{
			if (!$file)
			{
				$file = 'index';
			}
			hg_debug_tofile($url, 0, date('Y/m/d/') . $this->mApiDir, $file . '.txt');
			hg_debug_tofile($this->mRequestData, 1, date('Y/m/d/') . $this->mApiDir, $file . '.txt');
		}
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
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $this->mRequestData);
		}
        if ($this->mReponseHeader)
        {
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_NOBODY, false);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);	
        if($this->isSetTimeOut)
        {
        	curl_setopt($ch, CURLOPT_TIMEOUT, $this->isSetTimeOut);
        }
        $ret = curl_exec($ch);	
		$head_info = $this->mCurlInfo = curl_getinfo($ch);
        curl_close($ch);
		if($head_info['http_code']!= 200)
		{
			if (DEBUG_MODE)
			{
				hg_debug_tofile($head_info, 1, date('Y/m/d/') . $this->mApiDir, $file . '.txt');
			}
			if ($this->mErrorReturn == 'exit')
			{
				$uiview = new uiview();
				$uiview->ReportError('服务器访问接口[' . $url . ']异常,错误:' . $head_info['http_code']);
			}
			else
			{
        		return '';
			}

		}
		if (DEBUG_MODE)
		{
			hg_debug_tofile($ret, 0, date('Y/m/d/') . $this->mApiDir, $file . '.txt');
		}
        if($ret == 'null')
        {
        	return '';
        }
        		//	file_put_contents('1.txt', var_export('1',1));	
        
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
			if (in_array($ret['ErrorCode'], array(USER_NOT_LOGIN, NO_ACCESS_TOKEN)))
			{
				$_SESSION['livmcp_userinfo'] = array();
				if (!$this->input['ajax'])
				{
					header('Location:' . ROOT_DIR . 'login.php');
				}
				else
				{
					$data = array(
						'msg' => '请先登录',
						'callback' => "hg_ajax_post({href: 'login.php'}, '登录');",
					);
					echo json_encode($data);
					exit;
				}
			}
			elseif ($this->mNotInitedNeedExit && in_array($ret['ErrorCode'], array(NOT_INITED)) && $this->input['mid'])
			{
				header('Location:' . ROOT_DIR . 'settings.php?mid=' . $this->input['mid'] . '&a=configuare');
			}
			elseif (substr($ret['ErrorCode'], 0, 8) == 'REDIRECT')
			{
				$to = explode(' TO ', $ret['ErrorCode']);
				$to = explode(' ', $to[1]);
				$url = 'run.php?a=relate_module_show&app_uniq=' . trim($to[0]) . '&mod_uniq=' . trim($to[1]);
				header('Location:' . $url . '&infrm=1');
			}

			if ($ret['ErrorCode'] && $this->mErrorReturn == 'exit')
			{
				$uiview = new uiview();
				$uiview->ReportError($ret['ErrorCode'] . $ret['ErrorText']);
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