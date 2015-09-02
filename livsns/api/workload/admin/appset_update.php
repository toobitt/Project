<?php
define('MOD_UNIQUEID','appset');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/appset_mode.php');
class appset_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new appset_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	//新增需要统计工作量的应用
	public function create()
	{
		if(!trim($this->input['app_uniqueid']))
		{
			$this->errorOutput('应用标识不能为空');
		}
		$data = array(
			'app_uniqueid'	=> trim($this->input['app_uniqueid']),
			'name'			=> trim($this->input['name']),
			'state'			=> $this->input['state'] ? intval($this->input['state']) : 1,
			'filename'		=> trim($this->input['filename'],'.php').'.php',
			'functions'		=> trim($this->input['functions']),
			'color'			=> trim($this->input['color']),
			'user_id'		=> $this->user['user_id'],
			'user_name'		=> $this->user['user_name'],
			'create_time'	=> TIMENOW
		);
		$check_name = $this->mode->check_name_exist(trim($this->input['app_uniqueid']));
		if(!$check_name)
		{
			$this->errorOutput('该应用已经存在');
		}
		$check = $this->mode->check_exist($data['app_uniqueid'],$data['filename'],$data['functions']);//检查应用中是否存在该方法
		if(!$check)
		{
			$this->errorOutput('该应用不存在或未升级');
		}
		$vid = $this->mode->create($data);
		if($vid)
		{
			$data['id'] = $vid;
			$this->addLogs('创建',$data,'','创建' . $vid);
			$this->addItem('success');
			$this->output();
		}
	}
	
	//更新需要统计工作量的应用
	public function update()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		if(!trim($this->input['app_uniqueid']))
		{
			$this->errorOutput('应用标识不能为空');
		}
		$update_data = array(
			'app_uniqueid'	=> trim($this->input['app_uniqueid']),
			'name'			=> trim($this->input['name']),
			'state'			=> $this->input['state'] ? intval($this->input['state']) : 1,
			'filename'		=> trim($this->input['filename'],'.php').'.php',
			'functions'		=> trim($this->input['functions']),
			'color'			=> trim($this->input['color']),
		);
		$check = $this->mode->check_exist($update_data['app_uniqueid'],$update_data['filename'],$update_data['functions']);
		if(!$check)
		{
			$this->errorOutput('该应用不存在或未升级');
		}
		$ret = $this->mode->update($this->input['id'],$update_data);
		if($ret)
		{
			$this->addLogs('更新',$ret,'','更新' . $this->input['id']);
			$this->addItem('success');
			$this->output();
		}
	}
	
	//删除统计工作量的应用
	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->mode->delete($this->input['id']);
		if($ret)
		{
			$this->addLogs('删除',$ret,'','删除' . $this->input['id']);
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function audit()
	{}
	
	//开启或者关闭应用
	public function state()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$state = $this->input['state'];
		$ret = $this->mode->state($this->input['id'],$state);
		if($ret)
		{
			if($state)
			{
				$opera = '开启';
			}
			else 
			{
				$opera = '关闭';
			}
			$this->addLogs($opera,'',$ret,$opera . $this->input['id']);
			$this->addItem($ret);
			$this->output();
		}
	}
	
	public function sort()
	{
		$content_id = $this->input['content_id'];
		$order_id 	= $this->input['order_id'];
		if(!$content_id)
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->drag_order('appset', 'order_id');
	
		$this->addItem($ret);
		$this->output();
	}
	
	public function publish(){}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new appset_update();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>