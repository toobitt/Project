<?php
/***************************************************************************
* $Id: interactive.php 17440 2013-02-21 01:27:51Z lijiaying $
***************************************************************************/
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_PATH."global.php");
require(CUR_CONF_PATH."lib/functions.php");
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
		
//		require_once ROOT_PATH . 'lib/class/share.class.php';
//		$this->mShare = new share();
		
		require_once CUR_CONF_PATH . 'lib/basic_info.class.php';
		$this->mBasicInfo = new basicInfo();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$channel_id = intval($this->input['channel_id']);
		if (!$channel_id)
		{
			$this->errorOutput('未传入频道id');
		}
		
		//获取频道信息
		$channel_info = $this->mInteractive->get_channel_info($channel_id);
			
		//获取主持人信息
		$admin_info = $this->mInteractiveProgram->get_admin($this->settings['admin_type']['presenter']);

		if (!empty($admin_info))
		{
			$presenter = array();
			foreach ($admin_info AS $v)
			{
				$presenter[$v['id']]['user_name'] = $v['user_name'];
				$presenter[$v['id']]['avatar'] = $v['avatar'];
			}
		}
		
		//根据日期选择节目单
		$dates 		= $this->input['dates'] ? urldecode($this->input['dates']) : date('Y-m-d');
		$start_end 	= urldecode($this->input['start_end']);
		
		$program_start_end = $this->mInteractiveProgram->get_program_start_end($channel_id, $dates, $start_end);
		$start_time = $program_start_end['start_time'];
		$end_time 	= $program_start_end['end_time'];
		$program 	= $program_start_end['program'];
		$start_end 	= $program_start_end['start_end'];
		$theme 		= $program_start_end['theme'];
		
		if ($start_end) 
		{
			$start2end = explode(',', $start_end);
		}
		
		//获取节目环节
		$ret_interactive_program = $this->mInteractiveProgram->show($channel_id, $dates, $start_time, $end_time, ' AND status=1 ', 'theme');

		$condition	= $this->get_condition();
		$condition .= " AND is_shield = 0 ";
		//互动总数
		$total_all = $this->mInteractive->count($condition, $start_time, $end_time);

		$offset 	= $this->input['offset'] ? $this->input['offset'] : 0;
		$counts 	= $this->input['counts'] ? intval($this->input['counts']) : 20;
		$orderby 	= " ORDER BY i.id DESC ";
	//	$orderby 	= " ORDER BY recommend_time DESC ";
	//	$condition .= " AND is_recommend = 1 ";
		
		if (urldecode($this->input['interactive']) != 'interactive')
		{
			$interactive_info = $this->mInteractive->get_interactive_info($condition, $offset, $counts, $orderby, $start_time, $end_time);
		}
		
		//互动推荐总数
		$condition_2  = $this->get_condition();
		$condition_2 .= " AND is_shield = 0 ";
