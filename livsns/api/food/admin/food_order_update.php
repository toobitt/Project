<?php
define('MOD_UNIQUEID','food');
require_once('global.php');
class food extends adminUpdateBase
{
    public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		
	}
	
	public function update()
	{
		
	}
	
	public function delete()
	{
		
	}
	
	public function audit()
	{
		
	}
	
	public function sort()
	{
		
	}
	
	public function publish()
	{
		
	}
}

$out = new food();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>