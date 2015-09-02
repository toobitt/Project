<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
*@public function show|count|detail|type_source
*@private function get_condition|getInfo|getPlan|verify_plan
*
* $Id: channel_chg_plan.php 
***************************************************************************/
define('MOD_UNIQUEID','old_live');
require('global.php');
class channelChgPlanApi extends adminReadBase
{
	private $mChannelChgPlan;
	private $mBackup;
	private $mChannels;
	private $ntpTime;
	private $mLive;
	private $mStream;
	private $mLivemms;
	private $mServerConfig;
	function __construct()
	{
		parent::__construct();
		if($this->mNeedCheckIn && !$this->prms['series_connection'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		
		require_once CUR_CONF_PATH . 'lib/channel_chg_plan.class.php';
		$this->mChannelChgPlan = new channelChgPlan();
		
		require_once CUR_CONF_PATH . 'lib/backup.class.php';
		$this->mBackup = new backup();
		
		require_once CUR_CONF_PATH . 'lib/channels.class.php';
		$this->mChannels = new channels();
		
		require_once CUR_CONF_PATH . 'lib/stream.class.php';
		$this->mStream = new stream();
		
		require_once CUR_CONF_PATH . 'lib/livemms.class.php';
		$this->mLivemms = new livemms();
		
		require_once CUR_CONF_PATH . 'lib/server_config.class.php';
		$this->mServerConfig = new serverConfig();
		
		$this->mLive = $this->settings['wowza']['live_output_server'];
		
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 串联单显示
	 * @name show
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $condition 检索条件
	 * @param $channel_id int 频道ID
	 * @param $dates string 格式化日期(Y-m-d)
	 * @return $channel_name string 频道名称
	 * @return $channel_id int 频道ID
	 * @return $dates_api string 格式化日期(Y-m-d)
	 * @return $uri string 频道输出流地址
	 * @return $change array 某天串联单信息内容
	 */
	public function show()
	{
		$condition 	= $this->get_condition();
		$channel_id = intval($this->input['channel_id']);
		if(!$channel_id)
		{
			$this->errorOutput('未传入频道ID');
		}
		$dates = $this->input['dates'] ? urldecode($this->input['dates']) : date('Y-m-d');
		
		$sql = "SELECT t1.name, t1.code, t1.audio_only, t1.server_id, t2.out_stream_name FROM " . DB_PREFIX . "channel t1 ";
		$sql.= " LEFT JOIN " . DB_PREFIX . "channel_stream t2 ON t1.id=t2.channel_id ";
		$sql.= " WHERE t1.id=" . $channel_id . " AND t2.is_main = 1 ";
		$channel_info = $this->db->query_first($sql);
		
		if (empty($channel_info))
		{
			$this->errorOutput('该频道不存在或已被删除');
		}
		
		//服务器配置
		$server_id = $channel_info['server_id'];
		if ($server_id)
		{
			$server_info 	= $this->mServerConfig->get_server_config_by_id($server_id);
		}
		
		if ($server_info['core_in_host'])
		{
			if ($server_info['is_dvr_output'])
			{
				$host 	 = $server_info['dvr_in_host'] . ':' . $server_info['dvr_in_port'];
				$wowzaip = $server_info['dvr_in_host'] . ':' . $server_info['dvr_out_port'];
			}
			else 
			{
				$host 	 = $server_info['core_in_host'] . ':' . $server_info['core_in_port'];
				$wowzaip = $server_info['core_in_host'] . ':' . $server_info['core_out_port'];
			}
			
			$apidir_output	= $server_info['output_dir'];
		}
		else 
		{
			if ($this->settings['dvr_output_server'])
			{
				$host 			= $this->settings['wowza']['dvr_output_server']['host'] . ':' . $this->settings['wowza']['dvr_output_server']['port'];
				$apidir_output	= $this->settings['wowza']['dvr_output_server']['output_dir'];
				$wowzaip 		= $this->settings['wowza']['dvr_output_server']['host'] . ':' . $this->settings['wowza']['out_port'];
			}
			else
			{
				$host 			= $this->settings['wowza']['core_input_server']['host'] . ':' . $this->settings['wowza']['core_input_server']['port'];
				$apidir_output	= $this->settings['wowza']['core_input_server']['output_dir'];
				$wowzaip 		= $this->settings['wowza']['core_input_server']['host'] . ':' . $this->settings['wowza']['out_port'];
			}
		}
		
		$suffix	 = $this->settings['wowza']['dvr_output']['suffix'];
		
		if ($this->mLive)
		{
			if ($server_info['is_live_output'])
			{
				$host 			= $server_info['live_in_host'] . ':' . $server_info['live_in_port'];
				$apidir_output	= $server_info['output_dir'];
				$wowzaip 		= $server_info['live_in_host'] . ':'. $server_info['live_out_port'];
			}
			else 
			{
				$host 			= $this->settings['wowza']['live_output_server']['host'] . ':' . $this->settings['wowza']['live_output_server']['port'];
				$apidir_output 	= $this->settings['wowza']['live_output_server']['output_dir'];
				$wowzaip 		= $this->settings['wowza']['live_output_server']['host'] . ':' . $this->settings['wowza']['out_port'];
			}
			
			$suffix  = $this->settings['wowza']['live_output']['suffix'];
		}
		
		$uri = hg_streamUrl($wowzaip, $channel_info['code'], $channel_info['out_stream_name'] . $suffix, 'flv');
		$ret_ntpTime = $this->mLivemms->outputNtpTime($host, $apidir_output);
		
		$this->addItem_withkey('channel_name', $channel_info['name']);
		$this->addItem_withkey('channel_id', $channel_id);
		$this->addItem_withkey('server_id', $server_id);
		$this->addItem_withkey('dates_api', $dates);
		$this->addItem_withkey('uri', $uri);
		$this->addItem_withkey('audio_only', $channel_info['audio_only']);
		
		if ($ret_ntpTime['result'])
		{
			$ntpTime 	= $ret_ntpTime['ntp']['utc'];
			$ntpTime 	= ceil($ntpTime/1000);
			$ntpYmdhis 	= date('Y-m-d H:i:s', $ntpTime);
			$ntpHis 	= date('H:i:s', $ntpTime);
		}
		else 
		{
			$ntpYmdhis 	= date('Y-m-d H:i:s', TIMENOW);
			$ntpHis 	= date('H:i:s', TIMENOW);
		}
		
		$this->addItem_withkey('ntpYmdhis', $ntpYmdhis);
		$this->addItem_withkey('ntpHis', $ntpHis);
		
		$change 	= array();
		$start 		= strtotime($dates." 00:00:00");
		$end 		= strtotime($dates." 23:59:59");
		$com_time 	= 0;
		
		$today = date('Y-m-d',TIMENOW);
		$last_week_day = date('Y-m-d', (strtotime($today) + ((7-date('N',strtotime($today)))*86400)));
		$change_plan = $this->getPlan($channel_id, $dates);
		if($dates >= $today && $dates <= $last_week_day)
		{
			$change_plan = $this->getPlan($channel_id, $dates);
		}
		else 
		{
			$change_plan = array();
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "channel_chg_plan ";
		$sql.= " WHERE channel_id=" . $channel_id . " AND dates='". $dates ."'" . $condition . " ORDER BY change_time ASC ";
		$q = $this->db->query($sql);
		
		while($row = $this->db->fetch_array($q))
		{
			if(!$com_time && $row['change_time'] > $start)//头
			{
				$plan = $this->verify_plan($change_plan,$start,$row['change_time']);
				if($plan)
				{
					foreach($plan as $k => $v)
					{
						$change[] = $v;
					}
				}
				else
				{
					$change[] = $this->getInfo($start,$row['change_time'],$dates);
				}
			}

			if($com_time && $com_time != $row['change_time'])//中
			{				
				$plan = $this->verify_plan($change_plan,$com_time,$row['change_time']);
				if($plan)
				{
					foreach($plan as $k => $v)
					{
						$change[] = $v;
					}
				}
				else
				{
					$change[] = $this->getInfo($com_time,$row['change_time'],$dates); 
				}
			}
			
			$row['plan_status'] = ($row['change_time'] < TIMENOW) ? 1 : 0;
			$row['end_time'] 	= date('H:i:s', ($row['change_time'] + $row['toff']));
			$row['start_time'] 	= date('H:i:s', $row['change_time']);
			$row['e_time'] 		= date('H:i:s', ($row['program_start_time'] + $row['toff']));
			$row['s_time'] 		= date('m-d H:i:s', $row['program_start_time']);
			
			if($row['program_start_time'])
			{
				$row['program_end_time'] 	= date('Y-m-d H:i:s', ($row['program_start_time'] + $row['toff']));
				$row['program_start_time'] 	= date('Y-m-d H:i:s', $row['program_start_time']);
			}

			if($row['file_toff'])
			{
				$row['file_toff'] = time_format($row['file_toff']);
			}
			else 
			{
				unset ($row['file_toff']);
			}
			
			$row['start']	= date("H:i:s",$row['change_time']);
			$row['end'] 	= date("H:i:s",$row['change_time']+$row['toff']);
		
			$com_time 		= $row['change_time'] + $row['toff'];
			$change[] 		= $row;
		}
	
		if($com_time && $com_time < $end)//中
		{			
			$plan = $this->verify_plan($change_plan,$com_time,$end);
			if($plan)
			{
				foreach($plan as $k => $v)
				{
					$change[] = $v;
				}
			}
			else
			{
			//	$change[] = $this->getInfo($com_time,$end,$dates);
			}
		}
		if(empty($change))
		{
			$change 	= array();
			$start 		= strtotime($dates." 00:00:00");
			$end 		= strtotime($dates." 23:59:59");
			$com_time 	= 0;
			foreach($change_plan as $k => $v)
			{
				if(!$com_time && $v['change_time'] > $start)//头
				{
					$change[] = $this->getInfo($start,$v['change_time'],$dates);
				}

				if($com_time && $com_time != $v['change_time'])//中
				{
					$change[] = $this->getInfo($com_time,$v['change_time'],$dates); 
				}
				$v['start'] = date("H:i",$v['change_time']);
				$v['end'] 	= date("H:i",$v['change_time']+$v['toff']);
				
				$com_time 	= $v['change_time']+$v['toff'];
				$change[] 	= $v;
			}
		}
		
		$this->addItem($change);
		$this->output();	
	}

	/**
	 * 填补空白数据
	 * @name getInfo
	 * @access private
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $start int 开始时间
	 * @param $end int 结束时间
	 * @param $dates string 格式化日期(Y-m-d)
	 * @return $info array 空白数据
	 */
	private function getInfo($start,$end,$dates)
	{
		$info = array(
				'start_time' => $start,	
				'empty_toff' => $end-$start,
				'dates' 	 => $dates,
				'start'		 => date("H:i:s",$start),	
				'end'		 => date("H:i:s",$end),
			);
		return $info;
	}
	
	/**
	 * 获取该频道串联单计划信息
	 * @name getPlan
	 * @access private
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $channel_id int 频道ID
	 * @param $dates string 格式化日期(Y-m-d)
	 * @return $change_plan array 该频道串联单计划信息内容
	 */
	private function getPlan($channel_id,$dates)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "change_plan p ";
		$sql.= " LEFT JOIN " . DB_PREFIX . "change_plan_relation r ON r.plan_id=p.id";
		$sql.= " WHERE 1 and p.channel_id=" . $channel_id . " AND r.week_num=" . date("N",strtotime($dates));
		$sql.= " ORDER BY p.start_time ASC";
		$q = $this->db->query($sql);
		$change_plan = array();
		while($r = $this->db->fetch_array($q))
		{
			$week_days 	 = $r['week_days'];
			$week_d 	 = date('N', strtotime($dates));
			$week 		 = date('W',$r['program_start_time']);
			$this_week 	 = date('W',TIMENOW);
			$offset_week = ($this_week - $week)*24*3600*7;
			
			if($week_days == $week_d)
			{
				$program_start_time = date('Y-m-d H:i:s', ($r['program_start_time'] + $offset_week));
			}
			else if($week_days > $week_d)
			{
				$program_start_time = date('Y-m-d H:i:s', ($r['program_start_time'] - (86400*($week_days-$week_d)) + $offset_week));
			}
			else if($week_days < $week_d)
			{
				$program_start_time = date('Y-m-d H:i:s', ($r['program_start_time'] + (86400*($week_d-$week_days)) + $offset_week));
			}
			
			$change_plan[] = array(
					'id' 					=> hg_rand_num(12),	
					'channel_id' 			=> $r['channel_id'],
					'change_time' 			=> strtotime($dates . " " . date("H:i:s",$r['start_time'])),	
					'toff' 					=> $r['toff'],
					'file_toff' 			=> time_format($r['file_toff']),
					'stream_uri' 			=> $r['stream_uri'],
					'channel2_id' 			=> $r['channel2_id'],
					'channel2_name' 		=> $r['channel2_name'],
					'program_end_time' 		=> $r['program_start_time'] ? date('Y-m-d H:i:s',(strtotime($program_start_time)+$r['toff'])) : '',
					'program_start_time' 	=> $r['program_start_time'] ? $program_start_time : '',
					'week_days' 			=> $r['week_days'],
					'type' 					=> $r['type'],	
					'dates' 				=> $dates,
					'create_time' 			=> TIMENOW,	
					'update_time' 			=> TIMENOW,	
					'ip' 					=> hg_getip(),	
					'end_time' 				=> date("H:i:s",($r['start_time'] + $r['toff'])),
					'start_time' 			=> date("H:i:s", $r['start_time']),
					'e_time' 				=> $r['program_start_time'] ? date('H:i:s',(strtotime($program_start_time)+$r['toff'])) : '',
					's_time' 				=> $r['program_start_time'] ? date('m-d H:i:s',strtotime($program_start_time)) : '',
					'is_plan' 				=> 1,
				);
		}
		return $change_plan;
	}
		
	/**
	 * 合并串联单和串联单计划信息
	 * @name verify_plan
	 * @access private
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $plan array 串联单计划内容
	 * @param $start_time int 开始时间
	 * @param $end_time int 结束时间
	 * @return $change array 合并后信息内容
	 */
	private function verify_plan($plan,$start_time,$end_time)
	{
		$change_plan = array();
		if(!empty($plan))
		{
			foreach($plan as $k => $v)
			{
				if($v['change_time'] >= $start_time && ($v['change_time']+$v['toff'])<= $end_time)
				{
					$change_plan[] = $v;
				}
			}
			
			if(empty($change_plan))
			{
				return false;
			}
			
			$change   = array();
			$start 	  = $start_time;
			$end 	  = $end_time;
			$dates 	  = date("Y-m-d",$start_time);
			$com_time = 0;
			
			foreach($change_plan as $k => $v)
			{
				if(!$com_time && $v['change_time'] > $start)//头
				{
					$change[] = $this->getInfo($start,$v['change_time'],$dates);
				}

				if($com_time && $com_time != $v['change_time'])//中
				{
					$change[] = $this->getInfo($com_time,$v['change_time'],$dates); 
				}
				$v['start'] = date("H:i",$v['change_time']);
				$v['end'] 	= date("H:i",$v['change_time']+$v['toff']);
		
				$com_time = $v['change_time']+$v['toff'];
				$change[] = $v;
			}
			if($com_time && $com_time < $end)//中		暂时这样
			{
				$change[] = $this->getInfo($com_time,$end,$dates);
			}
			if(empty($change_plan))
			{
				return false;
			}
			return $change;
		}
		else
		{
			return false;
		}
	}
		
	/**
	 * 串联单来源类型
	 * @name type_source
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $channel_id int 频道ID
	 * @param $type tinyint 来源类型 (1-直播 2-文件 3-时移)
	 * @param $channel2_id int 来源类型ID
	 * @param $program_start_time string 时移开始时间 (Y-m-d H:i:s)
	 * @param $program_end_time string 时移结束时间  (Y-m-d H:i:s)
	 * @param $channel2_name string 来源名称
	 * @return $data array  串联单来源类型信息
	 */
	public function type_source()
	{
		$channel_id 		= intval($this->input['channel_id']);
		$type 				= intval($this->input['type']);
		$channel2_id		= intval($this->input['channel2_id']);
		$program_start_time = $this->input['program_start_time'];
		$program_end_time 	= $this->input['program_end_time'];
		$channel2_name 		= $this->input['channel2_name'];
		$audio_only 		= intval($this->input['audio_only']);
		
		$server_id			= intval($this->input['server_id']);
		
		$type_source = array(
			'channel_id' 		 => $channel_id,
			'type' 				 => $type,
			'channel2_id' 		 => $channel2_id,
			'program_start_time' => urldecode($program_start_time),
			'program_end_time'	 => urldecode($program_end_time),
			'channel2_name'		 => urldecode($channel2_name),
			'audio_only'		 => $audio_only,
			'server_id'		 	 => $server_id,
		);
		//频道	
		$channel_condition 	= ' AND c.audio_only = ' . $audio_only;
		$channel_condition .= ' AND c.server_id = ' . $server_id;
		$get_channel_info 	= $this->mChannels->show($channel_condition,0,100);//$this->settings['channelChgPlan2ChannelCount']
		$channel_total 	  	= $this->mChannels->count($channel_condition);
		
		//备播文件		
		$backup_condition 	= ' AND status=1 ';
		$backup_condition  .= ' AND server_id = ' . $server_id;
		$channelChgPlan2BackupCount = $this->settings['channelChgPlan2BackupCount'] ? $this->settings['channelChgPlan2BackupCount'] : 21;
		$get_backup_info 	= $this->mBackup->show($backup_condition, 0, $channelChgPlan2BackupCount);
		$backup_total 		= $this->mBackup->count($backup_condition);
		
		//备播信号
		$stream_condition 	= ' AND audio_only = ' . $audio_only;
		$stream_condition  .= ' AND server_id = ' . $server_id;
		$channelChgPlan2StreamCount = $this->settings['channelChgPlan2StreamCount'] ? $this->settings['channelChgPlan2StreamCount'] : 12;
		$get_stream_info 	= $this->mStream->show($stream_condition, 0, $channelChgPlan2StreamCount);
		$stream_total    	= $this->mStream->count($stream_condition);
		
		$data = array(
			'type_source' 	=> $type_source,
			'channel'	=> array(
				'info'	=> $get_channel_info,
				'total'	=> $channel_total['total'],
				'count'	=> ceil($channel_total['total']/$this->settings['channelChgPlan2ChannelCount']),
			),
			'stream'	=> array(
				'info'	=> $get_stream_info,
				'total'	=> $stream_total['total'],
				'count'	=> ceil($stream_total['total']/$channelChgPlan2StreamCount),
			),
			'backup'	=> array(
				'info'	=> $get_backup_info,
				'total'	=> $backup_total['total'],
				'count'	=> ceil($backup_total['total']/$channelChgPlan2BackupCount),
			),
		);
		
		$this->addItem($data);
		$this->output();
	}

	/**
	 * 分页调用接口
	 * Enter description here ...
	 */
	function page()
	{
		$type 		= intval($this->input['type']);
		$condition 	= '';
		$offset 	= intval($this->input['offset']);
		$count 		= intval($this->input['counts']);
		$audio_only = intval($this->input['audio_only']);
		
		$server_id	= intval($this->input['server_id']);
		
		if ($type == 1)
		{
			$condition = ' AND c.audio_only = ' . $audio_only;
			$condition.= ' AND c.server_id = ' . $server_id;
			$info = $this->mChannels->show($condition, $offset, $count);
		}
		else if ($type == 2)
		{
			$condition = ' AND status=1 ';
			$condition.= ' AND server_id = ' . $server_id;
			if(isset($this->input['k']) && !empty($this->input['k']))
			{
				$condition .= ' AND title like \'%' . trim(urldecode($this->input['k'])) . '%\'';
			}
			$info = $this->mBackup->show($condition, $offset, $count);
		}
		else if ($type == 4)
		{
			$condition = ' AND audio_only = ' . $audio_only;
			$condition.= ' AND server_id = ' . $server_id;
			$info = $this->mStream->show($condition, $offset, $count);
		}
		
		$ret = array(
			'type'			=> $type,
			'audio_only'	=> $audio_only,
			'info'			=> $info,
		);
		$this->addItem($ret);
		$this->output();
	}
	
	public function search_info()
	{
		$server_id = intval($this->input['server_id']);
		$condition = ' AND status=1 ';
		$condition.= ' AND server_id = ' . $server_id;
		
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' AND title like \'%' . trim(urldecode($this->input['k'])) . '%\'';
		}
		$channelChgPlan2BackupCount = $this->settings['channelChgPlan2BackupCount'] ? $this->settings['channelChgPlan2BackupCount'] : 21;
		
		$info 	= $this->mBackup->show($condition, 0, $channelChgPlan2BackupCount);
		$total 	= $this->mBackup->count($condition);
		$ret = array(
			'info'	=> $info,
			'total'	=> $total['total'],
			'count'	=> ceil($total['total']/$channelChgPlan2BackupCount),
		);

		$this->addItem($ret);
		$this->output();
	}
	
	/**
	 * 新版串联单获取备播文件
	 */
	public function get_backup_info()
	{
		$offset 	= intval($this->input['offset']);
		$count 		= $this->input['counts'] ? intval($this->input['counts']) : 20;
		$server_id 	= intval($this->input['server_id']);
		
		$condition = ' AND status=1 ';
		$condition.= ' AND server_id = ' . $server_id;
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' AND title like \'%' . trim(urldecode($this->input['k'])) . '%\'';
		}
		$info = $this->mBackup->getBackupInfo($condition, $offset, $count);
		$this->addItem($info);
		$this->output();
	}
	/**
	 * 新版串联单获取备播信号
	 */
	public function get_stream_info()
	{
		$offset 	= intval($this->input['offset']);
		$count 		= $this->input['counts'] ? intval($this->input['counts']) : 20;
		$audio_only = intval($this->input['audio_only']);
		$server_id 	= intval($this->input['server_id']);
		
		$condition = ' AND audio_only = ' . $audio_only;
		$condition.= ' AND server_id = ' . $server_id;
		
		$info = $this->mStream->getStreamInfo($condition, $offset, $count);
		$this->addItem($info);
		$this->output();
	}
	/**
	 * 新版串联单获取频道
	 */
	public function get_channel_info()
	{
		$offset 	= intval($this->input['offset']);
		$count 		= $this->input['counts'] ? intval($this->input['counts']) : 20;
		$audio_only = intval($this->input['audio_only']);
		$server_id 	= intval($this->input['server_id']);
		
		$condition = ' AND audio_only = ' . $audio_only;
		$condition.= ' AND server_id = ' . $server_id;
		
		$info = $this->mChannels->getChannelInfo($condition, $offset, $count);
		$this->addItem($info);
		$this->output();
	}
	/**
	 * 取单条信息
	 * @name detail
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $id int 串联单ID
	 * @return $row array 单条串联单信息
	 */
	function detail()
	{
		$id = urldecode($this->input['id']);
		if(!$id)
		{
			$condition = ' ORDER BY id DESC LIMIT 1';
		}
		else
		{
			$condition = ' WHERE id IN(' . $id .')';
		}			
		$sql = "SELECT * FROM " . DB_PREFIX . "channel_chg_plan " . $condition;		
		$row = $this->db->query_first($sql);
		$this->setXmlNode('channel_chg_plan' , 'info');
		
		if(is_array($row) && $row)
		{
			$row['create_time'] = date('Y-m-d H:i:s' , $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s' , $row['update_time']);
			$this->addItem($row);
			$this->output();
		}
		else
		{
			$this->errorOutput('串联单不存在');	
		} 	
	}
	
	
	/**
	 * 根据条件返回总数
	 * @name count
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @return $ret string 总数，json串
	 */
	public function count()
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "channel_chg_plan WHERE 1 ";

		//获取查询条件
		$condition = $this->get_condition();				
		$sql = $sql . $condition;
		$ret = $this->db->query_first($sql);

		//暂时这样处理
		echo json_encode($ret);
	}
	
			
	/**
	 * 检索条件
	 * @name get_condition
	 * @access private
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	private function get_condition()
	{
		$condition = '';
		return $condition;
	}
	
	public function index()
	{
		
	}
}

$out = new channelChgPlanApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>