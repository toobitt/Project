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
	
	public function doset()
	{
		$baseinfo = $this->input['base'];
		if ($baseinfo['type_id'])
		{
			$setting_info = array();
			foreach ($baseinfo['type_id'] as $k => $v)
			{
				$setting_info['type'][$v]['name'] = $baseinfo['type_name'][$k];
				$setting_info['type'][$v]['value'] = $baseinfo['type_value'][$k];
			}
		}
		$this->input['base'] = $setting_info;
		parent::doset();
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