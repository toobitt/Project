<?php
/***************************************************************************
* $Id: schedule_update.php 40126 2014-09-11 02:55:50Z develop_tong $
***************************************************************************/

define('MOD_UNIQUEID','schedule');
//define('DEBUG_MODE','schedule');
require('global.php');
class channelUpdateApi extends adminUpdateBase
{
	private $mLive;
	private $mSchedule;
	private $mLivemms;
	private $mLivMedia;
	private $mSourceType;
	private $mFileToff;
	private $mUrl;
	private $mPicture;
	private $mMediaserver;
	function __construct()
	{
		parent::__construct();
		require_once ROOT_PATH . 'lib/class/live.class.php';
		$this->mLive = new live();
		
		require_once ROOT_PATH . 'lib/class/livmedia.class.php';
		$this->mLivMedia = new livmedia();
		
		require_once CUR_CONF_PATH . 'lib/schedule.class.php';
		$this->mSchedule = new schedule();
		
		require_once CUR_CONF_PATH . 'lib/livemms.class.php';
		$this->mLivemms = new livemms();
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
		$id = trim($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入id');
		}
		
		//频道信息
		$channel_id = intval($this->input['channel_id']);
		if (!$channel_id)
		{
			$this->errorOutput('未传入频道id');
		}
		
		//#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		$nodes = array(
			'org_id' => $this->user['org_id'],
		);
		if($channel_id)
		{
			$nodes['nodes']['channel_node'][$channel_id] = $channel_id;
		}
		else
		{
			$nodes = array('nodes'=>array('channel_node'=>array()));
		}
		$this->verify_content_prms($nodes);
		//#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		
		$channel_data = array(
			'id'		=> $channel_id,
			'is_stream'	=> 1,
			'is_server'	=> 1,
			'field'		=> ' id, name, code, server_id, main_stream_name, is_mobile_phone, application_id, is_control ',
		);
		
		$channel_info = $this->mLive->getChannelInfoById($channel_data);
		$channel_info = $channel_info[0];
		if (empty($channel_info))
		{
			$this->errorOutput('该频道不存在或已被删除');
		}
	
		if (!$channel_info['is_control'])
		{
			$this->errorOutput('该频道不支持串联单，请到频道设置允许播控');
		}
		$server_info = $this->settings['server_info'];
		if (!$server_info['host'])
		{
			$this->errorOutput('直播服务器信息不存在或已被删除');
		}
		
		$host 		= $server_info['host'];
		$input_dir 	= $server_info['input_dir'];
		$output_dir = $server_info['output_dir'];
		

		$chanel_schedule_server = $this->db->query_first('SELECT * FROM ' . DB_PREFIX . 'channel_server WHERE channel_id=' . $channel_id);
		$output_id = $chanel_schedule_server['output_id'];
		$stream_id = $chanel_schedule_server['stream_id'];
		$application_data = array(
			'action'	=> 'select',
			'id'		=> $stream_id,
		);
		$ret_select = $this->mLivemms->inputStreamOperate($host, $input_dir, $application_data);
	
		$inputenabled = $ret_select['input']['enable'];

		$application_data = array(
			'action'	=> 'select',
			'id'		=> $output_id,
		);
		$ret_select = $this->mLivemms->inputOutputStreamOperate($host, $input_dir, $application_data);
		$outenabled = $ret_select['output']['enable'];

		if (!$outenabled || !$inputenabled)
		{
			$this->errorOutput('媒体服务器未启动');
		}
		
		
		//串联单
		$schedule = $this->mSchedule->get_schedule_info_by_id($id);
		
		if (empty($schedule))
		{
			$this->errorOutput('该串联单不存在或已被删除');
		}
		
		foreach ($schedule AS $k => $v)
		{
			if ($v['schedule_id'])
			{
				$schedule_data = array(
					'action'	=> 'delete',
					'id'		=> $v['schedule_id'],
				);
				
				$ret_schedule = $this->mLivemms->inputScheduleOperate($host, $input_dir, $schedule_data);
				
				if (!$ret_schedule['result'])
				{
					continue;
				}
			}
			
			if ($v['file_id'])
			{
				$file_data = array(
					'action'	=> 'delete',
					'id'		=> $v['file_id'],
				);
				
				$ret_file = $this->mLivemms->inputFileOperate($host, $input_dir, $file_data);
				
				if (!$ret_file['result'])
				{
					continue;
				}
			}
			/*
			if ($v['source_id'] && $v['type'] != 1)
			{
				$list_data = array(
					'action'	=> 'delete',
					'id'		=> $v['source_id'],
				);
				
				$ret_list = $this->mLivemms->inputListOperate($host, $input_dir, $list_data);
				
				if (!$ret_list['result'])
				{
					continue;
				}
			}
			*/
		}
		
