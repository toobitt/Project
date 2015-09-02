<?php
/*$Id: comments_to_me.php 3796 2011-04-26 07:25:46Z repheal $*/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');

/**
 * 获取“我”收到的评论列表
 * 
 */
class commentsToMe extends BaseFrm
{
	private $mUser,$mStatus;
	function __construct()
	{
		parent::__construct();
			
		include_once(ROOT_DIR . 'lib/user/user.class.php');
		$this->mUser = new user();
		include_once(ROOT_DIR . 'lib/class/status.class.php');
		$this->mStatus = new status();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$userinfo = $this->mUser->verify_credentials(); 
		if(!$userinfo['id'])
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		//$userinfo['id']=5;
		$since_id = intval($this->input['since_id']);
		$max_id = intval($this->input['max_id']);
		$page = intval($this->input['page']); 
		$count = intval($this->input['count']);
		$offset = $page * $count;
		//搜索'我的点滴'的被评论总数
		//$s = 'SELECT count(id) as total FROM ' .DB_PREFIX . 'status_comments WHERE status_id IN ( SELECT id FROM ' .DB_PREFIX . 'status WHERE member_id = ' .  $userinfo['id'] . ')';
		$s = 'SELECT sc.id as commid, s.id as sid FROM ' . DB_PREFIX . 'status_comments sc LEFT JOIN ' . DB_PREFIX . 'status s ON sc.status_id = s.id WHERE 1  and sc.flag = 0 AND s.member_id = ' . $userinfo['id'] . ' AND sc.member_id != ' . $userinfo['id'];
		$qqid = $this->db->query($s);
		while($rs = $this->db->fetch_array($qqid))
		{
			$commid[$rs['commid']] = $rs['commid'];//检索'我的点滴'被评论的评论id
			$sids[$rs['sid']] = $rs['sid'];//符合条件的点滴id
		}
		
		$s2 = 'SELECT status_id,id,reply_comment_id FROM ' . DB_PREFIX . 'status_comments WHERE flag = 0 AND reply_member_id = ' . $userinfo['id'] . ' AND member_id != ' . $userinfo['id'];
		$qqid2 = $this->db->query($s2);
		while($rs2 = $this->db->fetch_array($qqid2))
		{
			$commid[$rs2['id']] = $rs2['id'];//检索回复”我的评论“的评论id
			$sids[$rs2['status_id']] = $rs2['status_id'];//”我评论“的点滴id
			$rep[$rs2['reply_comment_id']] = $rs2['reply_comment_id'];
		}
		$total = count($commid);
		if(!$total)
		{  
			$this->errorOutput(OBJECT_NULL); 
		}
		if($rep)
		{
			$sql_str = 'SELECT status_id,content FROM ' .DB_PREFIX . 'status_comments WHERE member_id = ' .$userinfo['id'] . ' AND id in(  ' . implode(',',$rep) .')';
			
			$qqid3 = $this->db->query($sql_str);
			while($r = $this->db->fetch_array($qqid3))
			{
				$num[$r['status_id']] = $r['content'];//获取”我“评论的内容
			}
		}
			
		//此句sql搜索了我的回复，我的点滴的评论 
		$sql = 'SELECT * FROM ' .DB_PREFIX . 'status_comments WHERE id IN ( ' . implode(',',$commid) . ') AND flag = 0 ';
		if($since_id)
		{
			$and = ' AND id > ' . $since_id;
		}
		else if($max_id)
		{
			$and = ' AND id <=' . $max_id;
		}
		else if($since_id && $and)
		{
			$and = 'AND id BETWEEN ' . $since_id . ' AND ' . $max_id;
		}
		
		//isset($this->input['keywords']) && $and .= ' AND content LIKE "%' . urldecode($this->input['keywords']) . '%"';
		
		
		$limit = ' ORDER BY comment_time DESC LIMIT ' . $offset . ' , ' . $count;
		$sql .= $and . $limit;
		$query_id = $this->db->query($sql); 
		
		$myRecComments = array();
		$myRecComments['total'] = $total;
		$this->setXmlNode('comments','comment');
		
		//$split = '';
		while($rows = $this->db->fetch_array($query_id))
		{
			if($rows['member_id'] != $userinfo['id'])
			{
				$member_ids[$rows['member_id']] = $rows['member_id'];
				$status_ids[$rows['status_id']] = $rows['status_id'];
				$myRecComments[$rows['id']]['id'] = $rows['id'];
				$myRecComments[$rows['id']]['status_id'] =  $rows['status_id'];
				$myRecComments[$rows['id']]['member_id'] =  $rows['member_id'];
				$myRecComments[$rows['id']]['text'] =  $rows['content'];
				$myRecComments[$rows['id']]['create_at'] =  $rows['comment_time'];
				$myRecComments[$rows['id']]['status'] = array();
				$myRecComments[$rows['id']]['user'] = array();
				//$split = ',';
				if($rows['reply_member_id'])
				{
					$myRecComments[$rows['id']]['reply_comment_text'] = $num[$rows['status_id']];
				}
				
			}	
			
 		}
 	 	$member_ids = implode(',',$member_ids); 
 	 	$status_ids = implode(',',$status_ids);
 		
 	 	$statusArr = $this->mStatus->show($status_ids);
 		$memberArr = $this->mUser->getUserById($member_ids);
  		foreach($myRecComments as $id => $value)
 		{
 			foreach($statusArr as $kk => $vv)
 			{
 				if($value['status_id'] == $vv['id'])
 				{
 					$value['status'] = $vv;
 				}
 				
 				 
 			}
 			  
 			foreach($memberArr as $key => $val)
 			{
 				if($value['member_id'] == $val['id'])
 				{
 					$value['user'] = $val;
 				}
 			}
 			
 			$this->addItem($value);
 		}
 	//	$this->addItem($sql_str);
 	//	$this->addItem($rs2);
 		$this->output(); 
	}
}

$out = new commentsToMe();
$out->show();
	