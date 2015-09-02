<?php

/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: login.php 4103 2011-06-21 08:26:39Z repheal $
***************************************************************************/

define('ROOT_DIR', '../');
require('./global.php');
include './uclient/client.php';
class login extends uiBaseFrm
{	
	function __construct()
	{
		parent::__construct();
		header('Location:' . SNS_UCENTER . 'login.php');
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
				
		$this->tpl->setTemplateTitle($this->page_title);
		$this->tpl->outTemplate('login');
	}

	public function dologin()
	{
		if ($this->user['id'])
		{
			$this->Redirect('','','',1);
		}
		$url = $this->input['referto']?$this->input['referto']:"";
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
			$user_name = $member[1];
			$password = $member[4];
			$user_id = $member[0];
			
			$sql = "SELECT * FROM " . DB_PREFIX . "user WHERE username='".$user_name."'";
			$first = $this->db->query_first($sql);
			if(!$first)
			{
				include_once (ROOT_PATH . 'lib/user/user.class.php');
				$ucUser = new user();
				$info = $ucUser->verify_user_exist($user_name, addslashes($this->input[$request_password]));
				$ip = hg_getip();
				$sql = "INSERT INTO ".DB_PREFIX."user(id,username,password,salt,email,avatar,register_time,ip) 
				values(".$info['id'].",'".$info['username']."','".$info['password']."','".$info['salt']."','".$info['email']."','".$info['avatar']."',".$info['join_time'].",'".$ip."')";
				$this->db->query($sql);
				$id = $this->db->insert_id();
				$sql = "INSERT INTO ".DB_PREFIX."user_extra(user_id) 
				values(".$id.")";
				$this->db->query($sql);		
				$user_name = $second['username'];
				$password = $second['password'];
				$user_id = $id;
			}
			//本地系统
			hg_set_cookie('user', $user_name, $timestamp+ 31536000);
			hg_set_cookie('pass', $password, $timestamp+ 31536000);
			hg_set_cookie('userid',$user_id, $timestamp+ 31536000);			
			$this->Redirect($this->lang['loginsucess'], $url, 2, 0, $ucsynlogin);	
		}
		else
		{
			$this->ReportError($this->lang['nameerror']);
		}
	}
	
	public function logout()
	{
		$url = $this->input['referto']?$this->input['referto']:"";
		$timestamp = TIMENOW;
		hg_set_cookie('user', '', $timestamp+ 31536000);
		hg_set_cookie('pass','', $timestamp+ 31536000);
		hg_set_cookie('member_id',0, $timestamp+ 31536000);
		$syn = uc_user_synlogout();
		$this->Redirect($this->lang['logoutsucess'],$url);
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