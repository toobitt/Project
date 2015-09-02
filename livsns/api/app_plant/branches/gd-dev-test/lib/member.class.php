<?php

/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: applant.class.php 387 2011-03-01 03:35:15Z chengqing $
***************************************************************************/

class member
{
	private $curl;
	
	public function __construct()
	{
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gGlobalConfig['App_members']['host'], $gGlobalConfig['App_members']['dir']);
	}

	public function __destruct()
	{
	    unset($this->curl);
	}
	
	public function getActivateMemberCount($day = '')
	{		
        $this->curl->setSubmitType('post');
        $this->curl->initPostData();
        $this->curl->addRequestData('a','getActivateMemberCount');
        $this->curl->addRequestData('day',$day);
        $ret = $this->curl->request('member.php');
        if($ret && isset($ret[0]))
        {
        	$ret = $ret[0];
        }
        return $ret;
	}
}