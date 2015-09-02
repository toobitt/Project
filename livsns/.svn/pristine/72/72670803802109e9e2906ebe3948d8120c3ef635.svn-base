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
		include_once ROOT_PATH . 'lib/class/applant.class.php';
		$api = new applant();
		/*
		$template_info = $api->getTemplate(array('count' => -1));
		$this->addItem_withkey('template', $template_info);
		*/
		$ui_info = $api->getInterface(array('count' => -1));
		$this->addItem_withkey('interface', $ui_info);
		parent::settings();
	}
	
	public function doset()
	{
		$baseinfo = $this->input['base'];
		if ($baseinfo['names'])
		{
		    $setting_info = array();
		    foreach ($baseinfo['names'] as $k => $v)
		    {
		        $setting_info[$baseinfo['marks'][$k]] = array(
                    'name' => $v,
                    'url' => $baseinfo['urls'][$k]
                );
		    }
		    unset($this->input['base']['names']);
		    unset($this->input['base']['marks']);
		    unset($this->input['base']['urls']);
		    $this->input['base']['data_url']['file'] = $setting_info;
		}
		
		$size = array();
		foreach ($baseinfo['icon_size'] as $k => $v)
		{
		    $val = explode('|', $v);
		    foreach ($val as $vv)
		    {
		        $arr = explode(',', $vv);
		        $size[$k][] = array(
		            'width' => $arr[0],
		            'height' => $arr[1]
		        );
		    }
		}
		$size['max_size'] = $baseinfo['icon_max_size'];
		unset($this->input['base']['icon_max_size']);
		$this->input['base']['icon_size'] = $size;
		
		$size = array();
		foreach ($baseinfo['startup_size'] as $k => $v)
		{
		    $val = explode('|', $v);
		    foreach ($val as $vv)
		    {
		        $arr = explode(',', $vv);
		        $size[$k][] = array(
		            'width' => $arr[0],
		            'height' => $arr[1]
		        );
		    }
		}
		$size['max_size'] = $baseinfo['startup_max_size'];
		unset($this->input['base']['startup_max_size']);
		$this->input['base']['startup_size'] = $size;
		
		$size = array();
		foreach ($baseinfo['guide_size'] as $k => $v)
		{
		    $val = explode('|', $v);
		    foreach ($val as $vv)
		    {
		        $arr = explode(',', $vv);
		        $size[$k][] = array(
		            'width' => $arr[0],
		            'height' => $arr[1]
		        );
		    }
		}
		$size['max_size'] = $baseinfo['guide_max_size'];
		unset($this->input['base']['guide_max_size']);
		$this->input['base']['guide_size'] = $size;
		
		$size = array();
		foreach ($baseinfo['module_size'] as $k => $v)
		{
		    $val = explode('|', $v);
		    foreach ($val as $vv)
		    {
		        $arr = explode(',', $vv);
		        $size[$k][] = array(
		            'width' => $arr[0],
		            'height' => $arr[1]
		        );
		    }
		}
		$size['max_size'] = $baseinfo['module_max_size'];
		unset($this->input['base']['module_max_size']);
		$this->input['base']['module_size'] = $size;
		
		$size = array();
		foreach ($baseinfo['navBarTitle_size'] as $k => $v)
		{
		    $val = explode('|', $v);
		    foreach ($val as $vv)
		    {
		        $arr = explode(',', $vv);
		        $size[$k][] = array(
		            'width' => $arr[0],
		            'height' => $arr[1]
		        );
		    }
		}
		$size['max_size'] = $baseinfo['nav_max_size'];
		unset($this->input['base']['nav_max_size']);
		$this->input['base']['navBarTitle_size'] = $size;
		
		$size = array();
		foreach ($baseinfo['magazine_size'] as $k => $v)
		{
		    $val = explode('|', $v);
		    foreach ($val as $vv)
		    {
		        $arr = explode(',', $vv);
		        $size[$k][] = array(
		            'width' => $arr[0],
		            'height' => $arr[1]
		        );
		    }
		}
		$size['max_size'] = $baseinfo['magazine_max_size'];
		unset($this->input['base']['magazine_max_size']);
		$this->input['base']['magazine_size'] = $size;
		
		if (!$this->input['define']['USE_EFFECT'])
		{
		    $this->input['define']['USE_EFFECT'] = '0';
		}
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