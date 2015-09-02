<?php
require_once('global.php');
class index extends BaseFrm
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function show()
	{
	}
	
}

$out = new index();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();
?>