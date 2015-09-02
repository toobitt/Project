<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: base_frm.php 20394 2013-04-15 09:09:50Z zhuld $
***************************************************************************/

/**
 * 初始化类 接管全局数据
 * @author develop_tong
 *
 */
abstract class InitFrm
{
	protected $input;
	protected $settings;
	function __construct()
	{
		global $_INPUT, $gGlobalConfig;
		$this->input = &$_INPUT;
		$this->settings = &$gGlobalConfig;
	}
	function __destruct()
	{
		//NULL
	}
	public function __methods()
	{
		$methods = get_class_methods($this);
		foreach ($methods AS $f)
		{
			$this->addItem($f);
		}
		$this->output();
	}
}
/**
 * 核心基类 包含框架核心方法
 * @author develop_tong
 *
 */
abstract class coreFrm extends InitFrm
{
	protected $queue;
	//protected $memcache;
	protected $mData;
	protected $mType = 'xml';
	protected $mCharset = 'UTF-8';
	protected $mRootNode = 'root';
	protected $mItemNode = 'item';
	protected $lang = array();
	protected $mNodes = array();
	
	function __construct()
	{
		parent::__construct();
		$this->setOutputType($this->input['format']);
		$this->init();
	}
	function __destruct()
	{
		//NULL
		parent::__destruct();
	}
	
	function init()
	{
		if(!class_exists('curl'))
		{
			include_once(M2O_ROOT_PATH . 'lib/class/curl.class.php');
		}
	}
	
	/**
	 * 获取模块须权限控制方法
	 *
	 */
	
	/**
	 * 设置接口输出格式
	 *@param $type 输出格式，目前支持json和xml
	 *
	 */
	private function setOutputType($type = 'xml')
	{
		if (!in_array($type, array('json', 'xml')))
		{
			$type = 'json';
		}
		$this->mType = $type;
	}
	
	/**
	 * 增加xml格式条目数据
	 * @param Array $data 数据条目
	 *
	 */
	private function addItemxml($data)
	{
		$this->mData .= '<' . $this->mItemNode . '>';
		$this->mData .= $this->arrayToXml($data);
		$this->mData .= '</' . $this->mItemNode . '>';
	}
	
	
	/**
	 * 增加xml格式条目数据
	 * @param Array $data 数据条目
	 *
	 */
	private function addItemxml_withkey($key, $data)
	{
		$this->mData .= '<' . $this->mItemNode . '>';
		$this->mData .= $this->arrayToXml($data);
		$this->mData .= '</' . $this->mItemNode . '>';
	}
	
	/**
	 * 数组转化为xml
	 * @param Array $data 数据条目
	 *
	 */
	private function arrayToXml($data)
	{
		if (is_array($data))
		{
			$out = '';
			foreach ($data AS $k => $v)
			{
				if (is_numeric($k))
				{
					$k = 'items';
				}
				if (is_array($v))
				{
					$out .= '<' . $k . '>' . $this->arrayToXml($v) . '</' . $k . '>';
				}
				else
				{
					$out .= '<' . $k . '><![CDATA[' . $v . ']]></' . $k . '>';
				}
			}
		}
		else
		{
			$out = $data;
		}
		return $out;
	}
	
	/**
	 * 增加json格式条目数据
	 * @param Array $data 数据条目
	 *
	 */
	private function addItemjson($data)
	{
		$this->mData[] = $data;
	}
	
