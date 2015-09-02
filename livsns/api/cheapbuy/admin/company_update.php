<?php
define('MOD_UNIQUEID','company');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/company_mode.php');
class company_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		//检测是否具有配置权限
        $this->verify_setting_prms();
		$this->mode = new company_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		$name = trim($this->input['name']);
		
		if(!$name)
		{
			$this->errorOutput('请输入公司机构名称');
		}
		
		//联系方式
		if($this->input['contract_name'] || $this->input['contract_value'])
		{
			if(is_array($this->input['contract_name']))
			{
				foreach($this->input['contract_name'] as $k=>$v)
				{
					$contract['contract_name'][$k] = urldecode($this->input['contract_name'][$k]);
				}
			}
		
			if(is_array($this->input['contract_value']))
			{
				foreach($this->input['contract_value'] as $k=>$v)
				{
					$contract['contract_value'][$k] = urldecode($this->input['contract_value'][$k]);
				}
			}
		}
		$contract_way = serialize($contract);
		$data = array(
			'name' 			=> $name,
			'user_id' 		=> $this->user['id'],
			'user_name' 	=> $this->user['user_name'],
			'address'		=> trim($this->input['address']),
			'create_time'	=> TIMENOW,
			'update_time'	=> TIMENOW,
			'ip'			=> hg_getip(),
			'contract_way'	=> $contract_way,
			'brief'			=> trim($this->input['brief']),
		);
		
		$ret = $this->mode->create($data);
		if($ret)
		{
			$this->addLogs('创建',$ret,'','创建' . $this->input['id']);
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
		
		$name = trim($this->input['name']);
		if(!$name)
		{
			$this->errorOutput('请填写公司机构名称');
		}
		
		//联系方式
		if($this->input['contract_name'] || $this->input['contract_value'])
		{
			if(is_array($this->input['contract_name']))
			{
				foreach($this->input['contract_name'] as $k=>$v)
				{
					$contract['contract_name'][$k] = urldecode($this->input['contract_name'][$k]);
				}
			}
		
			if(is_array($this->input['contract_value']))
			{
				foreach($this->input['contract_value'] as $k=>$v)
				{
					$contract['contract_value'][$k] = urldecode($this->input['contract_value'][$k]);
				}
			}
		}
		$contract_way = serialize($contract);
		
		$update_data = array(
			'name' 			=> $name,
			'user_id' 		=> $this->user['id'],
			'user_name' 	=> $this->user['user_name'],
			'address'		=> trim($this->input['address']),
			'update_time'	=> TIMENOW,
			'ip'			=> hg_getip(),
			'contract_way'	=> $contract_way,
			'brief'			=> trim($this->input['brief']),
		);
		$ret = $this->mode->update($this->input['id'],$update_data);
		if($ret)
		{
			$this->addLogs('更新',$ret,'','更新' . $this->input['id']);
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
		$sql = "SELECT id FROM ".DB_PREFIX."product WHERE company_id IN (".$this->input['id'].")";
		$res = $this->db->query_first($sql);
		
		if($res)
		{
			$this->errorOutput('请先删除公司下产品');
		}
		
		$ret = $this->mode->delete($this->input['id']);
		if($ret)
		{
			$this->addLogs('删除',$ret,'','删除' . $this->input['id']);
			$this->addItem('success');
			$this->output();
		}
	}
	public function drag_order()
	{
		$tableName = 'company';
				
		$ids = explode(',',urldecode($this->input['video_id']));
		$order_ids = explode(',',urldecode($this->input['order_id']));
		
		foreach($ids as $k => $v)
		{
			$sql = "UPDATE " . DB_PREFIX . $tableName . " SET order_id = '" . $order_ids[$k] . "' WHERE id = '" . $v . "'";
			$this->db->query($sql);
		}
		$this->addItem($ids);
		$this->output();
	}
	public function audit()
	{
	}

	public function sort(){}
	public function publish(){}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new company_update();
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