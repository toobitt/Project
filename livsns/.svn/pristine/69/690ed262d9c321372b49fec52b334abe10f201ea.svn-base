<?php
define('MOD_UNIQUEID','identity_auth');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/identity_auth_mode.php');
require_once(CUR_CONF_PATH.'lib/company.class.php');
class identity_auth_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new identity_auth_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create(){}
	public function update(){}
	public function sort(){}
	public function publish(){}
	
	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->mode->delete($this->input['id']);
		if($ret)
		{
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function audit()
	{
		$id = $this->input['id'];
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		
		$status = intval($this->input['status']);
		if(!$status)
		{
			$this->errorOutput(NO_STATUS);
		}
		
		$data = array(
			'suggestion' 	=> $this->input['suggestion'],
			'status' 		=> $status,
		);

		$ret = $this->mode->update($id,$data);
		if($ret)
		{
			//申请创建成功后更改该用户的推送状态
			$company = new CompanyApi();
			//已审核，用户的推送状态是待开通
			if($status == 2)
			{
				$company->modifyUserPushStatus($ret['dingdone_user_id'],3);
			}
			else if($status == 3)//被打回，用户的推送状态变成审核未通过
			{
				$company->modifyUserPushStatus($ret['dingdone_user_id'],4);
			}
			$this->addItem($ret);
			$this->output();
		}
	}

	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new identity_auth_update();
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