<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* $Id: photoedit.php 17960 2013-03-21 14:28:00 jeffrey $
***************************************************************************/
require_once './global.php';
require_once CUR_CONF_PATH . 'lib/photoedit.class.php';
define('MOD_UNIQUEID', 'photoedit'); //模块标识

class photoeditApi extends outerReadBase
{
	private $photoedit;
	
	public function __construct()
	{
		parent::__construct();
		$this->photoedit = new photoeditClass();
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->photoedit);
	}
		
	
	public function show()
	{
	}
	
	public function count()
	{
		
	}
	
	public function detail()
	{
		
	}


	private function get_condition()
	{	
		return array(
			'key' => trim(urldecode($this->input['key'])),
		);
	}

	public function none()
	{
		$this->errorOutput('调用方法出错!');
	}
	
}



$out = new photoeditApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'none';
}
$out->$action();

?>