<?php
/* $Id: editpasswd.php 4194 2011-07-26 05:26:45Z lijiaying $ */
define('ROOT_DIR','../');
define('SCRIPTNAME', 'editpasswd');
require('./global.php');
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
		
		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->setTemplateTitle($this->page_title);
		$this->tpl->outTemplate('editpasswd');
	}
	function updatePasswd()
	{
		$password = array(
			'pwd1' =>$this->input['pwd1'],
			'pwd2' =>$this->input['pwd2'],
			'salt' =>$this->input['salt'],
		);
		if(strcmp($password['pwd1'],$password['pwd2'])==0)
		{
			$ret = $this->Muser->update_password($password['pwd1'], $password['salt']);
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