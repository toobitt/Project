<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: register.php 1679 2011-01-10 08:28:20Z repheal $
***************************************************************************/
define('ROOT_DIR', '../');
define('SCRIPTNAME', 'register');
@session_start();
require('./global.php');
class userInfo extends uiBaseFrm
{	
	private $info;
	function __construct()
	{		
		parent::__construct();
		$this->load_lang('register');
		if ($this->input['reffer_user'])
		{
			$reffer_user = intval($this->input['reffer_user']);
			hg_set_cookie('reffer_user', $reffer_user);
			header('Location:' . $_SERVER['PHP_SELF']);
		}
		include_once(ROOT_PATH . 'lib/user/user.class.php');
		$this->info = new user();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{				
		$this->page_title = $this->lang['pageTitle'];
		$invite_code = $this->input['invite_code']?$this->input['invite_code']:"";
		include_once(ROOT_DIR . 'lib/class/uset.class.php');
		$this->mUset = new uset();
		$rt = $this->mUset->get_desig_uset(array('register','noregister','isopeninvite'));
		$rt0 = $rt[0];//register
		$rt1 = $rt[1];//noregister
		$rt2 = $rt[2];//isopeninvite
		$rt2['descripion'] = "请通过邀请进行注册！";
		if(!$rt0['status'])//close register
		{
			if(!$rt2['status'])//close invite
			{
				$error = $rt1['status'];
				$this->ReportError($error);
			}
			else //open invite
			{
				if(!$invite_code)
				{
//					$error = $rt2['descripion'];
//					$this->ReportError($error);
					$this->tpl->outTemplate('noregister');
					exit;
				}
				else 
				{
					$ret = $this->info->verify_invite_code($invite_code);
					if(!$ret)
					{
//						$error = $rt2['descripion'];
//						$this->ReportError($error);
						$this->tpl->outTemplate('noregister');
						exit;
					}
				}
			}
		}
		else //open register
		{
			if($invite_code)
			{
				$ret = $this->info->verify_invite_code($invite_code);
			}
		}
		
		if($this->user['id'])
		{
			header("Location:index.php");
		}
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'zone.js');
		$gScriptName = SCRIPTNAME;
		//include hg_load_template('register');
		
		
		$this->tpl->addVar('register', $register);
		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->setTemplateTitle($this->page_title);
		$this->tpl->outTemplate('register');
		

		
		
	}
	public function create()
	{
		$reffer_user = hg_get_cookie('reffer_user');
		$userInfo = array(
			'email' => $this->input['email'],
			'username' => $this->input['username'],
			'password' => $this->input['password'],
			'digital_tv' => $this->input['digital_tv'],
			'location' => $this->input['location'],
			'location_code' => $this->input['location_code'],
			'invite_code' => $this->input['invite_code'],
			'reffer_user' => $reffer_user,
			'verifycode' => $this->input['verifycode']
		);
		
		
		if(!$userInfo['verifycode'])
		{
			echo json_encode('验证码不为空！');
			exit;
		}
		
		
		if(!$_SESSION['hg_verifycode'] || $userInfo['verifycode'] != $_SESSION['hg_verifycode'])
		{
			$ret = array('retcode' => '验证码不符！');
			echo json_encode($ret);exit;
		}
		$rets = $this->info->createUser($userInfo);
		if($rets['register'])//不能注册
		{
			$ret = array('retcode' => $rets['reason']);
			echo json_encode($ret);exit;
		}
		if($rets['banword'])//有关键词
		{
			$ret = array('retcode' => '有禁止词: '.$rets[0]);
			echo json_encode($ret);exit;
		}
		if($rets['user_exist'])
		{
			$ret = array('retcode' => '用户名已存在');
			echo json_encode($ret);exit;
		}
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
			$ret = array();
			$ret['script'] = $ucsynlogin;
			$ret['retcode'] = 1;
			$ret['email_action'] = $rets['email_action'];
			
		}
		else
		{
			$ret = array('retcode' => '注册失败');
		}
		echo json_encode($ret);

	}
	
	public function verifyUsername()
	{
		$username = $this->input['username'];
		//先判断是否有关键字
		if($this->checkBanword($username))
		{
			//有关键字
			echo json_encode($this->lang['banword']);
			exit;
		}
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
	
	public function checkBanword ($username)
	{
		include_once(ROOT_PATH . 'lib/class/banword.class.php');	
		$banword = new banword();
		$rt = $banword->banword($username);
		if($rt && $rt != "null")
		{
			return 1;
		}
		else
		{
			return 0;
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

	public function verifycode()
	{
		$verifycode = $this->input['verifycode'];
		if(!$_SESSION['hg_verifycode'] || !$verifycode)
		{
			echo json_encode('验证码不为空！');
			exit;
		}
		if($verifycode != $_SESSION['hg_verifycode'])
		{
			echo json_encode('验证码不符！');
			exit;
		}
	}
	
	public function record_email()
	{
		$email = $this->input['email']?$this->input['email']:"";
		if(!$email)
		{
			echo json_encode("");
			exit;
		}
		else 
		{
			$ret = $this->info->record_email($email);
			echo json_encode($ret);
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