<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: memcache.class.php 12037 2012-09-28 10:47:06Z repheal $
***************************************************************************/

class mcached
{	
	function __construct()
	{
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gGlobalConfig['App_memcached']['host'], $gGlobalConfig['App_memcached']['dir']);
	}

	function __destruct()
	{
	}
	
	public function set($key, $data, $group='')
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'set');
		$this->curl->addRequestData('key', $key);
		if(is_array($data))
		{
			$this->array_to_add('data', $data);
		}
		else
		{
			$this->curl->addRequestData('data', $data);
		}
		$this->curl->addRequestData('group', $group);
		$this->curl->addRequestData('bundle_id' , APP_UNIQUEID);
		$this->curl->addRequestData('module_id' , MOD_UNIQUEID);
		$this->curl->request('memcache.php');
		return;
	}

	public function get($key,$group)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'get');
		$this->curl->addRequestData('key', $key);
		$this->curl->addRequestData('group', $group);
		$this->curl->addRequestData('bundle_id' , APP_UNIQUEID);
		$this->curl->addRequestData('module_id' , MOD_UNIQUEID);
		$value = $this->curl->request('memcache.php');
		return $value[0];
	}
	
	public function delete($key,$group)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'delete');
		$this->curl->addRequestData('key', $key);
		$this->curl->addRequestData('group', $group);
		$this->curl->addRequestData('bundle_id' , APP_UNIQUEID);
		$this->curl->addRequestData('module_id' , MOD_UNIQUEID);
		$value = $this->curl->request('memcache.php');
		return $value[0];
	}
	
	public function flush($group)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'flush');
		$this->curl->addRequestData('group', $group);
		$this->curl->addRequestData('bundle_id' , APP_UNIQUEID);
		$this->curl->addRequestData('module_id' , MOD_UNIQUEID);
		$value = $this->curl->request('memcache.php');
		return $value[0];
	}
	
	public function array_to_add($str , $data)
	{
		$str = $str ? $str : 'data';
		if (is_array($data))
		{
			foreach ($data AS $kk => $vv)
			{
				if(is_array($vv))
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

}
?>