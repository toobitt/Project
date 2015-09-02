<?php
/*$Id: comments_by_me.php 17941 2013-02-26 02:20:49Z repheal $*/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');

/**
 * 获取“我”发出的评论列表
 * 
 */
class commentsByMe extends appCommonFrm
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
	
	public function listMyComments()
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
		$ss = 'SELECT s.id FROM ' .DB_PREFIX . 'status s LEFT JOIN ' . DB_PREFIX . 'status_comments sc ON s.id = sc.status_id WHERE 1 AND sc.member_id = ' .$userinfo['id'];
		
		$qqid = $this->db->query($ss);
		//如果被评论的点滴被删除，就不显示该点滴的评论
		while($result = $this->db->fetch_array($qqid))
		{
			$ntmp[$result['id']] = $result['id'];
		}
		 
		if(!$ntmp)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		else
		{ 
			$str = implode(',',$ntmp);
			$sql1 = 'SELECT count(id) as total FROM ' .DB_PREFIX . 'status_comments WHERE member_id = ' . $userinfo['id'] . ' AND status_id IN (' . $str .') and flag = 0';
			$total = $this->db->query_first($sql1); 
			$sql = 'SELECT * FROM ' .DB_PREFIX . 'status_comments WHERE member_id = ' . $userinfo['id'] . ' AND status_id IN (' . $str .') AND flag = 0 ';
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
			 
			$offset = $page * $count;
			
			$limit = ' ORDER BY comment_time DESC LIMIT ' . $offset . ' , ' . $count;
			$sql .= $and . $limit;
			$query_id = $this->db->query($sql);
			$myComments = array();
			$myComments['total'] = $total;//评论总记录
			
			$this->setXmlNode('comments','comment');
			$member = $this->mUser->getUserById($userinfo['id']);  
			while($rows = $this->db->fetch_array($query_id))
			{
				$replyCid[$rows['reply_comment_id']] = $rows['reply_comment_id'];
				 
				$myComments[$rows['id']]['id'] = $rows['id'];
				$myComments[$rows['id']]['create_at'] = $rows['comment_time'];
				$myComments[$rows['id']]['text'] = $rows['content'];
				$myComments[$rows['id']]['status_id'] = $rows['status_id'];
				$myComments[$rows['id']]['reply_comment_id'] = $rows['reply_comment_id'];
				$myComments[$rows['id']]['status'] = array();
				$myComments[$rows['id']]['reply_comment_text'] = '';
				$myComments[$rows['id']]['user'] = $member[0]; 
			}
			 
			$cc = count($replyCid);
			$tmp = array();
			if($cc)
			{
				$reSql = 'SELECT id,content FROM ' .DB_PREFIX . 'status_comments WHERE id IN(' . implode(',',$replyCid) . ') AND flag = 0';
				$qid = $this->db->query($reSql);
				while($rr = $this->db->fetch_array($qid))
				{
					$tmp[$rr[id]] = $rr;
				}
			}
			
			$status = $this->mStatus->show($str);
	
			foreach($myComments as $key => $value)
			{
				foreach($status as $k => $v)
				{
				
					if($value['status_id'] == $v['id'])
					{
						$value['status'] = $v;
					} 
				}
				foreach($tmp as $kk => $vv)
				{
					if($vv[id] == $value['reply_comment_id'])
					{
						$value['reply_comment_text'] = $vv['content'];
					}
				}
				$this->addItem($value); 
				 
			} 
		//	$this->addItem($total['total']);
			$this->output();
		}
			
	}
}

$out = new commentsByMe();
$out->listMyComments();
