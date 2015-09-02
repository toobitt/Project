<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: shorturl.class.php 19070 2013-03-22 06:35:44Z yaojian $
***************************************************************************/
class shorturl
{
	public function __construct()
	{
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gGlobalConfig['App_shorturl']['host'], $gGlobalConfig['App_shorturl']['dir']);
	}

	public function __destruct()
	{
		unset($this->curl);
	}

	public function shorturl($str)
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		//$this->curl->setReturnFormat('str');
		$this->curl->initPostData();
		$this->curl->addRequestData('str',$str);
		$result = $this->curl->request('shorturl.php');
		return $result;
	}
	
}
?>