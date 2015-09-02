<?php
define('WITHOUT_DB', true);
require('./global.php');
require(ROOT_PATH . 'frm/configuare_frm.php');
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

	protected function settings_process()//应用配置交互
	{
		$this->verifycode_syn();
	}

	private function verifycode_syn()
	{
		$this->memberConfig();
	}
	/**
	 * 
	 * 配置中心映射处理方法 ...
	 */
	private function memberConfig()
	{
		//接收用户中心部分配置至新会员
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