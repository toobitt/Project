<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: activity.class.php 4041 2011-06-07 02:29:13Z repheal $
***************************************************************************/
class activityCLass
{
	function __construct()
	{
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gGlobalConfig['App_activity']['host'], $gGlobalConfig['App_activity']['dir']);
	}
	function __destruct()
	{
		unset($this->curl);
	}
	
	//创建
	public function create($data)
	{
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'create');
		foreach($data as $k=>$v)
		{
			$this->curl->addRequestData($k, $v);
		}
		$ret = $this->curl->request('activity.php');
		return $ret;
	}
	//编辑
	public function update($data)
	{
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'update');
		foreach($data as $k=>$v)
		{
			$this->curl->addRequestData($k, $v);
		}
		$ret = $this->curl->request('activity.php');
		return $ret;
	}
	/**
	 * 显示具体某条活动
	 */
	public function detail($action_id, $offset = 0, $count = 10)
	{
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'detail');
		$this->curl->addRequestData('action_id', $action_id);
		$this->curl->addRequestData('offset', $offset);
		$this->curl->addRequestData('count', $count);
		$ret = $this->curl->request('activity.php');
		return $ret;
	}
	/*
	 * 对活动的操作
	 */
	public  function operation($action_id, $state)
	{
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'delete');
		$this->curl->addRequestData('action_id', $action_id);
		$this->curl->addRequestData('state', $state);
		$ret = $this->curl->request('activity.php');
		return $ret;
	}
	//更新活动数据
	public function updateAddData($data)
	{
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'updateAddData');
		foreach($data as $k=>$v)
		{
			$this->curl->addRequestData($k, $v);
		}
		$ret = $this->curl->request('activity.php');
		return $ret;
	}
	//报名
	public function join($data)
	{
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'create');
		foreach($data as $k=>$v)
		{
			$this->curl->addRequestData($k, $v);
		}
		$ret = $this->curl->request('apply.php');
		return $ret;
	}
	//取消报名
	public function cancel($data)
	{
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'delete');
		foreach($data as $k=>$v)
		{
			$this->curl->addRequestData($k, $v);
		}
		$ret = $this->curl->request('apply.php');
		return $ret;
	}
	//类型
	public function types()
	{
		$this->curl->setSubmitType('get');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'showApplyTypes');
		$ret = $this->curl->request('activity.php');
		return $ret;
	}
	
	public function show_all($count=-1)
	{
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		$this->curl->addRequestData('count', $count);
		$ret = $this->curl->request('activity.php');
		return $ret;
	} 
	
	//
	public function show($action_id, $offset=0, $count=1000)
	{
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		$this->curl->addRequestData('action_id', $action_id);
		$this->curl->addRequestData('offset', $offset);
		$this->curl->addRequestData('count', $count);
		$ret = $this->curl->request('activity.php');
		return $ret;
	} 
	
	/**
	 * 物理删除
	 * $team_id 小组id或者$action_id 活动id
	 */
	public function clear($data = array())
	{
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'clear');
		foreach($data as $k=>$v)
		{
			$this->curl->addRequestData($k, $v);
		}
		$ret = $this->curl->request('activity.php');
		return $ret;
	}
	/**
	 * 软删或者回复
	 * $team_id 小组id或者$action_id 活动id
	 */
	public function updateDeleteState($data = array())
	{
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'updateDeleteState');
		foreach($data as $k=>$v)
		{
			$this->curl->addRequestData($k, $v);
		}
		$ret = $this->curl->request('activity.php');
		return $ret;
	}
	/**
	 * 活动或关闭或者开启
	 * $team_id 小组id或者$action_id 活动id
	 */
	public function updateCloseState($data = array())
	{
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'updateCloseState');
		foreach($data as $k=>$v)
		{
			$this->curl->addRequestData($k, $v);
		}
		$ret = $this->curl->request('activity.php');
		return $ret;
	}
	
}
?>
