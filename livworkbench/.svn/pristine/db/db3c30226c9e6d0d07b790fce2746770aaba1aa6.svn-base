<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: auto_vod_record.php 481 2012-01-14 01:13:19Z repheal $
***************************************************************************/
define('WITH_DB', false);
define('NEED_AUTH', false);
define('WITHOUT_LOGIN', true);
define('ROOT_DIR', '../');
define('SCRIPT_NAME', 'get_last_version');
require('../global.php');
require('./upgrade.frm.php');
set_time_limit(0);

class get_last_version extends upgradeFrm
{
	private $files = array();
	private $fiter_files = array();
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
		$this->checklastversion();
	}

	public function checklastversion()
	{
		$version_dir = $this->mRootDir . $this->mVersionDir;
		echo $version = $this->getLastestVesion($version_dir);
	}
}

include (ROOT_PATH . 'lib/exec.php');
?>