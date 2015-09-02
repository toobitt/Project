<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function show|detail|count
* @private function get_condition
* 
* $Id: user_update.php 12846 2012-10-23 05:12:56Z lijiaying $
***************************************************************************/
require('global.php');
class userUpdateApi extends BaseFrm
{
	private $mUser;
	public function __construct()
	{
		parent::__construct();
		require(CUR_CONF_PATH . 'lib/user.class.php');
		$this->mUser = new user();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		$username = trim($this->input['addname']);
		if (!$username)
		{
			$this->errorOutput('用户名不能为空');
		}

		$password = trim($this->input['addpassword']);
		if (!$password)
		{
			$this->errorOutput('密码不能为空');
		}

		$email = trim($this->input['addemail']);
		if (!$email)
		{
			$this->errorOutput('邮件不能为空');
		}
	
		$ret = $this->mUser->uc_user_register($username, $password, $email);

		switch ($ret)
		{
			case -1 :
				$this->errorOutput('用户名不合法');
				break;
			case -2 :
				$this->errorOutput('用户名含有屏蔽字');
				break;
			case -3 :
				$this->errorOutput('用户名已存在');
				break;
			case -4 :
				$this->errorOutput('邮箱格式不正确');
				break;
			case -5 :
				$this->errorOutput('邮箱有错');
				break;
			case -6 :
				$this->errorOutput('邮箱已被注册');
				break;
			default:
				if ($ret > 0)
				{
					$this->addItem($ret);
				}
				else
				{
					$this->errorOutput($ret.'未知错误');
				}
				break;
		}

		$this->output();
	}
	
	public function update()
	{
		if (!$this->input['id'])
		{
			$this->errorOutput('未传入用户ID');
		}
			
		$username 	= trim($this->input['addname']);
		$oldpw 		= trim($this->input['old_addpassword']);
		$newpw 		= trim($this->input['addpassword']);
		$email 		= trim($this->input['addemail']);
		
		if (!$username)
		{
			$this->errorOutput('用户名不能为空');
		}
		
		if (!$email)
		{
			$this->errorOutput('邮件不能为空');
		}

		$ret = $this->mUser->uc_user_edit($username, $oldpw, $newpw, $email);

		switch ($ret)
		{
			case 0 :
				$this->errorOutput('没有做任何修改');
				break;
			case -1 :
				$this->errorOutput('旧密码不正确');
				break;
			case -4 :
				$this->errorOutput('邮箱格式不正确');
				break;
			case -5 :
				$this->errorOutput('不允许注册');
				break;
			case -6 :
				$this->errorOutput('邮箱已被注册');
				break;
			case -7 :
				$this->errorOutput('没有做任何修改');
				break;
			case -8 :
				$this->errorOutput('该用户受保护无权限更改');
				break;
			default:
				if ($ret > 0)
				{
					$this->addItem($ret);
				}
				else
				{
					$this->errorOutput($ret.'未知错误');
				}
				break;
		}

		$this->output();
	}

	public function delete()
	{
		$uid = trim($this->input['id']);
		if (!$uid)
		{
			$this->errorOutput('未传入用户ID');
		}
		
		$ret = $this->mUser->uc_user_delete($uid);

		if (!$ret)
		{
			$this->errorOutput('删除失败');
		}

		$this->addItem($uid);
		$this->output();
	}

	public function unknow()
	{
		$this->errorOutput('空方法');
	}
}

$out = new userUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>