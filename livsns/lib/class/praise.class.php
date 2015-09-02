<?php
/*
 *	编目curl
 *
 **/
class praise
{
	function __construct()
	{
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		if($gGlobalConfig['App_praise'])
		{
			$this->curl = new curl($gGlobalConfig['App_praise']['host'],$gGlobalConfig['App_praise']['dir']);
		}
	}
	
	function __destruct(){}
	
	public function create($is_praise = 0 , $content_id = 0 , $source = '')
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','create');
		$this->curl->addRequestData('source',$source);
		$this->curl->addRequestData('is_praise',$is_praise);
		$this->curl->addRequestData('content_id',$content_id);	
		$ret = $this->curl->request('admin/praise_update.php');
		return $ret[0];
	}
	
	
	public function update($is_praise = 0 , $content_id = 0 , $source = '')
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','update');
		$this->curl->addRequestData('source',$source);
		$this->curl->addRequestData('is_praise',$is_praise);
		$this->curl->addRequestData('content_id',$content_id);
		$ret = $this->curl->request('admin/praise_update.php');
		return $ret[0];
	}
	
	public function delete($content_id = 0 , $source = '')
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','delete');
		$this->curl->addRequestData('source',$source);
		$this->curl->addRequestData('content_id',$content_id);
		$ret = $this->curl->request('admin/praise_update.php');
		return $ret[0];
	}
}
?>