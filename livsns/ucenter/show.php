<?php
/*
 * $Id: show.php 2195 2011-02-23 14:01:02Z yuna $
 */

 define('ROOT_DIR', '../');
require('./global.php');

class commentShow extends uiBaseFrm
{
	private $mComment;
	function __construct()
	{
		parent::__construct();
		$this->check_login();
		$this->load_lang('comment');
		include_once(ROOT_PATH . 'lib/class/status.class.php');
		include_once(ROOT_PATH . 'lib/user/user.class.php');
		include_once(ROOT_PATH . 'lib/class/comment.class.php');
		$this->mComment = new comment();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		$info = new user();
		$status = new status();
		$userid = $this->user['id']; 
		$status_id = intval($this->input['id']);//要评论的点滴id
		$statusline = $status->show($status_id);
		$statusline = $statusline[0];  
		
		$count = !(RESULT_MAX_NUM) ? 50 : intval(RESULT_MAX_NUM); //每页返回的结果条数
 		$page = ceil(intval($this->input['pp'])/$count);
		$comments_arr = array();
		$comments_arr = $this->mComment->get_comment_list($status_id,$count,$page); 
 		$user_info = $statusline['user']; 
		if($this->input['ajax'])
		{  
		
			$this->tpl->addVar('user_info', $user_info);
			$this->tpl->addVar('comments_arr', $comments_arr);
			$this->tpl->addVar('status_id', $status_id);
			$this->tpl->addVar('$userid', $userid);
			$this->tpl->outTemplate('comment_list','hg_getCommentList,'.$status_id);
  	 
		}
		else
		{
			$pagelink = '?id=' . $this->input['id']; 
			$data = array(
				'totalpages' => $comments_arr[0],
				'perpage' => $count,
				'curpage' => $page,
				'pagelink' => $pagelink,
			);
			
			$showpages = hg_build_pagelinks($data);
			$this->page_title = $this->lang['pageTitle']; 
			hg_add_head_element("js" , RESOURCE_DIR . 'scripts/dispose.js');
			hg_add_head_element("js",RESOURCE_DIR . 'scripts/rotate.js');
			$this->tpl->addHeaderCode(hg_add_head_element('echo'));
			$this->tpl->setTemplateTitle($this->page_title);
			$this->tpl->outTemplate('show');
		}
	}

	//评论某条点滴
	public function comment()
	{
		$id = intval($this->input['status_id']);
		$content = $this->input['text'];
		$transmit_type = intval($this->input['transmit_type']);
		!$this->input['cid'] && $cid = 0; 
		$result = $this->mComment->comment($id,$content,$cid);
		if($result&&is_array($result)){
			$result['content'] = hg_verify($result['content']);
			$result['comment_time'] = $result['comment_time'];
			$result['create_at'] = hg_get_date($result['create_at']);
		}
		
		//同时转发到我的点滴
		if($transmit_type == 1)
		{
			$status = new status();
			$status->update($content,'点滴',$id); 
		}
		if($this->user['id'] != $result['status']['user']['id'])
		{
			include_once(ROOT_PATH . 'lib/class/notify.class.php');
			$notify = new notify();
			$content = array('title' => '新评论','page_link' => SNS_MBLOG . 'all_comment.php');
			$content = serialize($content);
			$notyfy_arr = $notify->notify_send($result['status']['member_id'],$content,2); //发新评论通知
		} 
		$result['text'] = hg_verify($result['text']);
		$result = json_encode($result); 
		print_r($result);
		
	}

	//回复某条评论
	public function reply_comment()
	{
		$status_id = intval($this->input['status_id']);
		$reply_id = intval($this->input['reply_id']);
		$text = $this->input['text'];
		$transmit_type = intval($this->input['transmit_type']); 
		$result = $this->mComment->reply_comment($reply_id,$status_id,$text);
		if($result&&is_array($result)){
			$result['content'] = hg_verify($result['content']);
			$result['comment_time'] = $result['comment_time'];
			$result['create_at'] = hg_get_date($result['create_at']);
		}
		
		//同时转发到我的点滴
		if($transmit_type == 1)
		{
			$status = new status();
			$status->update($text,'点滴',$status_id);
		}
 		$result = json_encode($result);
		//echo "<pre>";
		print_r($result);
	}
	
	//删除用户自己发布的评论
	public function del_comment()
	{
		$cid = intval($this->input['cid']); 
		$result = $this->mComment->del_comment($cid);
		foreach($result as $key => $value)
		{
			if($key == "content")
			{
				$value = hg_verify($value);
			}
		}
		$result = json_encode($result);
		print_r($result);
	}
}

$out = new commentShow();
$action = $_INPUT['a'];

if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>
