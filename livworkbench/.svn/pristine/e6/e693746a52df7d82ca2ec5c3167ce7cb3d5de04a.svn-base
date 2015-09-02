<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: auto_vod_record.php 481 2012-01-14 01:13:19Z repheal $
***************************************************************************/
define('WITH_DB', false);
define('NEED_AUTH', true);
define('WITHOUT_LOGIN', true);
define('ROOT_DIR', '../');
define('SCRIPT_NAME', 'check_version');
require('../global.php');
require('./upgrade.frm.php');
set_time_limit(0);

class check_version extends upgradeFrm
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
		if ($this->mApp == 'livtemplates')
		{
			echo 'http://upgrade.hogesoft.com:233/product/' . $this->mVersionDir . $this->mApp . '/';
		}
		else
		{
			if (!$this->mUser['source'])

			{
				$suffix = '_zend';
			}
			if ($this->input['install'])
			{
			}
			else
			{
				$suffix .= '_update';
			}
			$filename = $this->mApp . $suffix;
			if ($this->input['js'])
			{
				$suffix = '_script';
				$filename = $this->mApp . $suffix;
			}
		    $filename = md5($filename . 'cjjt') . '.zip';
            //$filename = $filename . '.zip';
			if (is_file($this->mRootDir . $this->mVersionDir . $this->mApp . '/' . $this->mVersion . '/' . $filename))
			{
				echo 'http://upgrade.hogesoft.com:233/product/' . $this->mVersionDir . $this->mApp . '/' . $this->mVersion . '/' . $filename;
			}
			else
			{
				echo 'NO_VERSION';
			}
		}
	}

	public function checklastversion()
	{
		$version_dir = $this->mRootDir . $this->mVersionDir;
		echo $version = $this->getLastestVesion($version_dir);
	}
}

include (ROOT_PATH . 'lib/exec.php');
?>
