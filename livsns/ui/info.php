<?php
/* $Id: info.php 4194 2011-07-26 05:26:45Z lijiaying $ */

define('ROOT_DIR', '../');
define('SCRIPTNAME', 'info');
require('./global.php');

class userInfo extends uiBaseFrm
{	
	private $status;
	private $pagelink;
	function __construct()
	{		
		parent::__construct();
		$this->load_lang('followers');
		$this->load_lang('info');
		$this->load_lang('userprofile');
		include_once(ROOT_PATH . 'lib/class/status.class.php');
		include_once(ROOT_PATH . 'lib/user/user.class.php');
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl();		
		$this->status = new status();	
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		global $gScriptName,$gScriptNameArray;
		$is_my_page = false;
		$user_info = $this->check("all");

		if(empty($user_info))
		{
			$this->ReportError('用户不存在!');
		}
				
		$user_info = $user_info[0];	
		$this->user_info = $user_info;
		if ($user_info['id'] == $this->user['id'])
		{
			$is_my_page = true;
		}
		
		
		$id = $user_info['id'];
		$topic = $this->status->getTopic();
		$topic_follow = $this->status->getTopicFollow();
		$this->page_title = $this->lang['pageTitle'];
		$gScriptName = SCRIPTNAME;
		hg_add_head_element('js-c',"
			var re_back = 'info.php';
			var re_back_login = 'login.php';
		");
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'dispose.js');
	//	include hg_load_template('info');
		$this->tpl->addVar('_user_info',$this->user_info);
		$this->tpl->addVar('user_info', $user_info);

		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->setTemplateTitle($this->page_title);
		$this->tpl->outTemplate('info');
	}
	
	public function check($type="base")
	{
		$this->input['name'] = trim(urlencode($this->input['name']));
		$get_userinfo_func = 'getUserById';
		if (!$this->input['user_id'] &&  !$this->input['name'])
		{
			if (!$this->user['id'])
			{
				$this->check_login();
			}
			else
			{
				$user_id = intval($this->user['id']);
			}
		}
		elseif ($this->input['user_id'])
		{
			$user_id = intval($this->input['user_id']);
			$this->pagelink = "?user_id=".$this->input['user_id'];
		}
		else
		{
			$user_id = $this->input['name'];
			$this->pagelink = "?name=".$this->input['name'];
			$get_userinfo_func = 'getUserByName';
		}
		$info = new user();		
		$user_info = $info->$get_userinfo_func($user_id,$type);
		return $user_info;
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