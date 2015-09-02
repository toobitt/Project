<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: index.php 2 2011-05-03 10:51:54Z develop_tong $
***************************************************************************/

define('ROOT_DIR', './');
define('SCRIPT_NAME', 'select');
require('./global.php');
require(ROOT_PATH . 'lib/class/curl.class.php');
class select extends uiBaseFrm
{	
	private $curl;
	function __construct()
	{
		parent::__construct();
		$application_id = $this->input['aid'];
		$application = $this->check_application();
		if (!$application)
		{
			$this->ReportError('应用不存在或已被删除');
		}
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		$this->tpl->setSoftVar($application['softvar']); //设置软件界面
		$this->tpl->outTemplate('index');
	}
	
}
include (ROOT_PATH . 'lib/exec.php');
?>