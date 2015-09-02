<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('WITH_DB', false);
define('ROOT_DIR', './');
define('SCRIPT_NAME', 'getcmsmodel');
include(ROOT_DIR.'lib/class/curl.class.php');
require('./global.php');
class getcmsmodel extends uiBaseFrm
{
	private $curl;
	function __construct()
	{
		parent::__construct();
		$this->curl = new curl($this->settings['App_livcms']['host'], $this->settings['App_livcms']['dir']);
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function show()
	{
		$this->curl->initPostData();
		$mo = $this->curl->request('read_model.php');
		//print_r($mo[0]);
		echo json_encode($mo[0]);
		exit;
	}
	function getModelField()
	{
		$fileds = array();
		if(!$this->input['applyid'])
		{
			return $fields;
		}
		$this->curl->initPostData();
		$this->curl->addRequestData('a','getModelField');
		$this->curl->addRequestData('applyid',intval($this->input['applyid']));
		$fileds = $this->curl->request('read_model.php');
		//print_r($mo[0]);
		echo json_encode($fileds);
		exit;
	}
}
include (ROOT_PATH . 'lib/exec.php');