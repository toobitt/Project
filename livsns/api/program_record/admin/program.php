<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program.php 6082 2012-03-13 03:16:40Z repheal $
***************************************************************************/
require("global.php");
define('MOD_UNIQUEID','program');
class programApi extends adminReadBase
{
	function __construct()
	{
		parent::__construct();
		include(ROOT_DIR . 'lib/class/program.class.php');
		$this->obj = new program();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	function show(){}
	function index(){}
	public function count(){}
	public function detail(){}
	
	public function getGreaterProgram()
	{
		$channel_id = trim($this->input['channel_id']);
		if(empty($channel_id))
		{
			$this->errorOutput('缺少频道ID');	
		}
		$ret = $this->obj->getGreaterProgram($channel_id);
		if(!empty($ret))
		{
			$this->addItem($ret);
			$this->output();
		}		
	}
}

$out = new programApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>