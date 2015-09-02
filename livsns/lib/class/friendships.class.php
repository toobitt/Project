<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: friendships.class.php 870 2010-12-18 09:07:24Z develop_tong $
***************************************************************************/
class friendShips
{
	private $curl;
	
	function __construct()
	{
		global $gMysqlfriendshipsConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gMysqlfriendshipsConfig['host'], $gMysqlfriendshipsConfig['apidir']);
	}

	function __destruct()
	{
	}

	public function create($id, $self_id = 0)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('user_id',$id);
		$this->curl->addRequestData('self_id',$self_id);
		return $this->curl->request('create.php');		
	}

	public function destroy($id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('user_id',$id);
		return $this->curl->request('destroy.php');		
	}
	
	public function move($id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('user_id',$id);
		return $this->curl->request('move.php');		
	}
	
	

	public function show($source_id , $target_id)
	{
		
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('source_id',$source_id);
		$this->curl->addRequestData('target_id',$target_id);
		return $this->curl->request('show.php');
	}
}
?>