//		$condition_2 .= " AND status = 1 ";
		$condition_2 .= " AND is_recommend = 1 ";
		
		$total_2 = $this->mInteractive->count($condition_2, $start_time, $end_time);
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
			//	$v['channel_name'] 		= $channel_info['channel_name'];
				$v['plat_name'] 		= $v['plat_name'] ? $v['plat_name'] : $v['appname'];
				
				if (!$v['avatar_url'] && $v['host'])
				{
					$v['avatar_url'] = hg_material_link($v['host'], $v['dir'], $v['filepath'], $v['filename'], '36x36/');
				}
				else 
				{
					$v['avatar_url'] = $v['weibo_avatar'];
				}
				
				$interactive[] 	= $v;
			}
		}
		
		//频道信息
		$channel_info['current_program'] = array(
			'theme'			=> $theme,
			'start_time'	=> $start2end[0],
			'end_time'		=> $start2end[1],
			'toff'			=> $end_time - $start_time,
		);
		
		//本期节目环节信息
		$interactive_program = array();
		if (!empty($ret_interactive_program))
		{
			foreach ($ret_interactive_program AS $v)
			{
				$interactive_program[] = $v['theme'];
			}
		}

		$channel_info['interactive_program'] = $interactive_program;
		
		//节目单
		$channel_info['program'] = $program;
		
		//获取互动节目单
		$in_program = $this->mBasicInfo->get_program_by_time($channel_id, $dates, $start_time, $end_time, '', 'id');
		
		$program_id = $in_program[0]['id'];
		
		if ($program_id)
		{
			//主持人
			$ret_presenter = $this->mBasicInfo->show($program_id, 'presenter', '', 'presenter_id');
			$presenter_info = array();
			if (!empty($ret_presenter))
			{
				foreach ($ret_presenter AS $v)
				{
					$presenter_info[] = $presenter[$v['presenter_id']];
				}
			}
			//话题
			$topic = $this->mBasicInfo->show($program_id, 'topic', ' ORDER BY id ASC ');
			
			//现场嘉宾
			$site_guests = $this->mBasicInfo->show($program_id, 'site_guests', ' ORDER BY id ASC ');

			//场外嘉宾
			$otc_guests = $this->mBasicInfo->show($program_id, 'otc_guests', ' ORDER BY id ASC ');
		}

		$program_info = array(
			'topic'				=> $topic,
			'site_guests'		=> $site_guests,
			'otc_guests'		=> $otc_guests,
		);
		$this->addItem_withkey('channel', $channel_info);
		$this->addItem_withkey('presenter', $presenter_info);
		$this->addItem_withkey('program_info', $program_info);
		$this->addItem_withkey('total_all', $total_all['total']);
		$this->addItem_withkey('total_2', $total_2['total']);
		$this->addItem_withkey('interactive', $interactive);
		$this->output();
	}
	
	/**
	 * 分页调用
	 * Enter description here ...
	 */
	public function get_interactive_info()
	{
		$channel_id = intval($this->input['channel_id']);
		$id			= intval($this->input['id']);
		
		if (!$channel_id)
		{
			$this->errorOutput('未传入频道id');
		}
		
		//根据日期选择节目单
		$dates 		= $this->input['dates'] ? urldecode($this->input['dates']) : date('Y-m-d');
		$start_end 	= urldecode($this->input['start_end']);
		if (!$start_end)
		{
			$this->errorOutput('未传入节目单时间段');
		}
		
		$start2end 	= explode(',', $start_end);
		$start_time = strtotime($dates . ' ' . $start2end[0]);
		$end_time 	= strtotime($dates . ' '. $start2end[1]);
		
		$condition	= $this->get_condition();
		$condition .= " AND is_shield = 0 ";
		
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
		//互动总数
		$total_all = $this->mInteractive->count($condition, $start_time, $end_time);

		$offset 	= $this->input['offset'] ? $this->input['offset'] : 0;
		$counts 	= $this->input['counts'] ? intval($this->input['counts']) : 20;
		$orderby 	= " ORDER BY id DESC ";
	//	$orderby 	= " ORDER BY recommend_time DESC ";
	//	$condition .= " AND is_recommend = 1 ";
		
		$interactive_info = $this->mInteractive->get_interactive_info($condition, $offset, $counts, $orderby, $start_time, $end_time);
		
		if (!empty($interactive_info))
		{
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
				
				$this->addItem($v);
			}
		}
		$this->output();
	}
	
	/**
	 * 获取节目环节
	 * Enter description here ...
	 */
	public function get_interactive_program()
	{
		$channel_id = intval($this->input['channel_id']);
		if (!$channel_id)
		{
			$this->errorOutput('未传入频道id');
		}
		
		//根据日期选择节目单
		$dates 		= $this->input['dates'] ? urldecode($this->input['dates']) : date('Y-m-d');
		$start_end 	= urldecode($this->input['start_end']);
		if (!$start_end)
		{
			$this->errorOutput('未传入节目单时间段');
		}
		
		$start2end 	= explode(',', $start_end);
		$start_time = strtotime($dates . ' ' . $start2end[0]);
		$end_time 	= strtotime($dates . ' '. $start2end[1]);
		//获取节目环节
		$ret_interactive_program = $this->mInteractiveProgram->show($channel_id, $dates, $start_time, $end_time, ' AND status=1 ', 'theme');
		
		//本期节目环节信息
		$interactive_program = array();
		if (!empty($ret_interactive_program))
		{
			foreach ($ret_interactive_program AS $v)
			{
				$interactive_program[] = $v['theme'];
			}
		}
		
		$this->addItem($interactive_program);
		$this->output();
	}
	
	public function count()
	{
		
	}

	private function get_condition()
	{
		$condition = "";
		
		if (isset($this->input['channel_id']) && $this->input['channel_id'])
		{
			$condition .= " AND channel_id = " . intval($this->input['channel_id']);
		}
		return $condition;
	}
	
	function unknow()
	{
		$this->errorOutput('未实现的空方法');
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