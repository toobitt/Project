<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: topic.php 7884 2012-07-13 07:13:19Z wangleyuan $
***************************************************************************/
define('ROOT_DIR', '../../');
define('MOD_UNIQUEID','mblog_topic_m');
require(ROOT_DIR.'global.php');
class topicShowApi extends outerReadBase
{
	function __construct()
	{
		parent::__construct();
		include(ROOT_DIR.'lib/user/user.class.php');
		$this->mUser = new user();/**/
	}
	function __destruct()
	{
		parent::__destruct();
		$this->db->close();
	}

	/**
	 * 获取话题
	 */
	function show()
	{
		$this->input['count'] = (isset($this->input['count']) && (int)$this->input['count']>0)
		?(int)$this->input['count'] 
		:10;
		$this->input['offset'] = (isset($this->input['offset']) && (int)$this->input['offset']>0) 
		?(int)$this->input['offset'] 
		:0;		
		$orders = array('relate_count', 'id');
		$descasc = strtoupper($this->input['hgupdn']);
		if ($descasc != 'ASC')
		{
			$descasc = 'DESC';
		}
		if (in_array($this->input['hgorder'], $orders))
		{
			$orderby = ' ORDER BY ' . $this->input['hgorder']  . ' ' . $descasc ;
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'topic ';
		$sql .= ' WHERE 1 '.$this->get_condition() . $orderby;
		$sql .= ' LIMIT '.$this->input['offset'].','.$this->input['count'];
		//exit($sql);
		$topic_all_data = $this->db->query($sql);
		$info = $topic_ids = array();
		while($result = $this->db->fetch_array($topic_all_data))
		{
			$result['audit'] = $result['status']?0:1;
			$result['state_tags'] = $result['status']?'关闭':'开启';
			$result['link'] = SNS_MBLOG."k.php?q=".$result['title'];
			
			$topic_ids[] = $result['id'];
			$info[$result['id']] = $result;
		}
		
		if ($topic_ids)
		{
			$topic_member = $this->get_topic_member($topic_ids);
		}
		
		if ($info)
		{
			$info_all = array();
			foreach ($info AS $k => $v)
			{
				if ($topic_member[$k])
				{
					$info_all[$k] = @array_merge($info[$k], $topic_member[$k]);
				}
				else 
				{
					$info_all[$k] = $info[$k];
				}
			}
		}
/*	hg_pre($topic_ids);
	hg_pre($topic_member);
	hg_pre($info_all);exit;*/
		$this->addItem($info_all);
		$this->output();
	}
	
	/*
	 * 获取具体的一条话题信息
	 */
	function detail()
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
		$sql = "SELECT * FROM " . DB_PREFIX . "topic".$condition;
		//echo ($sql);
		$r = $this->db->query_first($sql);
		$this->setXmlNode('topics' , 'topic');
		if(is_array($r) && $r)
		{
			$r['link'] = SNS_MBLOG."k.php?q=".$r['title'];
			$this->addItem($r);
			$this->output();
		}
	}
	
	/**
	 *获取话题和用户的信息 
	 */
	public function get_topic_member($ids)
	{
		if(!$ids || !is_array($ids))
		{
			return;
		}
		$sql = 'select * from '.DB_PREFIX.'topic_member where topic_id in('.implode(',', $ids).')';
		$query = $this->db->query($sql);
		$r = array();
		$member_ids = array();
		while($result = $this->db->fetch_array($query))
		{
			$result['create_time'] = date('Y-m-d H:i:s',$result['create_time']);
			$member_ids[] = $result['member_id'];
			$r[$result['topic_id']]['members'] = $result;
		}
		if($member_ids)
		{
			$userinfo = $this->mUser->getUserById(implode(',', $member_ids));
		}
		if($userinfo)
		{
			foreach ($userinfo as $k=>$v)
			{
				$userinfo = $v;
				unset($userinfo[$k]);	
			}
		}
		foreach ($r as $k=>$v)
		{
			$r[$v['members']['topic_id']]['members']['user'] = $userinfo;
		}/**/
		return $r;
		//hg_pre($r);
	}
	function count()
	{
		$sql = 'select count(*) as total from '.DB_PREFIX.'topic where 1 '.$this->get_condition();
		$topic_total_item = $this->db->query_first($sql);
		echo json_encode($topic_total_item);		
	}
	private function get_condition()
	{
		$condition = '';
		if(isset($this->input['start_time']) && !empty($this->input['start_time']))
		{
			$this->input['start_time'] = strtotime(urldecode($this->input['start_time']));
		    $a = $this->input['start_time'];
			if(isset($this->input['end_time']) && !empty($this->input['end_time']))
			{
				$this->input['end_time'] = strtotime(urldecode($this->input['end_time']));
				$condition .= 'and create_time between '.$this->input['start_time'].' and '.$this->input['end_time'];
			}
			else
			{
				$condition .= 'and create_time > '.$this->input['start_time'];
			}
		}
		if(!$this->input['start_time'] && isset($this->input['end_time']) && !empty($this->input['end_time']))
		{
			$this->input['end_time'] = strtotime(urldecode($this->input['end_time']));
			$condition .= 'and create_time < '.$this->input['end_time'];
		}
		
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' and title like \'%'.urldecode($this->input['k']).'%\'';
		}
		
		if(isset($this->input['state']))
		{
			if(-1!=$this->input['state'])
			{
				$condition .= ' and status = ' . intval($this->input['state']);
			}
		}
	
		//时间条件
		if($this->input['date_search'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date_search']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  create_time > '".$yesterday."' AND create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  create_time > '".$today."' AND create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  create_time > '".$last_threeday."' AND create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  create_time > '".$last_sevenday."' AND create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}
		
		//节点条件
		if ($this->input['_type'])
		{
			$before3hours = TIMENOW - 3*3600;
			$min_comment_count = 100;
			
			switch (intval($this->input['_type']))
			{
				case 1://最新更新
					$condition .= " AND create_time > '" . $before3hours . "' AND create_time < '" . TIMENOW . "'";
					break;
				case 2://最多评论
				//	$condition .= " AND m.create_time > '" . $min_comment_count . "'";
					break;
				default://最多@
				//	$condition .= " AND m.create_time > '" . $min_transmit_count . "'";
					break;
			}
		}
		
		return $condition;		
	}
}
	$topicShowApi = new topicShowApi();
	if(!method_exists($topicShowApi, $_INPUT['a']))
	{
		$_INPUT['a'] = 'show';
	}
	$topicShowApi->$_INPUT['a']();
?>