<?php
define('MOD_UNIQUEID','seekhelp_blacklist');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/seekhelp_blacklist_mode.php');
class seekhelp_blacklist_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new seekhelp_blacklist_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 拉黑app社区
	 * @param black_seekhelp 1 拉黑 0解封
	 * @param sort_id  社区id
	 * @param app_id  应用id
	 */
	public function black_seekhelp()
	{
		$black_seekhelp = intval($this->input['black_seekhelp']);
		$sort_id = intval($this->input['sort_id']);
		$app_id = intval($this->input['app_id']);
	
		if(!$sort_id || !$app_id)
		{
			$this->errorOutput(PARAM_WRONG);
		}
	
		$blackInfo = $this->mode->detail($sort_id);
		if($black_seekhelp)
		{
			//拉黑群聊
			$result = $this->Setblacklist($blackInfo,-1);
		}
		else
		{
			//解封群聊
			$result = $this->Setblacklist($blackInfo,0);
		}
	
		$this->addItem($result);
		$this->output();
	}
	
	public function Setblacklist($blackInfo = array(), $deadline = 0)
	{
		$total = 0 ;
		if($blackInfo && $deadline)
		{
			$total = $blackInfo['total'] + 1;
		}
	
		if($blackInfo)
		{
			$param = array(
					'app_id' => $this->input['app_id'],
					'deadline' => $deadline,
			);
			if($deadline == -1)
			{
				$param['total'] = $total;
			}
			$ret = $this->mode->update($this->input['sort_id'],$param);
		}
		else
		{
			$total = 1;
			$vid = $this->mode->create(array(
					'sort_id' => $this->input['sort_id'],
					'app_id' => $this->input['app_id'],
					'total' => $total,
					'deadline' => $deadline,
			));
		}
	
		$result = array(
				'sort_id' => $this->input['sort_id'],
				'app_id' => $this->input['app_id'],
				'total' => $total,
				'deadline' => $deadline
		);
		return $result;
	}
	
	public function create()
	{
		$data = array(
			/*
				code here;
				key => value
			*/
		);
		
		$vid = $this->mode->create($data);
		if($vid)
		{
			$data['id'] = $vid;
			//$this->addLogs('创建',$data,'','创建' . $vid);此处是日志，自己根据情况加一下
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
			/*
				code here;
				key => value
			*/
		);
		$ret = $this->mode->update($this->input['id'],$update_data);
		if($ret)
		{
			//$this->addLogs('更新',$ret,'','更新' . $this->input['id']);此处是日志，自己根据情况加一下
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
			//$this->addLogs('删除',$ret,'','删除' . $this->input['id']);此处是日志，自己根据情况加一下
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
			//$this->addLogs('审核','',$ret,'审核' . $this->input['id']);此处是日志，自己根据情况加一下
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

$out = new seekhelp_blacklist_update();
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