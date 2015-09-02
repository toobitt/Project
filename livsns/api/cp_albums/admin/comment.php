<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: comment.php 22925 2013-05-29 07:30:03Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','comment');//模块标识
require('global.php');
class commentShowApi extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

	public function index(){}

	/**
	 * 
	 * 获取所有评论数据
	 */
	public function show()
	{
		$condition = $this->get_condition();		
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;	
				
		$sql = "SELECT group_id,name FROM ". DB_PREFIX ."group ";
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$group[$r['group_id']] = $r['name'];
		}
		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;
		$sql = "SELECT c.comment_id,c.user_id,c.user_name,c.content,c.pub_time,c.state,c.poll_id,a.albums_id,a.user_id as auid,a.user_name as aname,a.albums_name
				FROM ". DB_PREFIX ."comment c 
					LEFT JOIN ". DB_PREFIX ."albums a ON a.albums_id = c.albums_id 
					WHERE a.albums_id <> ''";
		$sql = $sql . $condition . $data_limit;
	
		$q = $this->db->query($sql);
		$this->setXmlNode('comment' , 'info');
		while($row = $this->db->fetch_array($q))
		{
			if($this->settings['rewrite'])
			{
				$row['user_link'] = SNS_UCENTER."user-" . $row['user_id'] . ".html";
				$row['pub_user_link'] = SNS_UCENTER."user-" . $row['auid'] . ".html";
				$row['pagelink'] = SNS_ALBUMS . "albums-show-" . $row['albums_id'] . ".html#".$row['comment_id'];
				$row['commentlink'] = SNS_ALBUMS . "albums-c-" . $row['albums_id'] . ".html#".$row['comment_id'];
			}
			else 
			{
				$row['user_link'] = SNS_UCENTER . "user.php?user_id=" . $row['user_id'];
				$row['pub_user_link'] = SNS_UCENTER . "user.php?user_id=" . $row['auid'];
				$row['pagelink'] = SNS_ALBUMS . "?m=albums&albums_id=" . $row['albums_id'] . "&a=albums_view#".$row['comment_id'];
				$row['commentlink'] = SNS_ALBUMS . "?m=albums&albums_id=" . $row['albums_id'] . "&a=albums_comments#".$row['comment_id'];
			}
			$row['pub_time'] = date('Y-m-d H:i:s' , $row['pub_time']);
			$row['group_name'] = $group[$row['group_id']];
			$row['audit'] = $row['state'];
			$row['state_tags'] = $this->settings['state'][$row['state']];
			$row['content'] = hg_show_face($row['content']);
			$this->addItem($row);
		}
		$this->output();
	}
	
	/**
	 * 获取评论总数
	 * 默认为全部评论的总数
	 */
	public function count()
	{	
		$sql = "SELECT  COUNT(*) AS total FROM ". DB_PREFIX ."comment c 
					LEFT JOIN ". DB_PREFIX ."albums a ON a.albums_id = c.albums_id 
					WHERE a.albums_id <> ''";
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
			$extra = "AND c.comment_id=".$id;
		}
		
		$sql = "SELECT group_id,name FROM ". DB_PREFIX ."group ";
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$group[$r['group_id']] = $r['name'];
		}
		
		$sql = "SELECT c.comment_id,c.user_id,c.user_name,c.content,c.pub_time,c.state,c.poll_id,a.albums_id,a.user_id as auid,a.user_name as aname,a.albums_name
				FROM ". DB_PREFIX ."comment c 
					LEFT JOIN ". DB_PREFIX ."albums a ON a.albums_id = c.albums_id 
					WHERE a.albums_id <> '' " . $extra;
		$sql = $sql . $condition;
		$q = $this->db->query($sql);
		$this->setXmlNode('comment' , 'info');
		while($row = $this->db->fetch_array($q))
		{
			if($this->settings['rewrite'])
			{
				$row['user_link'] = SNS_UCENTER."user-" . $row['user_id'] . ".html";
				$row['pub_user_link'] = SNS_UCENTER."user-" . $row['auid'] . ".html";
				$row['pagelink'] = SNS_ALBUMS . "albums-show-" . $row['albums_id'] . ".html";
			}
			else 
			{
				$row['user_link'] = SNS_UCENTER . "user.php?user_id=" . $row['user_id'];
				$row['pub_user_link'] = SNS_UCENTER . "user.php?user_id=" . $row['auid'];
				$row['pagelink'] = SNS_ALBUMS . "?m=albums&albums_id=" . $row['albums_id'] . "&a=albums_view";
			}
			$row['pub_time'] = date('Y-m-d H:i:s' , $row['pub_time']);
			$row['group_name'] = $group[$row['group_id']];
			$row['audit'] = $row['state'];
			$row['state_tags'] = $this->settings['state'][$row['state']];
			$row['content'] = hg_show_face($row['content']);
			$this->addItem($row);
		}
	//	hg_pre($info);
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
			$condition .= " AND concat(c.content,a.albums_name) LIKE  '%" . trim(urldecode($this->input['k'])) . "%' ";
		}
			
		//查询的起始时间
		if($this->input['start_time'])
		{
			$condition .= " AND c.pub_time > " . strtotime($this->input['start_time']);
		}
		
		//查询的结束时间
		if($this->input['end_time'])
		{
			$condition .= " AND c.pub_time < " . strtotime($this->input['end_time']);	
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
						$condition .= " AND c.state = 0";
						break;
					case 1:
						$condition .= " AND c.state = 1";
						break;
					case 2:
						$condition .= " AND c.state = 0";
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
						$condition .= " ORDER BY c.pub_time";
						break;
			case 'comment_id' : 
						$condition .= " ORDER BY c.comment_id";
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