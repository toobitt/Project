<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: index.php 2 2011-05-03 10:51:54Z develop_tong $
***************************************************************************/
define('ROOT_DIR', './');
define('WITH_DB', false);
define('WITHOUT_LOGIN', true);
define('SCRIPT_NAME', 'runcron');
require('./global.php');
class runcron extends uiBaseFrm
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
		include(ROOT_DIR . 'lib/class/cron.class.php');
		$crond = new crond();
		if ($this->settings['croncmd'])
		{
			$crond->setCronCmd($this->settings['croncmd']);
		}
		echo $crond->start();
	}
}

include (ROOT_PATH . 'lib/exec.php');
?>