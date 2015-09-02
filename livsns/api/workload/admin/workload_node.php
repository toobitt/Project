<?php
require('global.php');
define('MOD_UNIQUEID','workload_node');
require_once(ROOT_PATH . 'frm/node_frm.php');
class workload_node extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
		require_once(ROOT_PATH . 'lib/class/auth.class.php');
		require_once(CUR_CONF_PATH . 'lib/workload_mode.php');
		$this->auth = new Auth();
		$this->mode = new workload_mode();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{	
		$fid = intval($this->input['fid']);
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if($this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'])
			{
				$arr = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
				if($arr)
				{
					$authnode_str = implode(',',$arr);
					$back = $this->mode->get_org_by_ids($authnode_str);
					if($back)
					{
						foreach($back as $k=>$v)
						{	
							$auth_node_arr[] = $v['childs'];
						}
						$authnodes = implode(',',$auth_node_arr);
						$authnodes_arr = explode(',',$authnodes);
					}
				}
			}
			$re = $this->auth->get_org($fid);
			if($re && is_array($re))
			{
				foreach ($re as $k=>$v)
				{
					if(in_array($v['id'],$authnodes_arr))
					{
						$this->addItem($v);
					}
				}
			}
		}
		else
		{
			$node = $this->auth->get_org($fid);
			if($node && is_array($node))
			{
				foreach ($node as $k=>$v)
				{
					$this->addItem($v);
				}
			}
		}
	
		$this->output();
	}
	
	public function  get_workload_node()
	{	
		if($this->input['fid'])
		{
			$modules = $this->auth->get_module('id,mod_uniqueid,name',$this->input['fid']);
			if(is_array($modules))
			{
				foreach($modules as $k=>$v)
				{
					$m = array('id'=>'mod_'.$v['id'],
							'name'=>$v['name'],
							'fid'=>$this->input['fid'],
							'depth'=>0,
							'is_last'=>1,
							'para'=>$v['mod_uniqueid']);
			 		 $this->addItem($m);
				}
			}
		}
		else
		{
			$apps_arr = array();
			$app_info = $this->auth->get_app();
			foreach($app_info as $k=>$v)
			{
				if('workload' !=$v['bundle'])
				{
					$apps = array('id'=>$v['id'],
								  'name'=>$v['name'],
								  'fid'=>0,
								  'depth'=>0,
								  'is_last'=>0,
								  'input_k'=>'_id',
								  'para'=>'app');
					$this->addItem($apps);
				}
			 }
			$this->output();
		}
	}
		//获取选中的节点树状
	public function get_selected_node_path()
	{
		$ids = urldecode($this->input['id']);
		$node = $this->auth->get_one_org($ids);
		$this->addItem($node);
		$this->output();
	}
	function index()
	{	
	}
	function detail()
	{	
	}
}
$out = new workload_node();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>
