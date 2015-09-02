<?php
class archive
{
	function __construct()
	{
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gGlobalConfig['App_archive']['host'],$gGlobalConfig['App_archive']['dir']);
	}
	
	function __destruct()
	{
	}
 
	public function create($name,$content, $username)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','create');
		$this->curl->addRequestData('app_mark',APP_UNIQUEID);
		$this->curl->addRequestData('module_mark',MOD_UNIQUEID);
		$this->curl->addRequestData('name', $name);
		$this->curl->addRequestData('content', json_encode($content));
		$this->curl->addRequestData('archive_user', json_encode($username));
		$this->curl->addRequestData('html',true);
		$ret = $this->curl->request('archive_update.php');
		return $ret[0];
	}
}
?>