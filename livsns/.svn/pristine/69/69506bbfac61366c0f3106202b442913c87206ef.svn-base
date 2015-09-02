<?php
define('NEED_CHECKIN', true);
define('MOD_UNIQUEID','food');
require_once('global.php');
require_once(ROOT_PATH.'lib/class/curl.class.php');
require_once(ROOT_PATH.'lib/class/material.class.php');
class food extends adminReadBase
{
    public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index()
	{
		
	}

	public function show()
	{
	   
	}

	public function count()
	{
		
	}
	
	public function get_condition()
	{
		
	}
	
	public function detail()
	{
		
	}
}

$out = new food();
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