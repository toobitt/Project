<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: friendsIds.class.php 776 2010-12-17 05:47:15Z yuna $
***************************************************************************/
class friendsIds
{
	function __construct()
	{
		global $gMysqlfriendsIdsConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gMysqlfriendsIdsConfig['host'], $gMysqlfriendsIdsConfig['apidir']);
	}

	function __destruct()
	{
	}

	public function friendsId($str)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('id',$str);
		return $this->curl->request('ids.php');
		
	}
	
}
?>