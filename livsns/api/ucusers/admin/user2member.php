<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function show|detail|count
* @private function get_condition
* 
* $Id: user2member.php 12846 2012-10-23 05:12:56Z lijiaying $
***************************************************************************/
require('global.php');
class user2MemberApi extends BaseFrm
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
	
	public function register()
	{		
		$username = trim($this->input['addname']);
		$password = trim($this->input['addpassword']);
		$email 	  = trim($this->input['addemail']);
		
		$ret = $this->mUser->uc_user_register($username, $password, $email);
		
		$this->addItem($ret);
		$this->output();
	}
	
	public function edit()
	{
		$username = trim($this->input['addname']);
		$oldpw 	  = trim($this->input['old_addpassword']);
		$newpw    = trim($this->input['addpassword']);
		$email    = trim($this->input['addemail']);

		$ret = $this->mUser->uc_user_edit($username, $oldpw, $newpw, $email);

		$this->addItem($ret);
		$this->output();
	}

	public function delete()
	{
		$uid = trim($this->input['id']);
		
		$ret = $this->mUser->uc_user_delete($uid);
		
		$this->addItem($ret);
		$this->output();
	}

	public function login()
	{
		$username = trim($this->input['username']);
		$password = trim($this->input['password']);
		
		$ret = $this->mUser->uc_user_login($username, $password);

		$this->addItem($ret);
		$this->output();
	}
	
	public function unknow()
	{
		$this->errorOutput('空方法');
	}
}

$out = new user2MemberApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>