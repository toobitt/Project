<?php
define('WITH_DB', true);
define('ROOT_DIR', '../');
require_once('../global.php');
require_once(ROOT_PATH . 'lib/class/curl.class.php');
class Prms extends uiBaseFrm
{
	private $curl;
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	function checkPrms()
	{
        //这里写检查权限代码

	}
}
$prms = new Prms();
$prms->checkPrms();
?>