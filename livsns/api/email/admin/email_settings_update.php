<?php
/***************************************************************************
* $Id: email_settings_update.php 41583 2014-11-13 05:46:44Z youzhenghuan $
***************************************************************************/
define('MOD_UNIQUEID', 'email');
require('global.php');
class emailSettingsUpdateApi extends adminUpdateBase
{
	private $mEmailSettings;
	public function __construct()
	{
		parent::__construct();
		include_once CUR_CONF_PATH . 'lib/email_settings.class.php';
		$this->mEmailSettings = new emailSettings();
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
			$this->errorOutput('名称不能为空');
		}
	
		$appuniqueid = trim($this->input['appuniqueid']);
		if (!$appuniqueid||$appuniqueid=='-1')
		{
			$this->errorOutput('请选择应用标识');
		}
		$Object = new email_content_template();
		if(!$Object->check_appuniqueid_exists($appuniqueid))
		{
			$this->errorOutput('请选择正确的邮件内容模版');
		}
		if ($this->mEmailSettings->check_appuniqueid_exists($appuniqueid))
		{
			$this->errorOutput($appuniqueid . ' 标识已被占用');
		}
		
		$emailsend = trim($this->input['emailsend']);
		if (!$emailsend)
		{
			$this->errorOutput('邮箱地址不能为空');
		}
		
		$emailwrapbracket = intval($this->input['emailwrapbracket']);
		$smtpauth = intval($this->input['smtpauth']);
		$smtphost = trim($this->input['smtphost']);
		$smtpport = intval($this->input['smtpport']);
		$fromname = trim($this->input['fromname']);
		$smtpuser = trim($this->input['smtpuser']);
		$smtppassword = trim($this->input['smtppassword']);
		if ($smtppassword)
		{
			$smtppassword	= hg_encript_str($smtppassword);
		}
		
		$brief = (trim($this->input['brief']) == '这里输入描述') ? '' : trim($this->input['brief']);
		
		$data = array(
			'name' 				=> $name,
			'brief' 			=> $brief,
			'appuniqueid' 		=> $appuniqueid,
			'emailsend' 		=> $emailsend,//用于发送信件的邮箱地址
			'emailwrapbracket' 	=> $emailwrapbracket,
			'emailtype' 		=> trim($this->input['emailtype']),
			'usessl' 			=> trim($this->input['usessl']),
			'smtpauth' 			=> $smtpauth,//SMTP 身份验证
			'smtphost' 			=> $smtphost,//SMTP 主机名称
			'smtpport' 			=> $smtpport ? $smtpport : 25,//SMTP 端口,默认值为 25
			'fromname' 			=> $fromname ? $fromname : '管理员',
			'smtpuser' 			=> $smtpuser,//SMTP 用户名
			'smtppassword' 		=> $smtppassword,//SMTP 密码
			'email_footer' 		=> $this->settings['email_settings']['email_footer'],
		);
		
		$htmlheader = '';
		
		$htmlfooter = '';
		
		$is_head_foot = intval($this->input['is_head_foot']);
		if ($is_head_foot)
		{
			$header = trim($this->input['header']);
			$footer = trim($this->input['footer']);
			if (!$header || !$footer)
			{
				$this->errorOutput('邮件头尾不能为空');
			}
		
			if (get_magic_quotes_gpc())
			{
				$htmlheader = stripslashes($header);
				$htmlfooter = stripslashes($footer);
			} 
			else 
			{
				$htmlheader = $header;
				$htmlfooter = $footer;
			}
		}
		
		$info = $this->mEmailSettings->create($data, $is_head_foot, $htmlheader, $htmlfooter);
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
		
		$appuniqueid = trim($this->input['appuniqueid']);
		if (!$appuniqueid)
		{
			$this->errorOutput('请选择应用标识');
		}
		
		if ($appuniqueid != trim($this->input['old_appuniqueid']) && $this->mEmailSettings->check_appuniqueid_exists($appuniqueid))
		{
			$this->errorOutput($appuniqueid . ' 标识已被占用');
		}
		
		$emailsend = trim($this->input['emailsend']);
		if (!$emailsend)
		{
			$this->errorOutput('邮箱地址不能为空');
		}
		
		$emailwrapbracket 	= intval($this->input['emailwrapbracket']);
		$smtpauth 			= intval($this->input['smtpauth']);
		$smtphost 			= trim($this->input['smtphost']);
		$smtpport 			= intval($this->input['smtpport']);
		$fromname 			= trim($this->input['fromname']);
		$smtpuser 			= trim($this->input['smtpuser']);
		$smtppassword 		= trim($this->input['smtppassword']);
		if ($smtppassword)
		{
			$smtppassword	= hg_encript_str($smtppassword);
		}
		
		$brief = (trim($this->input['brief']) == '这里输入描述') ? '' : trim($this->input['brief']);
		
		$data = array(
			'name' 				=> $name,
			'brief' 			=> $brief,
			'appuniqueid'		=> $appuniqueid,
			'emailsend' 		=> $emailsend,
			'emailwrapbracket' 	=> $emailwrapbracket,
			'emailtype' 		=> trim($this->input['emailtype']),
			'usessl' 			=> trim($this->input['usessl']),
			'smtpauth' 			=> $smtpauth,//SMTP 身份验证
			'smtphost' 			=> $smtphost,//SMTP 主机名称
			'smtpport' 			=> $smtpport ? $smtpport : 25,//SMTP 端口,默认值为 25
			'fromname' 			=> $fromname ? $fromname : '管理员',
			'smtpuser' 			=> $smtpuser,//SMTP 用户名
			'smtppassword' 		=> $smtppassword,//SMTP 密码
			'email_footer' 		=> $this->settings['email_settings']['email_footer'],
		);

		$htmlheader = '';
		
		$htmlfooter = '';
		
		$is_head_foot = intval($this->input['is_head_foot']);
		if ($is_head_foot)
		{
			$header = trim($this->input['header']);
			$footer = trim($this->input['footer']);
			if (!$header || !$footer)
			{
				$this->errorOutput('邮件头尾不能为空');
			}
		
			if (get_magic_quotes_gpc())
			{
				$htmlheader = stripslashes($header);
				$htmlfooter = stripslashes($footer);
			} 
			else 
			{
				$htmlheader = $header;
				$htmlfooter = $footer;
			}
		}
		
		$info = $this->mEmailSettings->update($id, $data, $is_head_foot, $htmlheader, $htmlfooter);
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
		
		$info = $this->mEmailSettings->delete($id);
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
		
		$type = trim($this->input['type']);
		if (!$type)
		{
			$this->errorOutput('请传入要审核的字段');
		}
		
		$info = $this->mEmailSettings->audit($id, $type);
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

$out = new emailSettingsUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>