<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: comment.php 7939 2012-07-14 08:36:15Z hanwenbin $
***************************************************************************/

define('ROOT_DIR', '../../');
define('MOD_UNIQUEID','cp_comment_m');//模块标识
require(ROOT_DIR . 'global.php');

/**
 * 
 * 评论数据获取API
 * 
 * 提供的方法：
 * 1) 获取所有评论数据
 * 2) 获取单条评论数据
 * 3) 获取指定评论的总数
 * 
 *
 */
class commentShowApi extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 
	 * 获取所有评论数据
	 */
	public function show()
	{
		//分页参数设置
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;		

		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;
		
		$condition = $this->get_condition();		
				
		$sql = "SELECT group_id,name FROM ". DB_PREFIX ."group ";
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$group[$r['group_id']] = $r['name'];
		}
		
		$sql = "SELECT p.post_id,p.pagetext,p.user_id,p.user_name,p.pub_time,p.poll_id,p.state,p.reply_user_name,p.reply_des,t.thread_id,t.title,t.group_id,t.user_id as tuid,t.user_name as tname,t.title as ttitle FROM ". DB_PREFIX ."post p 
					LEFT JOIN ". DB_PREFIX ."thread t ON t.thread_id = p.thread_id 
					WHERE p.post_id <> t.first_post_id ";
		$sql = $sql . $condition . $data_limit;
		$q = $this->db->query($sql);
		
		$poll_id = $space = '';
		while($row = $this->db->fetch_array($q))
		{
			$sql="select * from " . DB_PREFIX . "user where user_id=" . $row['user_id'];
            $rr=$this->db->query_first($sql);
			if($rr['avatar'])
			{
				$sql="select * from " . DB_PREFIX . "material where material_id=" . $row['avater'];
				$rrr=$this->db->query-first($sql);
				$row['avatar']=$this->settings['livime_upload_url'] . $rrr['filepath'] . $rrr['filename'];
			}
			$row['pub_time'] = date('Y-m-d H:i' , $row['pub_time']);
			$row['group_name'] = $group[$row['group_id']];
			$row['audit'] = $row['state'];
			$row['state_tags'] = $this->settings['state'][$row['state']];
			if($row['poll_id'])
			{
				$poll[$row['post_id']] = array('user_name' => $row['user_name'],'poll_id' => $row['poll_id'],'pagetext' => unserialize($row['pagetext']));
				$poll_id .= $space.$row['poll_id'];
				$space = ',';
			}
			$info[] = $row;
		}
		
		if(is_array($poll) && $poll_id)
		{
			$sql = "select poll_id,title,pollresult,user_id,user_name from ". DB_PREFIX ."poll where poll_id IN(" . $poll_id . ")";
			$q = $this->db->query($sql);
			while($row = $this->db->fetch_array($q))
			{
				$row['pollresult'] = unserialize($row['pollresult']);
				$all_poll[$row['poll_id']] = $row;
			}
			
			foreach($poll as $key => $value)
			{
				$poll[$key]['poll'] = $value['user_name']."参与了《".$all_poll[$value['poll_id']]['title']."》的投票 ，结果为：";
				if(is_array($value['pagetext']))
				{
					$result = $space = '';
					foreach($value['pagetext'] as $k => $v)
					{
						if(is_int($k))
						{
							$result .= $space . $all_poll[$value['poll_id']]['pollresult'][$v]['poll_opt'];
							$space = "，";
						}
					}
				}
				$poll[$key]['poll'] .= $result;
			}
		}
		
		
		$this->setXmlNode('comment' , 'info');
		if(is_array($info))
		{
			foreach($info as $key => $value)
			{
				if($this->settings['rewrite'])
				{
					$value['user_link'] = SNS_UCENTER."user-" . $value['user_id'] . ".html";
					$value['pub_user_link'] = SNS_UCENTER."user-" . $value['tuid'] . ".html";
					$value['pagelink'] = SNS_TOPIC . "thread-" . $value['thread_id'] . ".html" . "#" . $value['post_id'];
					$value['group_link'] = SNS_TOPIC . "group-" . $value['group_id'] . ".html";
				}
				else 
				{
					$value['user_link'] = SNS_UCENTER."user.php?user_id=" . $value['user_id'];
					$value['pub_user_link'] = SNS_UCENTER."user.php?user_id=" . $value['tuid'];
					$value['pagelink'] = SNS_TOPIC . "?m=thread&thread_id=" . $value['thread_id'] . "&a=detail&group_id=" . $value['group_id'] . "#" . $value['post_id'];
					$value['group_link'] = SNS_TOPIC . "?m=thread&group_id=" . $value['group_id'];
				}
				if($value['poll_id'])
				{
					$value['poll'] = $poll[$value['post_id']]['poll'];
				}
				else 
				{
					$value['poll'] = hg_show_face($value['pagetext']);
				}
				$this->addItem($value);
			}
		}
		$this->output();
	}
	
	/**
	 * 获取评论总数
	 * 默认为全部评论的总数
	 */
	public function count()
	{	
		$sql = "SELECT COUNT(*) AS total FROM ". DB_PREFIX ."post p 
					LEFT JOIN ". DB_PREFIX ."thread t ON t.thread_id = p.thread_id 
					WHERE p.thread_id <> t.first_post_id ";
		//获取查询条件
		$condition = $this->get_condition();				
		$sql = $sql . $condition;
		$r = $this->db->query_first($sql);

		echo json_encode($r);
		
		/*$total_nums = $r['total_nums'];		
		$this->setXmlNode('comment_info' , 'comment_count');
		$this->addItem($total_nums);	
		$this->output();*/
	}
	
	/**
	 * 获取单条评论信息
	 */
	public function detail()
	{
		$id = urldecode($this->input['id']);
	
		$condition = $this->get_condition();	
		
		if(!$id)
		{
			$condition .= ' LIMIT 1';
		}
		else 
		{
			$extra = " AND p.post_id=".$id;
		}
		
		$sql = "SELECT group_id,name FROM ". DB_PREFIX ."group ";
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$group[$r['group_id']] = $r['name'];
		}
		
		 $sql = "SELECT p.post_id,p.pagetext,p.user_id,p.user_name,p.pub_time,p.poll_id,p.state,t.thread_id,t.title,t.group_id,t.user_id as tuid,t.user_name as tname FROM ". DB_PREFIX ."post p 
					LEFT JOIN ". DB_PREFIX ."thread t ON t.thread_id = p.thread_id 
					WHERE p.post_id <> t.first_post_id " . $extra;
		$sql = $sql . $condition . $data_limit;
		$q = $this->db->query($sql);
		
		$poll_id = $space = '';
		while($row = $this->db->fetch_array($q))
		{
			$row['pub_time'] = date('Y-m-d H:i:s' , $row['pub_time']);
			$row['group_name'] = $group[$row['group_id']];
			$row['audit'] = $row['state'];
			$row['state_tags'] = $this->settings['state'][$row['state']];
			if($row['poll_id'])
			{
				$poll[$row['post_id']] = array('user_name' => $row['user_name'],'poll_id' => $row['poll_id'],'pagetext' => unserialize($row['pagetext']));
				$poll_id .= $space.$row['poll_id'];
				$space = ',';
			}
			$info[] = $row;
		}
		
		if(is_array($poll) && $poll_id)
		{
			$sql = "select poll_id,title,pollresult,user_id,user_name from ". DB_PREFIX ."poll where poll_id IN(" . $poll_id . ")";
			$q = $this->db->query($sql);
			while($row = $this->db->fetch_array($q))
			{
				$row['pollresult'] = unserialize($row['pollresult']);
				$all_poll[$row['poll_id']] = $row;
			}
			
			foreach($poll as $key => $value)
			{
				$poll[$key]['poll'] = $value['user_name']."参与了《".$all_poll[$value['poll_id']]['title']."》的投票 ，结果为：";
				if(is_array($value['pagetext']))
				{
					$result = $space = '';
					foreach($value['pagetext'] as $k => $v)
					{
						if(is_int($k))
						{
							$result .= $space . $all_poll[$value['poll_id']]['pollresult'][$v]['poll_opt'];
							$space = "，";
						}
					}
				}
				$poll[$key]['poll'] .= $result;
			}
		}
		$this->setXmlNode('comment' , 'info');
		if(is_array($info))
		{
			foreach($info as $key => $value)
			{
				if($this->settings['rewrite'])
				{
					$value['user_link'] = SNS_UCENTER."user-" . $value['user_id'] . ".html";
					$value['pub_user_link'] = SNS_UCENTER."user-" . $value['tuid'] . ".html";
					$value['pagelink'] = SNS_TOPIC . "thread-" . $value['thread_id'] . ".html#" . $value['post_id'];
					$value['group_link'] = SNS_TOPIC . "group-" . $value['group_id'] . ".html";
				}
				else 
				{
					$value['user_link'] = SNS_UCENTER."user.php?user_id=" . $value['user_id'];
					$value['pub_user_link'] = SNS_UCENTER."user.php?user_id=" . $value['tuid'];
					$value['pagelink'] = SNS_TOPIC . "?m=thread&thread_id=" . $value['thread_id'] . "&a=detail&group_id=" . $value['group_id'] . "#" . $value['post_id'];
					$value['group_link'] = SNS_TOPIC . "?m=thread&group_id=" . $value['group_id'];
				}
				if($value['poll_id'])
				{
					$value['poll'] = $poll[$value['post_id']]['poll'];
				}
				else 
				{
					$value['poll'] = hg_show_face($value['pagetext']);
				}
				$this->addItem($value);
			}
		}
		$this->output();							
	}
	
	/**
	 * 获取查询条件
	 */
	public function get_condition()
	{
		$condition = '';
		
		//查询的关键字
		if($this->input['k'])
		{
			$condition .= " AND concat(p.pagetext,t.title) LIKE  '%" . trim(urldecode($this->input['k'])) . "%' ";
		}
			
		//查询的起始时间
		if($this->input['start_time'])
		{
			$condition .= " AND p.pub_time > " . strtotime($this->input['start_time']);
		}
		
		//查询的结束时间
		if($this->input['end_time'])
		{
			$condition .= " AND p.pub_time < " . strtotime($this->input['end_time']);	
		}

		//查询评论的状态
		if (isset($this->input['state']))
		{
			switch (intval($this->input['state']))
				{
					case -1:
						$condition .= " ";
						break;
					case 0:
						$condition .= " AND p.state = 2";
						break;
					case 1:
						$condition .= " AND p.state = 1";
						break;
					case 2:
						$condition .= " AND p.state = 0";
						break;
					default:
						break;
				}
		}
		
		//查询排序类型(字段，默认为创建时间)
		$order = $this->input['order_field'] ? $this->input['order_field'] : 'pub_time'; 
		switch($order)
		{
			case 'pub_time' : 
						$condition .= " ORDER BY p.pub_time";
						break;
			case 'post_id' : 
						$condition .= " ORDER BY p.post_id";
						break;
			default: 
						$condition .= " ORDER BY " . $order;	
						break;
		}
		
		//查询排序方式(升序或降序,默认为降序)
		$condition .= $this->input['order_type'] ? $this->input['order_type'] : ' DESC ';
		
		return $condition;	
	}
}

/**
 *  程序入口
 */
$out = new commentShowApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();


?>