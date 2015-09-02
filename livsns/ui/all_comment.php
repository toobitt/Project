<?php
/*$Id: all_comment.php 4236 2011-07-28 08:29:28Z lijiaying $*/
define('ROOT_DIR', '../');
require('./global.php');
define("SCRIPTNAME","all_comment");
class myCommentsShow extends uiBaseFrm
{
	private $mComment,$mNotify;
	function __construct()
	{
		parent::__construct();
		$this->check_login();
		$this->load_lang('comment');
		include_once(ROOT_PATH . 'lib/class/status.class.php');
		include_once(ROOT_PATH . 'lib/user/user.class.php');
		include_once(ROOT_PATH . 'lib/class/comment.class.php');
		$this->mComment = new comment();
		include_once (ROOT_PATH . 'lib/class/notify.class.php');
		$this->mNotify = new notify();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		
		$user_info = array();
		$user_info = $this->user;

		$status = new status();
		$topic = $status->getTopic();

		$sendCommArr = array();
		$since_id = intval($this->input['since_id']);
		$max_id = intval($this->input['max_id']);
		$count = RESULT_MAX_NUM;
		$page = ceil(intval($this->input['pp']/$count)); 
		$tag = isset($this->input['t']) ? $this->input['t'] : 0;
		 
		if($tag)
		{
			$sendCommArr = $this->mComment->get_my_comments($since_id,$max_id,$page, $count);
			$pagelink = '?t=1';
			$cnt = $sendCommArr[0]['total'];
		
		}
		else
		{
			
			$sendCommArr = $this->mComment->get_resived_comments($since_id,$max_id,$page, $count);
			$pagelink = '?';
			$cnt = $sendCommArr[0];
			
			
		} 
		if($cnt && is_array($sendCommArr))
		{
			array_shift($sendCommArr);
		}
		 
		$data = array(
		'totalpages' => $cnt,
		'perpage' => $count,
		'curpage' => $page,
		'pagelink' => $pagelink,
		);
		$showpages = hg_build_pagelinks($data);
		hg_add_head_element('js',RESOURCE_DIR . 'scripts/comment.js');
		hg_add_head_element('js',RESOURCE_DIR . 'scripts/dialog.js');
		hg_add_head_element('js',RESOURCE_DIR . 'scripts/dispose.js');
		$this->page_title = ($tag == 0) ? $this->lang['resivedTitle'] : $this->lang['sendTitle'] ; //设定title
		
		$gScriptName = SCRIPTNAME;
		
		$this->tpl->addVar('tag', $tag);
		$this->tpl->addVar('showpages', $showpages);
		$this->tpl->addVar('user_info', $user_info);
		$this->tpl->addVar('sendCommArr', $sendCommArr);
		$this->tpl->addVar('cnt', $cnt);
		$this->tpl->addVar('topic', $topic);
		
		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->setTemplateTitle($this->page_title);
		$this->tpl->outTemplate('my_comments');					

	}
	
	public function del_more()
	{
		$comment_ids = $this->input['comment_ids'];
		$result = $this->mComment->delete_more_comments($comment_ids,intval($this->input['type'])); 
		print_r($result); 
	}
	
	public function del_comment()
	{
		$commentid = intval($this->input['commentid']);
		$result = $this->mComment->del_comment($commentid);
		$result = json_decode($result);
		echo $result;
	}
	
	public function repComment()
	{
		$commid = intval($this->input['commentid']);
		$statusid = intval($this->input['statusid']);
		$transmit_type = intval($this->input['transmit_type']);
		
		$result = $this->mComment->reply_comment($commid,$statusid,$this->input['text']);
		//同时转发到我的点滴
		if($transmit_type)
		{
			$status = new status();
			$status->update($this->input['text'],'点滴',$statusid);
		}
		$result = json_decode($result);
		echo $result;
	}
}
$out = new myCommentsShow();
$action = $_INPUT['a'];

if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();