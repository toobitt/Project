<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: k.php 4194 2011-07-26 05:26:45Z lijiaying $
***************************************************************************/
define('ROOT_DIR', '../');
require('./global.php');
class search extends uiBaseFrm
{	
	private $status;
	function __construct()
	{		
		parent::__construct();
		$this->check_login();
		$this->load_lang('k');
		include_once(ROOT_PATH . 'lib/class/status.class.php');
		$this->status = new status();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{	
		if($this->user['id']>0)	
		{
			$id = $this->user['id'];
			if($this->input['q'])
			{
				/*if(preg_match("/^[".chr(0xa1)."-".chr(0xff)."a-za-z0-9_]+$/",$this->input['q']))
				{
					$this->input['q'] = iconv('GBK', 'UTF-8', $this->input['q']);
				} */
				$result_count = 0;
				$keywords = trim($this->input['q']);
				$count = 50;
				$page = intval($this->input['pp']) / $count;		
				$statusline = $this->status->search($keywords,$page,$count);
				if(is_array($statusline))
				{
					$data['totalpages']   = $statusline[0]['total'];
					$result_count = $statusline[0]['total'];
					unset($statusline[0]);
					$data['perpage'] = $count;
					$data['curpage'] = $this->input['pp'];
					$data['pagelink'] = '?q=' . $keywords;
					$showpages = hg_build_pagelinks($data);
				}
				$info = $this->status->getTopicFollow($keywords);
				$this->status->addKeywords($keywords,$result_count);
			}
			$topic = $this->status->getTopic();
			$topic_follow = $this->status->getTopicFollow();
			hg_add_head_element('js-c',"
				var re_back = 'k.php?q=".$this->input['q']."';
				var re_back_login = 'login.php';
				");
		}
		$this->page_title = $this->lang['pageTitle'];
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'k.js');
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'dispose.js');
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'rotate.js');
		
		$this->tpl->addVar('keywords', $keywords);
		$this->tpl->addVar('statusline', $statusline);
		$this->tpl->addVar('showpages', $showpages);
		$this->tpl->addVar('info', $info);
		$this->tpl->addVar('topic', $topic);
		$this->tpl->addVar('topic_follow', $topic_follow);
		$this->tpl->addVar('data', $data);
		$this->tpl->addVar('_input',$this->input);

		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->setTemplateTitle($this->page_title);
		$this->tpl->outTemplate('k');
	}
}
$out = new search();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();



?>