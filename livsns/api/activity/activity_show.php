<?php
require( 'global.php');

class activityShow extends outerReadBase
{
	function __construct()
	{
		parent::__construct();
		require_once  CUR_CONF_PATH.'lib/activity.class.php';
		$this->libactivity = new activityLib();
		require_once (ROOT_PATH . 'lib/class/team.class.php');
		$this->team = new team();
		require_once (ROOT_PATH . 'lib/class/mark.class.php');
		$this->libmark = new mark();
		require_once (ROOT_PATH . 'lib/class/option.class.php');
		$this->liboption = new option();
	}
	/**
	 * 显示活动的类型
	 * Enter description here ...
	 */
	public function showApplyTypes()
	{
		$result = array();
		$result = $this->libactivity->get('activity_apply_type','type_id,type_name,type_value', array('user_id'=>0), 0, -1, array());
		$this->setXmlNode('activity', 'showApplyTypes');
		foreach($result as $k => $v)
		{
			$this->addItem_withkey($v['type_id'],$v);
		}
		
		$this->output();
	}
	
	//显示多条数据
	public function show()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 10;
		
		//获取选取条件
		$ce = $data = array();
		if(isset($this->input['type']))
		{
			if(isset($this->input['state']))
			{
				$ce['state'] = trim(htmlspecialchars_decode(urldecode($this->input['state'])));
			}
			if(isset($this->input['start_time']))
			{
				$ce['start_time'] = trim(htmlspecialchars_decode(urldecode($this->input['start_time'])));
			}
			if(isset($this->input['end_time']))
			{
				$ce['end_time'] = trim(htmlspecialchars_decode(urldecode($this->input['end_time'])));
			}
			if(isset($this->input['create_time']))
			{
				$ce['create_time'] = trim(htmlspecialchars_decode(urldecode($this->input['create_time'])));
			}
		}
		$data = $this->getCondition();
		
	
		if(isset($this->input['time']) && $this->input['time'] == 1 )
		{
			$lis = array('yet_join'=>'desc','create_time'=>'desc');
		}
		else 
		{
			$lis = array('create_time'=>'desc');
		}
		$result = $this->libactivity->get('activity', '*', $data, $offset, $count, $lis,$ce);
		if($result)
		{
			foreach ($result as $k=>$v)
			{
				if($v['action_img'])
				{
					$v['action_img'] = unserialize($v['action_img']);
				}
				$join_state = 0;
				if($this->user['user_id'])
				{
					$join_state = $this->libactivity->get('activity_apply','count(id) as total',array('user_id'=>$this->user['user_id'],'action_id'=>$v['action_id']),0,1,array());
				}
				$pp = $this->liboption->getTotalAndUse($this->user['user_id'], 'action', $v['action_id'], 'praise'); 
				$v['praise'] = $pp;
				$v['join_state'] = $join_state;
				$this->addItem_withkey($v['action_id'], $v);
			}
		}
		$this->output();
	}
	//显示具体某条
	public function detail()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 6;
		$data = $this->getCondition();
		//print_r($data);exit;
		$action_id = trim(urldecode($this->input['action_id']));
		if(!$action_id || !is_numeric($action_id))
		{
			$this->errorOutput("你搜索得活动id参数不合法");
		}
		//获取选取条件
		$data['action_id'] = $action_id;
		$result = array();
		$result = $this->libactivity->get('activity', '*', $data, 0 , 1, array());
		$this->setXmlNode('activity', 'detail');
		if(!$result)
		{
			$this->errorOutput("你搜索得活动不存在");
		}
		else
		{
			foreach ($result as $k=>$v)
			{
				if($k == 'action_img' && $v)
				{
					$v = unserialize($v);
				}
				$this->addItem_withkey($k,$v);
			}
			if($count == 0)
			{
				//获取用户信息
				$yet_join['total'] = $this->libactivity->getActivityApply('count(id) as total', array('action_id'=>$action_id,'apply_status'=>'0,2','state'=>1), 0, 1, '');
				if($yet_join['total'])
				{
					$yet_join['info'] = $this->libactivity->getActivityApply('*', array('action_id'=>$action_id,'apply_status'=>'0,2','state'=>1), $offset, $count, '');
				}
				$this->addItem_withkey('yet_join',$yet_join);
			}
			//标签信息
			$marks = $this->libmark->show_source_id_mark(array('source_id'=>$data['action_id'],'source'=>'activity','action'=>'keywords','offset'=>0,'count'=>6));
			$this->addItem_withkey('mark',$marks['data'][$action_id]);
			$review_info = $this->libactivity->get('material', '*', array('action_id'=>$data['action_id']), 0, -1, array());
			$img = array();
			if($review_info)
			{
				foreach($review_info as $k=>$v)
				{
					$v['img_info'] = unserialize($v['img_info']);
					$img[$v['m_id']] = $v;
				}
			}
			$this->addItem_withkey('review_img', $img);
		}
		$this->output();
	}
	//某个条件下的条数条数
	public function count()
	{
		//获取选取条件
		$data =  array();
		$data = $this->getCondition();
		$result = $this->libactivity->get('activity','count(action_id) as total', $data, 0, 1, array());
		$this->setXmlNode('activity_type', 'count');
		$this->addItem_withkey('total',$result);
		$this->output();
	}
	public function showMyAttentionAction()
	{
		//获取选取条件
		$data =  array();
		$data = $this->getCondition();
		$data['user_id'] = trim($this->input['user_id']);
		if(!$data['user_id'])
		{
			$this->errorOutput("你搜索得用户不存在");
		}
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 6;
		$this->setXmlNode('activity', 'showMyAttentionAction');
		$result = $this->libactivity->showMyAttentionAction($data, $offset, $count, array('apply_time'=>'desc','create_time'=>'desc'));
		if($result)
		{
			foreach($result as $k=>$v)
			{
				$this->addItem_withkey($v['action_id'], $v);
			}
		}
		$this->output();
	}
	//活动回顾
	public function getReviewActions()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 10;
		$team_id =  intval($this->input['team_id']) ;
		$this->setXmlNode('activity', 'getReviewActions');
		$result = $this->libactivity->get('activity', '*',array('state'=>1,'team_id'=>$team_id), $offset, $count, array(),array('end_time'=>'<1354204800'));
		if($result)
		{
			foreach($result as $k=>$v)
			{
				$v[ 'action_img'] = unserialize($v['action_img']);
				$review_info = $this->libactivity->get('material', '*', array('action_id'=>$v['action_id']), 0, -1, array());
				$img = array();
				if($review_info)
				{
					foreach($review_info as $m=>$n)
					{
						$n['img_info'] = unserialize($n['img_info']);
						$img[$n['m_id']] = $n;
					}
				}
				$v['review_img'] = $img;
				$this->addItem_withkey($v['action_id'], $v);
			}
		}
		$this->output();
	}
	//按时间段加载数据
	public function getTimeActions()
	{
		 $year = date("Y");
		 $month = date("m");
		 $allday = date("t");
		 $strat_time = strtotime($year."-".$month."-1");
		 $end_time = strtotime($year."-".$month."-".$allday);
		$data['start_time'] = ">".(isset($this->input['start_time']) ? intval($this->input['start_time']) : $strat_time);
		$data['end_time'] = "<".(isset($this->input['end_time']) ? intval($this->input['end_time']) : $end_time);
		$team_id =  intval($this->input['team_id']) ;
		if(!$team_id)
		{
			$this->errorOutput("你搜索得小组不存在不存在");
		}
		$this->setXmlNode('activity', 'getTimeActions');
		if($result)
		{
			foreach($result as $k=>$v)
			{
				$v[ 'action_img'] = unserialize($v['action_img']);
				$review_info = $this->libactivity->get('material', '*', array('action_id'=>$v['action_id']), 0, -1, array());
				$img = array();
				if($review_info)
				{
					foreach($review_info as $m=>$n)
					{
						$n['img_info'] = unserialize($n['img_info']);
						$img[$n['m_id']] = $n;
					}
				}
				$v['review_img'] = $img;
				$this->addItem_withkey($v['action_id'], $v);
			}
		}
		$this->output();
	}
	//
	public function getCondition ()
	{
		$data = array ();
		//状态
		$data['state'] = '1,2';
		if(isset($this->input['state']))
		{
			$data['state'] = trim($this->input['state']);
		}
		//小组
		if(isset($this->input['team_id']))
		{
			$data['team_id'] = trim($this->input['team_id']);
		}
		//小组
		if(isset($this->input['action_id']) && is_numeric($this->input['action_id']))
		{
			$data['action_id'] = trim($this->input['action_id']);
		}
		//类型
		if(isset($this->input['team_type']) || isset($this->input['team_category']))
		{
			$post = array();
			if(isset($this->input['team_type']))
			{
				$post['team_type'] = $this->input['team_type'];
			}
			if(isset($this->input['team_category']))
			{
				$post['team_category'] = $this->input['team_category'];
			}
			$post['state'] = 1;
			if($post)
			{
				$teams = $this->libactivity->get('team','team_id',$post,0,-1,array());
				$data['team_id'] = $sp = '';
				foreach($teams as $k=>$v)
				{
					$data['team_id'] .= $sp . $v;
					$sp = ',';
				}
			}
		}
		if(isset($this->input['action_name']))
		{
			$data['action_name'] = trim(htmlspecialchars_decode(urldecode($this->input['action_name'])));
		}
		if(isset($this->input['user_id']))
		{
			$data['user_id'] = trim(htmlspecialchars_decode(urldecode($this->input['user_id'])));
		}
		return $data;
	}
	function index()
	{
		
	}
	//失误方法
	function unknow()
	{
		$this->errorOutput("你搜索得方法不存在");
	}
	function __destruct()
	{
		parent::__destruct();
		unset($this->libactivity);
	}
}

$out = new activityShow();
$action = $_REQUEST['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();