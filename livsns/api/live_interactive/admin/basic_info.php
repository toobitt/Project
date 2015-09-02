<?php
/***************************************************************************
* $Id: basic_info.php 16563 2013-01-10 03:29:53Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','basic_info');
require('global.php');
class basicInfoApi extends BaseFrm
{
	private $mBasicInfo;
	private $mInteractiveProgram;
	private $mInteractive;
	public function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/basic_info.class.php';
		$this->mBasicInfo = new basicInfo();
		
		require_once CUR_CONF_PATH . 'lib/interactive_program.class.php';
		$this->mInteractiveProgram = new interactiveProgram();
		
		require_once CUR_CONF_PATH . 'lib/interactive.class.php';
		$this->mInteractive = new interactive();
		
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
		$ret_presenter_info = $this->mInteractiveProgram->get_admin($this->settings['admin_type']['presenter']);

		if (!empty($ret_presenter_info))
		{
			$presenter_info = array();
			foreach ($ret_presenter_info AS $v)
			{
				$presenter_info[$v['id']] = $v['user_name'];
			}
		}
		
		//获取导播信息
		$ret_director_info = $this->mInteractiveProgram->get_admin($this->settings['admin_type']['director']);

		if (!empty($ret_director_info))
		{
			$director_info = array();
			foreach ($ret_director_info AS $v)
			{
				$director_info[$v['id']] = $v['user_name'];
			}
		}
		
		$this->addItem_withkey('presenter_info', $presenter_info);
		$this->addItem_withkey('director_info', $director_info);
		
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
		
		//获取互动节目单
		$in_program = $this->mBasicInfo->get_program_by_time($channel_id, $dates, $start_time, $end_time);
		
		$this->addItem_withkey('in_program', $in_program);
		
		$in_program_id = $in_program[0]['id'];
		$this->addItem_withkey('in_program_id', $in_program_id);
		$this->addItem_withkey('program_id', $in_program[0]['program_id']);
		if ($in_program_id)
		{
			//导播
			$ret_director = $this->mBasicInfo->show($in_program_id, 'director', '', 'director_id');
			$director = array();
			if (!empty($ret_director))
			{
				foreach ($ret_director AS $v)
				{
					$director[] = $v['director_id'];
				}
			}
			$this->addItem_withkey('director', $director);
			
			//主持人
			$ret_presenter = $this->mBasicInfo->show($in_program_id, 'presenter', '', 'presenter_id');
			$presenter = array();
			if (!empty($ret_presenter))
			{
				foreach ($ret_presenter AS $v)
				{
					$presenter[] = $v['presenter_id'];
				}
			}
			$this->addItem_withkey('presenter', $presenter);
			
			//话题
			$topic = $this->mBasicInfo->show($in_program_id, 'topic', ' ORDER BY id ASC ');
			$this->addItem_withkey('topic', $topic);
			
			//现场嘉宾
			$site_guests = $this->mBasicInfo->show($in_program_id, 'site_guests', ' ORDER BY id ASC ');
			$this->addItem_withkey('site_guests', $site_guests);

			//场外嘉宾
			$otc_guests = $this->mBasicInfo->show($in_program_id, 'otc_guests', ' ORDER BY id ASC ');
			$this->addItem_withkey('otc_guests', $otc_guests);
		}
		
		//获取节目环节
		$interactive_program = $this->mInteractiveProgram->show($channel_id, $dates, $start_time, $end_time);

		$this->addItem_withkey('interactive_program', $interactive_program);
		
		$this->output();
	}
	
	
	private function get_condition()
	{
		$condition = '';
		return $condition;
	}
}

$out = new basicInfoApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>