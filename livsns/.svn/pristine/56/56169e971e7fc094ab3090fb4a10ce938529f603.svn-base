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
		$DELETE_DAYS = trim($this->input['define']['DELETE_DAYS']);
	
		$this->input['define']['DELETE_DAYS'] = $DELETE_DAYS;
		
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