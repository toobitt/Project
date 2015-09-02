<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: login.php 4194 2011-07-26 05:26:45Z lijiaying $
***************************************************************************/

define('ROOT_DIR', '../');
require('./global.php');
include './uclient/client.php';
class login extends uiBaseFrm
{	
	function __construct()
	{
		parent::__construct();
		$this->load_lang('login');
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		if ($this->user['id'])
		{
			if (strpos(REFERRER, 'login.php') != -1)
			{
				$url = 'index.php';
			}
			$this->Redirect('', $url,'',1);
		}
		$this->page_title = $this->lang['pageTitle'];

		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->setTemplateTitle($this->page_title);
		$this->tpl->outTemplate('login');	
	}

	public function dologin()
	{
		if ($this->user['id'])
		{
			$this->Redirect('','','',1);
		}
		$request_name = 'username';
		$request_password = 'password';
		$request_email = 'email';

		$user_name = addslashes($this->input[$request_name]);
		$password = addslashes($this->input[$request_password]);
		
		if(!$user_name&&!$password)
		{
			$this->ReportError($this->lang['nameerror']);
		}
		$email = addslashes($this->input[$request_email]); 	
		$member = uc_user_login($user_name, $password);
		$timestamp = TIMENOW;
		// uid 大于0 登录成功，-1 ： 用户不存在,或者被删除   -2：密码错误  其他：未定义
		if($member[0] > 0) 
		{
			//同步登录
			$ucsynlogin =  uc_user_synlogin($member[0]);
			hg_set_cookie('user', $member[1], $timestamp+ 31536000);
			hg_set_cookie('pass',$member[4], $timestamp+ 31536000);
			hg_set_cookie('member_id',$member[0], $timestamp+ 31536000);

			if($this->input['is_ajax_login'])
			{
				echo 'LOGINSUCCESS';
			}
			else
			{
				$this->Redirect($this->lang['loginsucess'], '', 2, 0, $ucsynlogin);	
			} 			
		}
		else
		{
			if($this->input['is_ajax_login'])
			{
				echo 'LOGINFAIL';
			}
			else
			{
				$this->ReportError($this->lang['nameerror']);
			} 			
		}
	}
	
	public function logout()
	{
		$timestamp = TIMENOW;
		hg_set_cookie('user', '', $timestamp+ 31536000);
		hg_set_cookie('pass','', $timestamp+ 31536000);
		hg_set_cookie('member_id',0, $timestamp+ 31536000);
		$syn = uc_user_synlogout();
		$this->Redirect($this->lang['logoutsucess'], '', 2, 0, $syn);
	}
}
$out = new login();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>