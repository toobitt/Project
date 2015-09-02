<?php
/***************************************************************************
* $Id: interactive_program_update.php 16559 2013-01-10 03:03:52Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','interactive_program');
require('global.php');
class interactiveProgramUpdateApi extends BaseFrm
{
	private $mInteractiveProgram;
	public function __construct()
	{
		parent::__construct();
		
		require_once CUR_CONF_PATH . 'lib/interactive_program.class.php';
		$this->mInteractiveProgram = new interactiveProgram();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function program_edit()
	{
		$id = $this->input['ids'];
		$channel_id = intval($this->input['channel_id']);
	//	$member_id = $this->input['member_id'];
		
		//互动节目单id
		$in_program_id 	= intval($this->input['in_program_id']);
		//频道节目单id
		$program_id 	= intval($this->input['program_id']);
		
		$dates = urldecode($this->input['dates']);
	
		$theme  = $this->input['theme'];
		$status = $this->input['status'];
	//	$flag  = $this->input['flag'];
		
		$start_end = urldecode($this->input['start_end']);
		
		if (!$channel_id)
		{
			$this->errorOutput('未传入频道id');
		}
	
		if (!$dates)
		{
			$this->errorOutput('日期不能为空');
		}
		
		if ($dates < date('Y-m-d', TIMENOW))
		{
			$this->errorOutput('只能添加今天及以后的节目');
		}
		
		if (!$start_end)
		{
			$this->errorOutput('请选择节目');
		}
		
		$start_end_array = explode(',', $start_end);
		$start_time 	 = strtotime($dates . ' ' . $start_end_array[0]);
		$end_time	 	 = strtotime($dates . ' ' . $start_end_array[1]);
		$toff			 = $end_time - $start_time;
		
		for ($i = 0; $i < count($theme); $i++)
		{
			if (!$theme[$i])
			{
				$this->errorOutput('节目环节不能为空');
			}
		}
		
		$program_info = array(
			'channel_id'	=> $channel_id,
			'program_id'	=> $program_id,
		//	'member_id' 	=> $member_id ? serialize($member_id) : '',
			'start_time'	=> $start_time,
			'toff'			=> $toff,
			'dates'			=> $dates,
		);
		
		$ret_program_info = $this->mInteractiveProgram->get_program_by_id($in_program_id);

		if (empty($ret_program_info))
		{
			$ret_in_program = $this->mInteractiveProgram->program_add($program_info);
		}
		else 
		{
			$ret_in_program = $this->mInteractiveProgram->program_edit($in_program_id, $program_info);
		}
		
		if (!$ret_in_program['id'])
		{
			$this->errorOutput('编辑节目失败');
		}

		//编辑节目单环节
		$ret_id = array();
		foreach ($theme AS $k => $v)
		{
			$add_input[$k] = array(
				'channel_id' 		=> $channel_id,
				'program_id' 		=> $ret_in_program['id'],
			//	'member_id' 		=> $member_id ? serialize($member_id) : '',
			//	'presenter_id'		=> serialize($presenter_id),
				'theme' 			=> $v,
				'start_time' 		=> $start_time,
				'toff' 				=> $toff,
				'dates' 			=> $dates,
				'update_time' 		=> TIMENOW,
				'status'			=> $status[$k],
			);

			if (!$id[$k])
			{
				$add_input[$k]['user_id'] 		= $this->user['user_id'];
				$add_input[$k]['user_name'] 	= $this->user['user_name'];
				$add_input[$k]['appid'] 		= $this->user['appid'];
				$add_input[$k]['appname'] 		= $this->user['display_name'];
				$add_input[$k]['create_time'] 	= TIMENOW;
				$add_input[$k]['ip'] 			= hg_getip();
				
				$ret_program[$k] = $this->mInteractiveProgram->create($add_input[$k]);
			}
			else
			{
				$ret_program[$k] = $this->mInteractiveProgram->update($add_input[$k], $id[$k]);
			}
			
			$ret_id[$k] = $ret_program[$k]['id'];
		}
		
		$ret = array(
			'ids'			=> $ret_id,
			'status'		=> $status,
			'in_program_id'	=> $ret_in_program['id'],
		);
		
		$this->addItem($ret);
		$this->output();
	}
	
	public function delete()
	{
		$id = $this->input['id'];
		if (!$id)
		{
			$this->errorOutput('未传入id');
		}
		$ret = $this->mInteractiveProgram->delete($id);
		if (!$ret)
		{
			$this->errorOutput('删除失败');
		}
		$this->addItem($ret);
		$this->output();
	}
	
	public function audit()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入id');
		}
		$ret = $this->mInteractiveProgram->audit($id, 'status');
		if (!$ret)
		{
			$this->errorOutput('操作失败');
		}
		$this->addItem($ret);
		$this->output();
	}
	
	/**
	 * 互斥审核
	 * Enter description here ...
	 */
	public function mutex_audit()
	{
		$id = intval($this->input['id']);
		$program_id = intval($this->input['in_program_id']);
		$ret_audit = $this->mInteractiveProgram->mutex_audit($id, $program_id);
		
		if (!$ret_audit)
		{
			$this->errorOutput('操作失败');
		}
		$ret = array(
			'id'			=> $id,
			'in_program_id'	=> $program_id,
		);
		$this->addItem($ret);
		$this->output();
	}
	
	function unknow()
	{
		$this->errorOutput('未实现的空方法');
	}
}

$out = new interactiveProgramUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>