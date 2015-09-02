<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: cellphone.php 4216 2011-07-27 09:43:47Z zhoujiafei $
***************************************************************************/

define('ROOT_DIR', '../');
define('SCRIPTNAME', 'cellphone');
require('./global.php');
class cellphone extends uiBaseFrm
{	
	private $mUser;
	function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'lib/user/user.class.php');
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		if(!$this->user['id'])
		{
			$url = SNS_UCENTER.'user.php';
			$this->Redirect('', $url,'',1);
		}
		$this->mUser = new user();
		$this->page_title = "手机绑定";
		$gScriptName = SCRIPTNAME;
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'dispose.js');
		$this->tpl->addHeaderCode(hg_add_head_element('echo'));	
		$this->tpl->setTemplateTitle($this->page_title );
		$this->tpl->outTemplate('cellphone');
	}
	
	public function create()
	{
		$mobile = $this->input['cellphone'];
		if(!$mobile)
		{
			echo "";
			exit;
		}
		$this->mUser = new user();
		$info = $this->mUser->cellphone($mobile);
		echo json_encode($info);
		exit;
	}
	
	public function delete()
	{
		$this->mUser = new user();
		$info = $this->mUser->unbindPhone();
		echo json_encode($info);
		exit;
	}
}
$out = new cellphone();
$action = $_INPUT['a'];

if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>