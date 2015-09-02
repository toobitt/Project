<?php
/*******************************************************************
 * filename :chinacache.class.php
 * Created  :2013年8月8日,Writen by scala 
 * export_var(get_filename()."_b.txt",var,__LINE__,__FILE__);
 * 
 ******************************************************************/
 class ChinaCache implements ICdnConf,ICdnFile
 {
    public $mych = null;
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
	    if(!defined('ChinaCache_UserName'))
            return false;
        if(!defined('ChinaCache_Password'))
            return false;
		if(empty($params))
			return false;
		if(!empty($params['urls']))
		{
			
			$i = 0;
			$urls = $params['urls'];
			foreach ($urls as $v)
			{
				if(strstr($v,"http")!==false)
				{
					$push[$i/CDN_MAX_DIR_NUM]['task']['urls'][$i%CDN_MAX_URL_NUM] = $v;
					$i++;
				}
				
			}
		}
		
        if(!defined('CDN_MAX_DIR_NUM'))
        {
            define('CDN_MAX_DIR_NUM',10);
        }
        
        if(!defined('CDN_MAX_URL_NUM'))
        {
            define('CDN_MAX_URL_NUM',100);
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
        $flag = 0;
		foreach($push as $push_type=>$values)
        {
            if(empty($values))
            $flag++;
        }
        
        if($flag==2)
        {
            return false;
        }
        
		$callback = $this->getCallback();
		
		foreach ($push as $k=>$v)
		{
			$push[$k]['task']['callback'] = $callback;
			//$push[$k]['task'] = $callback;
		}
		
		$apiurl	  		   = $this->settings['ChinaCache']['apiurl'];
		$resturn = array();
		
		$this->check_curl();
		
		foreach ($push as $v)
		{
			$datas = $v;
			/*
			 * 
			$datas['username'] = $this->settings['ChinaCache']['username'];
			$datas['password'] = $this->settings['ChinaCache']['password'];
			*/
			$datas['username'] = ChinaCache_UserName;
            $datas['password'] = ChinaCache_Password;
			//$datas['callback'] = str_replace("\\", "",json_encode($datas['callback']));
			$datas['task'] 	   = str_replace("\\", "",json_encode($datas['task']));
			//echo json_encode($datas);
			if($v['task']['urls'])
			{
				$return[] = $this->push_callback($this->postviacurl($datas,$apiurl),$datas);
			}
			
			//var_dump($this->curl_post($datas));
		}
		$this->close_curl();
		return $return;
		
	}
	public function pushfordb($datas)
	{
		$apiurl = $this->settings['ChinaCache']['apiurl'];
		/**
		 * 有可能之前的配置的密码不正确，以配置的账号为准
		 */
		 
		//$datas['username'] = $this->settings['ChinaCache']['username'];
		//$datas['password'] = $this->settings['ChinaCache']['password'];
        
        $datas['username'] = ChinaCache_UserName;
        $datas['password'] = ChinaCache_Password;
		$this->check_curl();
		$return = $this->postviacurl($datas,$apiurl);
		$re = $this->push_callback($return,$datas);
		//$this->close_curl();
	}
	
	//处理返回的结果
	public function push_callback($return,$datas)
	{
		$remsg = json_decode($return,1);
	
		if(!is_array($remsg)){
			$data['remsg'] = $return;
			$data['state'] = 0;
		}else{
			$data['remsg'] = 'SUCCESS';
			$data['reid'] = $remsg['r_id'];
		}
		$data['type'] = 'ChinaCache';
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
	
	public function check_curl()
	{
		if(!$this->mych)
			$this->mych = curl_init();
		
		if(!$this->ConnectTimeOut)
			$this->ConnectTimeOut = 3;
		
		if(!$this->TimeOut)
			$this->TimeOut = 3;
	}
	
	public function close_curl()
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
		// $post = '';
		// foreach ($params as $key=>$val)
		// {
			// $post .= "$key=$val,";
		// }
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
		$cdncallback = $this->settings['ChinaCache']['callback'];
		return $cdncallback;
	}
	//cdn file op end
 }
 