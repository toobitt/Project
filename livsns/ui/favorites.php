<?php
/***************************************************************************
* LivSNS 0.1
* (C)2009-2010 HOGE Software.
*
* $Id: favorites.php 4236 2011-07-28 08:29:28Z lijiaying $
***************************************************************************/
define('ROOT_DIR', '../');
define('SCRIPTNAME', 'favorites');
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
		$this->load_lang('favorites');
		include_once(ROOT_PATH . 'lib/class/favorites.class.php');
		include_once(ROOT_PATH . 'lib/user/user.class.php');	
		include_once(ROOT_PATH . 'lib/class/status.class.php');
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
			$user_info = $info->getUserById($this->user['id']);
			$user_info = $user_info[0];
			$favorites = new favorites();
			//传递参数
			$u_id = $this->input['user_id'];
			$status_id = $this->input['statusid'];
			$count = 50;
			$total = 'gettotal';
			$page = intval($this->input['pp']) / $count;		
			$statusline = $favorites->favorites($total,$page,$count,$u_id);
			if(is_array($statusline))
			{
				$data['totalpages'] = $statusline[0]['total'];
				unset($statusline[0]);
				$data['perpage'] = $count;
				$data['curpage'] = $this->input['pp'];
				$showpages = hg_build_pagelinks($data);
			}	
			$topic_follow = $this->status->getTopicFollow();
		}
		$topic = $this->status->getTopic();
		$this->page_title =  $this->lang['pageTitle'];
		$gScriptName = SCRIPTNAME;
		hg_add_head_element('js-c',"
			var re_back = 'favorites.php';
			var re_back_login = 'login.php';
		");
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'favorites.js');
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'dispose.js');
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'rotate.js');

		$this->tpl->addVar('user_info', $user_info);
		$this->tpl->addVar('status_id', $status_id);
		$this->tpl->addVar('statusline', $statusline);
		$this->tpl->addVar('showpages', $showpages);
		$this->tpl->addVar('no_result', $no_result);
		$this->tpl->addVar('have_followers', $have_followers);
		$this->tpl->addVar('showpages', $showpages);
		$this->tpl->addVar('topic_follow', $topic_follow);
		$this->tpl->addVar('topic', $topic);

		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->setTemplateTitle($this->page_title);
		$this->tpl->outTemplate('favorites');					//数据写入粉丝模板							
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