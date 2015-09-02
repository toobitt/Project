<?php
require( 'global.php');

class activityShow extends outerReadBase
{
	function __construct()
	{
		parent::__construct();
		require_once  'lib/activity.class.php';
		$this->libactivity = new activityLib();
		include_once (ROOT_PATH . 'lib/class/team.class.php');
		$this->team = new team();
	}
	
	//判定用户与活动的关系
	public function findRelationshipOfUserWithActivity()
	{
		$data['action_id'] = trim($this->input['action_id']);
		$data['user_id'] = trim($this->input['user_id']);
		if(!$data['action_id'] || !$data['user_id'])
		{
			$this->errorOutput("你传递的参数不完整");
		}
		$result = $this->libactivity->getActivityApply('count(action_id) as total,apply_status');
		$ret = array('state'=>0,'msg'=>'你没有报名');
		if($result['total'])
		{
			if($result['apply_status'] == 0 || $result['apply_status'] == 2)
			{
				$ret = array('state'=>1,'msg'=>'你的报名已通过审核');
			}
			else if($result['apply_status'] == 0)
			{
				$ret = array('state'=>2,'msg'=>'你的报名还在审核中');
			}
			else 
			{
				$ret = array('state'=>3,'msg'=>'你的报名还在已经被打回');
			}
		}
		$this->setXmlNode('activity_apply', 'relationship');
		$this->addItem($ret);
		$this->output();
	}
	

	//显示多条数据
	public function show()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 10;
		
		//获取选取条件
		$data = array();
		$data = $this->getCondition();
		
		$total = $this->libactivity->get('activity_apply','count(id) as total', $data, 0, 1, array());
		$this->setXmlNode('activity_apply', 'show');
		$result = $this->libactivity->get('activity_apply','*', $data, $offset, $count,array('apply_time'=>'desc'));
		if($result)
		{
			$ids = $sp ='';
			foreach($result as $k=>$v)
			{
				$ids .= $sp .$v['user_id'];
				$sp =',';
				$this->addItem_withkey($v['id'], $v);
			}
		}
		$this->output();
	}
	//显示具体某条
	public function detail()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 6;
		
		$action_id = trim(urldecode($this->input['action_id']));
		if(!$action_id || !is_numeric($action_id))
		{
			$this->errorOutput("你搜索得活动id参数不合法");
		}
		//获取选取条件
		$data = $this->getCondition();
		$rawData = $this->libactivity->get('activity', 'action_id,need_info', array('action_id'=>$action_id,'state'=>1), 0 ,1, array());
		if(!$rawData['action_id'])
		{
			$this->errorOutput("你搜索得活动id参数不存在");
		}
		$limit = 'user_id,action_id,apply_status,apply_time,levl,client,from_ip';
			
		if($rawData['need_info'])
		{
			 $limit .=  "," . $rawData['need_info'];
		}
		
		$result = $this->libactivity->get('activity_apply', $limit, array('action_id'=>$action_id,'state'=>1), $offset , $count, array('apply_time'=>'desc'));
		$this->setXmlNode('activity_apply', 'detail');
		if($result)
		{
			$user_ids = $sp = '';
			foreach ($result as $k=>$v)
			{
				$user_ids .= $sp . $v['user_id'];
				$sp = ',';
				$this->addItem_withkey($v['id'], $v);
			}
			if($user_ids)
			{
				$this->addItem_withkey('user_ids', $user_ids);
			}
		}
		$this->output();
	}
	/**
	 * 随机数个某个活动参与人数
	 * Enter description here ...
	 */
	public function showRodom()
	{
		$action_id = trim(urldecode($this->input['action_id']));
		if(!$action_id || !is_numeric($action_id))
		{
			$this->errorOutput("你搜索得活动id参数不合法");
		}
		$count = isset($this->input['count']) ? intval($this->input['count']) : 6;
		
		//获取选取条件
		$data = $this->getCondition();
		$rawData = $this->libactivity->get('activity', 'action_id,need_info', array('action_id'=>$action_id,'state'=>1), 0 ,1, array());
		if(!$rawData['action_id'])
		{
			$this->errorOutput("你搜索得活动id参数不存在");
		}
		$user_id = ($this->user['user_id']) ? $this->user['user_id'] : 0;
		$limit = 'user_id,action_id,apply_status,apply_time,levl,client,from_ip,app_name';
			
		if($rawData['need_info'])
		{
			 $limit .=  "," . $rawData['need_info'];
		}
		
		$result = $this->libactivity->get('activity_apply', $limit, array('action_id'=>$action_id,'state'=>1), 0, 6, array('RAND()'=>''),array('user_id'=>'!='.$user_id));
		$this->setXmlNode('activity_apply', 'showRodom');
		if($result)
		{
			$user_ids = $sp ='';
			foreach ($result as $k=>$v)
			{
				$user_ids .= $sp . $v['user_id'];
				$sp = ',';	
				$this->addItem_withkey($v['id'], $v);
			}
			if($user_ids)
			{
				$this->addItem_withkey('user_ids', $user_ids);
			}
		}
		$this->output();
	}
	//某个条件下的条数条数
	public function count()
	{
		//获取选取条件
		$data =  array();
		$data = $this->getCondition();
		$result = $this->libactivity->getActivityApply('count(id) as total', $data);
		$this->setXmlNode('activity_apply', 'count');
		$this->addItem_withkey('total',$result['total']);
		$this->output();
	}
	//
	public function getCondition ()
	{
		$data = array ();
		//状态
		$state = trim($this->input['state']);
		switch($state)
		{
			case 3://全部
				break;
			case 2:
				$data['state'] = 0;;
				break;
			default:
				$data['state'] = 1;
				break;
		}
		$apply_status = trim($this->input['apply_status']);
		switch($apply_status)
		{
			case 4://全部
				break;
			case 1:
			case 3:
				$data['apply_status'] = $apply_status;
				break;
			default:
				$data['apply_status'] = '0,2';
				break;
		}
		if(isset($this->input['action_id']))
		{
			$data['action_id'] = trim($this->input['action_id']);
		}
		return $data;
	}	
	
	public function index()
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
		unset($this->team);
	}
}

$out = new activityShow();
$action = $_REQUEST['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();