<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: index.php 2 2011-05-03 10:51:54Z develop_tong $
***************************************************************************/
define('ROOT_DIR', './');
define('SCRIPT_NAME', 'getUser');
require_once('global.php');
require_once(ROOT_PATH . 'lib/class/curl.class.php');
class getUser extends uiBaseFrm
{
	private $curl;
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		if($this->settings['App_auth'])
		{
			$this->curl = new curl($this->settings['App_auth']['host'], $this->settings['App_auth']['dir'] . 'admin/');
			$this->curl->initPostData();
			$this->curl->addRequestData('a','count');
			//先获取总数
			$total = $this->curl->request('admin.php');
			//再获取user
			$this->curl->initPostData();
			$this->curl->addRequestData('a','getUser');
			$this->curl->addRequestData('k',$this->input['name']);
			$this->curl->addRequestData('count',$total['total']);
			$user = $this->curl->request('admin.php');
			echo json_encode($user);
		}
	}
}
include (ROOT_PATH . 'lib/exec.php');
?>