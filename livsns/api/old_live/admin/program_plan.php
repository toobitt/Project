<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_plan.php 5399 2011-12-20 01:29:35Z repheal $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','old_live');
class programPlanApi extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		if($this->mNeedCheckIn && !$this->prms['program_plan'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		include(CUR_CONF_PATH . 'lib/program_plan.class.php');
		$this->obj = new programPlan();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	function show()
	{
		$condition = $this->get_condition();
		//$channel_id = $this->input['channel_id'];
		$info = $this->obj->show($condition);
		$time_arr = array();
		$all = array();
		foreach($info as $key => $value)
		{
			$time_arr[] = $value['start'];
			$time_arr[] = $value['end'];
			$all[$value['week_num']][] = $value;
			
		}
		$time_arr = array_unique($time_arr);
		if(!empty($time_arr))
		{
			$time_show = array(strtotime('00:00:00'));
			foreach($time_arr as $k => $v)
			{
				$time_show[] = $v;
			}
		}
		if(is_array($time_show))
		{
			sort($time_show);
		}

		$this->addItem_withkey('time_arr', $time_show);
		$this->addItem_withkey('info', $all);
		$this->output();
	}

	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->obj->count($condition);
		//暂时这样处理
		echo json_encode($info);
	}
	
	private function get_condition()
	{
		$condition = '';
		if($this->input['channel_id'])
		{
			$condition .= ' AND p.channel_id='.$this->input['channel_id'];
		}

		if($this->input['week_num'])
		{
			$condition .= ' AND r.week_num='.$this->input['week_num'];
		}

		return $condition;
	}
	
	public function detail()
	{
		if(!$this->input['channel_id'])
		{
			$this->errorOutput("缺少频道ID");
		}
		$condition = $this->get_condition();
		$info = $this->obj->detail($condition);
		$this->addItem($info);
		$this->output();
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


			