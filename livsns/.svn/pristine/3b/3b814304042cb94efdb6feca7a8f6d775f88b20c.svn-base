<?php

/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: applant.class.php 387 2011-03-01 03:35:15Z chengqing $
***************************************************************************/

class dingdonestatistics
{
	private $curl;
	
	public function __construct()
	{
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gGlobalConfig['App_dingdonestatistics']['host'], $gGlobalConfig['App_dingdonestatistics']['dir']);
	}

	public function __destruct()
	{
	    unset($this->curl);
	}
	
	public function getLivenessByTime($times = '')
	{	
        $this->curl->setSubmitType('post');
        $this->curl->initPostData();
        $this->curl->addRequestData('times',$times);
        $this->curl->addRequestData('a','getLivenessAppByTime');
        $ret = $this->curl->request('getActivateStatistics.php');
        if($ret && isset($ret[0]))
        {
        	$ret = $ret[0];
        }
        return $ret;
	}
	
	public function getTopTen()
	{
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','getTopTenAppId');
		$ret = $this->curl->request('getActivateStatistics.php');
		if($ret && isset($ret[0]))
		{
			$ret = $ret[0];
		}
		return $ret;
	}
	
	public function getCoverNums($start_time = 0 , $end_time = 0)
	{
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','getCoverNums');
		$this->curl->addRequestData('start_time', $start_time);
		$this->curl->addRequestData('end_time', $end_time);
		$ret = $this->curl->request('getActivateStatistics.php');
		if($ret && isset($ret[0]))
		{
			$ret = $ret[0];
		}
		return $ret;
	}
	
	public function getStart($day = '')
	{
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','getStart');
		$this->curl->addRequestData('day', $day);
		$ret = $this->curl->request('getActivateStatistics.php');
		if($ret && isset($ret[0]))
		{
			$ret = $ret[0];
		}
		return $ret;
	}
	
	public function getTopTenDown($time_type = 1 , $start_date = '', $end_date = '')
	{
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','getTopTenDown');
		$this->curl->addRequestData('time_type', $time_type);
		$this->curl->addRequestData('start_date', $start_date);
		$this->curl->addRequestData('end_date', $end_date);
		$ret = $this->curl->request('getActivateStatistics.php');
		if($ret && isset($ret[0]))
		{
			$ret = $ret[0];
		}
		return $ret;
	}
	
	public function getAppActivateRank($time_type = 1 , $start_date = '', $end_date = '')
	{
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','getAppActivateRank');
		$this->curl->addRequestData('time_type', $time_type);
		$this->curl->addRequestData('start_date', $start_date);
		$this->curl->addRequestData('end_date', $end_date);
		$ret = $this->curl->request('getActivateStatistics.php');
		if($ret && isset($ret[0]))
		{
			$ret = $ret[0];
		}
		return $ret;
	}
	
	public function getAllActivateAndDown($time_type = 1 , $start_date = '', $end_date = '')
	{
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','getAllActivateAndDown');
		$this->curl->addRequestData('time_type', $time_type);
		$this->curl->addRequestData('start_date', $start_date);
		$this->curl->addRequestData('end_date', $end_date);
		$ret = $this->curl->request('getActivateStatistics.php');
		if($ret && isset($ret[0]))
		{
			$ret = $ret[0];
		}
		return $ret;
	}
	
	public function getTodayDeviceInfo($zero_time)
	{
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','getTodayDeviceInfo');
		$this->curl->addRequestData('zero_time', $zero_time);
		$ret = $this->curl->request('getActivateStatistics.php');
		if($ret && isset($ret[0]))
		{
			$ret = $ret[0];
		}
		return $ret;
	}
	
	public function getSinkInfo($start_time = 0, $end_time = 0 , $source = 0 , $ids , $six_start_time = 0 , $six_end_time = 0)
	{
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','getSinkInfo');
		$this->curl->addRequestData('start_time', $start_time);
		$this->curl->addRequestData('end_time', $end_time);
		$this->curl->addRequestData('six_start_time', $six_start_time);
		$this->curl->addRequestData('six_end_time', $six_end_time);
		$this->curl->addRequestData('source', $$source);
		$this->curl->addRequestData('ids', $ids);
		$ret = $this->curl->request('getActivateStatistics.php');
		if($ret && isset($ret[0]))
		{
			$ret = $ret[0];
		}
		return $ret;
	}
	
	public function getAllActivateInfo($start_time = 0, $end_time = 0)
	{
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','getAllActivateInfo');
		$this->curl->addRequestData('start_time', $start_time);
		$this->curl->addRequestData('end_time', $end_time);
		$ret = $this->curl->request('getActivateStatistics.php');
		if($ret && isset($ret[0]))
		{
			$ret = $ret[0];
		}
		return $ret;
	}
	
	public function getTodayActivateInfo($start_time = 0)
	{
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','getTodayActivateInfo');
		$this->curl->addRequestData('start_time', $start_time);
		$ret = $this->curl->request('getActivateStatistics.php');
		if($ret && isset($ret[0]))
		{
			$ret = $ret[0];
		}
		return $ret;
	}
	
	public function getPerishInfo($three_month_start_time = 0 , $three_month_end_time = 0 ,$source , $ids = '')
	{
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','getPerishInfo');
		$this->curl->addRequestData('start_time',$three_month_start_time);
		$this->curl->addRequestData('a','getPerishInfo');
		$this->curl->addRequestData('end_time', $three_month_end_time);
		$this->curl->addRequestData('source', $source);
		$this->curl->addRequestData('ids', $ids);
		$ret = $this->curl->request('getActivateStatistics.php');
		if($ret && isset($ret[0]))
		{
			$ret = $ret[0];
		}
		return $ret;
	}
}