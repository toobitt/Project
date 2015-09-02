<?php
define('MOD_UNIQUEID','member');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/member_mode.php');
class member_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new member_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
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
			$this->addLogs('删除嘉宾',$ret,'','删除嘉宾' . $this->input['id']);
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
	
	//解除交换的人
	public function unexchange()
	{	
		if(!$this->input['other_id'] || !$this->input['self_id'])
		{
			$this->errorOutput(NOID);
		}
		
		//先查询出交换信息
		$exchange_info = $this->mode->get_exchange_info(" AND other_exchange_id = '" .$this->input['other_id']. "' AND self_exchange_id = '" .$this->input['self_id']. "' ");
		if(!$exchange_info)
		{
			$this->errorOutput(YOU_NOT_EXCHANGE_CARDS);
		}
		
		//删除交换表
		$this->mode->deleteExchangeCards(array($this->input['other_id'],$this->input['self_id']));
		$this->addItem('success');
		$this->output();
	}

	public function sort(){}
	public function publish(){}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new member_update();
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