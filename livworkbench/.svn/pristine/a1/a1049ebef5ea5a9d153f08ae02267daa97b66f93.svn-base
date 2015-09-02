<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('WITH_DB', true);
define('ROOT_DIR', './');
define('SCRIPT_NAME', 'specialnode');
require('./global.php');
require('./lib/class/curl.class.php');
class specialnode extends uiBaseFrm
{	
	private $site;
	function __construct()
	{
		parent::__construct();
		if(!$this->settings['App_special'])
		{
			return false;
		}
		$this->curl = new curl($this->settings['App_special']['host'],$this->settings['App_special']['dir'].'admin/');
	}
	function __destruct()
	{
		parent::__destruct();
	}
	//注意此处没有做limit限制 也就是在子栏目很多的情况下可能会影响加载速度
	public function show()
	{
		if(!$this->curl)
		{
			return false;
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('html',true);
		$this->curl->addRequestData('a', 'show');
		$sorts = $this->curl->request('special_sort.php');
		$sorts  = $sorts ? $sorts : array();
		exit(json_encode($sorts));
	}
	
	public function get_special()
	{
		if(!$this->curl)
		{
			return false;
		}
		$sort_id = intval($this->input['sort_id']);
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('html',true);
		$this->curl->addRequestData('a', 'get_special');
		$this->curl->addRequestData('sort_id', $sort_id);
		$specials = $this->curl->request('special.php');
		$specials  = $specials ? $specials : array();
		exit(json_encode($specials));
	}
	
	public function get_special_column()
	{
		if(!$this->curl)
		{
			return false;
		}
		$special_id = intval($this->input['special_id']);
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('html',true);
		$this->curl->addRequestData('a', 'get_special_column');
		$this->curl->addRequestData('special_id', $special_id);
		$columns = $this->curl->request('special.php');
		$columns  = $columns ? $columns : array();
		exit(json_encode($columns));
	}
	
	
}
include (ROOT_PATH . 'lib/exec.php');
?>