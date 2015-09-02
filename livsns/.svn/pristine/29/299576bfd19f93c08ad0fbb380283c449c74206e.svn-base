<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: webapp.class.php 16201 2012-12-28 06:01:09Z jeffrey $
***************************************************************************/

class webapp
{
	public function __construct()
	{
		global $gGlobalConfig;
		$this->curl = new curl($gGlobalConfig['App_webapp']['host'], $gGlobalConfig['App_webapp']['dir']);
	}

	public function __destruct()
	{
		unset($this->curl);
	}
	
	/**
	 * 评分入库
	 */
	
	public function createscore($webmark,$webappmark,$uid,$uname,$cid,$ctitle,$score)
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'create');
		$this->curl->addRequestData('webmark', $webmark);
		$this->curl->addRequestData('webappmark', $webappmark);
		$this->curl->addRequestData('uid', $uid);
		$this->curl->addRequestData('uname', $uname);
		$this->curl->addRequestData('cid', $cid);
		$this->curl->addRequestData('ctitle', $ctitle);
		$this->curl->addRequestData('score', $score);
		$result = $this->curl->request('score_update.php');
		return $result;
	}
	
	/**
	 * 单条纪录的评分详情
	 */
	
	public function showscore($webmark,$webappmark,$cid)
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		$this->curl->addRequestData('webmark', $webmark);
		$this->curl->addRequestData('webappmark', $webappmark);
		$this->curl->addRequestData('cid', $cid);
		$result = $this->curl->request('score.php');
		return $result;
	}
	
	/**
	 * 心情入库
	 */
	
	public function createmood($webmark,$webappmark,$uid,$uname,$cid,$ctitle,$typeid)
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'create');
		$this->curl->addRequestData('webmark', $webmark);
		$this->curl->addRequestData('webappmark', $webappmark);
		$this->curl->addRequestData('uid', $uid);
		$this->curl->addRequestData('uname', $uname);
		$this->curl->addRequestData('cid', $cid);
		$this->curl->addRequestData('ctitle', $ctitle);
		$this->curl->addRequestData('typeid', $typeid);
		$result = $this->curl->request('mood_update.php');
		return $result;
	}
	
	/**
	 * 心情展示 单条新闻的心情评分详情
	 */
	
	public function showmood($webmark,$webappmark,$cid)
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		$this->curl->addRequestData('webmark', $webmark);
		$this->curl->addRequestData('webappmark', $webappmark);
		$this->curl->addRequestData('cid', $cid);
		$result = $this->curl->request('mood.php');
		return $result;
	}
	
	
	/**
	 * 顶踩入库
	 */
	
	public function createupdown($webmark,$webappmark,$uid,$uname,$cid,$ctitle,$updown)
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'create');
		$this->curl->addRequestData('webmark', $webmark);
		$this->curl->addRequestData('webappmark', $webappmark);
		$this->curl->addRequestData('uid', $uid);
		$this->curl->addRequestData('uname', $uname);
		$this->curl->addRequestData('cid', $cid);
		$this->curl->addRequestData('ctitle', $ctitle);
		$this->curl->addRequestData('updown', $updown);
		$result = $this->curl->request('updown_update.php');
		return $result;
	}
	
	/**
	 * 单条纪录的顶踩总数
	 */
	
	public function showupdown($webmark,$webappmark,$cid)
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		$this->curl->addRequestData('webmark', $webmark);
		$this->curl->addRequestData('webappmark', $webappmark);
		$this->curl->addRequestData('cid', $cid);
		$result = $this->curl->request('updown');
		return $result;
	}
	
}
?>