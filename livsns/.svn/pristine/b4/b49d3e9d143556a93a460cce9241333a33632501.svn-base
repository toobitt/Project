<?php
define('MOD_UNIQUEID','activate_code');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/activate_code_mode.php');
class activate_code_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new activate_code_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		$activate_code = $this->input['activate_code'];
		$guest_type = intval($this->input['guest_type']);
		if(!$activate_code)
		{
			$this->errorOutput(NO_ACTIVATE_CODE);
		}
		
		//判断该激活码是不是已经存在
		if($this->mode->isExistsCode($activate_code))
		{
			$this->errorOutput(ACTIVATE_CODE_EXISTS);
		}

		$data = array(
			'activate_code' => $activate_code,
			'guest_type' 	=> $guest_type?$guest_type:1,
		);
		
		$vid = $this->mode->create($data);
		if($vid)
		{
			$data['id'] = $vid;
			$this->addLogs('创建激活码',$data,'','创建激活码' . $vid);
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
		
		$activate_code = $this->input['activate_code'];
		$guest_type = intval($this->input['guest_type']);
		if(!$activate_code)
		{
			$this->errorOutput(NO_ACTIVATE_CODE);
		}
		
		//判断该激活码是不是已经存在，并且排除自己本身
		if($this->mode->isExistsCode($activate_code,$this->input['id']))
		{
			$this->errorOutput(ACTIVATE_CODE_EXISTS);
		}

		$update_data = array(
			'activate_code' => $activate_code,
			'guest_type' 	=> $guest_type?$guest_type:1,
		);
		$ret = $this->mode->update($this->input['id'],$update_data);
		if($ret)
		{
			$this->addLogs('更新激活码',$ret,'','更新激活码' . $this->input['id']);
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
			$this->addLogs('删除激活码',$ret,'','删除激活码' . $this->input['id']);
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

$out = new activate_code_update();
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