	/**
	 * 增加json格式条目数据
	 * @param Array $data 数据条目
	 *
	 */
	private function addItemjson_withkey($key, $data)
	{
		$this->mData[$key] = $data;
	}
	/**
	 * 设置接口返回状态头
	 * @param $status 状态值
	 *
	 */
	protected function header($status)
	{
		if(!$status)
		{
			return;
		}
		$conf = array(
				100 => "HTTP/1.1 100 Continue",
				101 => "HTTP/1.1 101 Switching Protocols",
				200 => "HTTP/1.1 200 OK",
				201 => "HTTP/1.1 201 Created",
				202 => "HTTP/1.1 202 Accepted",
				203 => "HTTP/1.1 203 Non-Authoritative Information",
				204 => "HTTP/1.1 204 No Content",
				205 => "HTTP/1.1 205 Reset Content",
				206 => "HTTP/1.1 206 Partial Content",
				300 => "HTTP/1.1 300 Multiple Choices",
				301 => "HTTP/1.1 301 Moved Permanently",
				302 => "HTTP/1.1 302 Found",
				303 => "HTTP/1.1 303 See Other",
				304 => "HTTP/1.1 304 Not Modified",
				305 => "HTTP/1.1 305 Use Proxy",
				307 => "HTTP/1.1 307 Temporary Redirect",
				400 => "HTTP/1.1 400 Bad Request",
				401 => "HTTP/1.1 401 Unauthorized",
				402 => "HTTP/1.1 402 Payment Required",
				403 => "HTTP/1.1 403 Forbidden",
				404 => "HTTP/1.1 404 Not Found",
				405 => "HTTP/1.1 405 Method Not Allowed",
				406 => "HTTP/1.1 406 Not Acceptable",
				407 => "HTTP/1.1 407 Proxy Authentication Required",
				408 => "HTTP/1.1 408 Request Time-out",
				409 => "HTTP/1.1 409 Conflict",
				410 => "HTTP/1.1 410 Gone",
				411 => "HTTP/1.1 411 Length Required",
				412 => "HTTP/1.1 412 Precondition Failed",
				413 => "HTTP/1.1 413 Request Entity Too Large",
				414 => "HTTP/1.1 414 Request-URI Too Large",
				415 => "HTTP/1.1 415 Unsupported Media Type",
				416 => "HTTP/1.1 416 Requested range not satisfiable",
				417 => "HTTP/1.1 417 Expectation Failed",
				500 => "HTTP/1.1 500 Internal Server Error",
				501 => "HTTP/1.1 501 Not Implemented",
				502 => "HTTP/1.1 502 Bad Gateway",
				503 => "HTTP/1.1 503 Service Unavailable",
				504 => "HTTP/1.1 504 Gateway Time-out",
				505 => "HTTP/1.1 505 - HTTP Version Not Supported"
		);
		header($conf[$status],true,$status);
	}
	/**
	 * 清理xml字符串
	 * @param $str 待清理的字符串
	 *
	 */
	protected function xmlClean($str)
	{
		$str = preg_replace('/[\\x00-\\x08\\x0b-\\x0c\\x0e-\\x1f]/','',$str);
		return $str;
	}
	
	/**
	 * 设置xml的根节点和条目节点
	 * @param $root_node 根节点
	 * @param $item_node 条目节点
	 *
	 */
	protected function setXmlNode($root_node = 'root', $item_node = 'item')
	{
		$this->mRootNode = $root_node;
		$this->mItemNode = $item_node;
	}
	
	/**
	 * 增加条目数据
	 * @param Array $data 数据条目
	 *
	 */
	protected function addItem_withkey($key, $data)
	{
		$func = 'addItem' . $this->mType . '_withkey';
		$this->$func($key, $data);
	}
	
	
	/**
	 * 增加条目数据
	 * @param Array $data 数据条目
	 *
	 */
	protected function addItem($data)
	{
		if($this->settings['field_map'] && is_array($this->settings['field_map']))
		{
			foreach($this->settings['field_map'] as $from=>$to)
			{
				if($data[$from])
				{
					continue;
				}
				$data[$from] = $data[$to];
				//unset($data[$from]);
			}
		}
		$func = 'addItem' . $this->mType;
		$this->$func($data);
	}
	protected function errorOutput($errno = 'Unknow', $status = '200')
	{
		$this->header($status);
	
		include(CUR_CONF_PATH . 'conf/error.conf.php');
	
		if ('xml' == $this->mType)
		{
			$content_type = 'Content-Type:text/' . $this->mType . '; charset='.$this->mCharset;
			$output = '<?xml version="1.0" encoding="'.$this->mCharset.'"?>';
			$output .= '<Error>';
			$output .= '<ErrorCode>' . $errno . '</ErrorCode>';
			$output .= '<ErrorText>' . $errorConf[$errno] . '</ErrorText>';
	
			if (DEBUG_MODE)
			{
				$output  .= '<Debug>' . hg_page_debug() . '</Debug>';
			}
			$output .= '</Error>';
		}
		else
		{
			$content_type = 'Content-Type: text/plain';
			$output = array(
					'ErrorCode' => $errno,
					'ErrorText' => $errorConf[$errno],
			);
			if ($this->input['ikey'])
			{
				$output_k[$this->input['ikey']] = $output;
			}
			else
			{
				$output_k = $output;
			}
			$output = json_encode($output_k);
		}
		if (!$this->input['callback'])
		{
			header($content_type);
			echo $output;
		}
		else
		{
			header('Content-Type: text/javascript');
			echo $this->input['callback'] . '(' . $output . ');';
		}
		exit;
	}
	
