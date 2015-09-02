<?php
require_once './global.php';
require_once CUR_CONF_PATH.'lib/astro.class.php';
define('SCRIPT_NAME', 'astroinfoApi');
define('MOD_UNIQUEID','astroinfoApi');//模块标识
class astroinfoApi extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->astro = new astro();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	function count()
	{

	}
	public function show()
	{

		$data= $this->astro->astroinfoselect();

		foreach($data as $k => $v)
		{
			$this->addItem($v);
		}
		$this->output();
	}

	function detail()
	{

	}

}
include ROOT_PATH . 'excute.php';
?>