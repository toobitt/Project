<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function show|create|update|delete|unknow
*
* $Id: change_plan_update.php 
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','change_plan_m');//模块标识
class changePlanUpdateApi extends BaseFrm
{
	/**
	 * 构造函数
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include change_plan.class.php
	 */
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/change_plan.class.php');
		$this->obj = new changePlan();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	/**
	 * 操作串联单计划后显示
	 * @name show
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $channel_id int 频道ID
	 * @return array 所在频道串联单计划信息
	 */
	function show()
	{
		if($this->input['channel_id'])
		{
			$condition = ' AND p.channel_id='.$this->input['channel_id'];
		}
		$info = $this->obj->show($condition);
		$time_arr = $all = $time_show = array();
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
		$this->addItem(array('time_arr' => $time_show,'info' => $all));
		$this->output();
	}
	
	/**
	 * 串联单计划创建
	 * @name create
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $channel_id int 频道ID
	 * @param $start_time int 开始时间
	 * @param $end_time int 结束时间
	 * @param $channel2_ids int 来源类型ID
	 * @param $week_num tinyint 星期几
	 * @return array 所在频道串联单计划信息
	 */
	function create()
	{
		if(!$this->input['channel_id'])
		{
			$this->errorOutput("频道不为空");
		}
		$start_time = urldecode($this->input['plan_start_time']);
		if(!$this->input['plan_start_time'])
		{
			$this->errorOutput("开始时间不为空");
		}		
		$end_time = urldecode($this->input['plan_end_time']);
		if(strtotime(date("Y-m-d") . " " . $end_time) - strtotime(date("Y-m-d") . " " . $start_time) <= 0)
		{
			$this->errorOutput("结束时间必须大于开始时间");
		}
		if(!$this->input['channel2_ids'])
		{
			$this->errorOutput("来源类型不能为空");
		}
		$week_num = $this->input['week_day'];
		if(empty($week_num))
		{
			$this->errorOutput("请选择串联单计划的时间段");
		}

		$week = $this->obj->verify($start_time,$end_time,$this->input['channel_id']);
		if(!empty($week))
		{
			$result = hg_array_sameItems($week_num, $week);
			if(is_array($result) && !empty($result))
			{
				$weeks = $space = "";
				foreach($result as $k => $v)
				{
					$weeks .= $space . $v;
					$space = ",";
				}			
				$al = array(1,2,3,4,5,6,7);
				$ch = array('一', '二' , '三' , '四' , '五' , '六' , '日' );
				$output  = str_replace($al, $ch, $weeks);
				$this->errorOutput("星期" . $output . "已经包含" . $start_time . "~" . $end_time . "的串联单，请选择串联单计划的时间段");
			}
		}

		$info = $this->obj->create();
		if(!$info)
		{
			$this->errorOutput("创建失败！");
		}
		$this->show();
	}

	/**
	 * 串联单计划更新
	 * @name update
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $id int 串联单计划ID
	 * @param $channel_id int 频道ID
	 * @param $start_time int 开始时间
	 * @param $end_time int 结束时间
	 * @param $channel2_ids int 来源类型ID
	 * @param $week_num tinyint 星期几
	 * @return array 所在频道串联单计划信息
	 */
	function update()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput("未传入更新ID");
		}

		if(!$this->input['channel_id'])
		{
			$this->errorOutput("频道不为空");
		}

		$start_time = urldecode($this->input['plan_start_time']);
		if(!$start_time)
		{
			$this->errorOutput("开始时间不为空");
		}
		$end_time = urldecode($this->input['plan_end_time']);

		if(strtotime(date("Y-m-d") . " " . $end_time) - strtotime(date("Y-m-d") . " " . $start_time) <= 0)
		{
			$this->errorOutput("结束时间必须大于开始时间");
		}

		$week_num = $this->input['week_day'];
		if(empty($week_num))
		{
			$this->errorOutput("请选择串联单计划的时间段");
		}

		$week = $this->obj->verify($start_time,$end_time,$this->input['channel_id'],$this->input['id']);
		if(!empty($week))
		{
			$result = hg_array_sameItems($week_num, $week);
			if(is_array($result) && !empty($result))
			{
				$weeks = $space = "";
				foreach($result as $k => $v)
				{
					$weeks .= $space . $v;
					$space = ",";
				}			
				$al = array(1,2,3,4,5,6,7);
				$ch = array('一', '二' , '三' , '四' , '五' , '六' , '日' );
				$output  = str_replace($al, $ch, $weeks);
				$this->errorOutput("星期" . $output . "已经包含" . $start_time . "~" . $end_time . "的节目，请选择串联单计划的时间段");
			}
		}
		$info = $this->obj->update();	

		if(!$info)
		{
			$this->errorOutput("更新失败！");
		}
		$this->show();
	}

	function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput("未传入对象ID");
		}
		$info = $this->obj->delete();
		if(!$info)
		{
			$this->errorOutput("删除失败！");
		}
		$this->input['channel_id'] = $info;
		$this->show();
	}

	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new changePlanUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>


			