	/**
	 * 输出结果
	 *
	 */
	protected function output()
	{
		//$this->header(200);
		if ('xml' == $this->mType)
		{
			$content_type = 'Content-Type:text/' . $this->mType . '; charset='.$this->mCharset;
			$this->mData = $this->xmlClean($this->mData);
			$output = '<?xml version="1.0" encoding="'.$this->mCharset.'"?>';
			$output .= '<' . $this->mRootNode . '>';
			$output .= $this->mData;
	
			if (DEBUG_MODE)
			{
				$output  .= '<Debug>' . hg_page_debug() . '</Debug>';
			}
			$output .= '</' . $this->mRootNode . '>';
		}
		else
		{
			$content_type = 'Content-Type:text/plain';
			if (count($this->mData) == 1)
			{
				//$this->mData = $this->mData[0];
			}
			if ($this->input['ikey'])
			{
				$data[$this->input['ikey']] = $this->mData;
			}
			else
			{
				$data = $this->mData;
			}
			$output = json_encode($data);
		}
		if (!$this->input['callback'])
		{
			header($content_type);
			echo $output;
		}
		else
		{
			header('Content-Type: text/javascript');
			echo $this->input['callback'] . '(' . $output . ');';
		}
		exit;
	}

	/**
	 * 输出配置信息
	 *
	 */
	public function __getConfig()
	{
		if (!$this->settings)
		{
			$this->settings = array();
		}
		$this->setXmlNode('configs','config');
		$this->addItem($this->settings);
		$this->output();
	}
	
	/**
	 *  输出调试结果，debug用
	 *
	 */
	protected function ConnectQueue()
	{
		if (!$this->queue)
		{
			if (class_exists('Memcache'))
			{
				global $gQueueConfig;
				$queue = @array_pop($gQueueConfig);
				$this->queue = new Memcache();
				$connect = @$this->queue->connect($queue['host'], $queue['port']);
				if (!$connect)
				{
					include_once(M2O_ROOT_PATH . 'lib/class/queue.class.php');
					$this->queue = new queue();
				}
				else
				{
					if ($gQueueConfig)
					{
						foreach ($gQueueConfig AS $queue)
						{
							$this->queue->addServer($queue['host'], $queue['port']);
						}
					}
				}
			}
			else
			{
				include_once(M2O_ROOT_PATH . 'lib/class/queue.class.php');
				$this->queue = new queue();
			}
		}
	}
	protected function debug($data)
	{
		if (DEBUG_MODE)
		{
			if (1 == DEBUG_MODE)
			{
				if (!is_string($data))
				{
					print_r($data);
				}
				else
				{
					echo $data;
				}
				echo '<br />#----------------------------------------------------------------------------------------------------------------------------#<br />';
			}
			else
			{
				hg_mkdir(LOG_DIR);
				hg_debug_tofile($data, 1, LOG_DIR . 'debug.txt');
			}
		}
	}

	protected function verifyToken()
	{
		if(!defined('APP_UNIQUEID') || !defined('MOD_UNIQUEID'))
		{
			$this->errorOutput(UNKNOWN_APP_UNIQUEID);
		}
		$gAuthServerConfig = $this->settings['App_auth'];
		if(!$gAuthServerConfig) //未配置授权
		{
			$this->user = array(
				'user_id'		=>$this->input['user_id'],
				'user_name'		=> $this->input['user_name'],
				'group_type'	=>1,//超级用户
				'appid'			=>$this->input['appid'],
				'display_name'	=>$this->input['user_name'],
				'visit_client'	=>0,
			);
			return;
		}
		if(!class_exists('curl'))
		{
			include_once(M2O_ROOT_PATH . 'lib/class/curl.class.php');
		}
		$curl = new curl($gAuthServerConfig['host'], $gAuthServerConfig['dir']);
		$curl->initPostData();
		$postdata = array(
			'appid'			=>	$this->input['appid'],
			'appkey'		=>	$this->input['appkey'],
			'access_token'	=>	$this->input['access_token'],
			'mod_uniqueid'	=> 	MOD_UNIQUEID,
			'app_uniqueid'	=>	APP_UNIQUEID,
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
		$this->user = $ret[0];
	}
	
}

?>