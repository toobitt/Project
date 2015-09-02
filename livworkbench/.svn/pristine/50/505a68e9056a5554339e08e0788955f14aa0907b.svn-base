<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: auto_vod_record.php 481 2012-01-14 01:13:19Z repheal $
***************************************************************************/

define('WITH_DB', true);
define('NEED_AUTH', true);
define('WITHOUT_LOGIN', true);
define('ROOT_DIR', '../');
define('SCRIPT_NAME', 'UpgradeDb');
require('../global.php');
require('./upgrade.frm.php');
header('HTTP/1.1 200 OK',true,200);
class UpgradeDb extends upgradeFrm
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
		$app = $this->mApp;
		if ($this->input['install'])
		{
			$db = @file_get_contents($this->mProductDir . $app . '/' . $this->mVersion . '/db/create.m2o');
		}
		else
		{
			$db = @file_get_contents($this->mProductDir . $app . '/' . $this->mVersion . '/db/update.m2o');
			$dbcreate = @file_get_contents($this->mProductDir . $app . '/' . $this->mVersion . '/db/create.m2o');
		}
		if ($app == 'livworkbench')
		{
			if ($dbcreate)
			{
				$db = json_decode($db, 1);
				$dbcreate = json_decode($dbcreate, 1);
				
				$db = array('app' => $db, 'create' => $dbcreate);
				echo json_encode($db);
			}
			else
			{
				echo $db;
			}
			
		}
		else
		{
			$db = json_decode($db, 1);
			$db = array('app' => $db);
			
			if ($dbcreate)
			{
				$db['create'] = json_decode($dbcreate, 1);
			}
			$m2odata = @file_get_contents($this->mProductDir . $app . '/' . $this->mVersion . '/db/m2odata.m2o');
			$m2odata = json_decode($m2odata, 1);
			$db['m2o'] = $m2odata;
			echo json_encode($db);
		}
	}

}

include (ROOT_PATH . 'lib/exec.php');
?>
