<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: index.php 2 2011-05-03 10:51:54Z develop_tong $
***************************************************************************/

define('ROOT_DIR', './');
define('SCRIPT_NAME', 'main');
require('./global.php');
class main extends uiBaseFrm
{	
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		$this->tpl->outTemplate('main');
	}
	
}
include (ROOT_PATH . 'lib/exec.php');
?>