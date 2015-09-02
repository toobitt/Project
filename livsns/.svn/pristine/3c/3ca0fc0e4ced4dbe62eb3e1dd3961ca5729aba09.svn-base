<?php
define('MOD_UNIQUEID','publisher');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/publisher_mode.php');
class publisher_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new publisher_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		if(!$this->input['name'])
		{
			$this->errorOutput('名称不能为空');
		}
		
		$data = array(
			'name' 		  => $this->input['name'],
			'brief'		  => $this->input['brief'],
			'create_time' => TIMENOW,
			'update_time' => TIMENOW,
			'ip'		  => hg_getip(),
			'user_name'	  => $this->user['user_name'],
			'user_id'	  => $this->user['user_id'],
			'org_id'	  => $this->user['org_id'],
		);
		
		$vid = $this->mode->create($data);
		if($vid)
		{
			$data['id'] = $vid;
			$this->addLogs('创建电视剧版权商',$ret,'','创建电视剧版权商id:' . $vid);
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
			'name' 		  => $this->input['name'],
			'brief'		  => $this->input['brief'],
			'update_time' => TIMENOW,
		);
		$ret = $this->mode->update($this->input['id'],$update_data);
		if($ret)
		{
			$this->addLogs('更新电视剧版权商',$ret,'','更新电视剧版权商id:' . $this->input['id']);
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
			$this->addLogs('删除电视剧版权商',$ret,'','删除电视剧版权商id:' . $this->input['id']);
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function audit()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->mode->audit($this->input['id']);
		if($ret)
		{
			$this->addLogs('审核电视剧版权商','',$ret,'审核电视剧版权商id:' . $this->input['id']);
			$this->addItem($ret);
			$this->output();
		}
	}

	public function sort(){}
	public function publish(){}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new publisher_update();
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