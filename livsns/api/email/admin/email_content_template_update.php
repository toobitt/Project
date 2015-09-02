<?php
/***************************************************************************
* $Id: email_settings_update.php 17907 2013-02-25 05:48:25Z repheal $
***************************************************************************/
define('MOD_UNIQUEID', 'email_content_template');
require('global.php');
class email_content_template_updateAdmin extends adminUpdateBase
{
	private $mEmailSettings;
	public function __construct()
	{
		parent::__construct();
		$this->ect = new email_content_template();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function create()
	{
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		$name = trim($this->input['name']);
		if (!$name)
		{
			$this->errorOutput('请填写应用名称不能为空');
		}
	
		$appuniqueid = trim($this->input['appuniqueid']);
		if (!$appuniqueid)
		{
			$this->errorOutput('请填写应用标识');
		}
	
		if ($this->ect->check_appuniqueid_exists($appuniqueid))
		{
			$this->errorOutput($appuniqueid . ' 标识已被占用');
		}
		
		if(! $subject = trim($this->input['subject']))
		{
			$this->errorOutput('请填写邮件标题');
		}
		
		if(! $body = trim($this->input['body']))
		{
			$this->errorOutput('请填写邮件内容');
		}
		
		$status = $this->user['group_type'] <= MAX_ADMIN_TYPE ? 1 : 0;
		
		$data = array(
			'name' 				=> $name,
			'appuniqueid' 		=> $appuniqueid,
			'subject' 		    => $subject,
			'body' 		        => $body,
			'appid' 			=> $this->user['appid'],
			'appname' 			=> $this->user['display_name'],
			'user_id' 			=> $this->user['user_id'],
			'user_name' 		=> $this->user['user_name'],
			'create_time' 		=> TIMENOW,
			'update_time' 		=> TIMENOW,
			'ip' 				=> hg_getip(),
			'status'			=> $status,
		);
		
		$info = $this->ect->create($data);
		$this->addItem($info);
		$this->output();
	}
	
	public function update()
	{
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入ID');
		}
	
		$name = trim($this->input['name']);
		if (!$name)
		{
			$this->errorOutput('名称不能为空');
		}
		if(! $subject = trim($this->input['subject']))
		{
			$this->errorOutput('请填写邮件标题');
		}
		if(! $body = trim($this->input['body']))
		{
			$this->errorOutput('请填写邮件内容');
		}
		$content_template_info = $this->ect->detail($id);
		$appuniqueid = trim($this->input['appuniqueid']);
		if ($appuniqueid!=$content_template_info['appuniqueid'])
		{
			$this->errorOutput('请勿修改应用标识');
		}
		$status = $this->user['group_type'] <= MAX_ADMIN_TYPE ? 1 : 0;
		$data = array(
			'name' 				=> $name,
			'subject' 		    => strip_tags($subject),
			'body' 		        => $body,
			'user_id' 			=> $this->user['user_id'],
			'user_name' 		=> $this->user['user_name'],
			'update_time' 		=> TIMENOW,
			'ip' 				=> hg_getip(),
			'status'			=> $status,
		);
		
		$info = $this->ect->update(array('id'=>$id), $data);
		$this->addItem($info);
		$this->output();
	}
	
	public function delete()
	{
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		$id = trim($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入ID');
		}
		
		$info = $this->ect->delete($id);
		$this->addItem($id);
		$this->output();
	}
	
	public function audit()
	{
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入ID');
		}
		
		$type = 'status';
		if (!$type)
		{
			$this->errorOutput('请传入要审核的字段');
		}
		
		$info = $this->ect->audit($id, $type);
		$this->addItem($info);
		$this->output();
	}
	
	public function sort()
	{
		
	}
	
	public function publish()
	{
		
	}
	
	public function unknow()
	{
		$this->errorOutput('此方法不存在！');
	}

}

$out = new email_content_template_updateAdmin();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>