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
define('MOD_UNIQUEID','old_live');
class changePlanUpdateApi extends adminUpdateBase
{
	private $mChangePlan;
	private $mServerConfig;
	public function __construct()
	{
		parent::__construct();
		if($this->mNeedCheckIn && !$this->prms['serial_connection_plan'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		require_once CUR_CONF_PATH . 'lib/change_plan.class.php';
		$this->mChangePlan = new changePlan();
		
		require_once CUR_CONF_PATH . 'lib/server_config.class.php';
		$this->mServerConfig = new serverConfig();
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
		$info = $this->mChangePlan->show($condition);
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
		$channel_id = intval($this->input['channel_id']);
		$start_time = urldecode($this->input['plan_start_time']);
		$end_time 	= urldecode($this->input['plan_end_time']);
		$week_num 	= $this->input['week_day'];
		if(!$channel_id)
		{
			$this->errorOutput("未传入频道id");
		}
		//直播频道
		$channel_field = ' id, server_id ';
		$channel_info = $this->mChangePlan->get_channel_by_id($channel_id, $channel_field);
		if (empty($channel_info))
		{
			$this->errorOutput('该频道不存在或已被删除');
		}
		
		$server_id = $channel_info['server_id'];
		if ($server_id)
		{
			$server_info 	= $this->mServerConfig->get_server_config_by_id($server_id);
		}
		
		if(!$start_time)
		{
			$this->errorOutput("开始时间不为空");
		}		
		
		if(strtotime(date("Y-m-d") . " " . $end_time) - strtotime(date("Y-m-d") . " " . $start_time) <= 0)
		{
			$this->errorOutput("结束时间必须大于开始时间");
		}
		
		if(!$this->input['channel2_ids'])
		{
			$this->errorOutput("来源类型不能为空");
		}
		
		if(empty($week_num))
		{
			$this->errorOutput("请选择串联单计划的时间段");
		}

		$week = $this->mChangePlan->verify($start_time,$end_time,$channel_id);
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
		
		$add_input = array(
			'channel_id'			=> $channel_id,
			'channel2_ids'			=> $this->input['channel2_ids'],
			'channel2_name'			=> $this->input['channel2_name'],
			'type'					=> $this->input['type'],
			'plan_start_time'		=> $this->input['plan_start_time'],
			'plan_end_time'			=> $this->input['plan_end_time'],
			'program_start_time'	=> $this->input['program_start_time'],
			'week_d'				=> $this->input['week_d'],
			'week_day'				=> $this->input['week_day'],
			'server_id'				=> $channel_info['server_id'],
		);
		
		$info = $this->mChangePlan->create($add_input, $server_info, $this->user);
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
		$id 		= intval($this->input['id']);
		$channel_id = intval($this->input['channel_id']);
		$start_time = urldecode($this->input['plan_start_time']);
		$end_time 	= urldecode($this->input['plan_end_time']);
		$week_num 	= $this->input['week_day'];
		
		if(!$id)
		{
			$this->errorOutput("未传入更新ID");
		}
		
		if(!$channel_id)
		{
			$this->errorOutput("未传入频道id");
		}
		
		if(!$start_time)
		{
			$this->errorOutput("开始时间不为空");
		}

		if(strtotime(date("Y-m-d") . " " . $end_time) - strtotime(date("Y-m-d") . " " . $start_time) <= 0)
		{
			$this->errorOutput("结束时间必须大于开始时间");
		}

		if(empty($week_num))
		{
			$this->errorOutput("请选择串联单计划的时间段");
		}
	
		//直播频道
		$channel_field = ' id, server_id ';
		$channel_info = $this->mChangePlan->get_channel_by_id($channel_id, $channel_field);
		if (empty($channel_info))
		{
			$this->errorOutput('该频道不存在或已被删除');
		}
		
		$server_id = $channel_info['server_id'];
		if ($server_id)
		{
			$server_info 	= $this->mServerConfig->get_server_config_by_id($server_id);
		}
		
		$week = $this->mChangePlan->verify($start_time,$end_time,$channel_id,$id);
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
		
		$add_input = array(
			'channel_id'			=> $channel_id,
			'channel2_ids'			=> $this->input['channel2_ids'],
			'channel2_name'			=> $this->input['channel2_name'],
			'type'					=> $this->input['type'],
			'plan_start_time'		=> $this->input['plan_start_time'],
			'plan_end_time'			=> $this->input['plan_end_time'],
			'program_start_time'	=> $this->input['program_start_time'],
			'week_d'				=> $this->input['week_d'],
			'week_day'				=> $this->input['week_day'],
			'server_id'				=> $channel_info['server_id'],
		);
		
		$info = $this->mChangePlan->update($id, $add_input, $server_info);	

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
		$info = $this->mChangePlan->delete(urldecode($this->input['id']));
		if(!$info)
		{
			$this->errorOutput("删除失败！");
		}
		$this->input['channel_id'] = $info;
		$this->show();
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


			