<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_plan.php 5399 2011-12-20 01:29:35Z repheal $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','program_plan');
class programPlanApi extends adminReadBase
{
	public function __construct()
	{
		######分类和操作追加######
		$this->mNodes = array(
			'program_plan_node' => '频道列表',
		);
		$this->mModPrmsMethods = array(
		//'publish'=>array('name'=>'快速发布'),
		);
		######分类和操作追加######
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/program_plan.class.php');
		$this->obj = new programPlan();
		unset($this->mPrmsMethods['audit'],$this->mPrmsMethods['sort']);
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	function show()
	{
		$channel_id = intval($this->input['channel_id']) ? intval($this->input['channel_id']) : 0;
		if(empty($channel_id))
		{
			$this->errorOutput('频道ID不为空！');
		}
		include_once(ROOT_PATH . 'lib/class/live.class.php');
		$newLive = new live();
		$channel = $newLive->getChannelById($channel_id, -1);
		$channel = $channel[0];
		if(!$channel['id'])
		{
			$this->errorOutput('该频道已经不存在！');
		}
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		$nodes = array();
		$nodes['_action'] = 'manage';
		$nodes['nodes'][$channel_id] = $channel_id;
		
		$this->verify_content_prms($nodes);
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$data_limit = " LIMIT " . $offset . " , " . $count;
		$info = $this->obj->show($condition,$data_limit);
		$channel['plan_doing'] = $channel['plan_pass'] = $channel['plan_future'] = 0;
		foreach($info as $k => $v)
		{
			if($v['start_time'] > TIMENOW)
			{
				$info[$k]['state'] = -1;
				$channel['plan_future']++;
			}
			
			if($v['start_time'] <= TIMENOW && TIMENOW <= ($v['start_time']+$v['toff']))
			{
				$info[$k]['state'] = 1;
				$channel['plan_doing']++;
			}
			
			if(TIMENOW > ($v['start_time']+$v['toff']))
			{
				$info[$k]['state'] = 0;
				$channel['plan_pass']++;
			}			
		}		
		$this->addItem_withkey('channel_info', $channel);
		$this->addItem_withkey('program_plan', $info);	
		$this->output();
	}

	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->obj->count($condition);
		echo json_encode($info);
	}
	
	private function get_condition()
	{
		$condition = '';
		if($this->input['channel_id'])
		{
			$condition .= ' AND p.channel_id=' . intval($this->input['channel_id']);
		}
		
		if($this->input['week_num'])
		{
			$condition .= ' AND r.week_num=' . intval($this->input['week_num']);
		}
		
		//$condition .= ' AND p.start_time>' . strtotime(date('Y-m-d',TIMENOW));
		
		return $condition;
	}
	
	public function detail()
	{
		if(!$this->input['channel_id'])
		{
			$this->errorOutput("缺少频道ID");
		}
		$channel_id = intval($this->input['channel_id']);
		include_once(ROOT_PATH . 'lib/class/live.class.php');
		$newLive = new live();
		$channel = $newLive->getChannelById($channel_id, -1);
		$channel = $channel[0];
		if(!$channel['id'])
		{
			$this->errorOutput('该频道已经不存在！');
		}
		
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		$nodes = array();
		$nodes['_action'] = 'manage';
		$nodes['nodes'][$channel['id']] = $channel['id'];
		$this->verify_content_prms($nodes);
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		$condition = $this->get_condition();
		$info = $this->obj->detail($condition);
		$this->addItem($info);
		$this->output();
	}
		
	public function getChannel()
	{
		include_once(ROOT_PATH . 'lib/class/live.class.php');
		$newLive = new live();
		$channel = $newLive->getChannel();
		if(!empty($channel))
		{
			foreach($channel as $k => $v)
			{
				$this->addItem($v);
			}
			$this->output();
		}
	}

	public function get_item()
	{
		$info = $this->obj->get_item();
		$data = array();
		foreach($info as $k => $v)
		{
			$data[] = $v['name'];
		}
		$this->addItem($data);
		$this->output();
	}

	public function getItem()
	{
		$info = $this->obj->get_item();
		$data = array();
		foreach($info as $k => $v)
		{
			$data[$v['id']] = $v['name'];
		}
		echo json_encode($data);
	}
	
	function index()
	{
		
	}

	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new programPlanApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>