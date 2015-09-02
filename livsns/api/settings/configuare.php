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
	/**
	* 配置更新前处理
	*
	*/
	protected function settings_process()
	{
		$max_time_shift = intval($this->input['base']['max_time_shift']);
		$this->input['base']['max_time_shift'] = $max_time_shift > 0 ? $max_time_shift : 168;
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