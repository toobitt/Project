<?php
/* $Id: comment.php 17941 2013-02-26 02:20:49Z repheal $*/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class commentAPI extends appCommonFrm
{
	private $mStatus;
	function __construct()
	{
		parent::__construct();
		include_once(ROOT_DIR . 'lib/class/status.class.php');
		$this->mStatus = new status();
		include_once(ROOT_DIR . 'lib/class/member.class.php');
		$this->member = new member();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	public function comment()
	{
		/*include_once(ROOT_DIR . 'lib/class/settings.class.php');
		$setting = new settings();
		$result_setttings = $setting->getMark('mblog_comment');
		if(!empty($result_setttings) && $result_setttings['state'])
		{
			$this->errorOutput('评论已关闭');
		}
		*/
		if(!$this->user['user_id'])
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		
		$id = $this->input['id'];
		$content = urldecode($this->input['content']);
		
		include_once(ROOT_DIR . 'lib/class/banword.class.php');
		$banword = new banword();
		$status = 0;
		$banwords = $banword->banword(urlencode($content));

		if($banwords && $banwords!='null')//暂时先定义为没关键词
		{
		 	$status = 1;	
			$banwords = implode(',', $banwords);
		}
		else
		{
			$banwords = '';
		}
		//此ID没有用处
		$cid = intval($this->input['cid']);
		
		$time = time();
		!$cid ? $and = '' : $and = ' , reply_comment_id = ' . $cid;
		
		$sql = 'INSERT INTO ' . DB_PREFIX . 'status_comments SET status_id = ' . $id . ', flag = ' . $status . ',member_id = ' . $this->user['user_id'] . ',content = "' . $content . '",comment_time = "' . $time . '",ip = "' . hg_getip() . '"';
		$sql .= $and;

		$this->setXmlNode('comments','comment');
		
		/**
		 * 获取该条点滴的用户ID
		 */

		$user_id = $this->mStatus->getUserIdByStatusId($id);

		/**
		 * 获取该用户的权限
		 */

	    $authority = $this->member->get_authority($user_id);
				
		//评论权限
		$comment_authority = intval($authority[18]);
		
		/**
		 * 获取与该用户的关系
		 */
		
		include_once(ROOT_DIR . 'lib/class/friendships.class.php');
		$friendShips = new friendShips();
		
		$relation = $friendShips->show($this->user['user_id'] , $user_id);
		
		//任何人可评论
		if($comment_authority == 0)
		{
			$this->db->query($sql);
		}
		
		//关注的人可评论
		if($comment_authority == 1)
		{
			//关注
			if($relation == 3 || $relation == 1)
			{
				$this->db->query($sql);	
			}
			else
			{
				$this->errorOutput(NO_AUTHORITY);	
			} 	
		}
		
		//任何人不可评论
		if($comment_authority == 2)
		{
			$this->errorOutput(NO_AUTHORITY);	
		}		
		
//		$this->db->query($sql);
		$insert_id = $this->db->insert_id();
		$members = $this->member->getMemberById($this->user['user_id']);//评论者的信息数组

		$members = $members[0][$this->user['user_id']];
		//将点滴的评论次数加1
		$sql_str = 'UPDATE ' . DB_PREFIX . 'status_extra SET comment_count = comment_count + 1 WHERE status_id = ' . $id;
		$this->db->query($sql_str);
		$status_info = $this->mStatus->show($id);
		$return_array = array(
			'id' => $insert_id,
			'content' => $content,
			'create_at' => $time,
			'user' => $members,//评论者信息
			'status' => $status_info[0]//评论的那条点滴的信息
		); 
		 
		$this->addItem($return_array);
		$this->output();
	}
}

$out = new commentAPI();
$out->comment();
?>