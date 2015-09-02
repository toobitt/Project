<?php
require_once './global.php';
require_once CUR_CONF_PATH . 'lib/audit.class.php';
define('MOD_UNIQUEID', 'auditset'); //模块标识
class auditUpdateApi extends adminUpdateBase
{	
	public function __construct()
	{
		parent::__construct();
		$this->audit = new Classaudit();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function update()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput(NOID);
		}
		$data = array(
			'title'=>trim($this->input['title']),
			'brief'=>trim($this->input['brief']),
			'host'=>trim($this->input['host']),
			'dir'=>trim($this->input['dir']),
			'bundle'=>trim($this->input['bundle']),
			'filename'=>trim($this->input['filename']),
			'funcname'=>trim($this->input['funcname']),
			'update_time'=>TIMENOW,	
			'update_org_id'=>$this->user['org_id'],
			'update_user_id'=>$this->user['user_id'],
			'update_user_name'=>$this->user['user_name'],
			'update_ip'=>$this->user['ip'],
		);
		$start_time = $this->input['start_date'];
		$end_time = $this->input['end_date'];
		if (!$start_time)
		{
			$this->errorOutput('开始时间不能为空');
		}
		$start_time = strtotime($start_time);
		if ($end_time)
		{
			$end_time = strtotime($end_time);
			if ($end_time<$start_time)
			{
				$this->errorOutput('结束时间不能早于开始时间');
			}
		}
		$data['start_time'] = $start_time;
		$data['end_time'] = $end_time;
		$data['week_day'] = $this->input['week_day'] ? implode(',', $this->input['week_day']) : '' ;
		
		if (!$data['title'])
		{
			$this->errorOutput('请填写配置名称');
		}
		if (!$data['filename'])
		{
			$this->errorOutput('请填写配置文件名');
		}
		if (!$data['funcname'])
		{
			$this->errorOutput('请填写配置方法名');
		}
		if (!$data['bundle'])
		{
			if (!$data['host'] || !$data['dir'])
			{
				$this->errorOutput('请填写应用信息');
			}
		}
		$infor = array(
			'start_time'=>$this->input['start_time'],
			'end_time'=>$this->input['end_time'],
			'type'=>$this->input['type'],
		);
		$data['infor'] = serialize($infor);
		$ret = $this->audit->update($data, $id);
		$this->addItem($ret);
		$this->output();
	}
	
	public function delete()
	{
		$ids = trim($this->input['id']);
		if(empty($ids))
		{
			$this->errorOutput(NOID);
		}
		$data = $this->audit->delete($ids);
		$this->addItem($data);
		$this->output();
	}

	public function create()
	{
		$data = array(
			'title'=>trim($this->input['title']),
			'brief'=>trim($this->input['brief']),
			'host'=>trim($this->input['host']),
			'dir'=>trim($this->input['dir']),
			'bundle'=>trim($this->input['bundle']),
			'filename'=>trim($this->input['filename']),
			'create_time'=>TIMENOW,	
			'org_id'=>$this->user['org_id'],
			'user_id'=>$this->user['user_id'],
			'user_name'=>$this->user['user_name'],
			'ip'=>$this->user['ip'],
		);
		if (trim($this->input['funcname']))
		{
			$data['funcname'] = trim($this->input['funcname']);
		}
		
		$start_time = $this->input['start_date'];
		$end_time = $this->input['end_date'];
		if (!$start_time)
		{
			$this->errorOutput('开始时间不能为空');
		}
		$start_time = strtotime($start_time);
		if ($end_time)
		{
			$end_time = strtotime($end_time);
			if ($end_time<$start_time)
			{
				$this->errorOutput('结束时间不能早于开始时间');
			}
		}
		$data['start_time'] = $start_time;
		$data['end_time'] = $end_time;
		$data['week_day'] = $this->input['week_day'] ? implode(',', $this->input['week_day']) : '' ;
		
		if (!$data['title'])
		{
			$this->errorOutput('请填写配置名称');
		}
		if (!$data['filename'])
		{
			$this->errorOutput('请填写配置文件名');
		}
		if (trim($this->input['funcname']))
		{
			$data['funcname'] = trim($this->input['funcname']);
		}
		if (!$data['bundle'])
		{
			if (!$data['host'] || !$data['dir'])
			{
				$this->errorOutput('请填写应用信息');
			}
		}
		$infor = array(
			'start_time'=>$this->input['start_time'],
			'end_time'=>$this->input['end_time'],
			'type'=>$this->input['type'],
		);
		$data['infor'] = serialize($infor);
		$ret = $this->audit->create($data);
		$this->addItem($ret);
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
		$data = $this->audit->audit($ids,$status);
		$this->addItem($data);
		$this->output();
	}

	public function publish()
	{
		
	}

	public function sort()
	{
		$ret = $this->drag_order('auditset', 'order_id');
		$this->addItem($ret);
		$this->output();
	}

	public function unknow()
	{
		$this->errorOutput('调用方法出错!');
	}
}

$out = new auditUpdateApi();
if (!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
else
{
	$action = $_INPUT['a'];
} 
$out->$action();

?>