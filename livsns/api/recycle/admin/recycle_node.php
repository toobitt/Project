<?php
require('global.php');
define('MOD_UNIQUEID','recycle_node');
class recycleNodeApi extends adminReadBase
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function show()
	{
		include_once(ROOT_PATH . 'lib/class/auth.class.php');
		$this->auth = new Auth();
		if($this->input['fid'])
		{
			$modules = $this->auth->get_module('id,app_uniqueid,mod_uniqueid,name',$this->input['fid']);
			if(is_array($modules))
			{
				foreach($modules as $k=>$v)
				{
					 $m = array('id'=>$v['id'],'name'=>$v['name'],'fid'=>$this->input['fid'],'depth'=>0,'is_last'=>1,'_appid'=>$v['app_uniqueid'], '_modid' => $v['mod_uniqueid']);
				 	 $this->addItem($m);
				}
			}
			$this->output();
		}
		else
		{
			$app_info = $this->auth->get_app();
			if(is_array($app_info))
			{
				foreach($app_info as $k=>$v)
				{
					$app = array('id' => $v['id'],'name' => $v['name'],'fid' =>0, 'depth' => 0,'is_last' =>0,'input_K' => '_id','_appid' => $v['bundle'],'_modid' => '');
					$this->addItem($app);
				}
			}
			$this->output();
		}
	}
	function detail(){}
	function count(){}
	function index(){}	
}
$out = new recycleNodeApi();
$action = $_INPUT['a'];
if(!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>
