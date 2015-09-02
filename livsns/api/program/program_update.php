<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_update.php 20671 2013-04-19 03:44:15Z lijiaying $
***************************************************************************/
define('ROOT_DIR', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_DIR . "global.php");
require(CUR_CONF_PATH."lib/functions.php");
define('MOD_UNIQUEID','program');
class programApi extends outerUpdateBase
{
	private $mProgram;
	function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/program.class.php';
		$this->mProgram = new program();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function create()
	{
		
	}
	
	public function update()
	{
		
	}
	
	public function delete()
	{
	
	}
	
	/**
	 * 串联单生成节目单
	 * $channel_id 频道id
	 * $dates 日期
	 * $theme 节目
	 * $start_time 开始时间
	 * $end_time 结束时间
	 * $schedule_id 串联单id
	 * Enter description here ...
	 */
	public function schedule2program()
	{
		$channel_id = intval($this->input['channel_id']);
		$dates		= trim($this->input['dates']);
		$theme		= explode(',|', $this->input['theme']);
		$start_time	= explode(',|', $this->input['start_time']);
		$end_time	= explode(',|', $this->input['end_time']);
		$schedule_id = explode(',|', $this->input['schedule_id']);
		
		if (!$channel_id)
		{
			$this->errorOutput('未传入频道id');
		}
		if (!$dates)
		{
			$this->errorOutput('未传入日期');
		}
		
		if (empty($start_time))
		{
			$this->errorOutput('开始时间不能为空');
		}
		
		if (empty($end_time))
		{
			$this->errorOutput('结束时间不能为空');
		}
		
		foreach ($start_time AS $k => $v)
		{
			if ($end_time[$k] <= $start_time[$k])
			{
				$this->errorOutput('结束时间不能小于开始时间');
			}
		}
		
		$weeks = date('W', strtotime($dates));
		
		$return = array();
		foreach ($start_time AS $k => $v)
		{
			$data = array(
				'channel_id'	 => $channel_id,
				'schedule_id'	 => $schedule_id[$k],
				'start_time'	 => $start_time[$k],
				'toff'			 => $end_time[$k] - $start_time[$k],
				'theme'			 => $theme[$k] ? $theme[$k] : '精彩节目',
				'type_id'		 => 1,
				'weeks'			 => $weeks,
				'dates'			 => $dates,
				'create_time'	 => TIMENOW,
				'update_time'	 => TIMENOW,
				'ip'			 => hg_getip(),
				'is_show'		 => 1,
			);
			
			$return[] = $this->mProgram->create($data);
		}
		$this->addItem($return);
		$this->output();
	}
	
	/**
	 * 删除节目单
	 * $channel_id 频道id
	 * $dates 日期
	 * Enter description here ...
	 */
	public function delete_by_channel_id()
	{
		$channel_id = intval($this->input['channel_id']);
		$dates		= trim($this->input['dates']);
	
		if (!$channel_id)
		{
			$this->errorOutput('未传入频道id');
		}
		if (!$dates)
		{
			$this->errorOutput('未传入日期');
		}
		
		$return = $this->mProgram->delete_by_channel_id($channel_id, $dates);
		
		$this->addItem($return);
		$this->output();
	}
	
}

$out = new programApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>