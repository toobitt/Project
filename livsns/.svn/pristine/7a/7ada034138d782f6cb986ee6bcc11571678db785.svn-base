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
	
	public function settings()
	{
		$dbconfig = $this->dbconfig;
		unset($dbconfig['pass']);
		$this->addItem_withkey('db', $dbconfig);
		$settings = $this->settings;
		$const = $this->get_const();
		if ($const)
		{
			foreach ($const AS $k => $c)
			{
				$define[$k] = $c;
			}
		}
		if ($settings['zs_image'] && is_array($settings['zs_image']))
		{
			foreach ($settings['zs_image'] as $key=>$val)
			{
				if (is_array($val))
				{
					$settings['zs_image'][$key] = implode('|', $val);
				}
			}
		}
		$this->addItem_withkey('api_dir', realpath(CUR_CONF_PATH));
		$this->addItem_withkey('define', $define);
		$this->addItem_withkey('base', $settings);
		$this->output();
	}
	
	protected function settings_process()
	{
		$zs_image = $this->input['base']['zs_image'];
		if ($zs_image)
		{
			foreach ($zs_image as $key=>$val)
			{
				if ($val)
				{
					$val = explode('|', $val);
					$zs_image[$key] = array(
						'host'		=> $val[0],
						'dir'		=> $val[1],
						'filepath'	=> $val[2],
						'filename'	=> $val[3],
					);
				}
			}
		}
		$this->input['base']['zs_image'] = $zs_image;		
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