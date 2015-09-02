<?php
/***************************************************************************
* LivSNS 0.1
* (C)2009-2010 HOGE Software.
*
* $Id: material.class.php 8188 2012-07-21 05:15:58Z wangleyuan $
***************************************************************************/
class workbench extends BaseFrm
{
	function __construct()
	{
		parent::__construct();
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gGlobalConfig['App_workbench']['host'], $gGlobalConfig['App_workbench']['dir']);
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	public function update($moduleid, $conid, $new_colid,$admin_name)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','update');
		$this->curl->addRequestData('moduleid',$moduleid);
		$this->curl->addRequestData('conid',$conid);
		$this->curl->addRequestData('new_colid',$new_colid);
		$this->curl->addRequestData('admin_name',$admin_name);
		return $this->curl->request('vod_publish.php');
	}
}

?>