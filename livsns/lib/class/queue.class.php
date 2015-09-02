<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: queue.class.php 12037 2012-09-28 10:47:06Z repheal $
***************************************************************************/
class queue
{
	function __construct()
	{
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gGlobalConfig['App_queue']['host'], $gGlobalConfig['App_queue']['dir']);
	}

	function __destruct()
	{
	}

	public function set($name, $value)
	{
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'set');
		$this->curl->addRequestData('name', $name);
		$this->curl->addRequestData('value', $value);
		return $this->curl->request('queue.php');
	}
	
	public function get($name)
	{
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'get');
		$this->curl->addRequestData('name', $name);
		return $this->curl->request('queue.php');
	}
}
?>