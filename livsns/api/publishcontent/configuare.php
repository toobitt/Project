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
		$is_need_audit = intval($this->input['base']['is_need_audit']);
		$this->input['base']['is_need_audit'] = $is_need_audit ? 1 : 0;
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