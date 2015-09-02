<?php
/***************************************************************************
* LivSNS 0.1
* (C)2009-2010 HOGE Software.
*
* $Id: favorites.class.php 2774 2011-03-15 06:58:54Z wang $
***************************************************************************/
class favorites
{
	function __construct()
	{
		global $gfavoritesApiConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gfavoritesApiConfig['host'], $gfavoritesApiConfig['apidir']);	
	}

	function __destruct()
	{
	}
	
	public function update($id)
	{
		$this->curl->setSubmitType('post');		
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('id', $id);//点滴ID
		return $this->curl->request('create.php');		
	}
	public function favorites($gettoal,$page,$count = 20,$id)
	{
		$this->curl->setSubmitType('post');		
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('id', $id);
		$this->curl->addRequestData('page', $page);
		$this->curl->addRequestData('count', $count);
		$this->curl->addRequestData('gettoal', $gettoal);	
		return $this->curl->request('favorites.php');			
	}
	public function deletefavorites($id)
	{
		$this->curl->setSubmitType('post');		
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('id', $id);//点滴ID
		return $this->curl->request('destroy.php');			
	}
}

?>