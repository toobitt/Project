<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
***************************************************************************/
define('ROOT_PATH', '../../../../');
define('CUR_CONF_PATH', '../../');
define('SCRIPT_NAME', 'hg_news');
define('WITHOUT_DB', true);
define('CUSTOM_APPKEY', '');
require(ROOT_PATH."global.php");
require(CUR_CONF_PATH."lib/functions.php");
require(ROOT_PATH . 'lib/class/curl.class.php');
class hg_news extends BaseFrm
{
	protected $hg_extend_api	= '';
	protected $hg_agruments 	= 'a:6:{s:13:"argument_name";a:2:{i:0;s:9:"方法名";i:1;s:8:"文稿id";}s:5:"ident";a:2:{i:0;s:1:"a";i:1;s:2:"id";}s:11:"ident_input";a:2:{i:0;s:0:"";i:1;s:0:"";}s:5:"value";a:2:{i:0;s:6:"detail";i:1;s:5:"10232";}s:8:"val_type";a:2:{i:0;s:1:"0";i:1;s:1:"0";}s:10:"add_status";a:2:{i:0;s:1:"0";i:1;s:1:"1";}}';
	protected $hg_settings 		= 'a:23:{s:2:"id";s:4:"1029";s:9:"file_name";s:20:"airport_bus_info.php";s:6:"bundle";s:4:"news";s:12:"request_file";s:8:"news.php";s:4:"host";s:0:"";s:3:"dir";s:0:"";s:8:"protocol";s:1:"1";s:12:"request_type";s:1:"1";s:11:"data_format";s:1:"1";s:9:"data_node";s:0:"";s:17:"extend_api_switch";s:1:"0";s:10:"extend_api";s:0:"";s:7:"map_val";s:0:"";s:5:"token";s:0:"";s:5:"uname";s:0:"";s:3:"pwd";s:0:"";s:6:"status";s:1:"0";s:7:"sort_id";s:2:"56";s:13:"direct_return";s:1:"0";s:7:"codefmt";s:0:"";s:12:"cache_update";s:1:"0";s:12:"static_cache";s:1:"0";s:8:"sort_dir";s:6:"ceshi/";}';
	protected $hg_map_val 		= '';
	protected $hg_maps 			= '';
	
	protected $hg_mobile_cache;
	protected $hg_mobile_cache_path;
	protected $hg_mobile_ymd;
	
