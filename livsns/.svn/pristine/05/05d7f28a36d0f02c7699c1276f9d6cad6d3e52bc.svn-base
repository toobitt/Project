<?php
require_once './global.php';
class applyApi extends adminBase
{
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

	public function apply_audit()
	{
		$return = array();
		$return['id'] = $this->input['id'];
		$return['type'] = $this->input['type'];
		$this->addItem($return);
		$this->output();
	}	
}

$out = new  applyApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'apply_audit';
}
$out->$action();