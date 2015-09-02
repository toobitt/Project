<?php
/*******************************************************************
 * filename :UpYun.class.php
 * Created  :2013年8月8日,Writen by scala 
 * export_var(get_filename()."_b.txt",var,__LINE__,__FILE__);
 * 
 ******************************************************************/
 class UpYun implements ICdnConf,ICdnFile
 {
	private $btype = array(
		'file' => array('domain' => '.b0.aicdn.com', 'name' =>'文件空间'),
		'image' => array('domain' => '.b0.aicdn.com', 'name' =>'图片空间'),
		'cdn' => array('domain' => '.b0.aicdn.com', 'name' =>'静态空间'),
		'ucdn' => array('domain' => '.c1.aicdn.com', 'name' =>'动态空间'),
	);
 	public function  __construct()
 	{
 		$this->obj = new Core();
		global $gGlobalConfig;
		$this->settings = $gGlobalConfig;
 	}
 	
 	public function __destruct()
 	{
 		
 	}
 	
 	//cdn configure
 	function getCdnConf()
 	{
 		
 	}
	function addCdnConf()
	{
		
	}
	function delCdnConf()
	{
		
	}
	function updateCdnConf()
	{
		
	}

	//cdn configure
	
	//cdn file op
	/*
	 * define('MAX_DIR_NUM',10);				//需要刷新的 url 的地址,可以是多个,最多支持 100 条
     * define('MAX_URL_NUM',100);				//是需要刷新的目录的地址,最多支持 10 条
	 */
	function push($params)
	{
		include(CUR_CONF_PATH . 'lib/OAuth2Client.class.php');
		
		$upyun = $this->get_upyun_access_token();
		$access_token = $upyun['access_token'];
		$oauth = new OAuth2Client(OAUTH_CLIENT_ID, OAUTH_CLIENT_SECRET,
    						OAUTH_BASE_URI, OAUTH_AUTHORIZE_URI, OAUTH_ACCESS_TOKEN_URI);
    	$oauth->setAccessToken($access_token);
    	
    	//$params['urls'][0] = 'http://ce.wifiwx.com/ceess';
    	$return = array();
    	if(defined('CDN_CACHE_FILE') && CDN_CACHE_FILE =='preheat')
    	{
    		if($params['urls'] && is_array($params['urls']))
    		{
    			foreach($params['urls'] as $k=>$v)
    			{
    				$domains = $this->get_domains($v);
    				if($domains)
    				{
    					$data_ = array();
	    				$data_ = array(
							'url' 		=>	$v,
						);		
						
						$return = $oauth->request('/preheat/', 'POST', $data_);
    				}
    			}
    		}
    	}
    	else
    	{
    		if($params['urls'] && is_array($params['urls']))
    		{
    			foreach($params['urls'] as $k=>$v)
    			{
    				$domains = $this->get_domains($v);
    				if(!$domains)
    				{
    					unset($params['urls'][$k]);
    				}
    			}
    		}
    		if($params['urls'])
    		{
    			$urls = implode("\n",$params['urls']);
		       	$data = array(
					'urls' 				=>	$urls,
				);		
				
				$return = $oauth->request('/purge/', 'POST', $data);
				
				if(is_array($return) && in_array($return['message'] ,$this->settings['cdn']['token_error']))
				{
					$upy = $this->make_upyun_access_token();
					if($upy['access_token'])
					{
				        $oauth->setAccessToken($upy['access_token']);
						$return = $oauth->request('/purge/', 'POST', $data);
					}
				}
    		}
    	}		
		
		$push = $re = array();
		if(!empty($params['urls']))
		{
			$i = 0;
			$urls_ = $params['urls'];
			foreach ($urls_ as $v)
			{	
				if(strstr($v,"http")!==false)
				{
					$push[$i/CDN_MAX_DIR_NUM]['task']['urls'][$i%CDN_MAX_URL_NUM] = $v;
					$i++;
				}
			}
		}
		if(!empty($params['dirs']))
		{
			$j = 0;
			$dirs = $params['dirs'];
			foreach ($dirs as $v)
			{
				$push[$j/CDN_MAX_DIR_NUM]['task']['dirs'][$j%CDN_MAX_URL_NUM] = $v;
				$j++;
			}
		}
		
		$re = $this->obj->show('cdn_account');
		$account = $re['1'];
			
		if($push && is_array($push))
		{
			foreach($push as $k=>$v)
			{
				if($v['task']['urls'])
				{
					$re['username'] = $account['username'];
			        $re['password'] = $account['password'];
			        $re['task'] 	   = str_replace("\\", "",json_encode($v['task']));
				}
			}
		}
		if($re['task'] && $return)
		{
			$this->push_callback($return,$re,CDN_CACHE_FILE);
		}
		
	    return $return;
	}
	
	
	public function make_upyun_access_token()
	{
		$info = array();
		$re = $this->obj->show('cdn_account');
		$account = $re['1'];
       	$data = array(
			'username' 			=>	$account['username'],
			'password' 			=>	$account['password'],
			'grant_type' 		=>	'password',
			'client_id' 		=>	OAUTH_CLIENT_ID,
			'client_secret' 	=>	OAUTH_CLIENT_SECRET,
		);
		$oauth = new OAuth2Client(OAUTH_CLIENT_ID, OAUTH_CLIENT_SECRET,
    						OAUTH_BASE_URI, OAUTH_AUTHORIZE_URI, OAUTH_ACCESS_TOKEN_URI);
		$info = $oauth->request('/oauth/access_token/', 'POST', $data);
		if($info['access_token'])
		{
			if($info['expires_in'])
			{
				$info['expires_time'] = time()+$info['expires_in'];
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
		if(time() > $upyun['expires_time'])
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
	
	
	public function pushfordb($datas)
	{
		$params = array();
		$params = json_decode($datas['task'],1);
		$this->push($params);
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
	
	//处理返回的结果
	public function push_callback($return,$datas,$file='')
	{
		if($file =='preheat')
		{
			if($return['error_code'])
			{
				$data['remsg'] = $return['message'];
				$data['state'] = 0;
			}
			else
			{
				$data['remsg'] = 'SUCCESS';
			}
		}
		else
		{
			if(!is_array($return)){
				$data['remsg'] = $return;
				$data['state'] = 0;
			}
			elseif(!$return['result'][0]['count'])
			{
				$data['remsg'] = $return['result'][0]['status'];
				$data['state'] = 0;
			}
			else
			{
				$data['remsg'] = 'SUCCESS';
			}
		}
		
		$data['type'] = 'UpYun';
		$data['data'] = serialize($datas);
		$data['appid'] = intval($this->user['appid']);
		$data['appname'] = trim(($this->user['display_name']));
		$data['user_id'] = intval($this->user['user_id']);
		$data['user_name'] = $this->user['user_name'];
		$data['ip'] = hg_getip();
		$data['create_time'] = TIMENOW;
		$data['update_time'] = TIMENOW;
		$this->obj->insert('cdn_log',$data);
		
		return $return;
	}
	
	private function check_curl()
	{
		if(!$this->mych)
			$this->mych = curl_init();
		
		if(!$this->ConnectTimeOut)
			$this->ConnectTimeOut = 3;
		
		if(!$this->TimeOut)
			$this->TimeOut = 3;
	}
	
	private function close_curl()
	{
		if(!$this->mych)
			return true;
		curl_close($this->mych);
		return true;
	}
	private function postviacurl($params,$remote_server)
	{
		//$ch = curl_init();
		curl_setopt($this->mych, CURLOPT_URL, $remote_server);
		$post = '';
		foreach ($params as $key=>$val)
		{
			$post .= "$key=$val,";
		}
		curl_setopt($this->mych, CURLOPT_POSTFIELDS, $params);
		curl_setopt($this->mych, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->mych, CURLOPT_CONNECTTIMEOUT, $this->ConnectTimeOut);
		curl_setopt($this->mych, CURLOPT_TIMEOUT, $this->TimeOut);
		$data = curl_exec($this->mych);
		return $data;
	}
	
	public function delete($id)
	{
		$datas = $this->obj->delete('cdn_log',' where id in('.$id.')');
		
		return $id;
	}
	
	public function get_domains($domain)
	{
		$cache_file = CACHE_DIR . 'buckets.info';
		$domains = array();
		if (!is_file($cache_file) || filemtime($cache_file) >= (time() - 300))
		{
			$upyun = $this->get_upyun_access_token();
		
			$oauth = new OAuth2Client(OAUTH_CLIENT_ID, OAUTH_CLIENT_SECRET,
								OAUTH_BASE_URI, OAUTH_AUTHORIZE_URI, OAUTH_ACCESS_TOKEN_URI);
			$oauth->setAccessToken($upyun['access_token']);
			$info = $oauth->request('/buckets/?limit=100', 'GET');
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
						$tmp = array();
						foreach($info2['approval_domains'] as $ke=>$va)
						{
							$domains[] = $va;
							$tmp[] = $va;
						}
						if($domains)
						{
							$info['buckets'][$k]['domain'] = implode(',',$tmp);
						}
						$info['buckets'][$k]['cname'] = $val['bucket_name'] . $this->btype[$val['type']]['domain'];
						$info['buckets'][$k]['type'] = $this->btype[$val['type']]['name'];
					}
				}
       		 	file_put_contents($cache_file, json_encode($info['buckets']));
			}
		}
		else
		{
			$info['buckets'] = json_decode(file_get_contents($cache_file), 1);
			foreach($info['buckets'] as $k => $v)
			{
				$d = explode(',', $v['domain']);
				$domains = array_merge($domains, $d);
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
	
	function addCdnFile()
	{

		
	}
	function delCdnFile()
	{
		
	}
	function getCdnFile()
	{
		
	}
    
	function updateCdnFile()
	{
		
	}
	//获取回调,参数数据
	private function getCallback()
	{
		//$cdncallback['callback'] = $gGlobalConfig['chinacache']['callback'];
		//$cdncallback['username'] = $gGlobalConfig['chinacache']['username'];
		//$cdncallback['password'] = $gGlobalConfig['chinacache']['password'];
		$cdncallback = $this->settings['UpYun']['callback'];
		return $cdncallback;
	}
	//cdn file op end
 }
 