<?php
/***************************************************************************
* $Id: interactive.php 17440 2013-02-21 01:27:51Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','interactive');
require('global.php');
class interactiveApi extends BaseFrm
{
	private $mInteractive;
	private $mInteractiveProgram;
//	private $mShare;
	private $mBasicInfo;
	public function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/interactive.class.php';
		$this->mInteractive = new interactive();
		
		require_once CUR_CONF_PATH . 'lib/interactive_program.class.php';
		$this->mInteractiveProgram = new interactiveProgram();
		
	//	require_once ROOT_PATH . 'lib/class/share.class.php';
	//	$this->mShare = new share();
		
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
		
		if ($this->settings['admin_type']['presenter'] == $this->user['group_type'])
		{
			$this->errorOutput('您没有权限访问此页面');
		}
		
		$channel_id = intval($this->input['channel_id']);
		if (!$channel_id)
		{
			$this->errorOutput('未传入频道id');
		}
		
		//获取频道信息
		$channel_info = $this->mInteractive->get_channel_info($channel_id);
			
		$this->addItem_withkey('channel', $channel_info);
		
		//获取主持人信息
		$admin_info = $this->mInteractiveProgram->get_admin($this->settings['admin_type']['presenter']);

		if (!empty($admin_info))
		{
			$presenter = array();
			foreach ($admin_info AS $v)
			{
				$presenter[$v['id']] = $v['user_name'];
			}
		}
		
		$this->addItem_withkey('presenter', $presenter);
		
		//根据日期选择节目单
		$dates 		= $this->input['dates'] ? urldecode($this->input['dates']) : date('Y-m-d');
		$start_end 	= urldecode($this->input['start_end']);
		$this->addItem_withkey('dates', $dates);
		
		$program_start_end = $this->mInteractiveProgram->get_program_start_end($channel_id, $dates, $start_end);
		$start_time = $program_start_end['start_time'];
		$end_time 	= $program_start_end['end_time'];
		$program 	= $program_start_end['program'];
		$start_end 	= $program_start_end['start_end'];
		
		$this->addItem_withkey('start_end', $start_end);
		$this->addItem_withkey('program', $program);
		
		//获取节目环节
		$ret_interactive_program = $this->mInteractiveProgram->show($channel_id, $dates, $start_time, $end_time);

		$this->addItem_withkey('interactive_program', $ret_interactive_program);
		
		//获取互动节目单
		$in_program = $this->mBasicInfo->get_program_by_time($channel_id, $dates, $start_time, $end_time);
		
	//	$this->addItem_withkey('in_program', $in_program);
		
		$in_program_id = $in_program[0]['id'];
		$this->addItem_withkey('in_program_id', $in_program_id);
		$this->addItem_withkey('program_id', $in_program[0]['program_id']);
		
		$condition	= $this->get_condition();
		//互动总数
		$total_all = $this->mInteractive->count($condition, $start_time, $end_time);
		$this->addItem_withkey('total_all', $total_all['total']);
		
		$offset 	= $this->input['offset'] ? $this->input['offset'] : 0;
		$count 		= $this->input['count'] ? intval($this->input['count']) : 20;
		$orderby 	= " ORDER BY id DESC ";
		
		if ($this->input['offset_flag'] == 1)
		{
			$count 	= intval($this->input['counts']);
		//	$offset = $total_all['total'] - $offset - $count;
		}
		
		$interactive_info = $this->mInteractive->get_interactive_info($condition, $offset, $count, $orderby, $start_time, $end_time);
		
		
		
		//互动推荐总数
		$condition_2  = $this->get_condition();
//		$condition_2 .= " AND status = 1 ";
		$condition_2 .= " AND is_recommend = 1 ";
		
		$total_2 = $this->mInteractive->count($condition_2, $start_time, $end_time);
		$this->addItem_withkey('total_2', $total_2['total']);
		/*
		//获取站外平台名称
		$ret_get_plat = $this->mShare->get_plat();
		if ($ret_get_plat)
		{
			$plat_info = array();
			foreach ($ret_get_plat AS $k => $v)
			{
				$plat_info[$v['id']] = $v['name'];
			}
		}
		*/
		if (!empty($interactive_info))
		{
			$interactive = array();
			foreach ($interactive_info AS $v)
			{
				$member_ids[$v['id']] 	= $v['member_id'];
				$v['channel_name'] 		= $channel_info['channel_name'];
				$v['plat_name'] 		= $v['plat_name'] ? $v['plat_name'] : $v['appname'];
				
				if (!$v['avatar_url'] && $v['host'])
				{
					$v['avatar_url'] = hg_material_link($v['host'], $v['dir'], $v['filepath'], $v['filename'], '36x36/');
				}
				else 
				{
					$v['avatar_url'] = $v['weibo_avatar'];
				}
				
				$interactive[$v['id']] 	= $v;
			//	$this->addItem($v);
			}
		}
		$this->addItem_withkey('interactive', $interactive);
		$this->output();
	}
	
	public function total()
	{
		$channel_id = intval($this->input['channel_id']);
		$start_end  = urldecode($this->input['start_end']);
		$dates = $this->input['dates'] ? urldecode($this->input['dates']) : date('Y-m-d', TIMENOW);
		if (!$channel_id)
		{
			$this->errorOutput('未传入频道id');
		}
		/*
		if (!$start_end)
		{
			$this->errorOutput('请选择时间段');
		}
		*/
		$program_start_end = $this->mInteractiveProgram->get_program_start_end($channel_id, $dates, $start_end);
		$start_time 	= $program_start_end['start_time'];
		$end_time 		= $program_start_end['end_time'];
		$start_end 		= $program_start_end['start_end'];
		
		//所有
		$condition	= $this->get_condition();
		
		$total_all = $this->mInteractive->count($condition, $start_time, $end_time);
		$this->addItem_withkey('total_all', $total_all['total']);
		
		//导播推荐
		$condition_2  = $this->get_condition();
		$condition_2 .= " AND is_recommend = 1 ";
		
		$total_2 = $this->mInteractive->count($condition_2, $start_time, $end_time);
		$this->addItem_withkey('total_2', $total_2['total']);
		
		$this->output();
	}
	
	public function count()
	{
		$condition = $this->get_condition();
		
		$channel_id 	= intval($this->input['channel_id']);
		$dates 			= $this->input['dates'] ? urldecode($this->input['dates']) : date('Y-m-d');
		$start_end 		= urldecode($this->input['start_end']);
		
		$ret = $this->mInteractiveProgram->get_program_start_end($channel_id, $dates, $start_end);
		
		$info = $this->mInteractive->count($condition, $ret['start_time'], $ret['end_time']);
		echo json_encode($info);
	}
	
	private function get_condition()
	{
		$condition = '';
		
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' AND content like \'%'.urldecode($this->input['k']).'%\'';
		}
		
		if (isset($this->input['id']) && $this->input['id'])
		{
			$condition .= " AND id IN (" . $this->input['id'] . ")";
		}
		
		if (isset($this->input['start']) && isset($this->input['end']) && $this->input['start'] && $this->input['end'] && $this->input['dates'] && $this->input['start_end'] != -1)
		{
			$condition .= " AND create_time >= " . strtotime(trim(urldecode($this->input['dates'])) . trim(urldecode($this->input['start']))) . " AND create_time <= " . strtotime(trim(urldecode($this->input['dates'])) . trim(urldecode($this->input['end'])));
		}
		
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

$out = new interactiveApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>