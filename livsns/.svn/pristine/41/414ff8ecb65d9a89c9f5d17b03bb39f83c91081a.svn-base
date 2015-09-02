<?php
define('ROOT_DIR', '../../');
define('WITHOUT_DB', true);
require(ROOT_DIR . 'global.php');
require(ROOT_DIR . 'frm/configuare_frm.php');
class configuare extends configuareFrm
{	
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	function settings_process()
	{
		$dir = CUR_CONF_PATH . 'data/';
		if(!is_writable($dir))
		{
			$this->errorOutput("目录不可写");
		}
		file_put_contents($dir.'ping.txt', 'ok');
		$addomain = $this->input['define']['AD_DOMAIN'];
		$file = trim($addomain, '/').'/data/ping.txt';
		$ping = @file_get_contents($file);
		unlink($dir.'ping.txt');
		if($ping != 'ok')
		{
			$this->errorOutput("域名无法访问!");
		}
	}
}
$module = 'configuare';
$$module = new $module();  

$func = $_INPUT['a'];
if (!method_exists($$module, $func))
{
	$func = 'show';	
}
$$module->$func();
?>