<?php

/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: applant.class.php 387 2011-03-01 03:35:15Z chengqing $
***************************************************************************/

class CompanyApi
{
	private $curl;
	
	public function __construct()
	{
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gGlobalConfig['App_company']['host'], $gGlobalConfig['App_company']['dir']);
	}

	public function __destruct()
	{
	    unset($this->curl);
	}
	
	public function modifyUserPushStatus($user_id,$status = 1)
	{
		if(!$user_id)
		{
			return;
		}
		
        $this->curl->setSubmitType('get');
        $this->curl->initPostData();
        $this->curl->addRequestData('a','modifyUserPushStatus');
        $this->curl->addRequestData('user_id',$user_id);
        $this->curl->addRequestData('status',$status);
        $this->curl->request('user.php');
	}
}