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
	protected function settings_process()
	{
		parent::settings_process();
		$DATA_URL = trim($this->input['define']['DATA_URL']);
	
		$DATA_URL = rtrim($DATA_URL, '/') . '/';
		$this->input['define']['DATA_URL'] = $DATA_URL;
		
		file_put_contents('./data/ping.txt', 'ok');
		set_time_limit(5);
		$ping = @file_get_contents($DATA_URL . 'ping.txt');
		@unlink('./data/ping.txt');
		if ($ping != 'ok')
		{
			$this->errorOutput('DATA_URL_CAN_NOT_VISIT');
		}
	}
	
	public function get_config()
	{
		$config = $this->settings();
		$this->addItem($config);
		$this->output();
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