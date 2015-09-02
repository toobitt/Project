<?php
$_INPUT['access_token'] = '';
abstract class appstore_frm extends appCommonFrm
{
	private $mCustomerKey;
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}

	protected function verifyToken()
	{
		$gAuthServerConfig = $this->settings['App_serverAuth'];
		if(!$gAuthServerConfig)
		{
			$this->errorOutput(LINK_AUTH_SERVER_FAILED);
		}
		if(!class_exists('curl'))
		{
			include_once(ROOT_PATH . 'lib/class/curl.class.php');
		}
		$tokenfile = '/tmp/customerToken/';
		if (!is_dir($tokenfile))
		{
			@mkdir($tokenfile);
		}
		
		$tokenfile .= md5($this->input['appid'] . $this->input['appkey']);

		$this->mCustomerKey = $this->input['appkey'];
		$token_expire = $this->settings['token_expire'] ? $this->settings['token_expire'] : 600;
		if (is_file($tokenfile) && (TIMENOW - filemtime($tokenfile)) <= $token_expire)
		{
			$this->user = json_decode(file_get_contents($tokenfile), 1);
			return;
		}
		$curl = new curl($gAuthServerConfig['host'], $gAuthServerConfig['dir']);
		$curl->initPostData();
		$postdata = array(
			'appid'			=>	$this->input['appid'],
			'appkey'		=>	$this->input['appkey'],
			'a'				=>	'get_user_info',
		);
		foreach ($postdata as $k=>$v)
		{
			$curl->addRequestData($k, $v);
		}
		$ret = $curl->request('get_access_token.php');
		//判定终端是否需要登录授权
		if($ret['ErrorCode'])
		{
			$this->errorOutput($ret['ErrorCode']);
		}
		$this->user = array();
		if($ret && is_array($ret[0]))
		{
			$this->user['id'] = $ret[0]['appid'];
			$this->user['customer_name'] = $ret[0]['user_name'];
			$this->user['install_type'] = $ret[0]['install_type'];
			$this->user['app_limit'] = $ret[0]['app_limit'];
			$this->user['yunhosts'] = $ret[0]['yunhosts'];
			$this->user['openhelp'] = $ret[0]['openhelp'];
		}
       	@file_put_contents($tokenfile, json_encode($this->user));
	}
	public function en($str, $salt = '')
	{
		if (!$salt)
		{
			$salt = $this->mCustomerKey;
		}
		$saltlen = strlen($salt);
		$str = base64_encode($str);
		$str = str_replace('=', '', $str);
		$len = strlen($str);
		$lenlen = strlen($len);
		$templen = strval($len);
		$newstr = '';
		$h = 0;
		for($i=0; $i < $len; $i++)
		{
			$j = $i % $saltlen;
			$m = $i % 20;
			$n = ceil($m/$saltlen);
			$newstr .= $str[$i];
			if ($i % 2 != 0 && $h < $lenlen)
			{
				$newstr .= $templen[$h];
				$h++;
			}
			for ($k = 0; $k < $n; $k++)
			{
				$l = ($k + $j) % $saltlen;
				$newstr .= $salt[$l];
			}
		}		
		return $lenlen . $newstr;
	}
	
	public function de($str, $salt = '')
	{
		if (!$str)
		{
			return array();
		}
		if (!$salt)
		{
			$salt = $this->mCustomerKey;
		}
		if (function_exists('hoge_de'))
		{
			return hoge_de($str, $salt);
		}
		$saltlen = strlen($salt);
		$lenlen = $str[0];
		$len = '';
		$pos = 3;
		for($i = 0; $i < $lenlen; $i++)
		{
			$len .= $str[$pos];
			$pos += 5;
		}
		$len = intval($len);
		$str = substr($str, 1);
		$newstr = '';
		$offset = 0;
		for($i = 0 ; $i < $len;$i++)
		{			
			if (!$i)
			{
				$index = $i;
			}
			$newstr .= $str[$index];
			$m = $i % 20;
			$n = ceil($m/$saltlen);
			$index = $index + $n + 1;
			if ($i > 0 && $i % 2 != 0 && $offset < $lenlen)
			{
				$index++;
				$offset++;
			}
		}	
		return json_decode(base64_decode($newstr), 1);
	}
	
}
abstract class admin_appstore_frm extends appCommonFrm
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
}

?>