<?php 
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: cache.class.php 4808 2011-10-18 00:50:25Z develop_tong $
***************************************************************************/
require_once('livcms_frm.php');
class cache extends LivcmsFrm
{
	public $mcacheTime = 5; //单位秒 全局作用
	public $mcacheDir = 'cache/';//缓存目录
	public $menableCache = true;//默认启用缓存
	public $msuffix = 'php'; //缓存文件的后缀
	
	function __construct($cacheDir = '',$cacheTime = '')
	{
		parent::__construct();
		$this->cacheInit($cacheDir,$cacheTime);
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function cacheInit($cacheDir,$cacheTime)
	{
		$this->setCacheDir($cacheDir);
		$this->setCacheTime($cacheTime);
		$this->setSuffix();
		//全局定义常量CACHE_ENABLE
		if(defined('CACHE_ENABLE'))
		{
			$this->menableCache = CACHE_ENABLE;
		}
		if(defined('CACHE_TIME') && is_int(CACHE_TIME))
		{
			$this->mcacheTime = CACHE_ENABLE;
		}
	}
	//设置缓存时间	
	function setCacheTime($cachetime = '')
	{
		if($cachetime != '')
		{
			return $this->mcacheTime = $cachetime;
		}
	}
	//设置缓存目录
	function setCacheDir($dir = '')
	{
		if($dir != '' && is_dir($dir))
		{
			$dir = rtrim($dir, '/') . '/';
			return $this->mcacheDir = $dir;
		}
	}
	//设置缓存文件的后缀
	function setSuffix($suffix = '')
	{
		if($suffix != '')
		{
			return $this->msuffix = '.'.ltrim($suffix,'.');
		}
		return $this->msuffix = '.' . $this->msuffix;
	}
	//禁用缓存
	function CacheEnable($bool)
	{
		if($bool === false)
		{
			return $this->menableCache = false;
		}
	}
	//建立缓存 缓存数据为序列化的数组
	function buildCache($filename,$content)
	{
		if($this->menableCache)
		{
			$this->deleteCache($filename);
			$filename = $this->mcacheDir . md5($filename) . $this->msuffix;
			return hg_file_write($filename, $content);
		}
		else
		{
			return false;//缓存被禁用
		}
	}
	//删除缓存文件
	function deleteCache($filename)
	{
		if($path = $this->cacheExists($filename))
		{
			@unlink($path);
		}
	}
	//读取缓存 数组 缓存失效则返回空数组
	function readCache($filename)
	{
		if(!$this->menableCache)
		{
			return array();
		}
		if($path = $this->isCachceVailid($filename))
		{
			return unserialize(file_get_contents($path));
		}
		return array();
	}
	//判断缓存是否过期 返回 BOOL
	function isCachceVailid($filename)
	{
		if($path = $this->cacheExists($filename))
		{
			if(time()-filectime($path) > $this->mcacheTime)
			{
				$this->deleteCache($filename);
				return false;
			}
			return $path;
		}
		return false;
	}
	//检测缓存是否存在 返回路径
	function cacheExists($filename)
	{
		$filepath = $this->mcacheDir . md5($filename) . $this->msuffix;
		if(file_exists($filepath))
		{
			return $filepath;
		}
		return false;
	}
}
?>