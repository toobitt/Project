<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: index.php 325 2011-11-17 02:21:45Z develop_tong $
***************************************************************************/
define('WITH_DB', false);
define('NEED_AUTH', true);
define('WITHOUT_LOGIN', true);
define('ROOT_DIR', '../');
define('SCRIPT_NAME', 'config');
require('../global.php');
require('./upgrade.frm.php');
header('HTTP/1.1 200 OK',true,200);
class config extends upgradeFrm
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
		$db = include($this->mProductDir . $app . '/' . $this->mVersion . '/db/config.php');
		$configs['base'] = $gGlobalConfig;
		$configs['define'] = $this->get_const();
		$this->output($configs);
	}	
	
	protected  function get_const()
	{
		$app = $this->mApp;
		$content = file_get_contents($this->mProductDir . $app . '/' . $this->mVersion . '/db/config.php');
		if ($content)
		{
			preg_match_all("/define\('(.*?)'\s*,\s*\'{0,1}(.*?)\'{0,1}\);/is",$content, $const);
			if ($const[1])
			{
				$ret = array_combine($const[1], $const[2]);
				return $ret;
			}
		}
		return array();
	}
	
}
include (ROOT_PATH . 'lib/exec.php');
?>