	function __construct()
	{
		parent::__construct();
		
		//初始化数据
		$this->init_var();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	
	function show()
	{
		//user_agent判断
		$user_agent = $this->hg_settings['agent'];
		
		if($user_agent)
		{
			$user_agent_arr = array();
			$user_agent_arr = explode(',', $user_agent);
			
			
			if(!empty($user_agent_arr))
			{
				$user_agent_flag = '';
				$http_user_agent = $_SERVER['HTTP_USER_AGENT'];
				foreach ($user_agent_arr as $v)
				{
					if(stripos($http_user_agent,$v) !== false)
					{
						$user_agent_flag = 1;
						break;
					}
				}
				
				if(!$user_agent_flag)
				{
					$this->errorOutput('访问受限');
				}
			}
		}
		
		//缓存生成设置
		$cache_update = $this->hg_settings['cache_update'];
		
		//没有缓存设置或者缓存文件不存在，直接取数据或者建立缓存文件
		if (!$cache_update || !file_exists($this->hg_mobile_cache))
		{
			$this->get_data();
		}
		else//有缓存配置并且缓存文件存在,根据配置取数据
		{
			//缓存文件更新时间
			$update_time = filemtime($this->hg_mobile_cache);
			if(!$update_time)
			{
				//缓存文件创建时间
				$update_time = filectime($this->hg_mobile_cache);
			}
			//超过设定时间，查询数据重建缓存
			if(($update_time+$cache_update*60)<time())
			{
				$this->get_data();
			}
			else//在缓存周期内，直接取缓存
			{
				include($this->hg_mobile_cache);
			}
		}
	}
	
	//初始化数据
	function init_var()
	{
		$a = unserialize($this->hg_agruments);
		$this->hg_agruments = $a ? $a : array();
		
		$a = unserialize($this->hg_maps);
		$this->hg_maps  = $a ? $a : array();
		
		$a = unserialize($this->hg_settings);
		$this->hg_settings  =$a ? $a : array();
		
		$a = unserialize($this->hg_map_val);
		$this->hg_map_val  =$a ? $a : array();
		
		$a = unserialize($this->hg_extend_api);
		$this->hg_extend_api = $a ? $a : array();
		
		//缓存文件路径
		$this->hg_mobile_cache_path = CACHE_DIR . $this->hg_settings['sort_dir'];
		$this->hg_mobile_ymd 		= date('Y-m-d',TIMENOW);
		$this->hg_mobile_cache 		= $this->hg_mobile_cache_path . $this->hg_mobile_ymd . '/' . md5(serialize($this->get_con())) . '.php';
	}
	
	private function get_data()
	{
		//请求多接口，不需要对返回值做处理，只需要缓存
		if($this->hg_extend_api && $this->hg_settings['extend_api_switch'])
		{
			//判断移动app，host,dir是否存在
			if(!$this->settings['App_mobile']['host'] || !$this->settings['App_mobile']['dir'])
			{
				$this->errorOutput(UNKNOW_HOST);
			}
			
 			$this->curl = new curl();
 			//hg_pre($this->hg_extend_api,0);
 			
 			$path = '';
 			$path = $this->settings['App_mobile']['host'] . '/'. $this->settings['App_mobile']['dir'] . 'data/' . $this->hg_settings['sort_dir'];
 			
 			$this->curl->mRequestData = $this->input;
 			
 			foreach ($this->hg_extend_api as $k => $filename)
 			{
 				$res = '';
 				$url = '';
 					
 				$url = $path . $filename;
 				$res = $this->curl->post_files($url);
					
 				if(!$res)
 				{
 					$data[$k] = array();
 				}
 				else 
 				{
	 				if(!is_array($res))
	 				{
	 					$res = json_decode($res,1);
	 				}
	 				$data[$k] = $res;
 				}
 			}
 			//hg_pre($data,0);
		}
		else 
		{
			//初始化curl
			if($this->hg_settings['bundle'])
			{
				$this->hg_settings['host'] = $this->settings['App_' . $this->hg_settings['bundle']]['host'];
				$this->hg_settings['dir'] = $this->settings['App_'.$this->hg_settings['bundle']]['dir'];
			}
		
			//判断host,dir是否存在
			if(!$this->hg_settings['host'] || !$this->hg_settings['dir'])
			{
				$this->errorOutput(UNKNOW_HOST);
			}
						
			//参数处理代码
			
			$this->curl = new curl($this->hg_settings['host'], $this->hg_settings['dir']);
			
			$this->curl->initPostData();

			
			$this->curl->setCharset($this->hg_settings['codefmt']);
			if($this->hg_agruments)
			{
				foreach($this->hg_agruments['ident'] as $k=>$v)
				{
					if($v == 'formdata')
					{
						continue;
					}
					if($v == 'callback')
					{
						$this->input['callback'] = $this->hg_agruments['value'][$k];
						continue;
					}
					
					$file_tag = 0;
					$ident_input = '';
					//1=>user 0=>sys
					if(!$this->hg_agruments['add_status'][$k])
					{
						$va = $this->hg_agruments['value'][$k];
					}
					else if($this->hg_agruments['add_status'][$k] == 1)//用户自定义
					{
						$ident_input = $this->hg_agruments['ident_input'][$k];
						if($ident_input && isset($this->input[$ident_input]))
						{
							$va = $this->input[$ident_input];
						}
						elseif (isset($this->input[$v]))
						{
							$va = $this->input[$v];
						}
						elseif($this->hg_agruments['value'][$k])
						{
							$va = $this->hg_agruments['value'][$k];
						}
						else
						{
							continue;
						}
					}
					else if($this->hg_agruments['add_status'][$k] == 2)//文件上传
					{
						$file_tag = 1;
					}
					
					
					if(!$file_tag)
					{
						//$con[$v] = $va; //请求接口条件
						if ($this->hg_settings['codefmt'] && $this->hg_settings['codefmt'] != 'UTF-8')
						{
							$va = iconv($this->hg_settings['codefmt'], 'UTF-8//IGNORE',$va);
						}
					
						//防止用户提交的内容里面@符开头,curl在请求的时候报错
						if($va && $va[0] == '@')
						{
							$va = ' ' .$va;
						}
							
						if (is_array($va))
						{
							$this->array_to_add($v,$va);
						}
						else
						{
							$this->curl->addRequestData($v, $va);
						}
					}
					else 
					{
						if($_FILES)
						{
							$this->curl->addFile($_FILES);	
						}
					}
				}
			}
			
			if($this->hg_settings['request_type'] == '1')
			{
				$SubmitType = 'get';
			}
			else if($this->hg_settings['request_type'] == '2')
			{
				$SubmitType = 'post';
			}
			$this->curl->setSubmitType($SubmitType);
	
			if($this->hg_settings['protocol'] == 1)
			{
				$protocol = 'http';
			}
			else
			{
				$protocol = 'https';
			}
			$this->curl->setRequestType($protocol);
			
			//curl数据返回格式
			if($this->hg_settings['direct_return'])
			{
				$this->curl->setReturnFormat('str');
			}
			else if($this->hg_settings['data_format'])
			{
				$data_type = strtolower($this->settings[$this->hg_settings['data_format']]);
				if(in_array($data_type, array('json','xml', 'str')))
				{
					$this->curl->setReturnFormat($data_type);
				}
			}
			
			//请求结果
			$data = $this->curl->request("news.php");
			
			if(!is_array($data))
			{
				$data = json_decode($data,1);
			}
                        
                        //返回值处理代码
			
			
			//curl返回数据有错误时直接输出错误
			if(!$data || (is_array($data) && $data['ErrorCode']))
			{
				$data = json_encode($data);
				
				if($this->input['callback'])
				{
					header('Content-Type: text/javascript');
					$data = $this->input['callback'] . '(' . $data .')';
	 			}
	 			
	 			//返回值替换
	 			if($this->hg_map_val)
	 			{
		 			foreach ($this->hg_map_val as $k => $v)
					{
						$k = trim(json_encode($k),'"');
						$v = trim(json_encode($v),'"');
						$data = str_replace($k, $v, $data);
					}
	 			}
	 			
				echo $data;
				exit();
			}
			
			//有数据返回节点设置，返回节点里的数据
			if($this->hg_settings['data_node'] || $this->hg_settings['data_node'] == '0')
			{
				if(!is_array($data))
				{
					$data = json_decode($data,1);
				}
				
				$data_node = $this->hg_settings['data_node'];
				
				//$data_node = '1';
				$data_node_arr = array();
				$data_node_arr = explode(',', $data_node);
				
				$data1 = array();
				if(!empty($data_node_arr) && count($data_node_arr) > 1)
				{
					foreach ($data_node_arr as $val)
					{
						$arr = array();
						$arr = explode('=', $val);
						if($arr[1])
						{
							$fileds = array();
							$fileds = explode('/', $arr[1]);
							
							$temp = '';
							foreach ($fileds as $k)
							{
								if($data[$k])
								{
									$temp = $data[$k];
								}
								else if ($temp[$k])
								{
									$temp = $temp[$k];
								}
								
								if ($temp)
								{
									$data1[$arr[0]] = $temp;
								}
								else 
								{
									$data1[$arr[0]] = '';
								}
							}
						}
						else if($data[$arr[0]])
						{
							$data1[$arr[0]] = $data[$arr[0]];
						}
					}
				}
				else 
				{
					$data1 = $data[$data_node_arr[0]];
				}
				
				if($data1)
				{
					$data = $data1;
				}
				
				if($data[$this->hg_settings['data_node']])
				{
					//$data = $data[$this->hg_settings['data_node']];
				}
			}
			
			
			//替换返回值
			if($data && $this->hg_map_val)
			{
				if(is_array($data))
				{
					$data = json_encode($data);
				}
				
				foreach ($this->hg_map_val as $k => $v)
				{
					$k = trim(json_encode($k),'"');
					$v = trim(json_encode($v),'"');
					$data = str_replace($k, $v, $data);
				}
				
				//$data = json_decode($data,1);
				//hg_pre($data,0);
			}
			
			//直接返回
			if($this->hg_settings['direct_return'])
			{
				if(is_array($data))
				{
					$data = json_encode($data);
				}
				if($this->input['callback'])
				{
					header('Content-Type: text/javascript');
					$data = $this->input['callback'] . '(' . $data .')';
	 			}
	 			
				//返回值替换
	 			/*if($this->hg_map_val)
	 			{
		 			foreach ($this->hg_map_val as $k => $v)
					{
						$k = trim(json_encode($k),'"');
						$v = trim(json_encode($v),'"');
						$data = str_replace($k, $v, $data);
					}
	 			}*/
				echo $data;
				exit;
			}
		
			//替换映射字段
			if($data && $this->hg_maps)
			{
				$info =  array();
				if(!is_array($data))
				{
					$info = json_decode($data,1);
				}
				else 
				{
					$info = $data;
				}
			
				foreach ($this->hg_maps as $k => $v)
				{
					$k = ltrim(html_entity_decode($k, ENT_QUOTES),'{');
		    		$map_info[$v] = explode('}{', rtrim($k,'}'));
				}
				
				if($map_info && !empty($info))
				{
					$data = array();
					foreach ($info as $key => $value)
					{
						foreach ($map_info as $k => $val)
						{
							if(!$val || !is_array($val))
							{
								continue;
							}
							
							foreach ($val as $kk => $v)
							{
								if(substr($v, 0,1) == '$')
								{
									$value[$k] .= $value[ltrim($v,'$')];
								}
								else 
								{
									$value[$k] .= $v;
								}
							}
						}
						
						$data[$key] = $value;
					}
				}
			}
		}
		######################请求文件部分结束#####################
		
		//有生成缓存设置，生成缓存
		if ($this->hg_settings['cache_update'])
		{
			$cache_path = '';
			$cache_path = $this->hg_mobile_cache_path . $this->hg_mobile_ymd.'/';
			if(!is_dir($cache_path))
			{
				hg_mkdir($cache_path);
			}
			
			if(is_array($data))
			{
				$data = json_encode($data);
			}
			//生成缓存
			@file_put_contents($this->hg_mobile_cache, $data);
		}
		
		if(!$data)
		{
			$data = array();
		}
		//输出
		if(is_array($data))
		{
			$data = json_encode($data);
		}
		echo $data;
	}
	
	//获取缓存文件名(有缓存设置直接读取缓存)
	private function get_con()
	{
		if($this->hg_agruments)
		{
			foreach($this->hg_agruments['ident'] as $k=>$v)
			{
				//1=>user 0=>sys
				if(!$this->hg_agruments['add_status'][$k])
				{
					$va = $this->hg_agruments['value'][$k];
				}
				else if($this->hg_agruments['add_status'][$k] == 1)//用户自定义
				{
					$va = $this->input[$v] ? $this->input[$v] : $this->hg_agruments['value'][$k];
				}
				else if($this->hg_agruments['add_status'][$k] == 2)//文件上传
				{
					$va = $this->hg_argument['value'][$k];
				}
				$con[$v] = $va; //请求接口条件
			}
		}
		$con['file_name'] = 'news.php';
		
		//添加appid条件
		$appid = intval($this->input['appid']);
		$con['appid'] = $appid;
		
		return $con;
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
					$this->curl->addRequestData($str . "[$kk]", $vv);
				}
			}
		}
	}
	function verifyToken($extend_data=array())
	{
	}
}
include(ROOT_PATH . 'excute.php');
?>