<?php
/***************************************************************************
* LivSNS 0.1
* (C)2009-2010 HOGE Software.
*
* $Id: settings.class.php 12037 2012-09-28 10:47:06Z repheal $
***************************************************************************/
class settings
{
	function __construct()
	{
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		//$this->curl = new curl('apidev.hoolo.tv', 'admin/cp_status/');	
		$this->curl = new curl($gGlobalConfig['App_cp_status']['host'], $gGlobalConfig['App_cp_status']['dir']);
	}

	function __destruct()
	{
	}
	
	public function getMark($mark)
	{
		$this->curl->setSubmitType('post');		
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'getMark');
		$this->curl->addRequestData('mark', $mark);
		$this->curl->addRequestData('token', '8sdhu9a7sdASDSiSUDs9SwiU7sGF');
		$ret = $this->curl->request('settings.php');
		return $ret[0];
	}
}

?>