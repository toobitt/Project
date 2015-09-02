<?php
define('ROOT_DIR', '../../');
define('WITHOUT_DB', true);
require(ROOT_DIR . 'global.php');
require(ROOT_DIR . 'frm/configuare_frm.php');
require(ROOT_DIR . 'lib/class/curl.class.php');
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
		$basic_settings = $this->input['param']['cloudvideo_basic_set'];
		if(!is_array($basic_settings))
		{
			$basic_settings = array();
		}
		$content = '<?php
return $basic_settings = '.var_export($basic_settings,1).';
?>';
		hg_file_write(DATA_DIR . 'settings.php', $content);
	}
	function get_settings()
	{
		$basic_settings = @include(DATA_DIR . 'settings.php');
		if(!$basic_settings)
		{
			$basic_settings = array();
		}
		$this->addItem($basic_settings);
		$this->output();
	}
}
$module = 'configuare';
$$module = new $module();  

$func = $_INPUT['a'];
if (!method_exists($$module, $func))
{
	$func = 'doset';	
}
$$module->$func();
?>