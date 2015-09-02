<?php
require_once('global.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
define('SCRIPT_NAME', 'plan_node');
class plan_node extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	//默认列出顶级节点
	public function show()
	{
		$fid = $this->input['fid']?'0':$this->input['fid'];
		foreach($this->settings['action_type'] as $k=>$v)
		{
			$m = array('id'=>$k,"name"=>$v,"fid"=>$fid,"depth"=>1 ,'is_last'=>1);
			$this->addItem($m);
		}
		$this->output();
	}
}
include(ROOT_PATH . 'excute.php');
?>
