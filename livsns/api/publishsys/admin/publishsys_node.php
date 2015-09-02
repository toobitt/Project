<?php

define('MOD_UNIQUEID','publishsys_node');
require('global.php');

class publishsys_node extends adminReadBase
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
	public function detail(){}
	//默认载入第一维数据
	public function show()
	{
		$pub_node = $this->settings['publishsys_node'];
		if($pub_node && is_array($pub_node))
		{
			foreach($pub_node as $k=>$v)
			{
				$r['id'] = $k;
				$r['name'] = $v;
				$r['fid'] = 0;
				$r['childs'] = $k;
				$r['parents'] = $k;
				$r['depath'] = 1;
				$r['is_last'] = 1;
				$this->addItem($r);
			}
			$this->output();
		}
	}
	
	//获取选中的节点树状
	public function get_selected_node_path()
	{
		$ids = urldecode($this->input['id']);
		if(!$ids)
		{
			$this->errorOutput(NO_ID);
		}
		$idarr = explode(',',$ids);
		
		$pub_node = $this->settings['publishsys_node'];
		if($pub_node && is_array($pub_node))
		{
			foreach($pub_node as $k=>$v)
			{
				if($idarr && is_array($idarr) && in_array($v,$idarr))
				{
					$arr = array();
					$r['id'] = $k;
					$r['name'] = $v;
					$r['fid'] = 0;
					$r['childs'] = $k;
					$r['parents'] = $k;
					$r['depath'] = 1;
					$r['is_last'] = 1;
					$r['is_auth'] = 1;
					$arr[$r['id']] = $r;
					$this->addItem($arr);
				}
			}
			$this->output();
		}
	}
	
	//用于分页
	public function count()
	{
		parent::count($this->get_condition());	
	}

}

$out=new publishsys_node();
$action=$_INPUT['a'];
if(!method_exists($out,$action))
{
	$action='show';
}
$out->$action();
?>