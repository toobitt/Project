<?php
require_once('global.php');
define('MOD_UNIQUEID','statistics');//模块标识
class stat_node extends adminBase
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/statistics.class.php');
		$this->obj = new statistics();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		include_once(ROOT_PATH . 'lib/class/auth.class.php');
		$auth = new auth();
		$apps = $auth->get_app($this->settings['stat_app']);
		foreach($apps as $k=>$v)
		{
			$m = array('id'=>$v['bundle'],"name"=>$v['name'],"fid"=>$v['bundle'],"depth"=>1 ,'is_last'=>1);
			$this->addItem($m);
		}
		$this->output();
	}
}

/**
 * 程序入口
 */

$out = new stat_node();
$action = $_INPUT['a'];
if(!method_exists($out, $action))
{
	$action = 'show';	
}
$out->$action();

?>