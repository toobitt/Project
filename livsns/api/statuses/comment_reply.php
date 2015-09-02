<?php
/*$Id: comment_reply.php 17941 2013-02-26 02:20:49Z repheal $*/
//回复某条点滴的评论
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class replyAPI extends appCommonFrm
{
	private $member,$mStatus;
	function __construct()
	{
		parent::__construct();
			
		include_once(ROOT_DIR . 'lib/class/member.class.php');
		$this->member = new member();
		include_once(ROOT_DIR . 'lib/class/status.class.php');
		$this->mStatus = new status();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	public function reply()
	{		
		/*
include_once(ROOT_DIR . 'lib/class/settings.class.php');
		$setting = new settings();
		$result_setttings = $setting->getMark('mblog_comment');
		if(!empty($result_setttings) && $result_setttings['state'])
		{
			$this->errorOutput('评论回复已关闭');
		}
*/
		if(!$this->user['user_id'])
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		
		$status_id = intval($this->input['status_id']);
		$cid = intval($this->input['cid']);
		$text = urldecode($this->input['text']); 
		(!$status_id || !$text) && $this->errorOutput(OBJECT_NULL);//点滴id为空就返回错误
		 
		$time = time();
		$query_info = $this->db->query_first('SELECT member_id FROM ' . DB_PREFIX . 'status_comments WHERE id = ' . $cid );
		
		!$query_info && $this->errorOutput(OBJECT_NULL);
		
		$sql = 'INSERT INTO ' . DB_PREFIX . 'status_comments ( status_id , member_id , content , comment_time , reply_comment_id , reply_member_id )
				VALUES(' . $status_id . ', ' . $this->user['user_id'] . ', "' . $text . '" , "' . $time . '" , ' . $cid . ',' . $query_info['member_id'] .')';
		 
		$this->db->query($sql);
		$insert_id = $this->db->insert_id();
		$query_info = $this->db->query_first('SELECT member_id FROM ' . DB_PREFIX . 'status_comments WHERE status_id = ' . $status_id . ' AND id = ' . $cid );
		
		
		
		//将该条点滴的评论次数加1
		$this->db->query('UPDATE ' . DB_PREFIX . 'status_extra SET comment_count = comment_count + 1 WHERE status_id = ' . $status_id);
		
		$members = $this->member->getMemberById($this->user['user_id']);
		$members = $members[0][$this->user['user_id']];
		
		$status  = $this->mStatus->show($status_id);
		$return_array = array(
			'id' => $insert_id,
			'content' => $text,
			'create_at' => $time,
			'reply_member_id' => $query_info['member_id'],
			'reply_comment_id' => $cid,
			'status' => $status[0],
			'user' => $members
		);
			 
		/*if(($userinfo['id'] != $query_info['member_id']) && ($userinfo['id'] != $status[0]['user']['id']))
		{ 	
			$notify_userid = $query_info['member_id']. ',' . $status[0]['user']['id'];
			//加通知
			include_once(ROOT_PATH . 'lib/class/notify.class.php');
			$notify = new notify();
			$content = array('title' => '新回复','page_link' => SNS_MBLOG . 'all_comment.php');
			$content = serialize($content);
			$notyfy_arr = $notify->notify_send($notify_userid,$content,2); //发新评论通知 
		}*/
			
		
		$this->addItem($return_array);
		$this->output(); 
		
	}
}	
$out = new replyAPI();
$out->reply();	 
	