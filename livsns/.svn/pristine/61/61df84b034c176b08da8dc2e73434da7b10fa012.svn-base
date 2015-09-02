<?php
define('MOD_UNIQUEID','leancloud_user');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/leancloud_user.class.php');
require_once(CUR_CONF_PATH . 'lib/app.class.php');
require_once(CUR_CONF_PATH."lib/company.class.php");

class leancloud_user_update extends adminUpdateBase
{
	private $mode;
	private $api;
	private $company;
    public function __construct()
	{
		parent::__construct();
		
		$this->mode = new leancloud_user();
		$this->api = new app();
		$this->company = new companyApi();
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
		$master_key = trim($this->input['master_key']);
		$prod = trim($this->input['prod']);
		$certfile_name = trim($this->input['certfile_name']);
		$user_id = intval($this->input['user_id']);
		$update_data = array();
		if($master_key && $prod && $certfile_name)
		{
			$update_data['master_key'] = $master_key;
			$update_data['prod'] = $prod;
			$update_data['certfile_name'] = $certfile_name;
			
			//同时将masterkey更新到 company的 push——config中
			$this->company->updateMasterkey($user_id,$master_key);	
		}
		$idsArr = array(
			'user_id' => $user_id,
		);
		$ret = $this->api->update("leancloud_app", $update_data, $idsArr);
		if($ret)
		{
			$this->addItem('success');
		}
		$this->output();
		
// 		if(!$this->input['id'])
// 		{
// 			$this->errorOutput(NOID);
// 		}
		
// 		$update_data = array(
// 			/*
// 				code here;
// 				key => value
// 			*/
// 		);
// 		$ret = $this->mode->update($this->input['id'],$update_data);
// 		if($ret)
// 		{
// 			//$this->addLogs('更新',$ret,'','更新' . $this->input['id']);此处是日志，自己根据情况加一下
// 			$this->addItem('success');
// 			$this->output();
// 		}
	}
	
	public function uodateIos()
	{
		
		
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

$out = new leancloud_user_update();

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