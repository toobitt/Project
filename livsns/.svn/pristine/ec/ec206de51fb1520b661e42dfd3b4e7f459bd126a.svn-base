<?php
/* $Id: editpasswd.php 1713 2011-01-11 05:58:06Z repheal $ */
define('ROOT_DIR','../');
define('SCRIPTNAME', 'editpasswd');
require('./global.php');
include './uclient/client.php';
class editPasswd extends uiBaseFrm
{

	private $Muser;
	function __construct()
	{
		parent::__construct();
		$this->check_login();
		$this->load_lang('editpasswd');
		include_once (ROOT_PATH . 'lib/user/user.class.php');
		$this->Muser = new user();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function show()
	{
		$gScriptName = SCRIPTNAME;
		$this->page_title = '修改密码';
		$this->tpl->setTemplateTitle($this->page_title);
	    $this->tpl->outTemplate('editpasswd');
	}
	function updatePasswd()
	{
		$password = array(
			'pwd1' =>$this->input['pwd1'],
			'pwd2' =>$this->input['pwd2']
		);
		if(strcmp($password['pwd1'],$password['pwd2'])==0)
		{
			$ret = $this->Muser->update_password($password['pwd1']);
			uc_user_synupdatepw($this->user['username'],$password['pwd1']);
			if($this->user['id'] == $ret)
			{
				echo json_encode($ret);
			}
			else 
			{
				echo json_encode('');
			}
		}
	}
	
	public function verifyPassword()
	{
		$pwd = $this->input['pwd0']?$this->input['pwd0']:"";
		$ret = $this->Muser->verify_password($pwd);
		echo json_encode($ret);
	}


}
$out = new editPasswd();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>