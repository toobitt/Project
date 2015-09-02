<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: register.php 1679 2011-01-10 08:28:20Z repheal $
***************************************************************************/
define('ROOT_DIR', '../');
define('SCRIPTNAME', 'check_email');
require('./global.php');
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
		if($this->user['id'])
		{
			$emails = explode('@',$this->user['email']);
			$goto = "http://mail.".$emails[1];
		}
		$gScriptName = SCRIPTNAME;
		$this->tpl->setTemplateTitle($this->page_title);
		$this->tpl->outTemplate('check_email');
	}
	//通过发到邮箱的验证链接，进行验证
	public function doverify()
	{
		if($this->user['id'])
		{
			$data = array(
				'id' => $this->user['id'],
				'verify_code' => $this->input['verify_code'],
				'a' => 'check'
			);
			$info = $this->emailclass->checkLink($data);
			if($info['done'] == 1)
			{
				$this->Redirect($this->lang['check_ok'],'user.php',3);
			}
			else
			{
				$this->Redirect($this->lang['check_fail'],'check_email.php?a=show',3);
			}
		}
		else
		{
			header('Location:login.php');
		}
		
	}
	/*
	 * 重发链接信息码
	 */
	public function resendLink()
	{
		if($this->user['id'])
		{
			$data = array(
				'id' =>$this->user['id'],
				'username' =>$this->user['username'],
				'email' => $this->user['email']
			);
			$rt = $this->emailclass->send_link($data);
			if($rt['done'] == 1)
			{
				echo $this->lang['send_email_ok'];
			}
			else
			{
				echo $this->lang['send_email_fail'];
			}
		}
		else
		{
			echo $this->lang['no_power'];
		}
	}
	/*
	 * 更新邮箱
	 */
	public function updateEmail()
	{
		$return['done'] =0;
		if($this->user['id'])
		{
			$data = array(
				'id' =>$this->user['id'],
				'a' =>'update_email',
				'email' => $this->input['email']
			);
			if($this->verifyEmail($data['email']))
			{
				$return['info']=$this->lang['email_pattern'];
				echo json_encode($return);
				exit;
			}
			$rt = $this->userclass->verifyEmail($this->input['email']);
			if($rt[0])
			{
				$return['info']=$this->lang['email_exist'];
			}
			else 
			{
				$rt = $this->emailclass->update_email($data);
				if($rt == 1)
				{
					$data = array(
						'id' =>$this->user['id'],
						'username' =>$this->user['username'],
						'email' => $this->input['email']
					);
					$rt = $this->emailclass->send_link($data);
					if($rt['done'] == 1)
					{
						$return['info']=$this->lang['send_email_ok'];
					}
					else
					{
						$return['info']=$this->lang['send_email_fail'];
					}
					$return['done'] =1;
				}
				else
				{
					$return['info']=$this->lang['email_wrong'];
				}
			}
		}
		else
		{
			$return['info']=$this->lang['no_power'];
		}
		echo json_encode($return);
	}
	
	/*
	 * 检查邮箱格式
	 */
	public function verifyEmail($email)
	{
		$patten = "/^[\w]([\w]*[-_\.]?[a-z0-9_]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{2,3}([\.][a-z]{2})?$/i";
		if(!$email)
		{
			return true;
		}
		else
		{
			if(!preg_match($patten,$email))
			{
				return true;
			}
			else
			{
				return false;
			}
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