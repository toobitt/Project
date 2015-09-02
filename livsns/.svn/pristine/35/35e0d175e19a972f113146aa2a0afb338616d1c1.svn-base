<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: register.php 1679 2011-01-10 08:28:20Z repheal $
***************************************************************************/
define('ROOT_DIR', '../');
define('SCRIPTNAME', 'back_password');
require('./global.php');
include './uclient/client.php';
class userInfo extends uiBaseFrm
{	
	private $info;
	function __construct()
	{		
		parent::__construct();
		$this->load_lang('check_email');
		include_once(ROOT_PATH . 'lib/user/user.class.php');
		$this->userclass = new user();
		include_once(ROOT_PATH . 'lib/user/email.class.php');
		$this->emailclass = new email();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	/*
	 * 显示展示页面
	 */
	public function show()
	{				
		$this->page_title = $this->lang['pageTitle'];
		$this->show_edit =0;
		hg_add_head_element('js-c',"var SHOW_EDIT = ".$this->show_edit.";");
		$this->tpl->addVar('show_edit', $this->show_edit);
		
		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->setTemplateTitle($this->lang['pageTitle']);
		$this->tpl->outTemplate('back_password');

	}
	//通过发到邮箱的验证链接，进行验证
	public function doverify()
	{
		$data = array(
			'verify_code' => $this->input['verify_code'],
			'a' => 'check_pwd'
		);
		if(!($data['verify_code']))
		{
			$this->show();
			exit;
		}
		$info = $this->emailclass->checkLink($data);

		if($info['done'] == 1)
		{
			$this->show_edit =1;
			$this->tpl->addVar('show_edit', $this->show_edit);
			$this->tpl->outTemplate('back_password');
		}
		else
		{
			$this->ReportError('请确认验证地址');
		}
		
	}
	/*
	 * 重发链接信息码
	 */
	public function resendLink()
	{
		$userinfo = $this->userclass->getUserByName($this->input['name'],'all');
		$userinfo = $userinfo[0];
		if($userinfo)
		{
			$data = array(
				'id' =>$userinfo['id'],
				'username' =>$userinfo['username'],
				'email' => $userinfo['email'],
				'type' => 1
			);
			$rt = $this->emailclass->send_link($data);
			if($rt['done'] == 1)
			{
				$return['done'] =1;
				$emails = explode('@',$userinfo['email']);
				$return['email_link'] ="http://mail.".$emails[1];
				echo json_encode($return);
			}
			else
			{
				$return['done'] =0;
				$return['info'] ='发送邮件失败';
				echo json_encode($return);
			}
		}
		else
		{
			$return['done'] =0;
			$return['info'] ='没有该用户';
			echo json_encode($return);
		}
	}
	
	public function resetPwd()
	{
		$data = array(
			'password' => $this->input['password'],
			'password1' => $this->input['password1'],
			'verify_code' =>$this->input['verify_code'],
			'a' =>'update_pwd'
		);
		if(strcmp($data['password'],$data['password1'])==0)
		{
			unset($data['password1']);
			$rt = $this->emailclass->update_pwd($data);
			uc_user_synupdatepw($rt['name'],$this->input['password']);
			$return['done'] =0;
			if($rt['done'] == 1)
			{
				$return['done'] =1;
			}
			echo json_encode($return);
		}
	}
	
}
$out = new userInfo();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'doverify';
}
$out->$action();
?>