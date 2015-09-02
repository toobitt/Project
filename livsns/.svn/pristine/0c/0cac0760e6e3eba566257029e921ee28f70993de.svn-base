<?php
include_once (ROOT_PATH . 'lib/class/curl.class.php');
class authapp
{
	protected $curl = null;
	function __construct()
	{
		$this->setCurl();
	}

	function __destruct()
	{
	}

	function setCurl()
	{
		global $gGlobalConfig;
		$this->curl = new curl($gGlobalConfig['App_auth']['host'], $gGlobalConfig['App_auth']['dir'] . 'admin/');
	}
	function user_delete($condition = array())
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'delete');
		if($condition)
		{
			foreach ($condition as $k=>$v)
			{
				$this->curl->addRequestData($k, $v);
			}
		}
		return $this->curl->request('admin_update.php');
	}
	function user_update_org($condition = array())
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'update_user_org');
		if($condition)
		{
			foreach ($condition as $k=>$v)
			{
				$this->curl->addRequestData($k, $v);
			}
		}
		return $this->curl->request('admin_update.php');
	}
	function user_list($condition = array())
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		if($condition)
		{
			foreach ($condition as $k=>$v)
			{
				$this->curl->addRequestData($k, $v);
			}
		}
		return $this->curl->request('admin.php');
	}
	function user_count($condition)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'count');
		if($condition)
		{
			foreach ($condition as $k=>$v)
			{
				$this->curl->addRequestData($k, $v);
			}
		}
		return $this->curl->request('admin.php');
	}
	function user_detail($condition=array())
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'detail');
		if($condition)
		{
			foreach ($condition as $k=>$v)
			{
				$this->curl->addRequestData($k, $v);
			}
		}
		return $this->curl->request('admin.php');
	}
	function get_org($condition=array())
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		if($condition)
		{
			foreach ($condition as $k=>$v)
			{
				$this->curl->addRequestData($k, $v);
			}
		}
		return $this->curl->request('admin_org.php');
	}
}
?>