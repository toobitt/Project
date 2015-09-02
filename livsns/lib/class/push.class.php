<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: push.class.php 776 2010-12-17 05:47:15Z yuna $
***************************************************************************/
class push
{
	function __construct()
	{
		global $gMysqlpushConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gMysqlpushConfig['host'], $gMysqlpushConfig['apidir']);
	}

	function __destruct()
	{
	}

	public function getuserinbox($userid,$page,$count,$gettotal)
	{
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		$this->curl->addRequestData('user_id',$userid);
		$this->curl->addRequestData('count',$count);
		$this->curl->addRequestData('page',$page);
		$this->curl->addRequestData('gettotal',$gettotal);
		$this->curl->addRequestData('a','getuserinbox');
		$this->curl->setReturnFormat('json');
		return $this->curl->request('get_userstatus.php');
	}
	public function delete($statusid)
	{
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		$this->curl->addRequestData('statusid',$statusid);
		$this->curl->addRequestData('a','delete');
		$this->curl->setReturnFormat('json');
		return $this->curl->request('get_userstatus.php');
	}	

}
?>