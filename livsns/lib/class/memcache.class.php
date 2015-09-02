<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: memcache.class.php 12037 2012-09-28 10:47:06Z repheal $
***************************************************************************/

class memcache
{	
	function __construct()
	{
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gGlobalConfig['App_memcache']['host'], $gGlobalConfig['App_memcache']['dir']);
	}

	function __destruct()
	{
	}

	public function set($name, $value)
	{
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'set');
		$this->curl->addRequestData('name', $name);
		if (is_array($value))
		{
			$value = json_encode($value);
		}
		$this->curl->addRequestData('value', $value);
		return $this->curl->request('memcache.php');
	}

	public function get($name)
	{
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'get');
		$this->curl->addRequestData('name', $name);
		$value = $this->curl->request('memcache.php');
		$ret = json_decode($value);
		if ($ret)
		{
			return $ret;
		}
		else
		{
			return $value;
		}
	}

}
?>