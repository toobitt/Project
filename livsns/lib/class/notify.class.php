<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: notify.class.php 14563 2012-11-24 10:09:11Z repheal $
***************************************************************************/
class notify
{
	function __construct()
	{
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gGlobalConfig['App_notify']['host'], $gGlobalConfig['App_notify']['dir']);	
	}

	function __destruct()
	{
	}
	
	public function notify_send($content,$type)
	{
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'send');
		$this->curl->addRequestData('content', $content);
		$this->curl->addRequestData('type', $type);//
		$this->curl->addRequestData('html', 1);//
		return $this->curl->request('notify.php');
	}
	
	public function notify_get($user_id,$type,$pp=0,$count=50)
	{
		 
		$this->curl->setReturnFormat('json');
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'get');
		$this->curl->addRequestData('user_id', $user_id);
		$this->curl->addRequestData('type', $type);
		$this->curl->addRequestData('pp', $pp);
		$this->curl->addRequestData('count', $count);
		return $this->curl->request('notify.php');
	}
	

	public function notify_get_read($user_id,$type,$pp=0,$count=50)
	{
		 
		$this->curl->setReturnFormat('json');
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'get_read');
		$this->curl->addRequestData('user_id', $user_id);
		$this->curl->addRequestData('type', $type);
		$this->curl->addRequestData('pp', $pp);
		$this->curl->addRequestData('count', $count);
		return $this->curl->request('notify.php');
	}
	

	public function notify_get_unread($user_id,$type,$pp=0,$count=50)
	{
		 
		$this->curl->setReturnFormat('json');
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'get_unread');
		$this->curl->addRequestData('user_id', $user_id);
		$this->curl->addRequestData('type', $type);
		$this->curl->addRequestData('pp', $pp);
		$this->curl->addRequestData('count', $count);
		return $this->curl->request('notify.php');
	}
	
	public function notify_count($user_id,$type,$state = 0)
	{
		$this->curl->setReturnFormat('json');
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'count');
		$this->curl->addRequestData('user_id', $user_id);
		$this->curl->addRequestData('type', $type);
		$this->curl->addRequestData('state', $state);
		return $this->curl->request('notify.php');
	}
	public function notify_send_read($notify_id,$member_id,$type)
	{
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'send_read');
		$this->curl->addRequestData('notify_id', $notify_id);
		$this->curl->addRequestData('member_id', $member_id);
		$this->curl->addRequestData('type', $type);
		return $this->curl->request('notify.php');
	}
	public function notify_send_read_array($notify_read)
	{
		//$notify_read = array('0'=>array('notify_id'=>1,'member_id'=>2,'read_time'=>3,'type'=>4));
		$notify_read= json_encode($notify_read);
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'send_read');
		$this->curl->addRequestData('notify_read', $notify_read);
		return $this->curl->request('notify.php');
	}
}

?>