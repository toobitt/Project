<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: index.php 2454 2013-03-26 08:03:23Z develop_tong $
***************************************************************************/
define('WITH_DB', false);
define('NEED_AUTH', true);
define('WITHOUT_LOGIN', true);
define('ROOT_DIR', '../');
define('SCRIPT_NAME', 'check_version');
require('../../global.php');
require('../upgrade.frm.php');
class data_source 
{	
	private $modestore;
	function __construct()
	{
		parent::__construct();		
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	public function get_data_source_info()
	{
		$signs = $this->input['sign'];
		$flag = $this->input['flag'];
		
		$host = $this->settings['App_publishsys']['host'];
		$dir = $this->settings['App_publishsys']['dir'].'admin/';
		$curl = new curl($host,$dir);
		$curl->setSubmitType('post');
		$curl->initPostData();
		$curl->addRequestData('a','export_datasource');
		$curl->addRequestData('sign',$signs);
		
		if($flag)
		{
			$curl->addRequestData('flag',$flag);
		}
		
		$data_source_info = $curl->request('data_source.php');
		
		if($data_source_info && is_array($data_source_info))
		{
			$this->addItem($data_source_info);
		}
		$this->output();
	}
	
}
include (ROOT_PATH . 'lib/exec.php');
?>