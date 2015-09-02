<?php
define('MOD_UNIQUEID','developer_auth');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/developer_auth_mode.php');
require_once(CUR_CONF_PATH.'lib/company.class.php');
class developer_auth_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new developer_auth_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create(){}
	public function update(){}
	
	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->mode->delete($this->input['id']);
		if($ret)
		{
			$this->addLogs('删除开发者申请',$ret,'','删除开发者申请' . $this->input['id']);
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

        $status = intval($this->input['dev_status']);
        if(!$status)
        {
            $this->errorOutput(NO_STATUS);
        }
        $refuse_develop_reason = trim($this->input['refuse_develop_reason']);
        $data = array(
			'dev_status'    => $status,
        	'refuse_develop_reason' => $refuse_develop_reason,
        );

        $ret = $this->mode->update($id,$data);
        if($ret)
        {
            //申请创建成功后更改该用户的角色
            $company = new CompanyApi();
            //已审核，用户变成开发者角色
            if($status == 2)
            {
                $_ret = $company->setUserInfo(array(
                       'a'                => 'setUserRoleId',
                       'user_id'		  => $ret['dingdone_user_id'],
                       'dingdone_role_id' => 2,//设置为开发者
                ));
            }
            else if($status == 3)//被打回，
            {
                $_ret = $company->setUserInfo(array(
                       'a'				  => 'setUserRoleId',
                       'user_id'		  => $ret['dingdone_user_id'],
                       'dingdone_role_id' => 1,//设置为普通用户
                ));
            }
            
            if($_ret && is_array($_ret) && !isset($_ret['ErrorCode']))
            {
                $this->addItem($ret);
                $this->output();
            }
            else 
            {
                $this->errorOutput(FAILED);
            }
        }
    }

	public function sort(){}
	public function publish(){}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new developer_auth_update();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 