		$ret = $this->mSchedule->delete($id);
		if (!$ret)
		{
			$this->errorOutput('删除失败');
		}
		
		$this->addItem($id);
		$this->output();
	}
	
	public function sort()
	{
		
	}
	
	public function audit()
	{
		
	}
	
	public function publish()
	{
		
	}
	
	public function edit_new()
	{
		//频道信息
		$channel_id 		= intval($this->input['channel_id']);
		$id 				= $this->input['ids'] ? $this->input['ids'] : array();
		$start_time 		= $this->input['start_time'] ? $this->input['start_time'] : array();
		$end_time 			= $this->input['end_time'] ? $this->input['end_time'] : array();
		$change2_id 		= $this->input['change2_id'] ? $this->input['change2_id'] : array();
		$change2_name 		= $this->input['change2_name'] ? $this->input['change2_name'] : array();
		$type 				= $this->input['type'] ? $this->input['type'] : array();
	//	$schedule_id 		= $this->input['schedule_id'] ? $this->input['schedule_id'] : array();
	//	$file_id	 		= $this->input['file_id'] ? $this->input['file_id'] : array();
		$start_time_shift 	= $this->input['start_time_shift'] ? $this->input['start_time_shift'] : array();
		$start_time_num 	= $this->input['start_time_num'] ? $this->input['start_time_num'] : array();
		$end_time_num 		= $this->input['end_time_num'] ? $this->input['end_time_num'] : array();
		
		$dates = $this->input['dates'] ? trim($this->input['dates']) : date('Y-m-d');
		
		//标记串联单是否锁定 1-是 0-否
		$is_locked	= $this->input['is_locked'] ? $this->input['is_locked'] : array();
		
		if (!$channel_id)
		{
			$this->errorOutput('未传入频道id');
		}
		
		$channel_data = array(
			'id'		=> $channel_id,
			'is_stream'	=> 1,
			'is_server'	=> 1,
			'field'		=> ' id, name, code, server_id, main_stream_name, is_mobile_phone, application_id, is_control, node_id ',
		);
		
		$channel_info = $this->mLive->getChannelInfoById($channel_data);
		$channel_info = $channel_info[0];
		if (empty($channel_info))
		{
			$this->errorOutput('该频道不存在或已被删除');
		}
	
		if (!$channel_info['is_control'])
		{
			$this->errorOutput('该频道不支持串联单，请到频道设置允许播控');
		}
		
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		$nodes = array();
		$all_node = $this->mLive->getFatherNodeByid($channel_info['node_id']);
		$nodes['_action'] = 'edit';
		$nodes['nodes'][$channel_info['node_id']] = $all_node ? implode(',',$all_node) : '';
		$this->verify_content_prms($nodes);
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		
		//串联单信息
		$condition  = " AND channel_id = " . $channel_id;
		$condition .= " AND dates = '" . $dates . "'";
		$schedule_info = $this->mSchedule->show($condition);
		
		$_id = $delete_id = $edit_id = $delete_info = $schedule_id = $file_id = array();
		if (!empty($schedule_info))
		{
			foreach ($schedule_info AS $v)
			{
				//权限组织内、用户
				if($this->user['group_type'] > MAX_ADMIN_TYPE)
				{
					switch($this->user['prms']['default_setting']['manage_other_data'])
					{
						case 1://组织内，修改者和作者是否在同一组织
						if($this->user['org_id'] != $v['org_id'])
						{
							$this->errorOutput(NO_PRIVILEGE);
						}
						break;
						case 5://全部
						break;
						case 0://只能自己修改
						if($this->user['user_id'] != $v['user_id'])
						{
							$this->errorOutput(NO_PRIVILEGE);
						}
						break;
						default:
						break;
					}			
				}
				
				$_id[] = $v['id'];
				foreach ($id AS $vv)
				{
					if ($vv == $v['id'])
					{
						$edit_id[] 		= $v['id'];	
						$file_id[] 		= $v['file_id'];
						$schedule_id[] 	= $v['schedule_id'];
					}
				}
			}
		
			//分析出要删除的串联单id
			$delete_id = @array_diff($_id, $edit_id);
			
			foreach ($schedule_info AS $v)
			{
				foreach ($delete_id AS $vv)
				{
					if ($vv = $v['id'])
					{
						$delete_info[$v['id']] = $v;
					}
				}
			}
		}
		
		
		
		$this->mMediaserver = new curl($this->settings['App_mediaserver']['host'], $this->settings['App_mediaserver']['dir'] . 'admin/');
		
		$this->mMediaserver->setReturnFormat('str');
		$this->mMediaserver->setSubmitType('post');
		$this->mMediaserver->initPostData();
		$this->mMediaserver->addRequestData('a','vod2livets');
		$count = count($start_time);
		for ($i = 0; $i < $count; $i ++)
		{
			if (!$start_time[$i])
			{
				$this->errorOutput('开始时间不能为空');
			}
			
			if (!$end_time[$i])
			{
				$this->errorOutput('结束时间不能为空');
			}

			if ($end_time[$i] <= $start_time[$i] || $end_time_num[$i] <= $start_time_num[$i])
			{
				$this->errorOutput('结束时间不能小于开始时间');
			}
			
			if (!$change2_id[$i])
			{
				$this->errorOutput('所选文件、频道、时移 id不能为空');
			}
			
			if (!$change2_name[$i])
			{
				$this->errorOutput('名称不能为空');
			}
			
			if (!$type[$i])
			{
				$this->errorOutput('串联单类型不能为空');
			}
			
			if ($type[$i] == 3 && !$start_time_shift[$i])
			{
				$this->errorOutput('时移开始时间不能为空');
			}
		}
		
		$set_data = array(
			'change2_id' => $change2_id,
			'type' 		 => $type,
		);

		$this->setSourceInfo($set_data);
		
		$outputId = $channel_info['channel_stream'][0]['change_id'];
		
		//下一天时间戳
		$next_time = strtotime($dates) + 86400;
		
		$ret_id = $up_data = array();
		foreach ($start_time_num AS $k => $v)
		{
		//	$_start_time = strtotime($dates . ' ' . $start_time[$k]);
		//	$_end_time 	 = strtotime($dates . ' ' . $end_time[$k]);
		//	$toff = $_end_time - $_start_time;
			
			//计算时长
			$_start_time = strtotime($dates) + $start_time_num[$k];
			$toff 		 = $end_time_num[$k] - $start_time_num[$k];
			
			if ($_start_time < $next_time)
			{
				if (($_start_time + $toff) >= $next_time)
				{
					$toff = $next_time - $_start_time;
				}
			}
			$is_run = 0;
			
			$data = array(
				'order_id'			=> $k,
				'channel_id'		=> $channel_id,
				'change2_id'		=> $change2_id[$k],
				'change2_name'		=> $change2_name[$k],
				'type'				=> $type[$k],
				'change_id'			=> $outputId,
		//		'source_id'			=> $sourceId,
				'source_type'		=> $this->mSourceType[$k],
		//		'schedule_id'		=> $ret_schedule_id[$k],
		//		'file_id'			=> $file_id,
				'file_toff'			=> $this->mFileToff[$k],
				'url'				=> $this->mUrl[$k],
				'start_time'		=> $_start_time,
				'toff'				=> $toff,
				'dates'				=> $dates,
				'start_time_shift'	=> $start_time_shift[$k],
				'update_time'		=> TIMENOW,
				'picture'			=> $this->mPicture[$k],
				'is_locked'			=> $is_locked[$k],
				'start_time_num'	=> $start_time_num[$k],
				'end_time_num'		=> $end_time_num[$k],
				'is_run'			=> $is_run,
			);
			
			$ret_id[$k] = 0;
			
			if (!$id[$k]) //create
			{
				$data['org_id']			= $this->user['org_id'];
				$data['user_id']		= $this->user['user_id'];
				$data['user_name']		= $this->user['user_name'];
				$data['appid']			= $this->user['appid'];
				$data['appname']		= $this->user['display_name'];
				$data['appname']		= $this->user['display_name'];
				$data['create_time']	= TIMENOW;
				$data['ip']				= hg_getip();
				$ret = $this->mSchedule->create($data);
				
				if (!$ret['id'])
				{
					continue;
				}
				
				$ret_id[$k] = $ret['id'];
			}
			else //update
			{
				$data['id']				= $id[$k];
				$data['is_run'] 		= 0;
				$data['is_success'] 	= 0;
				$data['is_file_delete'] = 0;
				
				$ret = $this->mSchedule->update($data);
				
				if (!$ret['id'])
				{
					continue;
				}
				
				$ret_id[$k] = $ret['id'];
			}
			$up_data[$k] = $data;
		}
		
		if (!empty($delete_id))
		{
			$delete_id = implode(',', $delete_id);
			$ret_delete = $this->mSchedule->delete($delete_id);
		}
		//$this->build_ts($channel_id, $dates);
		//记录日志
		$pre_data = $schedule_info;
		
		$this->addLogs('编辑串联单', $pre_data, $up_data, $channel_info['name'], $channel_info['id']);
		
		$return = array(
			'id' => $ret_id,
		);
		
		$this->addItem($return);
		$this->output();
	}

	public function edit()
	{
		//频道信息
		$channel_id 		= intval($this->input['channel_id']);
		$id 				= $this->input['ids'] ? $this->input['ids'] : array();
		$start_time 		= $this->input['start_time'] ? $this->input['start_time'] : array();
		$end_time 			= $this->input['end_time'] ? $this->input['end_time'] : array();
		$change2_id 		= $this->input['change2_id'] ? $this->input['change2_id'] : array();
		$change2_name 		= $this->input['change2_name'] ? $this->input['change2_name'] : array();
		$type 				= $this->input['type'] ? $this->input['type'] : array();
	//	$schedule_id 		= $this->input['schedule_id'] ? $this->input['schedule_id'] : array();
	//	$file_id	 		= $this->input['file_id'] ? $this->input['file_id'] : array();
		$start_time_shift 	= $this->input['start_time_shift'] ? $this->input['start_time_shift'] : array();
		$start_time_num 	= $this->input['start_time_num'] ? $this->input['start_time_num'] : array();
		$end_time_num 		= $this->input['end_time_num'] ? $this->input['end_time_num'] : array();
		
		$dates = $this->input['dates'] ? trim($this->input['dates']) : date('Y-m-d');
		
		//标记串联单是否锁定 1-是 0-否
		$is_locked	= $this->input['is_locked'] ? $this->input['is_locked'] : array();
		
		if (!$channel_id)
		{
			$this->errorOutput('未传入频道id');
		}
		
		
		$channel_data = array(
			'id'		=> $channel_id,
			'is_stream'	=> 1,
			'is_server'	=> 1,
			'field'		=> ' id, name, code, server_id, main_stream_name, is_mobile_phone, application_id, is_control, node_id ',
		);
		$channel_info = $this->mLive->getChannelInfoById($channel_data);
		$channel_info = $channel_info[0];
		if (!$channel_info['is_control'])
		{
			$this->errorOutput('该频道不支持串联单，请到频道设置允许播控');
		}
		$server_info = $this->settings['server_info'];		
		if (!$server_info['host'])
		{
			$this->errorOutput('串联单服务器信息不存在或已被删除');
		}
		
		if (empty($channel_info))
		{
			$this->errorOutput('该频道不存在或已被删除');
		}
	
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		$nodes = array();
		$all_node = $this->mLive->getFatherNodeByid($channel_info['node_id']);
		$nodes['_action'] = 'edit';
		$nodes['nodes'][$channel_info['node_id']] = $all_node ? implode(',',$all_node) : '';
		$this->verify_content_prms($nodes);
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		
		//串联单信息
		$condition  = " AND channel_id = " . $channel_id;
		$condition .= " AND dates = '" . $dates . "'";
		$schedule_info = $this->mSchedule->show($condition);
		
		$_id = $delete_id = $edit_id = $delete_info = $schedule_id = $file_id = array();
		if (!empty($schedule_info))
		{
			foreach ($schedule_info AS $v)
			{
				//权限组织内、用户
				if($this->user['group_type'] > MAX_ADMIN_TYPE)
				{
					switch($this->user['prms']['default_setting']['manage_other_data'])
					{
						case 1://组织内，修改者和作者是否在同一组织
						if($this->user['org_id'] != $v['org_id'])
						{
							$this->errorOutput(NO_PRIVILEGE);
						}
						break;
						case 5://全部
						break;
						case 0://只能自己修改
						if($this->user['user_id'] != $v['user_id'])
						{
							$this->errorOutput(NO_PRIVILEGE);
						}
						break;
						default:
						break;
					}			
				}
				
				$_id[] = $v['id'];
				foreach ($id AS $vv)
				{
					if ($vv == $v['id'])
					{
						$edit_id[] 		= $v['id'];	
						$file_id[] 		= $v['file_id'];
						$schedule_id[] 	= $v['schedule_id'];
					}
				}
			}
		
			//分析出要删除的串联单id
			$delete_id = @array_diff($_id, $edit_id);
			
			foreach ($schedule_info AS $v)
			{
				foreach ($delete_id AS $vv)
				{
					if ($vv == $v['id'])
					{
						$delete_info[$v['id']] = $v;
					}
				}
			}
		}
		
		$host 		= $server_info['host'];
		$input_dir 	= $server_info['input_dir'];
		$chanel_schedule_server = $this->db->query_first('SELECT * FROM ' . DB_PREFIX . 'channel_server WHERE channel_id=' . $channel_id);
		$output_id = $chanel_schedule_server['output_id'];
		$stream_id = $chanel_schedule_server['stream_id'];
		if (!$output_id)
		{
			$application_data = array(
				'action'	=> 'insert',
				'url'		=> $channel_info['channel_stream'][0]['url'],
			);
			$inputstream = $this->mLivemms->inputStreamOperate($host, $input_dir, $application_data);
			if ($inputstream['result'] && $inputstream['input']['id'])
			{
				$stream_id = $inputstream['input']['id'];
				$application_data = array(
					'action'	=> 'insert',
					'sourceId'		=> $stream_id,
					'sourceType'		=> 1,
				);
				$inputoutput = $this->mLivemms->inputOutputStreamOperate($host, $input_dir, $application_data);
				if ($inputoutput['result'] && $inputoutput['output']['id'])
				{
					$output_id = $inputoutput['output']['id'];
					$sql = 'INSERT INTO ' . DB_PREFIX . 'channel_server (channel_id, output_id, stream_id) VALUES (' . $channel_id . ', ' . $output_id . ', ' . $stream_id . ')';
					$this->db->query($sql);
				}
			}
		}
		
		$application_data = array(
			'action'	=> 'select',
			'id'		=> $stream_id,
		);
		$ret_select = $this->mLivemms->inputStreamOperate($host, $input_dir, $application_data);
		$inputenabled = $ret_select['input']['enable'];
		if (!$inputenabled)
		{
			$application_data = array(
				'action'	=> 'start',
				'id'		=> $stream_id,
			);
			$ret_select = $this->mLivemms->inputStreamOperate($host, $input_dir, $application_data);
			if ($ret_select['result'])
			{
				$inputenabled = 1;
			}
		}
		$application_data = array(
			'action'	=> 'select',
			'id'		=> $output_id,
		);
		$ret_select = $this->mLivemms->inputOutputStreamOperate($host, $input_dir, $application_data);
		$outenabled = $ret_select['output']['enable'];
		if (!$outenabled)
		{
			$application_data = array(
				'action'	=> 'start',
				'id'		=> $output_id,
			);
			$ret_select = $this->mLivemms->inputOutputStreamOperate($host, $input_dir, $application_data);
			if ($ret_select['result'])
			{
				$outenabled = 1;
			}
		}
		if (!$outenabled || !$inputenabled)
		{
			$this->errorOutput('媒体服务器未启动');
		}
		
		$count = count($start_time);
		for ($i = 0; $i < $count; $i ++)
		{
			if (!$start_time[$i])
			{
				$this->errorOutput('开始时间不能为空');
			}
			
			if (!$end_time[$i])
			{
				$this->errorOutput('结束时间不能为空');
			}

			if ($end_time[$i] <= $start_time[$i] || $end_time_num[$i] <= $start_time_num[$i])
			{
				$this->errorOutput('结束时间不能小于开始时间');
			}
			
			if (!$change2_id[$i])
			{
				$this->errorOutput('所选文件、频道、时移 id不能为空');
			}
			
			if (!$change2_name[$i])
			{
				$this->errorOutput('名称不能为空');
			}
			
			if (!$type[$i])
			{
				$this->errorOutput('串联单类型不能为空');
			}
			
			if ($type[$i] == 3 && !$start_time_shift[$i])
			{
				$this->errorOutput('时移开始时间不能为空');
			}
		}
		
		
		$set_data = array(
			'change2_id' => $change2_id,
			'type' 		 => $type,
		);
		$this->setSourceInfo($set_data);
		
		$outputId = $channel_info['channel_stream'][0]['change_id'];
		
		//下一天时间戳
		$next_time = strtotime($dates) + 86400;
		
		$ret_id = $up_data = array();
		foreach ($start_time_num AS $k => $v)
		{
		//	$_start_time = strtotime($dates . ' ' . $start_time[$k]);
		//	$_end_time 	 = strtotime($dates . ' ' . $end_time[$k]);
		//	$toff = $_end_time - $_start_time;
			
			//计算时长
			$_start_time = strtotime($dates) + $start_time_num[$k];
			$toff 		 = $end_time_num[$k] - $start_time_num[$k];
			
			if ($_start_time < $next_time)
			{
				if (($_start_time + $toff) >= $next_time)
				{
					$toff = $next_time - $_start_time;
				}
			}
			$liveurl = explode('?', $this->mUrl[$k]);
			$liveurl = $liveurl[0];
			$data = array(
				'order_id'			=> $k,
				'channel_id'		=> $channel_id,
				'change2_id'		=> $change2_id[$k],
				'change2_name'		=> $change2_name[$k],
				'type'				=> $type[$k],
				'change_id'			=> $outputId,
		//		'source_id'			=> $sourceId,
				'source_type'		=> $this->mSourceType[$k],
		//		'schedule_id'		=> $ret_schedule_id[$k],
		//		'file_id'			=> $file_id,
				'file_toff'			=> $this->mFileToff[$k],
				'url'				=> $liveurl,
				'start_time'		=> $_start_time,
				'toff'				=> $toff,
				'dates'				=> $dates,
				'start_time_shift'	=> $start_time_shift[$k],
				'update_time'		=> TIMENOW,
				'picture'			=> $this->mPicture[$k],
				'is_locked'			=> $is_locked[$k],
				'start_time_num'	=> $start_time_num[$k],
				'end_time_num'		=> $end_time_num[$k],
			);
			
			$ret_id[$k] = 0;
			
			if (!$id[$k]) //create
			{
				$data['org_id']			= $this->user['org_id'];
				$data['user_id']		= $this->user['user_id'];
				$data['user_name']		= $this->user['user_name'];
				$data['appid']			= $this->user['appid'];
				$data['appname']		= $this->user['display_name'];
				$data['appname']		= $this->user['display_name'];
				$data['create_time']	= TIMENOW;
				$data['ip']				= hg_getip();
				$data['is_run']				= 0;
				
				$ret = $this->mSchedule->create($data);
				if ($type[$k] == 2)
				{
					//创建文件流
					$callback = $this->settings['App_schedule']['protocol'] . $this->settings['App_schedule']['host'] . '/' . $this->settings['App_schedule']['dir'] . 'admin/callback.php?a=backup_callback&id=' . $ret['id'] . '&appid=' . intval($this->input['appid']) . '&appkey=' . trim($this->input['appkey']);

					$file_data = array(
						'action'	=> 'insert',
						'url'		=> $this->mUrl[$k],
						'callback'	=> urlencode($callback),
					);
					
					$ret_file = $this->mLivemms->inputFileOperate($host, $input_dir, $file_data);
					if ($ret_file['result'] && $ret_file['file']['id'])
					{
						$data['file_id'] = $ret_file['file']['id'];
						$data1 = array(
							'id' => $ret['id'],
							'file_id' => $ret_file['file']['id'],
						);
						$this->mSchedule->update($data1);
					}
				}
				
				if (!$ret['id'])
				{
					continue;
				}
				$ret_id[$k] = $ret['id'];
			}
			else //update
			{
				$data['id']				= $id[$k];
				$ret = $this->mSchedule->update($data);
				
				if (!$ret['id'])
				{
					continue;
				}
				
				$ret_id[$k] = $ret['id'];
			}
			$up_data[$k] = $data;
		}
		foreach ($delete_info AS $k => $v)
		{
			/*
			if ($v['file_id'])
			{
				$application_data = array(
					'action'	=> 'delete',
					'id'		=> $v['file_id'],
				);
				$inputstream = $this->mLivemms->inputFileOperate($host, $input_dir, $application_data);
				if (!$inputstream['result'])
				{
					$this->errorOutput($v['change2_name'] . '正在播放，无法更改');
				}
			}
			*/
			if ($v['schedule_id'])
			{
				$application_data = array(
					'action'	=> 'delete',
					'id'		=> $v['schedule_id'],
				);
				$inputstream = $this->mLivemms->inputScheduleOperate($host, $input_dir, $application_data);
				if (!$inputstream['result'])
				{
					$this->errorOutput($v['change2_name'] . '正在播放，无法更改');
				}
			}
			if ($v['input_id'])
			{
				$application_data = array(
					'action'	=> 'delete',
					'id'		=> $v['input_id'],
				);
				$inputstream = $this->mLivemms->inputStreamOperate($host, $input_dir, $application_data);
				if (!$inputstream['result'])
				{
					$this->errorOutput($v['change2_name'] . '正在播放，无法更改');
				}
			}
		}
		
		$delete_id = implode(',', $delete_id);
		if($delete_id)
		{
			$ret_delete = $this->mSchedule->delete($delete_id);
		}
		//记录日志
		$pre_data = $schedule_info;
		
		$this->addLogs('编辑串联单', $pre_data, $up_data, $channel_info['name'], $channel_info['id']);
		
		$return = array(
			'id' => $ret_id,
		);
		
		$this->addItem($return);
		$this->output();
	}
	
	/**
	 * 串联单生成节目单
	 * $channel_id 频道id
	 * $dates 日期
	 * $theme 节目
	 * $id 串联单id
	 * Enter description here ...
	 */
	public function schedule2program()
	{
		$channel_id  = intval($this->input['channel_id']);
		$dates 		 = trim($this->input['dates']);
		$id 		 = $this->input['ids'];
		$theme		 = $this->input['theme'];
		
		if (!$channel_id)
		{
			$this->errorOutput('未传入频道id');
		}
		
		if (!$dates)
		{
			$this->errorOutput('未传入日期');
		}
		
		if (empty($id))
		{
			$this->errorOutput('未传入串联单id');
		}
		
		foreach ($id AS $k => $v)
		{
			if (!$v)
			{
				$this->errorOutput('串联单id不能为空');
			}
		}
		
		$schedule_field = 'id, start_time, toff';
		$schedule	  	= $this->mSchedule->get_schedule_info_by_id(implode(',', $id), $schedule_field);
		
		if (empty($schedule))
		{
			$this->errorOutput($dates . '串联单不存在或已删除');
		}
		
		$schdeule_info = array();
		foreach ($schedule AS $v)
		{
			$schdeule_info[$v['id']] = $v;
		}
		
		require_once ROOT_PATH . 'lib/class/program.class.php';
		$mProgram = new program();
		
		if (!$mProgram)
		{
			$this->errorOutput('节目单应用没有安装');
		}
		
		if (!$this->settings['App_program'])
		{
			$this->errorOutput('节目单应用没有安装');
		}
		
		$delete_data = array(
			'channel_id' => $channel_id,
			'dates' 	 => $dates,
		);
		
		$ret_delete = $mProgram->delete_by_channel_id($delete_data);
		
		if (!$ret_delete)
		{
			$this->errorOutput('删除已有的节目单失败');
		}
		
		$start_time = $end_time = $_theme = array();
		$tmp_toff = 0;
		$i = 0;
		foreach ($id AS $k => $v)
		{
			$tmp_toff += $schdeule_info[$v]['toff'];
			if ($tmp_toff >= ($this->settings['schedule2program_min_toff'] * 60))
			{
				$start_time[$i] = strtotime(date('Y-m-d H:i', $schdeule_info[$v]['start_time']));
				$end_time[$i]   = strtotime(date('Y-m-d H:i', $schdeule_info[$v]['start_time'])) + $schdeule_info[$v]['toff'];
				$_theme[$i]     = $theme[$k];
				$tmp_toff   = 0;
				$i ++;
			}
		}

		$program_data = array(
			'channel_id'	 => $channel_id,
			'dates'			 => $dates,
			'schedule_id'	 => implode(',|' ,$id),
			'start_time'	 => implode(',|' ,$start_time),
			'end_time'		 => implode(',|' ,$end_time),
			'theme'			 => implode(',|' ,$_theme),
		);
		
		$return = $mProgram->schedule2program($program_data);

		$this->addItem($return);
		$this->output();
	}
	
	private function setSourceInfo($data = array())
	{
		$change2_id 	= $data['change2_id'];
		$type 			= $data['type'];
		
		$channel_id = $vod_id = array();
		foreach ($change2_id AS $i => $id)
		{
			if ($type[$i] == 1 || $type[$i] == 3)
			{
				$channel_id[] = $id;
			}
			elseif ($type[$i] == 2)
			{
				$vod_id[] = $id;
			}
		}
		
		if (!empty($channel_id) && $type[$i])
		{
			$channel_id = implode(',', $channel_id);
			$channel_data = array(
				'id'		=> $channel_id,
				'is_stream'	=> 1,
				'is_server'	=> 0,
				'field'		=> ' id, name, code, server_id, main_stream_name, is_mobile_phone, logo_rectangle ',
			);
			
			$channel_info = $this->mLive->getChannelInfoById($channel_data);
			
			$channel = array();
			if (!empty($channel_info))
			{
				foreach ($channel_info AS $v)
				{
					$channel[$v['id']] = $v;
				}
			}
		}
		
		if (!empty($vod_id) && $type[$i])
		{
			$vod_id = implode(',', $vod_id);
			
			$vod_info = $this->mLivMedia->getVodInfoById($vod_id);
			
			$vod = array();
			if (!empty($vod_info))
			{
				foreach ($vod_info AS $v)
				{
					$vod[$v['id']] = $v;
				}
			}
		}

		foreach ($change2_id AS $i => $id)
		{
			$this->mFileToff[$i] = 0;
			$this->mPicture[$i] = '';
			if ($type[$i] == 1)
			{
				$this->mSourceType[$i] 	= 1;
				$this->mUrl[$i] 		= $channel[$id]['channel_stream'][0]['output_url_rtmp'];
				//$this->mUrl[$i] 		= $channel[$id]['channel_stream'][0]['m3u8'];
				$this->mPicture[$i] 	= hg_material_link($channel[$id]['logo_rectangle']['host'], $channel[$id]['logo_rectangle']['dir'], $channel[$id]['logo_rectangle']['filepath'], $channel[$id]['logo_rectangle']['filename']);
			}
			elseif ($type[$i] == 2)
			{
				$this->mSourceType[$i] 	= 4;
				$this->mFileToff[$i] 	= $vod[$id]['toff'];
				$this->mUrl[$i] 	 	= $vod[$id]['vodurl'] . $vod[$id]['video_filename'];
				if (0 && $vod[$id]['video_filename'])
				{
					$this->mMediaserver->addRequestData('path',$vod[$id]['video_path']);
					$this->mMediaserver->addRequestData('targetpath',$vod[$id]['video_path']);
					$this->mMediaserver->addRequestData('filename', str_replace('.mp4', '', $vod[$id]['video_filename']));
					$this->mMediaserver->request('split_ts.php');
					
					$this->mUrl[$i] 	 	= $vod[$id]['hostwork'] . '/filestream/' . $vod[$id]['video_path'] . 'playlist.m3u8';
				}
				$this->mPicture[$i] 	= $vod[$id]['img'];
			}
			elseif ($type[$i] == 3)
			{
				$m3u8 = explode('?', $channel[$id]['channel_stream'][0]['m3u8']);
				$url = str_replace('playlist.m3u8',  $channel[$id]['main_stream_name'] . '/', $m3u8[0]);
				$this->mSourceType[$i] 	= 4;
				$this->mUrl[$i] 		= $url;
			}
		}
		return true;
	}

	public function test()
	{
		$channel_id = $this->input['id'];
		print_r($this->build_ts($channel_id));
	}

	private function build_ts($channel_id, $dates = '')
	{
		if (!$dates)
		{
			$dates = date('Y-m-d');
		}
		$curl = new curl($this->settings['App_live']['host'], $this->settings['App_live']['dir'] . 'admin/');
		
		$curl->setReturnFormat('json');
		$curl->setSubmitType('post');
		$curl->initPostData();
		$curl->addRequestData('a','create');
		$curl->addRequestData('dates',$dates);
		$curl->addRequestData('channel_id',$channel_id);
		$curl->addRequestData('level',2);
		$today_time = strtotime($dates . ' 00:00:00');
		$end_time = strtotime($dates . ' 23:59:59');
		if ($today_time == strtotime(date('Y-m-d') . ' 00:00:00'))
		{
			//$today_time = time();
		}
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'schedule WHERE channel_id=' . $channel_id . ' AND start_time>= ' . $today_time . ' AND start_time<' . $end_time . ' AND type=2 ORDER BY start_time ASC';
		$q = $this->db->query($sql);
		$ts_start_time = $duration = $path = array();
		$i = 0;
		$postdata = array();
		while($r = $this->db->fetch_array($q))
		{
			$type = $r['type'];
			$start_time = $r['start_time'] * 1000;
			$f_start_time = $start_time;
			$toff = $r['toff'];
			$m3u8_url = $r['url'];
			if ($type == 2)
			{
				$tmp = explode('/', $m3u8_url);
				$root_url = $tmp[0] . '//' . $tmp[2] . '/';
				unset($tmp[count($tmp) - 1]);
				$cur_url = implode('/', $tmp) . '/';
				$m3u8 = file_get_contents($m3u8_url);
				$m3u8_list = $this->parse_m3u8($m3u8);
				$lefttime = $r['toff'] * 1000;
				$lenth = count($m3u8_list) - 1;
				foreach($m3u8_list AS $k => $v)
				{	
					if (($lefttime - $v['dur']) < 500)
					{
						break;
					}
					if (!$k)
					{
						$file_start = 1;
					}
					else
					{
						$file_start = 0;
					}
					if ($k == $lenth)
					{
						$file_end = 1;
					}
					else
					{
						$file_end = 0;
					}
					$duration[] = $v['dur'];
					
					if (substr($v['ts'], 0, 4) != 'http')
					{
						$c = substr($v['ts'], 0, 1);
						if ($c == '/')
						{
							$ts = $root_url . $v['ts'];
						}
						else
						{
							$ts = $cur_url . $v['ts'];
						}
					}
					else
					{
						$ts = $v['ts'];
					}
					$postdata[] = $start_time . '#' . $v['dur'] . '#' .$ts . '#0#' . $r['change2_id'] . '#' . $f_start_time . '#' . $lefttime . '#' . $file_start . '#' . $file_end;
					//$curl->addRequestData('data[' . $i . ']', $start_time . '#' . $v['dur'] . '#' .$ts . '#0#' . $r['change2_id'] . '#' . $f_start_time . '#' . $lefttime . '#' . $file_start . '#' . $file_end);
					$lefttime = $lefttime - $v['dur'];
					$start_time = $start_time + $v['dur'];
					$i++;
				}
			}
		}
		if ($postdata)
		{
			$curl->addRequestData('data', implode(']ts[', $postdata));
			$ret = $curl->request('dvr_update.php');
		}
		return $ret;
	}
	private function parse_m3u8($m3u8)
	{
		$m3u8_list = array();
		preg_match_all('/\#EXTINF\:([0-9\.]+)\,[\r\n]*(.*?\.ts)/is', $m3u8, $match);
		if ($match)
		{
			foreach ($match[1] AS $k => $v)
			{
				$m3u8_list[] = array(
					'dur' => intval($v * 1000),
					'ts' => $match[2][$k],
				);
			}
		}
		return $m3u8_list;
	}
	public function unknow()
	{
		$this->errorOutput('未定义的空方法');
	}
	
}
$out = new channelUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>