<?php
class CacheFile 
{
	private $_settings = array(
		'type' => 'array',           				//缓存格式 array数组 serialize串行化
		'suf'  => '.cache.php', 					//文件后缀
	);
	function __construct($settings = array())
	{
		if($settings)
		{
			$this->_settings = array_merge($this->_settings,$settings);
		}
	}
	function __destruct()
	{	
	}
		
	function get_cache($cacheName,$cacheDir = CACHE_DIR)
	{
		if(!$cacheName)
		{
			return array();
		}
		$cacheName = $this->convert_cache_name($cacheName);
		$cacheFile= $cacheDir . $cacheName ;
		if(!file_exists($cacheFile))
		{
			return array();
		}
		else
		{
			if($this->_settings['type'] == 'array')
			{
				$data = @require($cacheFile);
			}
			else if($this->_settings['type'] == 'serailize')
			{
				$data = file_get_contents($cacheFile);
				if($data == 'a:0:{}')
				{
					$data = array();
				}
				else
				{
					$data = unserialize($data);
				}
			}
		}
		return $data;
	}
	
	function set_cache($cacheName, $data = array(), $cacheDir = CACHE_DIR)
	{
		if(!$cacheName) 
		{
			return false;
		}
		if(!is_dir($cacheDir))
		{
			mkdir($cacheDir, 0777, true);
		}
		$cacheName = $this->convert_cache_name($cacheName);
		$cacheFile = $cacheDir . $cacheName;
		if($this->_settings['type'] == 'array')
		{
			$data = "<?php\nreturn " .var_export($data,1). ";\n?>";
		}
		else if($this->_settings['type'] == 'serialize')
		{
			$data = serialize($data);
		}
		$return = file_put_contents($cacheFile, $data);
		return $return ? $return : false;
	}
	
	function rm_cache($cacheName,$cacheDir = CACHE_DIR)
	{
		if(!$cacheName)
		{
			return false;
		}
		if(is_array($cacheName))
		{
			return array_map(array(&$this,'delete'),$cacheName);
		}
		else
		{
			return @unlink($cacheDir . $this->convert_cache_name($cacheName));
		}	
	}
	
	function cache_info($cacheName,$cacheDir = CACHE_DIR)
	{
		if($cacheName)
		{
			return false;
		}
		$cacheFile = $cacheDir . $cacheName;
		if(file_exists($cacheFile))
		{
			$info = array();
			$info['ctime'] = filectime($cacheFile);
			$info['mtime'] = filemtime($cacheFile);
			$info['filesize'] = filesize($cacheFile);
			return $info;
		}
		else
		{
			return false;
		}
	}	
	
	private function convert_cache_name($cacheName)
	{
		if(!$cacheName)
		{
			return false;
		}
		if(is_array($cacheName))
		{
			foreach($cacheName as $k => $v)
			{
				$cacheName[$k] = $v . $this->_settings['suf'];	
			}
//			array_map(array(&$this,'convert_cache_name'),$cacheName);
		}
		else
		{
			$cacheName = $cacheName . $this->_settings['suf'];
		}
		return $cacheName;
	}	
}
?>