<?php
require_once('global.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
define('SCRIPT_NAME', 'site_node');
define('MOD_UNIQUEID','site');
class site_node extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/site.class.php');
		$this->obj = new site();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$sitedata = $this->obj->get_site(' * ','',0,1000);
		foreach($sitedata as $v)
		{
			$m = array('id'=>$v['id'],"name"=>$v['site_name'],"fid"=>$v['id'],"depth"=>1,'is_last'=>1,'input_k'=>'id','total'=>'10');
			$this->addItem($m);
		}
		$this->output();
	}
	
}
include(ROOT_PATH . 'excute.php');
?>
