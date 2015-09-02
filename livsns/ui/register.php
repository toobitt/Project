<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: register.php 4194 2011-07-26 05:26:45Z lijiaying $
***************************************************************************/
define('ROOT_DIR', '../');
define('SCRIPTNAME', 'register');

require('./global.php');
class userInfo extends uiBaseFrm
{	
	private $info;
	function __construct()
	{		
		parent::__construct();
		$this->load_lang('register');
		include_once(ROOT_PATH . 'lib/user/user.class.php');
		$this->info = new user();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{				
		if($this->user['id'])
		{
			header("Location:index.php");
		}
		$this->page_title = $this->lang['pageTitle'];
		$gScriptName = SCRIPTNAME;
		
		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->setTemplateTitle($this->page_title);
		$this->tpl->outTemplate('register');	
	}
	public function create()
	{
		$userInfo = array(
			'email' => $this->input['email'],
			'username' => $this->input['username'],
			'password' => $this->input['password'],
		);
		$ret = $this->info->createUser($userInfo);
		$user_name = addslashes($this->input['username']);
		$password = addslashes($this->input['password']);
		$email = addslashes($this->input['email']); 	
		include_once './uclient/client.php';
		$member = uc_user_login($user_name, $password);
		// uid 大于0 登录成功，-1 ： 用户不存在,或者被删除   -2：密码错误  其他：未定义
		if($member[0] > 0) 
		{
			//同步登录
			$ucsynlogin =  uc_user_synlogin($member[0]);
			hg_set_cookie('user', $member[1], $timestamp+ 31536000);
			hg_set_cookie('pass',$member[4], $timestamp+ 31536000);
			hg_set_cookie('member_id',$member[0], $timestamp+ 31536000);
			$ret['script'] = ($ucsynlogin);
		}
		else
		{
			$this->ReportError($this->lang['nameerror']);
		}
		
		echo json_encode($ret);

	}
	
	public function verifyUsername()
	{
		$username = $this->input['username'];
		$patten = "/[!@#$%&()><\\/:;|,，。？！}{‘’“”\'\"]+/u";
		$flag1 = false;
		$flag2 = false;
		$flag3 = false;
		if(!$username)
		{
			echo json_encode($this->lang['username_tips_six']);
			exit;
		}
		else
		{
			$flag1 = true;
		}
		$num = mb_strlen($username);
		if($num <3 && $num >0)
		{
			echo json_encode($this->lang['username_tips_three']);
			exit;
		}
		else
		{
			if(preg_match($patten,$username))
			{			
				 echo json_encode($this->lang['username_tips_two']);
				 exit;
			}
			else
			{
				$flag2 = true;
			}
		}
		
		if($num>45 && $num>=3)
		{
			echo json_encode($this->lang['username_tips_four']);
			exit;
		}	
		else
		{
			if(preg_match($patten,$username))
			{		
				 echo json_encode($this->lang['username_tips_two']);
				 exit;
			}
			else
			{
				$flag3 = true;
			}
		}
		
		if($flag1 && $flag2 && $flag3)
		{			
			$ret = $this->info->verifyUsername($username);		
			if($ret[0] == 'true')
			{
				echo json_encode($this->lang['username_tips_five']);
				exit; 
			}				
		}	
	}
	
	public function verifyEmail()
	{
		$email = $this->input['email'];
		$patten = "/^[\w]([\w]*[-_\.]?[a-z0-9_]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{2,3}([\.][a-z]{2})?$/i";
		$flag = false;
		if(!$email)
		{
			echo json_encode($this->lang['email_tips_two']);
			exit;
		}
		else
		{
			if(!preg_match($patten,$email))
			{
				echo json_encode($this->lang['email_tips_three']);
				exit;
			}
			else
			{
				$flag = true;
			}
		}
		if($flag)
		{	
			$ret = $this->info->verifyEmail($this->input['email']);
			if($ret[0] == 'true')
			{
				echo json_encode($this->lang['email_tips_four']);
				exit; 
			}	
		}
	}

	public function password()
	{
		$password = $this->input['password'];
		$patten = '/^[\w.?-]*$/i';
		$flag1 = false;
		$flag2 = false;
		if(!preg_match($patten,$password))
		{
			echo json_encode($this->lang['password_tips_one']);
			exit;
		}
		if(!$password || strlen($password)<6)
		{
			echo json_encode($this->lang['password_tips_two']);
			exit;
		}	
		if(strlen($password)>15 && strlen($password)>6)
		{
			echo json_encode($this->lang['password_tips_three']);
			exit;
		}
	}

	public function passwords()
	{
		$password = $this->input['password'];
		$passwords = $this->input['passwords'];
		if(!$passwords)
		{
			echo json_encode($this->lang['passwords_tips_one']);
			exit;
		}
		if($password != $passwords)
		{
			echo json_encode($this->lang['passwords_tips_two']);
			exit;
		}
	}
}
$out = new userInfo();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>