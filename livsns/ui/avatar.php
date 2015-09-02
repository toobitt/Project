<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: avatar.php 4194 2011-07-26 05:26:45Z lijiaying $
***************************************************************************/
define('ROOT_DIR', '../');
define('SCRIPTNAME', 'avatar');
require('./global.php');
class avatar extends uiBaseFrm
{	
	private $info;
	public $user_info;
	function __construct()
	{		
		parent::__construct();
		$this->load_lang('avatar');
		
		include_once(ROOT_PATH . 'lib/user/user.class.php');
		$this->info = new user();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{		
		$this->check_login();
		$this->page_title = $this->lang['pageTitle'];
		$this->user_info = $this->info->getUserById($this->user['id']);
		$gScriptName = SCRIPTNAME;
		$this->page_title = '上传头像';
		

		$this->tpl->addVar('_user_info', $this->user_info);
		
		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->setTemplateTitle($this->page_title);
		$this->tpl->outTemplate('avatar');
	}
	
	public function uploadImage()
	{
		$ret = $this->info->update_profile_image($_FILES);
		$this->Redirect();
	}
	
	public function get_avatar()
	{
		$uid = $this->input['user_id']?$this->input['user_id']:$this->user['id'];
		$info = $this->info->getUserById($uid);
		header("Location: ".$info[0]['middle_avatar']);
	}
	
	
}
$out = new avatar();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();



?>