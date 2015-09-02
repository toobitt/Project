<?php

define('MOD_UNIQUEID','push_platform_node');
require('./global.php');

class PushPlatformNode extends adminReadBase
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
	public function count(){}
	
	//默认载入第一维数据
	public function show()
	{
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$nodes = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if($nodes)
			{
				$cond = " AND id IN (".implode(',', $nodes).")";
			}
		}
		$cond .= " ORDER BY id DESC";
		$sql = "SELECT id,name FROM " . DB_PREFIX . "app_info WHERE 1 " . $cond;
		$q = $this->db->query($sql);
		
		
		while($r = $this->db->fetch_array($q))
		{
			$r['id'] 		= $r['id'];
			$r['fid'] 		= 0;
			$r['name'] 		= $r['name'];
			$r['depath'] 	= 1;
			$r['is_last'] 	= 1;
			$this->addItem($r);
		}
		$this->output();
	}
}

$out=new PushPlatformNode();
$action=$_INPUT['a'];
if(!method_exists($out,$action))
{
	$action='show';
}
$out->$action();
?>