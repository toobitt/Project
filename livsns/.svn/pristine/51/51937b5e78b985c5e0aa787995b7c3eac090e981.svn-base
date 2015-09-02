<?php
require('global.php');
define('MOD_UNIQUEID','logs_node');
require_once(ROOT_PATH . 'frm/node_frm.php');
class logs_node extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
		require_once(ROOT_PATH . 'lib/class/auth.class.php');
		$this->auth = new Auth();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function  show()
	{	
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND user_id = '.$this->user['user_id'];
			}
			if($this->user['prms']['logs']['show']['node'])
			{
				$arr = $this->user['prms']['logs']['show']['node']['logs_node'];
				foreach($arr as $k=>$v)
				{	
					if($r = stristr($v, 'mod_'))
					{
						$re = $this->auth->get_module('application_id','','',substr($v,4));
						$mod_app[] = $re[0]['application_id'];
						$mod[$re[0]['application_id']][] = substr($v,4);
					}
					else
					{
						$app[] = $v;
					}
				}
				$apps = implode(',',array_unique(array_merge($mod_app,$app)));
			}
			
			if($this->input['fid'])
			{
				$modules = $this->auth->get_module('id,mod_uniqueid,name',$this->input['fid']);
				if(is_array($modules))
				{
					if(!$mod_app)
					{
						$mod_app = array();
					}
					if(in_array($this->input['fid'],$mod_app))
					{
						foreach($modules as $k=>$v)
						{
							if(in_array($v['id'],$mod[$this->input['fid']]))
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
				$this->output();
			}
			else
			{
				$apps_arr = array();
				$app_info = $this->auth->get_app('','',$apps);
				if(is_array($app_info) && !empty($app_info))
				{
				foreach($app_info as $k=>$v)
				{
					if('logs' !=$v['bundle'])
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
				}
			}
		}
		else
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
				if(is_array($app_info) && !empty($app_info))
				{
					foreach($app_info as $k=>$v)
					{
						if('logs' !=$v['bundle'])
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
				}
			}
		}
		$this->output();
	}
	
	public function  get_logs_node()
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
				if('logs' !=$v['bundle'])
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
		//file_put_contents('022',$ids);
		$sql = 'SELECT * from '.DB_PREFIX.'categorys_tv WHERE cpid IN (' . $ids . ')';
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$tree[$row['cpid']][$row['cpid']] = array(
					'id'		=> 		$row['cpid'],
					'name'		=> 		$row['cp_name'],
					'fid'		=> 		0,
					'parents'	=> 		$row['cpid'],
					'childs'	=> 		$row['cpid'],
					'is_last'	=> 		1,
					'depath'	=> 		1,
					'is_auth'	=> 		1,
			);
		}
		
		if(!$ids)
		{
			$this->errorOutput(NO_ID);
		}
		if($tree)
		{
			foreach($tree as $v)
			{
				$this->addItem($v);
			}
		}
		$this->output();
	}
	function index()
	{	
	}
	function detail()
	{	
	}
}
$out = new logs_node();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>
