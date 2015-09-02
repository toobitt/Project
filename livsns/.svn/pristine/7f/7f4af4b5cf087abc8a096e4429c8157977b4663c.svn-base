<?php

/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: albums.class.php 387 2011-03-01 03:35:15Z chengqing $
***************************************************************************/

class albums
{
	private $curl;
	function __construct()
	{
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gGlobalConfig['App_albums']['host'], $gGlobalConfig['App_albums']['dir']);
	}

	function __destruct(){}
	
	//更新相册(微博1、行动2、头像3)
	public function add_sys_albums($type, $photo)
	{
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		$this->curl->addRequestData('type', $type);
		$this->curl->addRequestData('photo', $photo);
		$this->curl->addRequestData('html', 1);
		$this->curl->addRequestData('a', 'add_sys_albums');
		$ret = $this->curl->request('photo_update.php');
		return $ret;
	}
	/*
	public function get_user_albums($user_id = 0)
	{
		$this->curl->setSubmitType('get');		
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('user_id', $user_id);
		$this->curl->addRequestData('a', 'get_user_albums');	
		return $this->curl->request('albums/get_user_albums.php');	
	}
	
	public function getAlbumsNum()
	{
		$this->curl->setSubmitType('get');		
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'get_albums_num');
		$r = $this->curl->request('albums/get_user_albums.php');	
		return $r;	
	}
	
	public function getPicNum()
	{
		$this->curl->setSubmitType('get');		
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'get_pic_num');
		$r = $this->curl->request('albums/get_user_albums.php');	
		return $r;	
	}*/
}