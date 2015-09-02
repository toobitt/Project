<?php
/*$Id: comment_list.php 3817 2011-04-28 09:45:21Z repheal $*/

//获取某条点滴的评论列表
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class comments extends BaseFrm
{
	private $mUser,$mStatus;
	function __construct()
	{
		parent::__construct(); 
		include_once(ROOT_DIR . 'lib/user/user.class.php');
		include_once(ROOT_DIR . 'lib/class/status.class.php');
		$this->mUser = new user();
		$this->mStatus = new status();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	public function commentList()
	{
		 
		$userinfo = $this->mUser->verify_credentials(); 
		
		$id = $this->input['id'];
		$per_page = intval($this->input['count']);//每页显示的结果数
		($per_page > RESULT_MAX_NUM) && $per_page == RESULT_MAX_NUM;//每页显示的数量大于规定的最大的显示数目
		
		$page_num = intval($this->input['page']);//返回的结果页 
		
		$sql = 'SELECT id , content , member_id , comment_time as "create_at", reply_comment_id ,reply_member_id FROM ' . DB_PREFIX . 'status_comments WHERE 1 AND status_id = ' . $id . ' AND flag = 0 ORDER BY comment_time DESC ';
		$queryid = $this->db->query($sql); 
		$rows_num = $this->db->num_rows($queryid);
		$comment_array = array();
		$comment_array['total'] = $rows_num; 
		
		$offset = $page_num * $per_page;//计算偏移量
		$limit = ' LIMIT ' . $offset . ', ' . $per_page;
		
		$sql .= $limit;  
		$this->setXmlNode('comments','comment');
		$qid = $this->db->query($sql);
		$split = '';
		  
		$status = $this->mStatus->show($id);
		while($rows = $this->db->fetch_array($qid))
		{
			$member_ids .= $split . $rows['member_id']; 
			$comment_array[$rows['id']] = $rows; 
			$comment_array[$rows['id']]['status'] = $status[0]; 
			
			$split = ',';
		}
		
		$members = array();  
		$members = $this->mUser->getUserById($member_ids); 
		foreach($comment_array as $k => $comment)
		{
			foreach($members as $id => $user)
			{
				if($comment['member_id'] == $user['id'] )
				{
					$comment['user'] = $user;
				}
			}
			
			$this->addItem($comment);
		}
		 
		$this->output();

	}
}

$out = new comments();
$out->commentList();