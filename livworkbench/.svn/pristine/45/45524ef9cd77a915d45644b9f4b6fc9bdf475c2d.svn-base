<?php
# **************************************************************************#
# $Id: functions_codeparse.php 363 2007-11-13 14:12:19Z develop_tong $
# **************************************************************************#
class class_functions
{
	public $cache;
	function __construct()
	{
	}
	
	function __destruct()
	{
		
	}

	/**
	 * 读取保存串行化字符串的文件
	 * .php 文件去除开头的结束语句
	 */
	function read_serialize_file($filename)
	{
		if ($return = @file_get_contents($filename))
		{
			if (strrchr($filename, '.') == '.php')
			{
				$return = substr($return, 14);
			}
			if ($return === 'a:0:{}')
			{
				return array();
			}
			else
			{
				return @unserialize($return);
			}
		}
		return false;
	}
	
	function check_cache($cache_name = '', $extra = '', $return = false,$cache_dir = CACHE_DIR)
	{
		if (DEVELOP_MODE)
		{
			$this->recache($cache_name,$extra,$cache_dir);
		}
		if (isset($this->cache[$cache_name]))
		{
			return $return ? $this->cache[$cache_name] : true;
		}
		$cache_file = $cache_dir . $this->convert_cache_name($cache_name) . '.php'; 
		if (!defined('CACHE_INCLUDE'))
		{
			$this->cache[$cache_name] = $this->read_serialize_file($cache_file); 
		}
		else
		{
			if (!@include($cache_file))
			{
				$this->cache[$cache_name] = false;
			}
		}

		if ($return)
		{
			return $this->cache[$cache_name];
		}
		else if ($this->cache[$cache_name] === false)
		{
			$this->recache($cache_name,$extra,$cache_dir);
		}
		return true;
	}

	function update_cache($v = array(),$cache_dir = CACHE_DIR)
	{
		if (is_string($v) && !empty($v))
		{
			$v['name'] = $v;
		}
		if ($v['name'])
		{
			if (empty($v['value']))
			{
				$v['value'] = $this->cache[$v['name']];
			}
			else
			{
				$this->cache[$v['name']] = $v['value'];
			}
			$cache_file = $cache_dir . $this->convert_cache_name($v['name'], true) . '.php';
			if ($v['value'] === false)
			{
				$v['value'] = 0;
			}
			if (!defined('CACHE_INCLUDE'))
			{
				$content = '<'. '?php exit; ?'. '>' . serialize($v['value']);
			}
			else
			{
				$content = '<'. "?php\n\$gCache['{$v['name']}'] = " . var_export($v['value'], true) . ";\n?" . '>';
			}
			hg_file_write($cache_file, $content);
		}
	}

	/**
	 * 重建缓存
	 *
	 * @param string $cache_name 缓存名字
	 */
	function recache($cache_name = '',$extra_func = '',$cache_dir = '')
	{
		if (empty($cache_name))
		{
			return false;
		}

		static $cache = null;
		if ($cache === null)
		{
			include_once(ROOT_PATH . 'lib/class/recache.class.php');
			$cache = new recache();
		}
		if($extra_func && method_exists($cache, $extra_func))
		{
			$cache->$extra_func($cache_name,$cache_dir = '');
		}
		
		$cache_name .= '_recache';
		if (method_exists($cache, $cache_name))
		{
			$cache->$cache_name();
			return true;
		}
		return false;
	}

	/**
	 * 删除缓存
	 *
	 * @param string $cache_name 缓存名字
	 */
	function rmcache($cache_name = '')
	{
		if (empty($cache_name))
		{
			return false;
		}

		if (is_array($cache_name))
		{
			return array_map(array(&$this, 'rmcache'), $cache_name); 
		}
		else
		{
			return @unlink(CACHE_DIR . $this->convert_cache_name($cache_name) . '.php');
		}
	}

	/**
	 * 转换缓存名, 将其中的 - 变为 /
	 *
	 * @param string $name 缓存名
	 * @param boolean $check_dir 是否检查目录, 生成时用
	 */
	function convert_cache_name($name, $check_dir = false)
	{
		if (strpos($name, '-') !== false)
		{
			$name = str_replace('-', '/', $name);
			if ($check_dir && !hg_mkdir(CACHE_DIR . $name))
			{
				return false;
			}
		}

		return $name;
	}
}
?>