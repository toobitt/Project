<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('WITH_DB', true);
define('ROOT_DIR', '../');
define('SCRIPT_NAME', 'mk_cache');
require('../global.php');
require('../lib/class/curl.class.php');
class mk_cache extends uiBaseFrm
{	
	
	function __construct()
	{
		parent::__construct();
		include ROOT_PATH . 'lib/class/publishsys.class.php';
		$this->publishsys = new publishsys();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	
	//节点
	public function show()
	{
		$plan['site_id'] = intval($this->input['site_id']);
		$plan['page_id'] = intval($this->input['page_id']);
		$plan['page_data_id'] = intval($this->input['page_data_id']);
		$plan['content_type'] = intval($this->input['content_type']);
		$plan['client_type'] = intval($this->input['client_type']);
		if(!$plan['site_id'])
		{
			echo 'NO_SITE_ID';exit;
		}
		$this->publishsys->mk_cache($plan);
	}
	
}
include (ROOT_PATH . 'lib/exec.php');
?>