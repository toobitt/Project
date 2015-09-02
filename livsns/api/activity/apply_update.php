<?php
require( 'global.php');

class applyUpdate extends outerUpdateBase
{
	function __construct()
	{
		parent::__construct();
		require_once  'lib/activity.class.php';
		$this->libactivity = new activityLib();
		include_once (ROOT_PATH . 'lib/class/team.class.php');
		$this->team = new team();
	}
	
	//参数获取
	public function getData()
	{
		$data = array();
		$data = $this->libactivity->checkUserExit();
		//id
		if($this->input['id'])
		{
			$data['id'] = trim($this->input['id']);
		}
		//活动
		$data['action_id'] = trim($this->input['action_id']);
		if(!$data['action_id'] || !is_numeric($data['action_id']))
		{
			$this->errorOutput("你申请的活动id参数不合法");
		}
		//申请人级别
		if($this->input['levl'])
		{
			$data['levl'] = trim(urldecode($this->input['levl']));
		}
		$joinData = $this->libactivity->get('activity', 'start_time,end_time,need_info,need_num,need_pay,yet_join,team_id', array('action_id'=>$data['action_id'],'state'=>1), 0, 1, array());
		if(!$joinData)
		{
			$this->errorOutput("你申请的活动已关闭或者不存在");
		}
		if($joinData['start_time'] > TIMENOW)
		{
			$this->errorOutput("你申请的活动报名尚未开始");
		}
		else if($joinData['end_time'] < TIMENOW)
		{
			$this->errorOutput("你申请的活动报名已经结束");
		}
		else 
		{
			//TODO
		}
		if($joinData['need_info'])
		{
			$limintData = array();
			$limintData = explode(',', $joinData['need_info']);
			if(is_array($limintData))
			{
				foreach($limintData as $k => $v )
				{
					if(isset($this->input[$v]))
					{
						$data[$v] = trim(urldecode($this->input[$v]));
					}
					else 
					{
						$this->errorOutput("你填写的资料信息不完整");
					}
				}
			}
		}
		if(!$data['part_num'])
		{
			$data['part_num'] = 1;
		}
		if($joinData['need_num'])
		{
			if($joinData['need_num'] < $joinData['yet_join'] + $data['part_num'])
			{
				$this->errorOutput("你填写的人数超过剩余可报名人数大小");
			}
		}
		$data['team_id'] = $joinData['team_id'];
		return $data;
	}
	
//创建
	public function create()
	{
		$data = array();
		//加载数据
		$data = $this->getData();		
		//加载原始数据
		$rawData = $this->libactivity->get('activity_apply','id,apply_status', array('user_id'=>$data['user_id'],'action_id'=>$data['action_id'],'state'=>1), 0, 1, array());
		$result = $this->team->get_permission($data['team_id'], $data['user_id'], 'ADD_ACTIVITY');
		if(!$result['permission'])
		{
			//$this->errorOutput("你没有权限操作");
		}
		//加载创建时间
		$data['apply_time'] = TIMENOW;
		//来源ip
		$data['from_ip'] = hg_getip();
		//来源部分
		$data['app_name'] = $this->user['display_name'];
		//来源客户端
		$data['client'] = $_SERVER['HTTP_USER_AGENT'];
		
		//有效的
		$data['state'] = 1;
		unset($data['team_id']);
		if(!$rawData)
		{
			//加载审核状态
			$data['apply_status'] = 0;
			$result = $this->libactivity->insert('activity_apply', $data);
			if($result)
			{
				$this->libactivity->updateData($data['action_id'], array('apply_num'=>$data['part_num'],'yet_join'=>$data['part_num']), "+");

			}
			$result = $data['apply_status'];
		}
		else 
		{
			if($this->libactivity->update('activity_apply', $data, array('id'=>$rawData['id'])))
			{
				$result = $rawData['apply_status'];
			}
		}
		
		$this->setXmlNode('activity_apply', 'create');
		$this->addItem_withkey('apply_status', $result);
		$this->output();
	}
	
	//更新数据
	public function update()
	{
		//TODO
	}
	//取消申请
	public function delete()
	{
		$data = $this->getData();
		
		if(!$data['action_id'] || !is_numeric($data['action_id']))
		{
			$this->errorOutput("你搜索得活动id参数不合法");
		}
		$rawData = $this->libactivity->get('activity_apply','id,apply_status,part_num', array('user_id'=>$data['user_id'],'action_id'=>$data['action_id'],'state'=>1), 0, 1, array());
		if(!$rawData)
		{
			$this->errorOutput("你搜索得数据不存在");
		}
		$result = $this->team->get_permission($data['team_id'], $data['user_id'], 'ADD_ACTIVITY');
		if(!$result['permission'])
		{
			$this->errorOutput("你没有权限操作");
		}
		//关闭
		$data['state'] = 0;
		$result = $this->libactivity->deteleActivityApply($data);
		if($result)
		{
			$arr = array('apply_num'=>$rawData['part_num']);
			if($rawData['0']['apply_status'] == 0 || $rawData['0']['apply_status'] == 2)
			{
				$arr['yet_join'] = $rawData['part_num'];
			}
			$this->libactivity->updateData($data['action_id'], $arr, "-");
		}
		$this->setXmlNode('activity_apply', 'delete');
		$this->addItem_withkey(array('state'=>$result));
		$this->output();
	}
	
	public function audit()
	{
		
	}
	public function sort()
	{
		
	}
	public function publish()
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

$out = new applyUpdate();
$action = $_REQUEST['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();