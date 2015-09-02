<?php
define('ROOT_DIR', '../../');
require('./conf/config.php');
require(ROOT_DIR . 'global.php');
require(ROOT_DIR . 'frm/conf_api_frm.php');
class confApi extends confApiFrm
{	
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
}
$module = 'confApi';
$$module = new $module();  

$func = $_INPUT['a'];
if (!method_exists($$module, $func))
{
	$func = 'unknow';	
}
$$module->$func();
?>