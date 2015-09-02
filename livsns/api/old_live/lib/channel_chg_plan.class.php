<?php
/***************************************************************************
* $Id: channel_chg_plan.class.php 17183 2013-01-30 08:00:51Z lijiaying $
***************************************************************************/
class channelChgPlan extends InitFrm
{
	private $mLivemms;
	private $mSourceId;
	private $mSourceType;
	private $mFileToff;
	private $mFileId;
	public function __construct()
	{
		parent::__construct();
		
		require_once CUR_CONF_PATH . 'lib/livemms.class.php';
		$this->mLivemms = new livemms();
		
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 创建、更新 串联单
	 * @name edit
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $channel_id int 频道ID
	 * @param $date string 日期
	 * @param $chg_plan_ids array 串联单ID
	 * @param $start_time array 开始时间
	 * @param $end_time array 结束时间
	 * @param $type array 来源类型 (1-直播频道 2-备播文件 3-时移 4-备播信号)
	 * @param $channel2_id array 来源类型ID
	 * @param $channel2_name array 来源类型名称
	 * @param $program_start_time array 时移开始时间
	 * @param $epg_id array 32接口返回串联单ID
	 * @param $hidden_temp array 标记该条串联单是否被修改过 (1-是 0-否)
	 * @param $toff array 时长
	 * @param $uri array 流地址
	 * @param $create_time array 创建时间
	 * @param $update_time array 更新时间
	 * @param $admin_id array 用户ID
	 * @param $admin_name array 用户名
	 * @param $ip array 创建者IP
	 * @return $ids array 创建成功串联单ID
	 */
	function edit($channel_id, $date, $add_input, $channel_stream, $server_info, $user)
	{
		if ($server_info['core_in_host'])
		{
			$host 	= $server_info['core_in_host'] . ':' . $server_info['core_in_port'];
			$apidir = $server_info['input_dir'];
		}
		else 
		{
			$host 	= $this->settings['wowza']['core_input_server']['host'] . ':' . $this->settings['wowza']['core_input_server']['port'];
			$apidir = $this->settings['wowza']['core_input_server']['input_dir'];
		}
		
		$chg_plan_ids 		= $add_input['chg_plan_ids'];
		$start_time 		= $add_input['start_time'];
		$end_time 			= $add_input['end_time'];
		$type 				= $add_input['type'];
		$channel2_id 		= $add_input['channel2_id'];
		$channel2_name 		= $add_input['channel2_name'];
		$program_start_time = $add_input['program_start_time'];
		$epg_id 			= $add_input['epg_id'];
		$source_id 			= $add_input['source_id'];
		$hidden_temp 		= $add_input['hidden_temp'];
		
		$this->setSourceInfo($channel2_id, $type, $server_info);

		$ids = $epg_ids = array();
		foreach ($chg_plan_ids AS $i => $id)
		{
			if(strlen($id) >= 12)
			{
				$id = '';
			}

			$sourceId 	= $this->mSourceId[$i];
			$sourceType = $this->mSourceType[$i];

			if (!$sourceId)
			{
				return -22;
			}
			
			$start = strtotime($date . ' ' . urldecode($start_time[$i]));
			$end   = strtotime($date . ' ' . urldecode($end_time[$i]));
			$toff  = $end - $start;
			if ($program_start_time[$i] && $type[$i] == 3)
			{
				$program_start[$i] = strtotime(urldecode($program_start_time[$i]));
			}
			
			$epg_insert = array();
			
			$outputId = $channel_stream[0]['chg_stream_id'];
			if ($type[$i] == 3)
			{
				$url = $sourceId;
			}
			$data = array(
				'out_stream_id'		 => $outputId,
				'source_id' 		 => $sourceId,
				'source_type' 		 => $sourceType,
				'channel_id' 		 => $channel_id,
				'channel2_id' 		 => $channel2_id[$i],
				'channel2_name' 	 => urldecode($channel2_name[$i]),
				'change_time' 		 => $start,
				'toff' 				 => $toff,
				'file_toff' 		 => $this->mFileToff[$i],
				'fileid' 		 	 => $this->mFileId[$i],
				'type' 				 => $type[$i],
				'program_start_time' => $program_start[$i],
				'stream_uri'		 => $url ? $url : '',
			);

			if ($id)
			{
				$epg_ids[$i] = $epg_id[$i];

				if($hidden_temp[$i])
				{
					if (strlen($epg_id[$i]) < 13)
					{
						if ($epg_id[$i])
						{
							$epg_delete = $this->mLivemms->inputScheduleOperate($host, $apidir, 'delete', $epg_id[$i]);
						}
	
						if (!$epg_delete['result'])
						{
							return -23;
						}
					}
					
					unset($epg_ids[$i]);
					
					if ($type[$i] != 3)
					{
						$epg_insert = $this->mLivemms->inputScheduleInsert($host, $apidir, $outputId, $sourceId, $sourceType, $start, $toff);
						if (!$epg_insert['result'])
						{
							return -24;
						}
					}
				
					if ($source_id[$i] && $type[$i] != 1)
					{
						$ret_file_list = $this->mLivemms->inputFileListOperate($host, $apidir, 'delete', $source_id[$i]);
					}
					
					$data['epg_id'] 	 = $epg_insert['schedule']['id'] ? $epg_insert['schedule']['id'] : $program_start[$i].'000';
					$data['update_time'] = TIMENOW;
					$data['record_flag'] = '00';
					
					
					$sql_ =  "SELECT * FROM " . DB_PREFIX . "channel_chg_plan WHERE id = " . $id;
					$pre_data = $this->db->query_first($sql_);
		
					$sql = "UPDATE " . DB_PREFIX . "channel_chg_plan SET ";
					$space = "";
					$sql_extra = "";
					foreach($data AS $key => $value)
					{
						if($value)
						{
							$sql_extra .= $space . $key . "=" . "'" . $value . "'";
							$space = ",";
						}
					}
					if($sql_extra)
					{
						$sql .= $sql_extra . " WHERE id=" . $id;
						$this->db->query($sql);
					}
					$this->addLogs('update' , $pre_data , $data , '' , '');
					$epg_ids[$i] = $epg_insert['schedule']['id'];
				}
				$ids[$i] = $id;
			}
			else 
			{
				if ($type[$i] != 3)
				{
					$epg_insert = $this->mLivemms->inputScheduleInsert($host, $apidir, $outputId, $sourceId, $sourceType, $start, $toff);
					if (!$epg_insert['result'])
					{
						return -25;
					}
				}
				
				$data['epg_id'] 	 = $epg_insert['schedule']['id'] ? $epg_insert['schedule']['id'] : $program_start[$i].'000';
				$data['dates']		 = $date;
				$data['create_time'] = TIMENOW;
				$data['update_time'] = TIMENOW;
				$data['admin_name']  = $user['user_name'];
				$data['admin_id']	 = $user['user_id'];
				$data['ip']			 = hg_getip();
						
				$createsql = "INSERT INTO " . DB_PREFIX . "channel_chg_plan SET ";
				$space = "";
				foreach($data AS $key => $value)
				{
					$createsql .= $space . $key . "=" . "'" . $value . "'";
					$space = ",";
				}
					
				$this->db->query($createsql);
				$data['id']	 = $this->db->insert_id();
				
				$ids[$i]	 = $data['id'];
				$epg_ids[$i] = $epg_insert['schedule']['id'] ? $epg_insert['schedule']['id'] : $program_start[$i].'000';
				/*
				if ($type[$i] == 3)
				{
					$url = $sourceId . '&duration=' . $toff . '000';
					$callback = $this->settings['App_live']['protocol'].$this->settings['App_live']['host'].'/'.$this->settings['App_live']['dir'].'admin/callback.php?a=record_callback';
					$record_info = array(
						'id' 			=> $this->settings['mms']['record_server_callback']['prefix'] . $data['id'],
						'uploadFile' 	=> 0,
						'access_token'  => $this->user['token'],
						'channel_id'  	=> $data['channel2_id'],
					//	'starttime'  	=> $program_start[$i],
					//	'toff'  		=> $toff,
					);
					$ret_record = $this->mLivmms->recordInsert($record_info, urlencode($url), urlencode($callback));
					if ($ret_record['result'])
					{
						$sql = "UPDATE " . DB_PREFIX . "channel_chg_plan SET record_flag = 1 WHERE id = " . $data['id'];
						$this->db->query($sql);
					}
				}
				*/
			}
		}

		$return = array(
			'ids' 	  => $ids,
			'epg_ids' => $epg_ids,
		);

		return $return;
	}
	
	private function setSourceInfo($channel2_id, $type, $server_info)
	{
		if (!$channel2_id)
		{
			return false;
		}
		
		$channel_id = $file_id = $stream_id = array();
		foreach($channel2_id AS $i => $id)
		{	
			if ($type[$i] == 2)
			{
				$file_id[] = $id;
			}
			elseif ($type[$i] == 4)
			{
				$stream_id[] = $id;
			}
			else 
			{
				$channel_id[] = $id;
			}
		}
		
		//频道
		if ($channel_id && $type[$i])
		{
			$condition = " WHERE c.id IN(" . implode(',', $channel_id) .")";
			$sql = "SELECT c.id, c.code, c.stream_id, c.live_delay, s.other_info, s.type FROM " . DB_PREFIX . "channel c LEFT JOIN " . DB_PREFIX . "stream s ON c.stream_id=s.id " .$condition;
			$q = $this->db->query($sql);
		
			$channel = array();
			while($row = $this->db->fetch_array($q))
			{
				$row['other_info'] 	 = @unserialize($row['other_info']);
				$channel[$row['id']] = $row;			
			}
			
			$sql = "SELECT id, channel_id, out_stream_name, delay_stream_id, chg_stream_id, out_stream_id, stream_name FROM " . DB_PREFIX . "channel_stream WHERE channel_id IN (" . implode(',', $channel_id) . ")";
			$q = $this->db->query($sql);
			
			$channel_stream = array();
			while ($row = $this->db->fetch_array($q))
			{
				$channel_stream[$row['channel_id']]['channel_stream'][] = $row;
			}
			
			$channel_info = array();
			if (!empty($channel))
			{
				foreach ($channel AS $k => $v)
				{
					if ($channel_stream[$k])
					{
						$channel_info[$k] = @array_merge($channel[$k], $channel_stream[$k]);
					}
					else
					{
						$channel_info[$k] = $channel[$k];
					}
				}
			}
		}

		//备播文件
		if ($file_id && $type[$i]) 
		{
			$sql = "SELECT id, fileid, toff FROM " . DB_PREFIX . "backup WHERE id IN(" . implode(',', $file_id) .")";
			$f = $this->db->query($sql);

			$backup = array();
			while ($row = $this->db->fetch_array($f))
			{
				if ($row['fileid'])
				{
					if ($server_info['core_in_host'])
					{
						$host	= $server_info['core_in_host'] . ':' . $server_info['core_in_port'];
						$apidir	= $server_info['input_dir'];
					}
					else 
					{
						$host 	= $this->settings['wowza']['core_input_server']['host'] . ':' . $this->settings['wowza']['core_input_server']['port'];
						$apidir = $this->settings['wowza']['core_input_server']['input_dir'];
					}
					$ret_list = $this->mLivemms->inputFileListInsert($host, $apidir, $row['fileid']);

					if (!$ret_list['result'])
					{
						continue;
					}
					
					$row['list_fileid'] = $ret_list['list']['id'];
				}
			
				$backup[$row['id']] = $row;
			}
		}
		
		//备播信号
		if ($stream_id && $type[$i])
		{
			$sql = "SELECT id, ch_name, type, audio_only, other_info FROM " . DB_PREFIX . "stream WHERE id IN (" . implode(',', $stream_id) . ")";
			$q = $this->db->query($sql);
			
			$stream = array();
			while ($row = $this->db->fetch_array($q))
			{
				$row['other_info'] = @unserialize($row['other_info']);
				$stream[$row['id']] = $row;
			}
		}
		
		foreach($channel2_id AS $i => $id)
		{
			$this->mFileToff[$i] = 0;
			$this->mFileId[$i] 	 = 0;
			
			if ($type[$i] == 2)
			{
				$this->mSourceId[$i] 	= $backup[$id]['list_fileid'];
				$this->mSourceType[$i] 	= 3;
				$this->mFileToff[$i] 	= $backup[$id]['toff'];
				$this->mFileId[$i] 		= $backup[$id]['fileid'];
			}
			elseif ($type[$i] == 4)
			{
				$this->mSourceId[$i] 	= $stream[$id]['other_info']['input'][0]['id'];
				$this->mSourceType[$i] 	= $stream[$id]['type'] ? 3 : 1;
			}
			elseif ($type[$i] == 3)
			{
				if ($server_info['core_in_host'])
				{
					if ($server_info['is_dvr_output'])
					{
						$wowzaip = $server_info['dvr_in_host'] . ':' . $server_info['dvr_out_port'];
					}
					else 
					{
						$wowzaip = $server_info['core_in_host'] . ':' . $server_info['core_out_port'];
					}
				}
				else 
				{
					if ($this->settings['wowza']['dvr_output_server'])
					{
						$wowzaip = $this->settings['wowza']['dvr_output_server']['host'] . ':' . $this->settings['wowza']['out_port'];
					}
					else 
					{
						$wowzaip = $this->settings['wowza']['core_input_server']['host'] . ':' . $this->settings['wowza']['out_port'];
					}
				}
				
				$suffix  = $this->settings['wowza']['dvr_output']['suffix'];
				
				$this->mSourceId[$i] 	= hg_streamUrl($wowzaip, $channel_info[$id]['code'], $channel_info[$id]['channel_stream'][0]['out_stream_name'] . $suffix, 'm3u8');
				$this->mSourceType[$i] 	= 3;
			}
			else
			{
				if ($channel_info[$id]['live_delay'])
				{
					$this->mSourceId[$i] 	= $channel_info[$id]['channel_stream'][0]['delay_stream_id'];
					$this->mSourceType[$i] 	= 2;
				}
				else 
				{
					$this->mSourceId[$i] 	= $channel_info[$id]['other_info']['input'][0]['id'];
					$this->mSourceType[$i] 	= $channel_info[$id]['type'] ? 3 : 1;
				}
			}
		}
		return true;
	}
	
	function record_add($data)
	{
		$sql = "INSERT INTO " . DB_PREFIX . "channel_chg_record SET ";
		$space = "";
		foreach($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
			
		$this->db->query($sql);
		$data['id']	 = $this->db->insert_id();
		if ($data['id'])
		{
			return $data;
		}
		return false;
	}
	
	function get_record_by_chg_id($chg_id)
	{
		$sql = "SELECT t1.*, t2.out_stream_id, t2.source_type, t2.type, t2.change_time, t2.toff AS toff, t2.program_start_time, t3.server_id FROM " . DB_PREFIX . "channel_chg_record t1 ";
		$sql.= " LEFT JOIN " . DB_PREFIX . "channel_chg_plan t2 ON t2.id = t1.chg_id ";
		$sql.= " LEFT JOIN " . DB_PREFIX . "channel t3 ON t3.id = t2.channel_id ";
		$sql.= " WHERE t1.chg_id = " . $chg_id;
		$chg_record = $this->db->query_first($sql);
		
		return $chg_record;
	}
	
	function schedule_edit($data, $id)
	{
		$sql = "UPDATE " . DB_PREFIX . "channel_chg_plan SET ";
		$space = "";
		foreach($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		
		$sql .= " WHERE id = " . $id;	
		
		$this->db->query($sql);
		
		$data['id'] = $id;
		
		if ($data['id'])
		{
			return $data;
		}
		return false;
	}
	
	function record_edit($chg_id, $start_time, $toff)
	{
		$sql = "UPDATE " . DB_PREFIX . "channel_chg_record SET start_time = " . $start_time . ", toff = " . $toff . " WHERE chg_id = " . $chg_id;
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}
	
	function schedule_delete($id)
	{
		$sql_ =  "SELECT * FROM " . DB_PREFIX . "channel_chg_plan WHERE id = " . $id;
		$pre_data = $this->db->query_first($sql_);
		
		$sql = "DELETE FROM " . DB_PREFIX . "channel_chg_plan WHERE id = " . $id;
		if ($this->db->query($sql))
		{
			$this->addLogs('delete' , $pre_data , '' , '' , '');
			return true;
		}
		return false;
	}
	
	public function get_channel_by_id($id, $field = ' * ')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "channel WHERE id = " . $id;
		$ret = $this->db->query_first($sql);
		return $ret;
	} 
}
?>