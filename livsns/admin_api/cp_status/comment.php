<?php 
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: comment.php 7884 2012-07-13 07:13:19Z wangleyuan $
***************************************************************************/
define(ROOT_DIR, '../../');
define('MOD_UNIQUEID','mblog_comment_m');
require_once(ROOT_DIR.'global.php');
class commentShowApi extends BaseFrm
{
	function __construct()
	{
		parent::__construct();
		include_once(ROOT_DIR.'lib/user/user.class.php');
		$this->mUser = new user();
	}
	function __destruct()
	{
		parent::__destruct();
		$this->db->close();
	}
	/*
	 * 获取具体的一条评论信息
	 */
	function detail()
	{
		$this->input['id'] = urldecode($this->input['id']);
		if(!$this->input['id'])
		{
			return;
		}
		if($this->input['id'] == 'lastest')
		{
			$condition = ' ORDER BY  id DESC LIMIT 1';
		}
		else
		{
			$condition = ' WHERE id in('.$this->input['id'].')';
		}
	
		$sql = "SELECT * FROM " . DB_PREFIX . "status_comments".$condition;		
		$r = $this->db->query_first($sql);
		$this->setXmlNode('comments' , 'comment');
		if(is_array($r) && $r)
		{
			$r['comment_time'] = date('Y-m-d H:i:s',$r['comment_time']);
			$this->addItem($r);
			$this->output();
		}
	
	}
	/**
	 * 获取评论信息
	 */
	function show()
	{
		$this->input['count'] = $this->input['count']?$this->input['count']:10;
		$this->input['offset'] = $this->input['offset']?$this->input['offset']:0;
		$sql = 'select * from '.DB_PREFIX.'status_comments where 1 '
		.$this->get_condition().' order by comment_time desc limit '.$this->input['offset'].','.$this->input['count'];
		//hg_pre($sql);
		$comment_all_data = $this->db->query($sql);
		$member_ids = array();
		$status_ids = array();
		while($result = $this->db->fetch_array($comment_all_data))
		{
			$result['comment_time'] = date('Y-m-d H:i',$result['comment_time']);
			
			//审核状态
			$result['state'] = $result['flag'];
			
			$result['flag'] = $result['flag']?'通过':'待审';
			$member_ids[] = $result['member_id'];
			$status_ids[] = $result['status_id'];
			$result['content'] = hg_verify($result['content']);
			$comments[] = $result;
		}
		//获取评论相对应的用户信息
		$userinfo = $this->get_user_info($member_ids);
		//hg_pre($status_ids);
		//获取评论相对应的点滴信息
		$statusinfo = $this->get_status_info($status_ids);
		//评论 用户 点滴信息的合并
		foreach ($comments as $k=>$v)
		{
			$v['user'] = $userinfo[$v['member_id']];
			$v['status'] = $statusinfo[$v['status_id']];
			$this->addItem($v);
			$a[] = $v;
		}
		//hg_pre($a);
		$this->output();
	}
	/**
		取出总的微博记录数
	*/
	function count()
	{
		$sql = 'select count(*) as total from '.DB_PREFIX.'status_comments where 1 '.$this->get_condition();
		$comment_total_item = $this->db->query_first($sql);
		echo json_encode($comment_total_item);
	}
	/**
	 * 获取点滴信息
	 * @pams $ids 点滴的ID
	 */
	function get_status_info($ids)
	{
		$r = array();
		if(!$ids)
		{
			return;
		}
		else
		{
			$ids = array_unique($ids);
		}
		$sql = 'select id,text from '.DB_PREFIX.'status where id in('.implode(',', $ids).')';
		//hg_pre($sql);
		$query = $this->db->query($sql);
		while($result = $this->db->fetch_array($query))
		{
			$r[$result['id']] = $result;
		}
		return $r;
	}
	/**
	 * 获取用户信息
	 * @pams $ids 用户的ID
	 */
	function get_user_info($ids)
	{
		if(!ids)
		{
			return;
		}
		$userinfo = $this->mUser->getUserById(implode(',',array_unique($ids)));
		if(!empty($userinfo))
		{
			foreach ($userinfo as $key=>$value)
			{
				$userinfo_tmp[$value['id']] = $value;
			}
			unset($userinfo);
			return $userinfo_tmp;
		}
		else
		{
			return false;
		}
		//hg_pre($userinfo_tmp);
	}
	/**
		预处理传入的ID
		id = 1,2,3 => id in(1,2,3)
		@no params 
	*/
	/*private function prefilterId()
	{
		if(!empty($this->input['id']) && isset($this->input['id']))
		{
			$this->input['id'] = trim(urldecode($this->input['id']));
		}
		else 
		{
			return FALSE;
		}
		$length = strlen($this->input['id'])-1;
		if(strpos($this->input['id'], ','))
		{	
			if($length==strrpos($this->input['id'], ','))
			{
				$this->input['id'] = substr($this->input['id'], 0,-1);
			}
			foreach(explode(',', $this->input['id']) as $id)
			{
				if(!is_numeric($id)) 
				{
					return FALSE;
				}
			}
		}
		return $where_id_in = 'id in('.$this->input['id'].')';
	}*/
	/**
	 * 搜索设置 条件选择
	 */
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
				$condition .= 'and comment_time between '.$this->input['start_time'].' and '.$this->input['end_time'];
			}
			else
			{
				$condition .= 'and comment_time > '.$this->input['start_time'];
			}
		}
		if(!$this->input['start_time'] && isset($this->input['end_time']) && !empty($this->input['end_time']))
		{
			$this->input['end_time'] = strtotime(urldecode($this->input['end_time']));
			$condition .= 'and comment_time < '.$this->input['end_time'];
		}
		
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' and content like \'%'.urldecode($this->input['k']).'%\'';
		}
		
		if(isset($this->input['state']))
		{
			if(-1!=$this->input['state'])
			{
				$condition .= ' and flag = '.intval($this->input['state']);
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
					$condition .= " AND  comment_time > '".$yesterday."' AND comment_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  comment_time > '".$today."' AND comment_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  comment_time > '".$last_threeday."' AND comment_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  comment_time > '".$last_sevenday."' AND comment_time < '".$tomorrow."'";
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
					$condition .= " AND comment_time > '" . $before3hours . "' AND comment_time < '" . TIMENOW . "'";
					break;
				case 2://最多评论
				//	$condition .= " AND comment_count > '" . $min_comment_count . "'";
					break;
				default://最多@
				//	$condition .= " AND se.transmit_count > '" . $min_transmit_count . "'";
					break;
			}
		}
		
		return $condition;
	}	
}
	$obj = new commentShowApi();
	if(!method_exists($obj, $_INPUT['a']))
	{
		$_INPUT['a'] = 'show';
	}
	$obj->$_INPUT['a']();
?>