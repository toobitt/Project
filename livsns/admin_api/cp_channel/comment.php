<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: comment.php 6766 2012-05-17 09:39:51Z hanwenbin $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class commentApi extends BaseFrm
{	
	public function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'api/lib/video.class.php');
		$this->mVideo = new video();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		//分页参数设置
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;		
		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;
		//$sql = "SELECT v.* , u.username FROM " . DB_PREFIX . "video AS v LEFT JOIN " . DB_PREFIX  . "user AS u ON v.user_id = u.id" . $condition;				
		$sql = "SELECT c.*, v.title FROM " . DB_PREFIX . "comments AS c LEFT JOIN " . DB_PREFIX  . "video AS v ON c.cid = v.id WHERE 1 ";

		//获取查询条件
		$condition = $this->get_condition();		
		$sql = $sql . $condition . $data_limit;		
		$q = $this->db->query($sql);
		$uid = array();
		$space = '';
		while($row = $this->db->fetch_array($q))
		{
			$row['create_time'] = date('Y-m-d H:i:s' , $row['create_time']);
			$row['content'] = hg_verify($row['content']);
			$comment[] = $row;
			$uid[] = $row['user_id'];
			$uid[] = $row['reply_user_id'];
		}
		$userinfo = $this->mVideo->getUserById(implode(',', array_unique($uid)));
		$this->setXmlNode('comment' , 'info');
		foreach($comment as $key=>$value)
		{
			if($this->settings['rewrite'])
			{
				$comment[$key]['user_link'] = SNS_UCENTER."user-" . $value['user_id'] . ".html";
				if($value['reply_user_id'])
				{
					$comment[$key]['reply_user_link'] = SNS_UCENTER."user-" . $value['reply_user_id'] . ".html";
				}
				$comment[$key]['pagelink'] = SNS_VIDEO . "video-" . $value['cid'] . ".html";
			}
			else 
			{
				$comment[$key]['user_link'] = SNS_UCENTER . "user.php?user_id=" . $value['user_id'];
				if($value['reply_user_id'])
				{
					$comment[$key]['reply_user_link'] = SNS_UCENTER . "user.php?user_id=" . $value['reply_user_id'];
				}
				$comment[$key]['pagelink'] = SNS_VIDEO . "video_play.php?id=" . $value['cid'];
			}
			$comment[$key]['user'] = $userinfo[$value['user_id']];
			$comment[$key]['reply_user'] = $userinfo[$value['reply_user_id']];
		//	hg_pre($comment[$key]);
			$this->addItem($comment[$key]);	
		}
		$this->output();			
	}
	
	/**
	 * 获取视频评论总数
	 * 默认为全部视频评论的总数
	 */
	public function count()
	{	
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "comments WHERE 1 ";

		//获取查询条件
		$condition = $this->get_condition();				
		$sql = $sql . $condition;
		$r = $this->db->query_first($sql);

		//暂时这样处理
		echo json_encode($r);
	}
		
	/**
	 * 获取单条数据
	 */
	public function detail()
	{
		$this->input['id'] = urldecode($this->input['id']);
		if(!$this->input['id'])
		{
			$condition = ' ORDER BY id DESC LIMIT 1';
		}
		else
		{
			$condition = ' WHERE id in(' . $this->input['id'] .')';
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "comments " . $condition;
		
		$r = $this->db->query_first($sql);
		$uid = $r['user_id'] . ',' . $r['reply_user_id'];
		$userinfo = $this->mVideo->getUserById($uid);
		$this->setXmlNode('comment' , 'info');
		
		if(is_array($r) && $r)
		{
			if($this->settings['rewrite'])
			{
				$r['user_link'] = SNS_UCENTER."user-" . $r['user_id'] . ".html";
				if($r['reply_user_id'])
				{
					$r['reply_user_link'] = SNS_UCENTER."user-" . $r['reply_user_id'] . ".html";
				}
				$r['pagelink'] = SNS_VIDEO . "video-" . $r['cid'] . ".html";
			}
			else 
			{
				$r['user_link'] = SNS_UCENTER . "user.php?user_id=" . $r['user_id'];
				if($r['reply_user_id'])
				{
					$r['reply_user_link'] = SNS_UCENTER . "user.php?user_id=" . $r['reply_user_id'];
				}
				$r['pagelink'] = SNS_VIDEO . "video_play.php?id=" . $r['cid'];
			}
			$r['create_time'] = date('Y-m-d H:i:s' , $r['create_time']);
			$r['user'] = $userinfo[$r['user_id']];
			$r['content'] = hg_verify($r['content']);
			$r['reply_user'] =  $userinfo[$r['reply_user_id']];
			$this->addItem($r);
			$this->output();
		}
		else
		{
			$this->errorOutput('评论不存在');	
		} 					
	}

	/**
	 * 获取搜索条件
	 */
	public function get_condition()
	{
		$condition = '';
		
		//查询的关键字
		if($this->input['k'])
		{
			$condition .= " AND c.content LIKE '%" . trim(urldecode($this->input['k'])) . "%' ";
		}
		
		//查询的起始时间
		if($this->input['start_time'])
		{
			$condition .= " AND c.create_time > " . strtotime($this->input['start_time']);
		}
		
		//查询的结束时间
		if($this->input['end_time'])
		{
			$condition .= " AND c.create_time < " . strtotime($this->input['end_time']);	
		}
		
		//查询视频的类型
		if (isset($this->input['comment_status']))
		{
			switch($this->input['comment_status'])
			{
				case -1:
						$condition .= " ";
					break;
				case 0:
						$condition .= " AND c.state = 0 ";
					break;
				case 1:
						$condition .= " AND c.state = 1 ";
					break;
				case 2:
						$condition .= " AND c.state = 0 ";
					break;
			}
		}
		$condition .= " AND c.type = 0 "; //表示是视频
		$orders = array('collect_count', 'comment_count', 'click_count', 'play_count');
		$descasc = strtoupper($this->input['hgupdn']);
		if ($descasc != 'ASC')
		{
			$descasc = 'DESC';
		}
		if (!in_array($this->input['hgorder'], $orders))
		{
			$this->input['hgorder'] = 'create_time';
		}
		
		$orderby = ' ORDER BY ' . $this->input['hgorder']  . ' ' . $descasc ;
		return $condition . $orderby;
	}
}

/**
 *  程序入口
 */
$out = new commentApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>