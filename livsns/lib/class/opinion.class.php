<?php
class opinion
{
	function __construct()
	{
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gGlobalConfig['App_opinion']['host'], $gGlobalConfig['App_opinion']['dir']);
	}

	function __destruct()
	{
	}

	public function addOpinion($rid,$data)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','create');
		$this->curl->addRequestData('app_mark',APP_UNIQUEID);
		$this->curl->addRequestData('module_mark',MOD_UNIQUEID);
		$this->curl->addRequestData('rid',$rid);
		$this->curl->addRequestData('content',$data);
		$ret = $this->curl->request('opinion.php');
		return $ret;
	}
	public function showOpinion($rid,$flag = 0)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','detail');
		$this->curl->addRequestData('app_mark',APP_UNIQUEID);
		$this->curl->addRequestData('module_mark',MOD_UNIQUEID);
		$this->curl->addRequestData('rid',$rid);
		$this->curl->addRequestData('flag',$flag);
		$ret = $this->curl->request('opinion.php');
		return $ret[0];
	}
	public function updateOpinion($rid, $data)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','update');
		$this->curl->addRequestData('app_mark',APP_UNIQUEID);
		$this->curl->addRequestData('module_mark',MOD_UNIQUEID);
		$this->curl->addRequestData('rid',$rid);
		$this->curl->addRequestData('content',$data);
		$ret = $this->curl->request('opinion.php');
		return $ret;
	}
	public function deleteOpinion($rid)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','delete');
		$this->curl->addRequestData('app_mark',APP_UNIQUEID);
		$this->curl->addRequestData('module_mark',MOD_UNIQUEID);
		$this->curl->addRequestData('rid',$rid);
		$ret = $this->curl->request('opinion.php');
		return $ret;
	}
	
}

?>