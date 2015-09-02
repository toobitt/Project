<?php
/***************************************************************************
* $Id: interactive_program.php 15347 2012-12-12 01:54:09Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','interactive_program');
require('global.php');
class interactiveProgramApi extends BaseFrm
{
	private $mInteractive;
	private $mInteractiveProgram;
	private $mAuth;
	public function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/interactive.class.php';
		$this->mInteractive = new interactive();
		
		require_once CUR_CONF_PATH . 'lib/interactive_program.class.php';
		$this->mInteractiveProgram = new interactiveProgram();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		$channel_id = intval($this->input['channel_id']);
		
		//获取频道信息
		if ($channel_id)
		{
			$channel_info = $this->mInteractive->get_channel_info($channel_id);
			
			$this->addItem_withkey('channel', $channel_info);
		}
		
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
		
		$presenter = $presenter ? $presenter : $this->settings['presenter'];
		
		$this->addItem_withkey('presenter', $presenter);
		//搜索日期
		$dates = $this->input['dates'] ? urldecode($this->input['dates']) : date('Y-m-d', TIMENOW);
		$this->addItem_withkey('dates', $dates);
		
		$channel_id = intval($this->input['channel_id']);
		if ($channel_id)
		{
			$ret_program = $this->mInteractiveProgram->get_program_by_channel_id($channel_id, $dates);
		}
//		hg_pre($ret_program);exit;
		$this->addItem_withkey('program', $ret_program);
		
		if (!empty($ret_program))
		{
			$start_time = $end_time = '';
			$zhibo = '';
			foreach ($ret_program AS $v)
			{
				if ($v['zhi_play'])
				{
					$start_time = $v['start_time'];
					$end_time = $v['start_time'] + $v['toff'];
					$zhibo = 1;
				}
			}
		}
		
		$start_end = urldecode($this->input['start_end']);
		if ($start_end)
		{
			$start2end = explode(',', $start_end);
			$start_time = strtotime($dates .' '.$start2end[0]);
			$end_time = strtotime($dates .' '.$start2end[1]);
		}
		else if(!$zhibo)
		{
			$start_time = $ret_program[0]['start_time'];
			$end_time = $ret_program[0]['start_time'] + $ret_program[0]['toff'];
			if ($dates != date('Y-m-d', TIMENOW))
			{
				$start_end = date('H:i:s', $start_time) . ',' . date('H:i:s', $end_time);
			}
		}
		
		$this->addItem_withkey('start_end', $start_end);
		
		//节目环节
		$ret_interactive_program = $this->mInteractiveProgram->show($channel_id, $dates, $start_time, $end_time);
//		hg_pre($ret_interactive_program);exit;
		if (!empty($ret_program))
		{
			foreach ($ret_interactive_program AS $program)
			{
				$this->addItem($program);
			}
		}
		$this->output();
	}
	
}

$out = new interactiveProgramApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>