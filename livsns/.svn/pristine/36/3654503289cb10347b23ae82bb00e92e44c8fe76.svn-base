<?php
require_once './global.php';
require_once CUR_CONF_PATH.'lib/dingdone_user.class.php';
include_once ROOT_PATH . 'lib/class/auth.class.php';
define('MOD_UNIQUEID','dingdone_user');//模块标识
class dingdoneUserUpdate extends adminUpdateBase
{
	
	private $auth;
	public function __construct()
	{
		parent::__construct();
		$this->duser = new ClassDingdoneUser();
		$this->auth = new Auth();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		if (!$this->settings['App_auth'])
		{
			$this->errorOutput('权限系统未安装！');
		}
		if (!$this->settings['default_role'])
		{
			$this->errorOutput('角色未定义！');
		}
		if (!trim($this->input['account']))
		{
			$this->errorOutput('帐户名不能为空！');
		}
		$data = array(
			'account'		=> trim($this->input['account']),
			'password'		=> trim($this->input['password']),
			'email'			=> trim($this->input['email']),
			'status'		=> 0,
			'create_time'	=> TIMENOW,
			'update_time'	=> TIMENOW,
			'user_id'		=> $this->user['user_id'],
			'user_name'		=> $this->user['user_name'],
			'ip'			=> $this->user['ip'],
			'org_id'		=> $this->user['org_id'],
		);
		if (!$data['account'] || !$data['password'])
		{
			$this->errorOutput('请填写帐户名和密码');
		}
		$brief = trim($this->input['brief']);
		//创建组织机构
		$orgData = array(
			'name' => $data['account']
		);
		$org_info = $this->auth->create_org($orgData);
		if (!$org_info) $this->errorOutput(FAILED);
		$org 	= $org_info['id'];		
		$role 	= implode(',', $this->settings['default_role']);
		
		$ret = $this->duser->create($data, $role, $org, $brief,$_FILES);
		$this->addItem($ret);
		$this->output();
	}
	
	public function update()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		if (!$this->settings['App_auth'])
		{
			$this->errorOutput('权限系统未安装！');
		}
		if (!$this->settings['default_role'])
		{
			$this->errorOutput('角色未定义！');
		}
		if (!trim($this->input['account']))
		{
			$this->errorOutput('帐户名不能为空！');
		}
		$data = array(
			'account'=>trim($this->input['account']),
			'password'=>trim($this->input['password']),
			'email'=>trim($this->input['email']),
			'update_time'=>TIMENOW,
			'update_user_id'=>$this->user['user_id'],
			'update_user_name'=>$this->user['user_name'],
			'update_ip'=>$this->user['ip'],
			'update_org_id'=>$this->user['org_id'],
		);
		$brief = trim($this->input['brief']);		
		$ret = $this->duser->update($id, $data, $brief, $_FILES);
		$this->addItem($ret);
		$this->output();
	}
	
	public function delete()
	{
		$ids = $this->input['id'];
		if (!$ids)
		{
			$this->errorOutput(NOID);
		}
		if (!$this->settings['App_auth'])
		{
			$this->errorOutput('权限系统未安装！');
		}
		$data = $this->duser->delete($ids);
		$this->addItem($data);
		$this->output();
	}
	
	public function sort()
	{
		$this->addLogs('更改报料排序', '', '', '更改报料排序');
		$ret = $this->drag_order('account', 'order_id');
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
		$status = ($status==1 || $status==2) ? $status : 0;
		$data = $this->duser->audit($ids,$status);
		$this->addItem($data);
		$this->output();
	}
	
	public function publish()
	{
	
	}
	
	public function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
	
	
	
}
$ouput= new dingdoneUserUpdate();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'create';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();