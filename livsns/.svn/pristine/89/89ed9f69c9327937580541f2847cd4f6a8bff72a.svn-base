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
	
	public function getActivateMemberCount($start_time = 0 , $end_time = 0)
	{		
        $this->curl->setSubmitType('post');
        $this->curl->initPostData();
        $this->curl->addRequestData('a','getActivateMemberCount');
        $this->curl->addRequestData('start_time', $start_time);
		$this->curl->addRequestData('end_time', $end_time);
        $ret = $this->curl->request('dingdone_member.php');
        if($ret && isset($ret[0]))
        {
        	$ret = $ret[0];
        }
        return $ret;
	}
	
	public function getTodayMemberInfo($start_time = 0)
	{
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','getTodayMemberInfo');
		$this->curl->addRequestData('start_time', $start_time);
		$ret = $this->curl->request('dingdone_member.php');
		if($ret && isset($ret[0]))
		{
			$ret = $ret[0];
		}
		return $ret;
	}
}