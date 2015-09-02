<?php
require('global.php');
define('MOD_UNIQUEID','create_page');
class create_page extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	public function index(){}
	
	public function show()
	{
		$this->output();
	}
	private function get_condition()
	{
		$condition = '';
		return $condition;
	}
	function detail()
	{
	}
	public function count()
	{
	}		
}
$out = new cellApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>
