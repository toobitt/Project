<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: login.php 9694 2012-08-22 08:36:50Z repheal $
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
		
		include_once(ROOT_DIR . 'lib/class/uset.class.php');
		$this->mUset = new uset();
		$rt = $this->mUset->get_desig_uset(array('register','isopeninvite', 'pubtesturl'));
		$is_open_register = $rt[0]['status'];//register
		$isopeninvite = $rt[1]['status'];//isopeninvite
		$pubtesturl = $rt[2]['status'];//pubtesturl
		
		$referto = $this->input['referto'] ? $this->input['referto'] : '';
	
		$this->tpl->addVar("is_open_register",$is_open_register);
		$this->tpl->addVar("isopeninvite",$isopeninvite);
		$this->tpl->addVar("pubtesturl",$pubtesturl);
		$this->tpl->addVar("message",$message);
		$this->tpl->addVar("referto",$referto);
		$this->tpl->outTemplate('login');
	}

	public function dologin()
	{
		if ($this->user['id'])
		{
		//	$this->Redirect('','','',1);
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
			$this->Redirect($this->lang['loginsucess'], '', 2, 0, $ucsynlogin);
		}
		else
		{
			$this->ReportError($this->lang['nameerror']);
		}
	}
	
	public function logout()
	{
		$timestamp = TIMENOW;
		hg_set_cookie('user', '', $timestamp+ 31536000);
		hg_set_cookie('pass','', $timestamp+ 31536000);
		hg_set_cookie('member_id',0, $timestamp+ 31536000);
		$syn = uc_user_synlogout();
		if ($this->input['debug'])
		{
			echo REFERRER;
			exit;
		}
		$this->Redirect($this->lang['logoutsucess'], REFERRER, 2, 0, $syn);
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