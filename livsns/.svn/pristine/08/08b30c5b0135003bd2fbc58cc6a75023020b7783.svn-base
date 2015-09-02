<?php 
class m2oLive extends InitFrm
{
	protected  $mNginx;
	protected  $mNginxApiEnabled = false;
	public function __construct($service = array())
	{
		parent::__construct();
		if($service)
		{
			$this->init_env($service);
		}
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	//初始化直播服务环境
	public function init_env($service)
	{
		if(!$service)
		{
			return false;
		}
		
		//协议
		$service['protocol'] = $service['protocol'] ? $service['protocol'] : 'http://';
		
		//
		if(!$service['host'] || !$service['dir'])
		{
			return false;
		}
		$service['host'] = trim($service['host'], '/');
		
		$service['dir'] = trim($service['dir'], '/') . '/';
		if(strpos($service['dir'], ':') === False)
		{
			$service['dir'] = '/' . ltrim($service['dir'], '/');
		}
		
		$this->mNginx = $service['protocol'] . $service['host']  . $service['dir'];
		
		$this->mNginxApiEnabled = true;
		
	}
	public function create($parameters = array())
	{
		if(!$this->mNginxApiEnabled)
		{
			return true;
		}
		$postdata = array(
		'srv' 		=> $parameters['srv'] ? $parameters['srv'] : 0,
		'app' 		=> $parameters['app'] ? $parameters['app'] : 'live',
		'name'		=> $parameters['name'] ? $parameters['name'] : '',
		'url'		=> $parameters['url'] ? $parameters['url'] : '',
		'tc_url'	=> $parameters['tc_url'] ? $parameters['tc_url'] : '',
		'page_url'	=> $parameters['page_url'] ? $parameters['page_url'] : '',
		'swf_url'	=> $parameters['swf_url'] ? $parameters['swf_url'] : '',
		'flash_ver'	=> $parameters['flash_ver'] ? $parameters['flash_ver'] : '',
		'play_path'	=> $parameters['play_path'] ? $parameters['play_path'] : '',
		'mysql_table' => $parameters['mysql_table'] ? $parameters['mysql_table'] : '',
		);
		//file_put_contents(CACHE_DIR . 'debug.txt', var_export($postdata,1), FILE_APPEND);
		if(!$parameters['name'] || !$parameters['url'])
		{
			return false;
		}
		if(!$this->request($this->mNginx . 'add/static_pull',$postdata))
		{
			return false;
		}
		return true;
	}
	public function update($create=array(), $delete = array())
	{
		if(!$this->mNginxApiEnabled)
		{
			return true;
		}
		$postdata = array(
		'srv' 		=> $create['srv'] ? $create['srv'] : 0,
		'app' 		=> $create['app'] ? $create['app'] : 'live',
		'name'		=> $create['name'] ? $create['name'] : '',
		'url'		=> $create['url'] ? $create['url'] : '',
		'tc_url'	=> $create['tc_url'] ? $create['tc_url'] : '',
		'page_url'	=> $create['page_url'] ? $create['page_url'] : '',
		'swf_url'	=> $create['swf_url'] ? $create['swf_url'] : '',
		'flash_ver'	=> $create['flash_ver'] ? $create['flash_ver'] : '',
		'play_path'	=> $create['play_path'] ? $create['play_path'] : '',
		);
		$delete = array(
		'srv' 		=> $create['srv'] ? $create['srv'] : 0,
		'app' 		=> $create['app'] ? $create['app'] : 'live',
		'name'		=> $create['name'] ? $create['name'] : '',
		);
		if(!$postdata['name'] || !$postdata['url'])
		{
			return false;
		}
		//file_put_contents(CACHE_DIR . 'debug.txt', var_export($delete,1));
		if(!$this->request($this->mNginx . 'drop/static_pull',$delete))
		{
			//file_put_contents(CACHE_DIR . 'debug.txt', var_export($delete,1), FILE_APPEND);
			//return false;
		}
		
		if(!$this->request($this->mNginx . 'add/static_pull',$postdata))
		{
			return false;
		}
		return true;
	}
	public function delete($parameters)
	{
		if(!$this->mNginxApiEnabled)
		{
			return true;
		}
		$postdata = array(
		'srv' 		=> $parameters['srv'] ? $parameters['srv'] : 0,
		'app' 		=> $parameters['app'] ? $parameters['app'] : 'live',
		'name'		=> $parameters['name'] ? $parameters['name'] : '',
		);
		//file_put_contents(CACHE_DIR . 'debug.txt', var_export($postdata,1), FILE_APPEND);
		if(!$parameters['name'])
		{
			return false;
		}
		if(!$this->request($this->mNginx . 'drop/static_pull',$postdata))
		{
			return false;
		}
		return true;
	}
	public function select()
	{
		if(!$this->mNginxApiEnabled)
		{
			return true;
		}
		if($respnce = $this->request($this->mNginx . 'get/all_streams', '', 'servers'))
		{
			//file_put_contents(CACHE_DIR . 'debug.txt', var_export($respnce,1), FILE_APPEND);
			return $respnce;
		}
		return false;
	}
	public function set_timeshift_length($parameters = array())
	{
		if(!$this->mNginxApiEnabled)
		{
			return true;
		}
		$postdata = array(
		'srv' 		=> $parameters['srv'] ? $parameters['srv'] : 0,
		'app' 		=> $parameters['app'] ? $parameters['app'] : 'live',
		'name'		=> $parameters['name'] ? $parameters['name'] : '',
		'playlen'	=> $parameters['playlen'] ? 1000*3600*intval($parameters['playlen']) : '',//单位毫秒
		);
		if(!$postdata['name'] || !$postdata['playlen'] || !$postdata['app'])
		{
			return false;
		}
		if(!$this->request($this->mNginx . 'set/hls_playlen',$postdata))
		{
			return false;
		}
		return true;
	}
	/*
	 * 检查TS流目录存储
	 */
	public function check_ts_path($parameters = array())
	{
		if(!$this->mNginxApiEnabled)
		{
			return true;
		}
		//获取直播服务器磁盘信息 (存储单位是字节)
		$return = $this->request($this->mNginx . 'get/fs_status', false, 'fs_statuses');
		if(!$return)
		{
			return false;
		}
		
		$hls_path = unserialize($parameters['hls_path']);
		foreach((array)$return as $k => $v)
		{
			if($hls_path['base_hls_path'] == $v['dir'])
			{
				if($parameters['needsize'] >= $v['size'])
				{
					return false;
				}
			}
		}
		return true;
	}
	/*
	 * 设置时移目录
	 */
	public function set_timeshift_path($parameters = array())
	{
		if(!$this->mNginxApiEnabled)
		{
			return true;
		}
		$tmp = unserialize($parameters['hls_path']);
		$hls_path = $tmp['base_path'].$tmp['hls_path'];
		$postdata = array(
		'app' 		=> $parameters['app'] ? $parameters['app'] : 'live',
		'name'		=> $parameters['name'] ? $parameters['name'] : '',
		'hls_path'	=> $hls_path ? $hls_path : '',
		);
		if(!$postdata['name'] || !$postdata['hls_path'] || !$postdata['app'])
		{
			return false;
		}
		if(!$this->request($this->mNginx . 'set/hls_path',$postdata))
		{
			return false;
		}
		return true;
	}
	public function get_timeshift_length()
	{
		if($responce = $this->request($this->mNginx . 'get/hls_playlen', 'servers'))
		{
			return $responce;
		}
		return false;
	}
	public function stop($parameters = array())
	{
		if(!$this->mNginxApiEnabled)
		{
			return true;
		}
		$app = $parameters['app'] ? $parameters['app'] : 'live';
		$name = $parameters['name'] ? $parameters['name'] : '';
		if(!$name)
		{
			return false;
		}
		$postdata = array(
		'app'=>$app,
		'name'=>$name,
		);
		if(!$this->request($this->mNginx . 'stop/static_pull',$postdata))
		{
			return false;
		}
		return true;
	}
	public function start($parameters = array())
	{
		if(!$this->mNginxApiEnabled)
		{
			return true;
		}
		$app = $parameters['app'] ? $parameters['app'] : 'live';
		$name = $parameters['name'] ? $parameters['name'] : '';
		if(!$name)
		{
			return false;
		}
		$postdata = array(
		'app'=>$app,
		'name'=>$name,
		);
		if(!$this->request($this->mNginx . 'start/static_pull',$postdata))
		{
			return false;
		}
		return true;
	}
	public function restart($parameters = array())
	{
		if(!$this->mNginxApiEnabled)
		{
			return true;
		}
		if($this->stop($parameters))
		{
			return $this->start($parameters);
		}
		return false;
	}
	private function request($url,$postdata, $return_data = '')
	{
        $ch = curl_init();
        if($postdata)
        {
        	if(strpos($url, '?') === false)
        	{
        		$url = $url . '?';
        	}
        	foreach($postdata as $key=>$val)
        	{
        		$url .= $key . '=' . $val . '&';
        	}
        	$url = rtrim($url, '&');
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        //file_put_contents(CACHE_DIR . 'debug.txt', $url, FILE_APPEND);
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); 
		curl_setopt($ch,CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $ret = curl_exec($ch);
		$head_info = curl_getinfo($ch);
        curl_close($ch);
		if($head_info['http_code']!= 200)
		{
        	return false;
		}
		$ret = json_decode($ret,1);
		if(!$return_data)
		{
			return $ret['return'] == 'ok' ? true : false;
		}
		return $ret[$return_data];
	}
	/*
	 * 发送数据库信息
	 */
	public function set_database($parameters = array())
	{
		foreach((array)$parameters as $v)
		{
			if(!trim($v))
			{
				return false;
			}
		}
		if(!$this->request($this->mNginx . 'set/sql_info',$parameters))
		{
			return false;
		}
		return true;
	}
	/*
	 * 获取服务器运行状态
	 */
	public function get_status()
	{
		if(!$this->request($this->mNginx . 'get/status',$parameters))
		{
			return false;
		}
		return true;
	}
}
?>