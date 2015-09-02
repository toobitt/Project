<?php
define('MOD_UNIQUEID','rongcloud_blacklist');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/rongcloud_blacklist_mode.php');
class rongcloud_blacklist_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new rongcloud_blacklist_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 拉黑群组
	 * @param black_im 1 拉黑 0解封
	 * @param rc_id rongcloud_info 关联id
	 * @param app_id  应用id
	 */
	public function black_im()
	{
		$black_im = intval($this->input['black_im']);
		$rc_id = intval($this->input['rc_id']);
		$app_id = intval($this->input['app_id']);
		
		if(!$rc_id || !$app_id)
		{
			$this->errorOutput(PARAM_WRONG);
		}
		
		$blackInfo = $this->mode->detail($rc_id);
		if($black_im)
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
			$ret = $this->mode->update($this->input['rc_id'],$param);
		}
		else
		{
			$vid = $this->mode->create(array(
					'rc_id' => $this->input['rc_id'],
					'app_id' => $this->input['app_id'],
					'total' => 1,
					'deadline' => $deadline,
			));
		}
		
		$result = array(
				'rc_id' => $this->input['rc_id'],
				'app_id' => $this->input['app_id'],
				'total' => $this->input['total'],
				'deadline' => $deadline
		);
		return $result;
	}
	
	
	public function create()
	{
		$data = array(
				'rc_id' => $this->input['rc_id'],
				'app_id' => $this->input['app_id'],
				'total' => '1',
				'deadline' => -1,
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
		if(!$this->input['rc_id'])
		{
			$this->errorOutput(NOID);
		}
		
		if($this->input['black_im'])
		{
			//拉黑群聊
			$blackInfo = $this->mode->detail($$this->input['rc_id']);
			if($blackInfo)
			{
				$total = $blackInfo['total'] + 1;
				$update_data = array(
						'app_id' => $this->input['app_id'],
						'total' => $total,
						'deadline' => '-1',
				);
			}
		}
		else 
		{
			//解封群聊
			$blackInfo = $this->mode->detail($$this->input['rc_id']);
			if($blackInfo)
			{
				$update_data = array(
						'deadline' => '0',
				);
			}
		}
		
		$ret = $this->mode->update($this->input['rc_id'],$update_data);
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

$out = new rongcloud_blacklist_update();
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