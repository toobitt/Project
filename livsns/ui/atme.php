<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: atme.php 4236 2011-07-28 08:29:28Z lijiaying $
***************************************************************************/
define('ROOT_DIR', '../');
define('SCRIPTNAME', 'atme');
require('./global.php');
//require(ROOT_PATH . 'lib/class/notify.class.php');

class userInfo extends uiBaseFrm
{	
	private $status;
	function __construct()
	{		
		parent::__construct();
		$this->check_login();
		$this->load_lang('followers');
		$this->load_lang('atme');
		include_once(ROOT_PATH . 'lib/class/status.class.php');
		include_once(ROOT_PATH . 'lib/user/user.class.php');
		$this->status = new status();		
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		global $gScriptName,$gScriptNameArray;		
		if($this->user['id'] > 0)
		{
			$info = new user();		
			$count = 50;
			$total = 'gettotal';
			$page = intval($this->input['pp']) / $count;		
			$statusline = $this->status->mentions($total,$page,$count);
			if(is_array($statusline))
			{
				$data['totalpages'] = $statusline[0]['total'];
				unset($statusline[0]);
				$data['perpage'] = $count;
				$data['curpage'] = $this->input['pp'];
				$showpages = hg_build_pagelinks($data);
			}	
			$topic_follow = $this->status->getTopicFollow();
			$user_info = $info->getUserById($this->user['id']);
			$user_info = $user_info[0];			
		}
		$topic = $this->status->getTopic();	
		$this->page_title = $this->lang['pageTitle'];
		$gScriptName = SCRIPTNAME;
		hg_add_head_element('js-c',"
			var re_back = 'atme.php';
			var re_back_login = 'login.php';
		");
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'atme.js');
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'dispose.js');
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'rotate.js');

		$this->tpl->addVar('statusline', $statusline);
		$this->tpl->addVar('user_info', $user_info);
		$this->tpl->addVar('showpages', $showpages);
		$this->tpl->addVar('topic', $topic);
		$this->tpl->addVar('topic_follow', $topic_follow);
		$this->tpl->addVar('total', $total);

		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->setTemplateTitle($this->page_title);
		$this->tpl->outTemplate('atme');			
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