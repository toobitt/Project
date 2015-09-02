<?php
include(CUR_CONF_PATH . 'lib/OAuth2Client.class.php');
 class UpYunApi extends InitFrm
 {
 	public function  __construct()
 	{
 		parent::__construct();
 	}
 	
 	public function __destruct()
 	{
 		
 	}
	public function newOAuth()
	{
		 return new OAuth2Client(OAUTH_CLIENT_ID, OAUTH_CLIENT_SECRET,
    						OAUTH_BASE_URI, OAUTH_AUTHORIZE_URI, OAUTH_ACCESS_TOKEN_URI);
	}
	public function make_upyun_access_token()
	{
		$info = array();
       	$data = $this->settings['UpYun'];
		$oauth = $this->newOAuth();
		$info = $oauth->request('/oauth/access_token/', 'POST', $data);
		if($info['access_token'])
		{
			if($info['expires_in'])
			{
				$info['expires_time'] = TIMENOW+$info['expires_in'];
			}
			
			$upyun_str  = serialize($info);

	        $returnstr = "<?php\r\n";
	        $returnstr .= "\$upyun_info = array(";
	        $returnstr .= "'upyun'  => " . "'" . $upyun_str . "',";
	        $returnstr .= ");\r\n?>";
	
	        $filename = 'upyun.php';
	        $name     = CACHE_DIR . $filename;
	        file_put_contents($name, $returnstr);
	        return $info ;
		}
		else
		{
			 return $info ;
		}
	}
	
	public function get_upyun_access_token()
	{
		$f    = CACHE_DIR. 'upyun.php';
		$re = file_exists($f);
		if(!$re)
		{
			$this->make_upyun_access_token();
			$re = file_exists($f);
		}
		if($re)
		{
			include($f);
	        if(empty($upyun_info['upyun']))
	        {
	        	$this->make_upyun_access_token();
	        	include($f);
	        }
		}
		$upyun = unserialize($upyun_info['upyun']);
		if(TIMENOW > $upyun['expires_time'])
		{
			$this->make_upyun_access_token();
			if($re)
			{
        		include($f);
			}
        	$upyun = unserialize($upyun_info['upyun']);
		}
		return	$upyun;
	}	
	 
	function curlrequest($url,$data,$method='post')
	{
	    $ch = curl_init(); //初始化CURL句柄
	    curl_setopt($ch, CURLOPT_URL, $url); //设置请求的URL
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //设为TRUE把curl_exec()结果转化为字串，而不是直接输出
	    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method); //设置请求方式
	    curl_setopt($ch,CURLOPT_HTTPHEADER,array("X-HTTP-Method-Override: $method"));//设置HTTP头信息
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//设置提交的字符串
	    $document = curl_exec($ch);//执行预定义的CURL
	    if(!curl_errno($ch)){
		      $info = curl_getinfo($ch);
		      echo 'Took ' . $info['total_time'] . ' seconds to send a request to ' . $info['url'];
	    } 
	    else 
	    {
	      echo 'Curl error: ' . curl_error($ch);
	    }
	    curl_close($ch);
	    return $document;
	}
	
	public function get_domains($domain)
	{
		$domains = array();
		$upyun = $this->get_upyun_access_token();
	
		$oauth = new OAuth2Client(OAUTH_CLIENT_ID, OAUTH_CLIENT_SECRET,
    						OAUTH_BASE_URI, OAUTH_AUTHORIZE_URI, OAUTH_ACCESS_TOKEN_URI);
    	$oauth->setAccessToken($upyun['access_token']);
		$info = $oauth->request('/buckets/', 'GET');
		
		if($info['buckets'] && is_array($info['buckets']))
		{
			foreach($info['buckets'] as $k => $v)
			{
				$data = array(
					'bucket_name'		=> $v['bucket_name'],
				);
				$info2 = $oauth->request('/buckets/info/', 'GET',$data);
				if($info2['approval_domains'] && is_array($info2['approval_domains']))
				{
					foreach($info2['approval_domains'] as $ke=>$va)
					{
						$domains[] = $va;
					}
				}
			}
		}
		preg_match('@^(?:http://)?([^/]+)@i',$domain, $matches);
		$host = $matches[1];
		if(in_array($host,$domains))
		{
			return 1;
		}
		else
		{
			return 0;
		}
		
	}
	/**
	 * 
	 * 申请空间 ...
	 * @param unknown_type $config
	 */
	public function spaceApply($config)
	{
		$ret = array();
		$data = array(
			'bucket_name'		=> $config['bucket_name'],
            'type'				=> $config['type'],
            'quota'				=> $config['quota'],
		);
		if($data['type']&&!array_key_exists($data['type'], $this->settings['space']['space_type']))
		{
			return $ret;
		}
		$ret = $this->OauthCrul('/buckets/', 'PUT',$data);
		return $ret;
	}
	/**
	 * 
	 * 空间域名绑定或者解除绑定 ...
	 * @param unknown_type $config
	 */
	public function spaceBindDomain($config,$op = 'PUT')
	{
		$ret = array();
		$data = array(
			'bucket_name'		=> $config['bucket_name'],
            'domain'			=> $config['domain'],
		);
		$ret = $this->OauthCrul('/buckets/domains/',$op,$data);
		return $ret;
	}
	/**
	 * 
	 * 操作员授权或者取消授权 ...
	 * @param unknown_type $config
	 */
	public function spaceOperatorsAuth($config,$op = 'PUT')
	{
		$ret = array();
		$data = array(
			'bucket_name'		=> $config['bucket_name'],
            'operator_name'			=> $config['operator_name'],
		);
		$ret = $this->OauthCrul('/buckets/operators/',$op,$data);
		return $ret;
	}
	
	public function getSpaceInfo($bucket_name)
	{
		$data = array(
			'bucket_name'		=> $bucket_name,
		);
		$ret = array();
		$ret = $this->OauthCrul('/buckets/info/','GET',$data);
		return $ret;
	}
	
	public function OauthCrul($route,$op = 'PUT',$params=array())
	{
		$oauth = $this->newOAuth();
    	$upyun = $this->get_upyun_access_token();
    	$oauth->setAccessToken($upyun['access_token']);
		return $oauth->request($route,$op,$params);
	}
	public function UpFormApi($config)
	{
		$data = array(
			'bucket_name'		=> $config['bucket_name'],
			'status'				=> $config['status'],
			'update_key'		=> $config['update_key'],
		);
		$ret = array();
		$ret = $this->OauthCrul('/buckets/formapi/','POST',$data);
		return $ret;
	}
 	public function BucketInfo($config)
	{
		$data = array(
			'bucket_name'		=> $config['bucket_name'],
		);
		$ret = array();
		$ret = $this->OauthCrul('/buckets/info/','GET',$data);
		return $ret;
	}
 	public function BucketStatus($config)
	{
		$data = array(
			'bucket_name'		=> $config['bucket_name'],
			'domain'			=> $config['domain'],
			'period'			=> $config['period'],
			'start_day'			=> $config['start_day'],
		);
		$ret = array();
		$ret = $this->OauthCrul('/stats/','GET',$data);
		return $ret;
	}
 	public function BucketQuota($config)
	{
		$data = array(
			'bucket_name'		=> $config['bucket_name'],
			'quota'			=> $config['quota'],
		);
		$ret = array();
		$ret = $this->OauthCrul('/buckets/quota/','POST',$data);
		return $ret;
	}
 }
 