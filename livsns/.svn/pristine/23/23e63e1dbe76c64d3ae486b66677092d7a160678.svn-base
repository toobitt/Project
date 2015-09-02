<?php
require_once './global.php';
require_once CUR_CONF_PATH.'lib/seekhelp_account.class.php';
define('MOD_UNIQUEID','seekhelp_account');//模块标识
class seekhelpAccountUpdate extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		$this->account = new ClassSeekhelpAccount();
		
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		if (!$this->settings['App_auth'])
		{
			$this->errorOutput('权限系统未安装！');
		}
		if (!SEEKHELP_ORG || !defined('SEEKHELP_ORG'))
		{
			$this->errorOutput('组织未定义！');
		}
		if (!SEEKHELP_ROLE || !defined('SEEKHELP_ROLE'))
		{
			$this->errorOutput('角色未定义！');
		}
		if (!trim($this->input['account']))
		{
			$this->errorOutput('帐户名不能为空！');
		}
		if (!trim($this->input['name']))
		{
			$this->errorOutput('机构名称不能为空！');
		}
		
		//密保卡绑定标识
		$cardid = intval($this->input['cardid']);
		$data = array(
			'account'			=>trim($this->input['account']),
			'password'			=>trim($this->input['password']),
			'email'				=>trim($this->input['email']),
			'name'				=>trim($this->input['name']),
			'sort_id'			=>intval($this->input['sort_id']),
			'status'			=>0,
			'create_time'		=>TIMENOW,
			'update_time'		=>TIMENOW,
			'user_id'			=>$this->user['user_id'],
			'user_name'			=>$this->user['user_name'],
			'ip'				=>$this->user['ip'],
			'org_id'			=>$this->user['org_id'],
			'cardid'			=> $cardid,
		);
		if (!$data['account'] || !$data['password'])
		{
			$this->errorOutput('请填写帐户名和密码');
		}
		if (!$data['name'])
		{
			$this->errorOutput('请填写机构名称');
		}
		$brief = trim($this->input['brief']);
		
		$ret = $this->account->create($data,$brief,$_FILES);
		$this->addItem($ret);
		$this->output();
	}
	
	public function update()
	{
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		if (!$this->settings['App_auth'])
		{
			$this->errorOutput('权限系统未安装！');
		}
		if (!SEEKHELP_ORG || !defined('SEEKHELP_ORG'))
		{
			$this->errorOutput('组织未定义！');
		}
		if (!SEEKHELP_ROLE || !defined('SEEKHELP_ROLE'))
		{
			$this->errorOutput('角色未定义！');
		}
		if (!trim($this->input['account']))
		{
			$this->errorOutput('帐户名不能为空！');
		}
		if (!trim($this->input['name']))
		{
			$this->errorOutput('机构名称不能为空！');
		}
		
		//密保卡绑定标识
		$cardid = intval($this->input['cardid']);
		
		$data = array(
			'account'			=>trim($this->input['account']),
			'password'			=>trim($this->input['password']),
			'email'				=>trim($this->input['email']),
			'name'				=>trim($this->input['name']),
			'sort_id'			=>intval($this->input['sort_id']),
			'update_time'		=>TIMENOW,
			'update_user_id'	=>$this->user['user_id'],
			'update_user_name'	=>$this->user['user_name'],
			'update_ip'			=>$this->user['ip'],
			'update_org_id'		=>$this->user['org_id'],
			'cardid'			=> $cardid,
		);
		$brief = trim($this->input['brief']);
		
		$ret = $this->account->update($id, $data, $brief, $_FILES);
		$this->addItem($ret);
		$this->output();
	}
	
	public function delete()
	{
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		$ids = $this->input['id'];
		if (!$ids)
		{
			$this->errorOutput(NOID);
		}
		if (!$this->settings['App_auth'])
		{
			$this->errorOutput('权限系统未安装！');
		}
		$data = $this->account->delete($ids);
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
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		$ids = $this->input['id'];
		if (!$ids)
		{
			$this->errorOutput(NOID);
		}
		$status = intval($this->input['status']);
		$status = ($status==1 || $status==2) ? $status : 0;
		$data = $this->account->audit($ids,$status);
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
$ouput= new seekhelpAccountUpdate();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'create';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();