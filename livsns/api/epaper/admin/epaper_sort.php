<?php

define('MOD_UNIQUEID','epaper_sort');
require('global.php');

class magazine_node extends adminReadBase
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
		$sql = "SELECT id,name FROM ".DB_PREFIX."epaper";
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$r['fid'] = 0;
			$r['childs'] = $r['id'];
			$r['parents'] = $r['id'];
			$r['depath'] = 1;
			$r['is_last'] = 1;
			$this->addItem($r);
		}
		$this->output();
	}
	//获取选中的节点树状
	public function get_selected_node_path()
	{
		$ids = urldecode($this->input['id']);
		if(!$ids)
		{
			$this->errorOutput(NO_ID);
		}
		$sql = "SELECT id,name FROM ".DB_PREFIX."epaper WHERE id IN(".$ids.")";
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$arr = array();
			$r['fid'] = 0;
			$r['childs'] = $r['id'];
			$r['parents'] = $r['id'];
			$r['depath'] = 1;
			$r['is_last'] = 1;
			$r['is_auth'] = 1;
			$arr[$r['id']] = $r;
			$this->addItem($arr);
		}
		$this->output();
	}
	
	//用于分页
	public function count()
	{
		parent::count($this->get_condition());	
	}

}

$out=new magazine_node();
$action=$_INPUT['a'];
if(!method_exists($out,$action))
{
	$action='show';
}
$out->$action();
?>