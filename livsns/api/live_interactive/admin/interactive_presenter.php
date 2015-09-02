<?php
/***************************************************************************
* $Id: interactive_presenter.php 17440 2013-02-21 01:27:51Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','interactive_presenter');
require('global.php');
class interactivePresenterApi extends BaseFrm
{
	private $mInteractive;
	private $mInteractiveProgram;
	private $mBasicInfo;
	public function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/interactive.class.php';
		$this->mInteractive = new interactive();
		
		require_once CUR_CONF_PATH . 'lib/interactive_program.class.php';
		$this->mInteractiveProgram = new interactiveProgram();
		
		require_once CUR_CONF_PATH . 'lib/basic_info.class.php';
		$this->mBasicInfo = new basicInfo();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		if ($this->user['user_id'] < 0)
		{
			$this->errorOutput('请登录');
		}
		
		$channel_id = intval($this->input['channel_id']);
		if (!$channel_id)
		{
			$this->errorOutput('未传入频道id');
		}
		
		//获取频道信息
		$channel_info = $this->mInteractive->get_channel_info($channel_id);
		
		$this->addItem_withkey('channel', $channel_info);

		//根据日期选择节目单
		$dates 		= $this->input['dates'] ? urldecode($this->input['dates']) : date('Y-m-d', TIMENOW);
		$start_end 	= urldecode($this->input['start_end']);
		$this->addItem_withkey('dates', $dates);
		
		$program_start_end = $this->mInteractiveProgram->get_program_start_end($channel_id, $dates, $start_end);
		$start_time 	= $program_start_end['start_time'];
		$end_time 		= $program_start_end['end_time'];
		$start_end 		= $program_start_end['start_end'];
		$ret_program 	= $program_start_end['program'];
		
		$this->addItem_withkey('start_end', $start_end);
		
		//获取互动节目单
		$in_program = $this->mBasicInfo->get_program_by_time($channel_id, $dates, $start_time, $end_time);
		
	//	$this->addItem_withkey('in_program', $in_program);
		
		$in_program_id = $in_program[0]['id'];
		$this->addItem_withkey('in_program_id', $in_program_id);
		$this->addItem_withkey('program_id', $in_program[0]['program_id']);
		//获取主持人信息
		$presenter_info = $this->mInteractiveProgram->get_presenter_by_program_id($channel_id, $dates);
		$presenter_id 	= ($this->user['user_id'] > 2) ? $this->user['user_id'] : '';
		$presenter		= $presenter_info[$presenter_id];

		if (!empty($ret_program))
		{
			$program = array();
			foreach ($ret_program AS $v)
			{
				if (!empty($presenter))
				{
					foreach ($presenter AS $vv)
					{
						if ($vv['start_time'] == $v['start_time'])
						{
							$program[] = $v;
						}
					}
				}
			}
		}

		//获取节目环节
		$interactive_program = array();
		$ret_interactive_program = $this->mInteractiveProgram->show($channel_id, $dates, $start_time, $end_time);

		//互动数据
		$condition	= $this->get_condition();
		$offset 	= $this->input['offset'] ? intval($this->input['offset']) : 0;			
		$count 		= $this->input['count'] ? intval($this->input['count']) : 20;
		$orderby 	= " ORDER BY id DESC ";
		$condition .= " AND is_shield = 0 ";
		
		//互动总数
		$total_all = $this->mInteractive->count($condition, $start_time, $end_time);
		
		$interactive_info = $this->mInteractive->get_interactive_info($condition, $offset, $count, $orderby, $start_time, $end_time);
		
		//互动推荐总数
		$condition_2  = $this->get_condition();
		$condition_2 .= " AND is_shield = 0 ";
		$condition_2 .= " AND is_recommend = 1 ";
		$orderby_2 	  = " ORDER BY recommend_time DESC ";
		
		$total_2 = $this->mInteractive->count($condition_2, $start_time, $end_time);
		
		$interactive_info_2 = $this->mInteractive->get_interactive_info($condition_2, $offset, $count, $orderby_2, $start_time, $end_time);
		
		if (!empty($interactive_info))
		{
			$interactive = array();
			foreach ($interactive_info AS $v)
			{
				$v['plat_name'] = $v['plat_name'] ? $v['plat_name'] : $v['appname'];
				
				if (!$v['avatar_url'] && $v['host'])
				{
					$v['avatar_url'] = hg_material_link($v['host'], $v['dir'], $v['filepath'], $v['filename'], '36x36/');
				}
				else 
				{
					$v['avatar_url'] = $v['weibo_avatar'];
				}
				
				$interactive[] = $v;
			}
		}
		
		if (!empty($interactive_info_2))
		{
			$interactive_2 = array();
			foreach ($interactive_info_2 AS $v)
			{
				$v['plat_name'] = $v['plat_name'] ? $v['plat_name'] : $v['appname'];
			
				if (!$v['avatar_url'] && $v['host'])
				{
					$v['avatar_url'] = hg_material_link($v['host'], $v['dir'], $v['filepath'], $v['filename'], '36x36/');
				}
				else 
				{
					$v['avatar_url'] = $v['weibo_avatar'];
				}
				
				$interactive_2[] = $v;
			}
		}
		
		$interactive_program = $ret_interactive_program;
		
		if (empty($program) && $this->settings['admin_type']['presenter'] == $this->user['group_type'])
		{
			$interactive_program = $program = $interactive = $interactive_2 = array();
			$total_all['total'] = $total_2['total'] = 0;
		}
		else if (empty($program))
		{
			$program = $ret_program;
		}

		$this->addItem_withkey('interactive_program', $interactive_program);
		$this->addItem_withkey('program', $program);
		$this->addItem_withkey('total_all', $total_all['total']);
		$this->addItem_withkey('total_2', $total_2['total']);
		$this->addItem_withkey('interactive', $interactive);
		$this->addItem_withkey('interactive_2', $interactive_2);
		$this->output();
	}
	
	public function get_interactive_info()
	{
		$channel_id = intval($this->input['channel_id']);
		$id 		= intval($this->input['id']);
		
		//推荐时间
		$recommend_time = intval($this->input['recommend_time']);
		
		if (!$channel_id)
		{
			$this->errorOutput('未传入频道id');
		}
		
		$dates 		= $this->input['dates'] ? urldecode($this->input['dates']) : date('Y-m-d');
		$start_end 	= urldecode($this->input['start_end']);
		if (!$start_end)
		{
			$this->errorOutput('未传入节目单时间段');
		}
		
		$start2end 	= explode(',', $start_end);
		$start_time = strtotime($dates . ' ' . $start2end[0]);
		$end_time 	= strtotime($dates . ' '. $start2end[1]);

		//互动数据
		$condition	= $this->get_condition();
		$offset 	= $this->input['offset'] ? intval($this->input['offset']) : 0;			
		$counts 	= $this->input['counts'] ? intval($this->input['counts']) : 20;
		
		$condition .= " AND is_shield = 0 ";
		
		if (!$this->input['type'])	//导播推荐
		{
			if ($recommend_time)
			{
				if (!intval($this->input['old']))	//取新数据
				{
					$condition .= " AND recommend_time > " . $recommend_time;
				}
				else //取老数据
				{
					$condition .= " AND recommend_time < " . $recommend_time;
				}
			}
			$condition .= " AND is_recommend = 1 ";
			$orderby 	= " ORDER BY recommend_time DESC ";
		}
		else 	//不包含屏蔽的数据
		{
			if ($id)
			{
				if (!intval($this->input['old']))	//取新数据
				{
					$condition .= " AND id > " . $id;
				}
				else //取老数据
				{
					$condition .= " AND id < " . $id;
				}
			}
			$orderby 	= " ORDER BY id DESC ";
		}
		
		$interactive_info = $this->mInteractive->get_interactive_info($condition, $offset, $counts, $orderby, $start_time, $end_time);

		if (!empty($interactive_info))
		{
			$interactive = array();
			foreach ($interactive_info AS $v)
			{
				$v['plat_name'] = $v['plat_name'] ? $v['plat_name'] : $v['appname'];
				
				if (!$v['avatar_url'] && $v['host'])
				{
					$v['avatar_url'] = hg_material_link($v['host'], $v['dir'], $v['filepath'], $v['filename'], '36x36/');
				}
				else 
				{
					$v['avatar_url'] = $v['weibo_avatar'];
				}
				
				$interactive[] = $v;
			}
		}
	
		if ($this->settings['admin_type']['presenter'] == $this->user['group_type'])
		{
			$ret_presenter = $this->mInteractiveProgram->get_presenter_info($channel_id, $start_time, $this->user['user_id']);
			if (!$ret_presenter[0]['presenter_id'])
			{
				$interactive = array();
			}
		}
		
		$this->addItem($interactive);
		$this->output();
	}
	
	public function get_interactive_program()
	{
		$channel_id = intval($this->input['channel_id']);
		$dates = $this->input['dates'] ? urldecode($this->input['dates']) : date('Y-m-d');
		if (!$channel_id)
		{
			$this->errorOutput('未传入频道id');
		}
		
		$start_end 	= urldecode($this->input['start_end']);
		if (!$start_end)
		{
			$this->errorOutput('未传入节目单时间段');
		}
		
		$start2end 	= explode(',', $start_end);
		$start_time = strtotime($dates . ' ' . $start2end[0]);
		$end_time 	= strtotime($dates . ' '. $start2end[1]);
		//筛选节目环节
		$ret_interactive_program = $this->mInteractiveProgram->show($channel_id, $dates, $start_time, $end_time);
		$this->addItem($ret_interactive_program);
		$this->output();
	}
	
	public function total()
	{
		$channel_id = intval($this->input['channel_id']);
		if (!$channel_id)
		{
			$this->errorOutput('未传入频道id');
		}
		
		$dates = $this->input['dates'] ? urldecode($this->input['dates']) : date('Y-m-d');
		$start_end 	= urldecode($this->input['start_end']);
		if (!$start_end)
		{
			$this->errorOutput('未传入节目单时间段');
		}
		
		$start2end 	= explode(',', $start_end);
		$start_time = strtotime($dates . ' ' . $start2end[0]);
		$end_time 	= strtotime($dates . ' '. $start2end[1]);
		
		//获取节目环节
		$interactive_program = array();
		$ret_interactive_program = $this->mInteractiveProgram->show($channel_id, $dates, $start_time, $end_time);
		
		//所有
		$condition	= $this->get_condition();
	//	$condition .= " AND status = 1 ";
		$condition .= " AND is_shield = 0 ";
		
		$total_all = $this->mInteractive->count($condition, $start_time, $end_time);
		
		//导播推荐
		$condition_2  = $this->get_condition();
	//	$condition_2 .= " AND status = 1 ";
		$condition_2 .= " AND is_recommend = 1 ";
		
		$total_2 = $this->mInteractive->count($condition_2, $start_time, $end_time);
	
		$interactive_program = $ret_interactive_program;
		if ($this->settings['admin_type']['presenter'] == $this->user['group_type'])
		{
			$ret_presenter = $this->mInteractiveProgram->get_presenter_info($channel_id, $start_time, $this->user['user_id']);
			if (!$ret_presenter[0]['presenter_id'])
			{
				$interactive_program = array();
				$total_all['total'] = $total_2['total'] = 0;
			}
		}
		
		$this->addItem_withkey('total_2', $total_2['total']);
		$this->addItem_withkey('total_all', $total_all['total']);
		$this->addItem_withkey('interactive_program', $interactive_program);
		$this->output();
	}
	
	public function count()
	{
		$condition = $this->get_condition();
	//	$condition .= " AND status = 1 ";
		$condition .= " AND is_recommend = 1 ";
		$info = $this->mInteractive->count($condition);
		echo json_encode($info);
	}

	private function get_condition()
	{
		$condition = '';
		
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' AND content like \'%'.urldecode($this->input['k']).'%\'';
		}
		/*
		if (isset($this->input['id']) && $this->input['id'])
		{
			$condition .= " AND id IN (" . $this->input['id'] . ")";
		}
		*/
		if (isset($this->input['dates']) && $this->input['dates'])
		{
			$condition .= " AND dates = '" . trim(urldecode($this->input['dates'])) . "' ";
		}
		
		if (isset($this->input['channel_id']) && $this->input['channel_id'] && $this->input['channel_id'] != -1)
		{
			$condition .= " AND channel_id IN (" . $this->input['channel_id'] . ")";
		}
		
		if (isset($this->input['member_id']) && $this->input['member_id'])
		{
			$condition .= " AND member_id IN (" . $this->input['member_id'] . ")";
		}

		if (isset($this->input['member_name']) && $this->input['member_name'])
		{
			$condition .= " AND member_name = '" . trim($this->input['member_name']) . "' ";
		}
		
		if(isset($this->input['start_time']) && !empty($this->input['start_time']))
		{
			$start_time = strtotime(trim($this->input['start_time']));
			$condition .= " AND create_time >= '".$start_time."'";
		}
		
		if(isset($this->input['end_time']) && !empty($this->input['end_time']))
		{
			$end_time = strtotime(trim($this->input['end_time']));
			$condition .= " AND create_time <= '".$end_time."'";
		}
		
		if(isset($this->input['status']) && $this->input['status'] != -1)
		{
			$condition .= " AND status = " . intval($this->input['status']);
		}
		/*
		if(isset($this->input['type']) && $this->input['type'] != -1)
		{
			$condition .= " AND type = " . intval($this->input['type']);
		}
		*/
		if(isset($this->input['date_search']) && !empty($this->input['date_search']))
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date_search']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND create_time > '".$yesterday."' AND create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND create_time > '".$today."' AND create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND create_time > '".$last_threeday."' AND create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND create_time > '".$last_sevenday."' AND create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}
		return $condition;
	}
}

$out = new interactivePresenterApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>