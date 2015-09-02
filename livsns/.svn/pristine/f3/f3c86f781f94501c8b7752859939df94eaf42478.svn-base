<?php
define('MOD_UNIQUEID', 'gather_set');
require_once ('./global.php');
require_once CUR_CONF_PATH.'lib/gatherSet.class.php';
class GatherModuleUpdate extends adminUpdateBase
{
	function __construct()
	{
		parent::__construct();
		$this->set = new ClassgatherSet();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		$app_name = trim($this->input['app_name']);
		if (!$app_name)
		{
			$this->errorOutput('应用名称不能为空');
		}
		$info = array(
			'app_name'		=> $app_name,
			'sort_id'		=> intval($this->input['sort_id']),
			'request_type'	=> $this->input['request_type'],
			'bundle'		=> trim($this->input['bundle']),
			'host'			=> trim($this->input['host']),
			'dir'			=> trim($this->input['dir']),
			'filename'		=> trim($this->input['filename']),
			'funcname'		=> trim($this->input['funcname']),
			'delete_funcname'=> trim($this->input['delete_funcname']),
			'is_relay'		=> intval($this->input['is_relay']),
			'create_time'	=> TIMENOW,
			'org_id'		=> $this->user['org_id'],
			'user_id'		=> intval($this->user['user_id']),
			'user_name' 	=> $this->user['user_name'],
			'update_time'	=> TIMENOW,
		);
		if (!$info['bundle'] && !($info['host'] || $info['dir']))
		{
			$this->errorOutput('请选择应用或者填写配置');
		}
		if (!$info['sort_id'])
		{
			$this->errorOutput('请选择分类');
		}	
		$para = array(
			'argument' => $this->input['argument'],
			'mark' => $this->input['mark'],
			'dict' => $this->input['dict'],
			'value' => $this->input['value'],
			'way' => $this->input['way'],
		);
		$info['parameter'] = serialize($para);
		$data = $this->set->create($info);
		$this->addItem($data);
		$this->output();
	}
	
	public function delete()
	{
		$ids = $this->input['id'];
		if (!$ids)
		{
			$this->errorOutput(NOID);
		}
		$data = $this->set->delete($ids);
		$this->addItem($data);
		$this->output();
	}
	
	public function update()
	{
		$id = $this->input['id'];
		if (!$id)
		{
			$this->errorOutput(NOID);
		}
		$app_name = trim($this->input['app_name']);
		if (!$app_name)
		{
			$this->errorOutput('应用名称不能为空');
		}
		$info = array(
			'app_name'			=> $app_name,
			'sort_id'			=> intval($this->input['sort_id']),
			'request_type'		=> $this->input['request_type'],
			'bundle'			=> trim($this->input['bundle']),
			'host'				=> trim($this->input['host']),
			'dir'				=> trim($this->input['dir']),
			'filename'			=> trim($this->input['filename']),
			'funcname'			=> trim($this->input['funcname']),
			'delete_funcname'	=> trim($this->input['delete_funcname']),
			'is_relay'			=> intval($this->input['is_relay']),
			'update_time'		=> TIMENOW,
			'update_org_id'		=> $this->user['org_id'],
			'update_user_id'	=> intval($this->user['user_id']),
			'update_user_name' 	=> $this->user['user_name'],
		);
		if (!$info['bundle'] && !($info['host'] || $info['dir']))
		{
			$this->errorOutput('请选择应用或者填写配置');
		}
		if (!$info['sort_id'])
		{
			$this->errorOutput('请选择分类');
		}
		$para = array(
			'argument' => $this->input['argument'],
			'mark' => $this->input['mark'],
			'dict' => $this->input['dict'],
			'value' => $this->input['value'],
			'way' => $this->input['way'],
		);
		$info['parameter'] = serialize($para);		
		$data = $this->set->update($info, $id);
		$this->addItem($data);
		$this->output();
	}
	
	public function audit()
	{
		$ids = $this->input['id'];
		if (!$ids)
		{
			$this->errorOutput(NOID);
		}
		$status = intval($this->input['status']);
		$status = $status ? $status : 0;
		$data = $this->set->audit($ids,$status);
		$this->addItem($data);
		$this->output();
	}
	
	public function sort()
	{
		$ret = $this->drag_order('gather_set', 'order_id');
		$this->addItem($ret);
		$this->output();
	}
	public function publish()
	{
		
	}
	
	public function unknow()
	{		
		$this->errorOutput("此方法不存在！");
	}
}
$ouput = new GatherModuleUpdate();
if (!method_exists($ouput, $_INPUT['a']))
{
	$action = 'unknow';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
?>