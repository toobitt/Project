<?php
include_once ('global.php');
define('MOD_UNIQUEID','mateiral');
class cacheApi extends adminUpdateBase
{
	function __construct()
	{
		parent::__construct();
		include_once(CUR_CONF_PATH . 'lib/cache.class.php');
		$this->obj = new cache;
	}
	function __destruct()
	{
		parent::__destruct();
	}
	public function create(){}
	public function update(){}
	public function delete(){}
	public function audit(){}
	public function sort(){}
	public function publish(){}

	public function check_cache()
	{
		$material_type = $this->obj->check_cache('material_type.cache.php');
		$this->addItem($material_type);
		$this->output();
	}
}

$out = new cacheApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'check_cache';
}
$out->$action();
?>