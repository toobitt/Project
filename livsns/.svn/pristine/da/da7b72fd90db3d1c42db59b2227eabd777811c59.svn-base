<?php
define('MOD_UNIQUEID','mosaic');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/mosaic_mode.php');
class mosaic_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new mosaic_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		$data = array(
			'name' 			=> trim($this->input['name']),
			'x'	   			=> trim($this->input['x']),
			'y'	   			=> trim($this->input['y']),
			'width'			=> trim($this->input['width']),
			'height'		=> trim($this->input['height']),
			'create_time' 	=> TIMENOW,
			'update_time' 	=> TIMENOW,
			'user_id'		=> $this->user['user_id'],
			'user_name'		=> $this->user['user_name'],
			'org_id'		=> $this->user['org_id'],
		);
		if(!$data['name'])
		{
			$this->errorOutput('马赛克名称不能为空');
		}
		if(!$data['x'] || !$data['y'] || !$data['width'] || !$data['height'])
		{
			$this->errorOutput('马赛克参数不能为空');
		}
		
		$vid = $this->mode->create($data);
		if($vid)
		{
			$data['id'] = $vid;
			$this->addLogs('创建马赛克',$data,'','创建马赛克' . $vid);
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function update()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$update_data = array(
			'name' 	=> trim($this->input['name']),
			'x'	   	=> trim($this->input['x']),
			'y'	   	=> trim($this->input['y']),
			'width'	=> trim($this->input['width']),
			'height'=> trim($this->input['height']),
			'update_time' 	=> TIMENOW,
		);
		if(!$update_data['name'])
		{
			$this->errorOutput('马赛克名称不能为空');
		}
		if(!$update_data['x'] || !$update_data['y'] || !$update_data['width'] || !$update_data['height'])
		{
			$this->errorOutput('马赛克参数不能为空');
		}
		$ret = $this->mode->update($this->input['id'],$update_data);
		if($ret)
		{
			$this->addLogs('更新马赛克',$ret,'','更新马赛克' . $this->input['id']);
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->mode->delete($this->input['id']);
		if($ret)
		{
			$this->addLogs('删除马赛克',$ret,'','删除马赛克' . $this->input['id']);
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function audit(){}
	public function sort(){}
	public function publish(){}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new mosaic_update();
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