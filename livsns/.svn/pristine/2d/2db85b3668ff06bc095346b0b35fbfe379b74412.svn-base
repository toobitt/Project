<?php
define('MOD_UNIQUEID','app_store');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/app_store_mode.php');
require_once(CUR_CONF_PATH . 'lib/company.class.php');

class business_auth_update extends adminUpdateBase
{
	private $mode;
	private $company;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new app_store_mode();
		$this->company = new CompanyApi();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		
	}
	
	public function audit()
	{
		
	}
	
	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->mode->delete($this->input['id']);
		if($ret || true)
		{
			$this->addLogs('删除上架申请',$ret,'','删除上架申请' . $this->input['id']);
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function update()
	{
	    $id = $this->input['id'];
        if(!$id)
        {
            $this->errorOutput(NOID);
        }

        $status = intval($this->input['status']);
        
        //准备好更新的数据
        $data = array('status' => $status);
        if($status == 1 || $status == 3)
        {
            $data['audit_time'] = TIMENOW;//记录开通时间
        }
        
        //如果被打回接收打回的原因
        if($status == 2)
        {
            $data['message'] = $this->input['message'];
        }
        
        $ret = $this->mode->update($id,$data);
        if(!$ret)
        {
            $this->errorOutput(FAILED);
        }
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

$out = new business_auth_update();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();