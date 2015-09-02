<?php

define('MOD_UNIQUEID','push_message_node');
require('./global.php');

class push_message_node extends adminReadBase
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
				$cond = " AND appid IN (".implode(',', $nodes).")";
			}
		}
		$cond .= " ORDER BY appid DESC";
		$sql = "SELECT appid,appname FROM " . DB_PREFIX . "certificate WHERE 1 " . $cond;
		$q = $this->db->query($sql);
		
		$appAuthInfo = $this->get_app_auth();
		
		if($appAuthInfo)
		{
			foreach ($appAuthInfo as $k => $v)
			{
				$authInfo[$v['appid']] = $v['custom_name'];
			}
		}
		
		while($r = $this->db->fetch_array($q))
		{
			if($authInfo[$r['appid']])
			{
				$r['name'] = $authInfo[$r['appid']];
			}
			else 
			{
				$r['name'] = '此应用已删除';
			}
			$r['fid'] = 0;
			$r['id'] = $r['appid'];
			//$r['name'] = $r['appname'];
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
		$sql = "SELECT appid,appname FROM ".DB_PREFIX."certificate WHERE appid IN(".$ids.")";
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$arr = array();
			$r['fid'] = 0;
			$r['id'] = $r['appid'];
			$r['name'] = $r['appname'];
			$r['childs'] = $r['appid'];
			$r['parents'] = $r['appid'];
			$r['depath'] = 1;
			$r['is_last'] = 1;
			$r['is_auth'] = 1;
			$arr[$r['id']] = $r;
			$this->addItem($arr);
		}
		$this->output();
	}
	public function get_app_auth()
	{
		include_once(ROOT_PATH . 'lib/class/auth.class.php');
		$this->pub = new Auth();
		$app_auth = $this->pub->get_auth_list(0,100);
		return $app_auth;
	}
}

$out=new push_message_node();
$action=$_INPUT['a'];
if(!method_exists($out,$action))
{
	$action='show';
}
$out->$action();
?>