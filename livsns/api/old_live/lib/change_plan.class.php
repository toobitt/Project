<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: change_plan.class.php 
***************************************************************************/
class changePlan extends InitFrm
{
	private $mLivemms;
	private $mSourceId;
	private $mSourceType;
	private $mFileToff;
	public function __construct()
	{
		parent::__construct();
		$this->mDates = date('2012-01-01');
		include_once(ROOT_PATH . 'lib/class/recycle.class.php');
		$this->recycle = new recycle();
		
		require_once CUR_CONF_PATH . 'lib/livemms.class.php';
		$this->mLivemms = new livemms();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	function show($condition)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "change_plan_relation r left join " . DB_PREFIX . "change_plan p on p.id=r.plan_id WHERE 1 " . $condition . " ORDER BY start_time ASC";
		$q = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($q))
		{
			$row['start'] = strtotime(date("H:i:s",$row['start_time']));
			$row['end'] = strtotime(date("H:i:s",$row['start_time']+$row['toff']));
			$info[] = $row;
		}
		return $info;
	}

	function create($add_input, $server_info, $user)
	{
		$dates = $this->mDates;
		
		$channel2_id[0] = $add_input['channel2_ids'];
		$type[0] = $add_input['type'];
		
		$this->setSourceInfo($channel2_id, $type, $server_info);

		$sourceId = $this->mSourceId[0];
		$sourceType = $this->mSourceType[0];
		
		$toff = strtotime($dates . " " . $add_input['plan_end_time']) - strtotime($dates . " " . $add_input['plan_start_time']);
		if($add_input['program_start_time'])
		{
			$program_start_time = strtotime($add_input['program_start_time']);
		}
		$today = date('N', TIMENOW);
		$week_days = $add_input['week_d'];
		if($week_days <= $today)
		{
			$week_days = $today;
		}
		
		if ($type[0] == 3)
		{
			$stream_uri = $sourceId;
			$sourceId   = 0;
		}
		
		$info = array(
			'channel_id' 		 => $add_input['channel_id'],
			'type' 				 => $add_input['type'],
			'source_id' 		 => $sourceId,
			'source_type' 		 => $sourceType,
			'channel2_id' 		 => $add_input['channel2_ids'],
			'channel2_name' 	 => $add_input['channel2_name'],
			'start_time' 		 => strtotime($dates . " " . $add_input['plan_start_time']),
			'toff'				 => $toff,
			'file_toff'			 => $this->mFileToff[0],
			'program_start_time' => $program_start_time ? $program_start_time : 0,
			'week_days' 		 => $week_days,
			'admin_name'		 => $user['user_name'],
			'admin_id'			 => $user['user_id'],
			'create_time'		 => TIMENOW,
			'update_time'		 => TIMENOW,
			'ip'				 => hg_getip(),
			'stream_uri'		 => $stream_uri,
			'server_id'			 => $add_input['server_id'],
		);
		$sql_extra = $space = "";
		foreach($info AS $key => $value)
		{
			if($value)
			{
				$sql_extra .= $space . $key . "='" . $value . "'";
				$space = ",";
			}
		}
		if($sql_extra && $add_input['week_day'])
		{
			$sql = "INSERT INTO " . DB_PREFIX . "change_plan SET ".$sql_extra;
			$this->db->query($sql);
			$info['id'] = $this->db->insert_id();
			if($info['id'])
			{
				$week_num = $add_input['week_day'];
				if(!empty($week_num))
				{
					$sql_extra = $space = '';
					foreach($week_num AS $k => $v)
					{
						$sql_extra .= $space . '(' . $info['id'] . ',' . $v . ')';
						$space = ',';
					}
					$sql = "INSERT INTO " . DB_PREFIX . "change_plan_relation(plan_id,week_num) value".$sql_extra;
					$this->db->query($sql);
				}
			}
			return $info;
		}
		return false;
	}

	function update($id, $add_input, $server_info)
	{	
		$sql = "SELECT week_days, program_start_time, source_id FROM " . DB_PREFIX . "change_plan WHERE id=" . $id;
		$change_plan = $this->db->query_first($sql);
		
		if (empty($change_plan))
		{
			return false;
		}
		
		$dates = $this->mDates;
		
		$channel2_id[0] = $add_input['channel2_ids'];
		$type[0] = $add_input['type'];
		
		$this->setSourceInfo($channel2_id, $type, $server_info);

		$sourceId = $this->mSourceId[0];
		$sourceType = $this->mSourceType[0];
		
		if ($sourceId && $type[0] == 2)
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
			$ret_file_list = $this->mLivemms->inputFileListOperate($host, $apidir, 'delete', $change_plan['source_id']);
		}
		
		$toff = strtotime($dates . " " . $add_input['plan_end_time']) - strtotime($dates . " " . $add_input['plan_start_time']);
		if($add_input['program_start_time'])
		{
			$program_start_time = strtotime($add_input['program_start_time']);
		}
		else
		{
			$program_start_time = '00';
		}
		
		if ($type[0] == 3)
		{
			$stream_uri = $sourceId;
			$sourceId   = 0;
		}
		
		$info = array(
			'channel_id'		 => $add_input['channel_id'],
			'type'				 => $add_input['type'],
			'source_id'			 => $sourceId,
			'source_type'		 => $sourceType,
			'channel2_id'		 => $add_input['channel2_ids'],
			'channel2_name'		 => $add_input['channel2_name'],
			'start_time'		 => strtotime($dates . " " . $add_input['plan_start_time']),
			'toff'				 => $toff,
			'file_toff'			 => $this->mFileToff[0],
			'program_start_time' => $program_start_time,
			'update_time'		 => TIMENOW,
			'ip'				 => hg_getip(),
			'stream_uri'		 => $stream_uri,
			'server_id'			 => $add_input['server_id'],
		);
		
		if(!$change_plan['program_start_time'] && $program_start_time)
		{
			$today 		= date('N', TIMENOW);
			$week_days 	= $add_input['week_d'];
			
			if($week_days <= $today)
			{
				$week_days = $today;
			}
	
			$info['week_days'] = $week_days;
		}
		
		$sql_extra = $space = "";
		foreach($info AS $key => $value)
		{
			if($value)
			{
				$sql_extra .= $space . $key . "='" . $value . "'";
				$space = ",";
			}
		}
		if($sql_extra && $add_input['week_day'])
		{
			$sql_ =  "SELECT * FROM " . DB_PREFIX . "change_plan WHERE id = " . $id;
			$pre_data = $this->db->query_first($sql_);
			
			$sql = "UPDATE " . DB_PREFIX . "change_plan SET " . $sql_extra . " WHERE 1 AND id=" . $id;
			$this->db->query($sql);
			$info['id'] =  $id;
			
			if($info['id'])
			{
				$sql = "DELETE FROM " . DB_PREFIX . "change_plan_relation where plan_id=" . $info['id'];
				$this->db->query($sql);
				$week_num = $add_input['week_day'];
				if(is_array($week_num))
				{
					$sql_extra = $space = '';
					foreach($week_num AS $k => $v)
					{
						$sql_extra .= $space . '(' . $info['id'] . ',' . $v . ')';
						$space = ',';
					}
					$sql = "INSERT INTO " . DB_PREFIX . "change_plan_relation(plan_id,week_num) value".$sql_extra;
					$this->db->query($sql);
				}
			}
			
			$this->addLogs('update' , $pre_data , $info , '' , '');
			
			return $info;
		}
		return false;		
	}
	

	public function count($condition)
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "change_plan_relation r left join " . DB_PREFIX . "change_plan p on p.id=r.plan_id WHERE 1 ";
		$sql = $sql . $condition;
		$r = $this->db->query_first($sql);
		return $r;
	}

	public function delete($id)
	{
	/*
		$id = trim($this->input['id']);
	
		//放入回收站
		$sql = "select * from " . DB_PREFIX . "change_plan where id = " . $id;
		$ret = $this->db->query($sql);
		while($row = $this->db->fetch_array($ret))
		{
			$data2[$row['id']] = array(
					'delete_people' => trim(urldecode($this->user['user_name'])),
					'title' => $row['channel2_name'],
					'cid' => $row['id'],
			);
			$data2[$row['id']]['content']['change_plan'] = $row;
		}
		$sql = "select * from " . DB_PREFIX . "change_plan_relation where id = " . $id;
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$data2[$row['id']]['content']['change_plan_relation'][] = $row;
		}
		if($data2)
		{
			//放入回收站
			foreach($data2 as $key => $value)
			{
				$res = $this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
				
			}	
			//放入回收站结束
		}
		if($res['sucess'])
		{
			$sql = "select * from " . DB_PREFIX . "change_plan WHERE id=" . $id;
			$f = $this->db->query_first($sql);
			if($f['id'])
			{
				$sql = "DELETE FROM " . DB_PREFIX . "change_plan WHERE id=".$id;
				$r = $this->db->query($sql);
				$sql = "DELETE FROM " . DB_PREFIX . "change_plan_relation WHERE plan_id=".$id;
				$r = $this->db->query($sql);
			}
			return $f['channel_id'];
		}
		else 
		{
			return false;
		}
		*/
		$sql = "SELECT * FROM " . DB_PREFIX . "change_plan WHERE id=" . $id;
		$f = $this->db->query_first($sql);
		if($f['id'])
		{
			$sql = "DELETE FROM " . DB_PREFIX . "change_plan WHERE id=".$id;
			$r = $this->db->query($sql);
			
			$sql = "DELETE FROM " . DB_PREFIX . "change_plan_relation WHERE plan_id=".$id;
			
			if ($r = $this->db->query($sql))
			{
				$this->addLogs('delete' , $f , '' , '' , '');
				
				return $f['channel_id'];
			}
		}
		
		return false;
	}

	public function verify($start,$end,$channel_id,$id = 0)
	{
		$start = strtotime($this->mDates . " " . $start);
		$end = strtotime($this->mDates . " " . $end);
		
		if($start >= $end)
		{
			return false;
		}
		$sql = "select *,FROM_UNIXTIME(start_time, '%Y-%m-%d %H:%i:%S') as start_time,FROM_UNIXTIME((start_time+toff), '%Y-%m-%d %H:%i:%S') as plan_end_time from " . DB_PREFIX . "change_plan where channel_id=" . $channel_id." and start_time >" . $start . " and (start_time) <" . $end ." or (start_time+toff) >" . $start . " and (start_time+toff) <" . $end . " or start_time=" . $start . " and (start_time+toff)=" . $end;
		$q = $this->db->query($sql);
		$id_array = array();
		while($r = $this->db->fetch_array($q))
		{
			$id_array[] = $r['id'];
		}
		$id_array = array_unique($id_array);
		$ids = implode(',',$id_array);

		if(!$ids)
		{
			return false;
		}

		$sql = "SELECT * FROM " . DB_PREFIX . "change_plan_relation WHERE plan_id IN(" . $ids . ")";
		$q = $this->db->query($sql);
		$week_array = array();
		while($r = $this->db->fetch_array($q))
		{
			if($r['plan_id'] != $id)
			{
				$week_array[] = $r['week_num'];
			}
		}
		return $week_array;
	}

	
	/**
	 * 获取单条信息
	 */
	public function detail($id, $week_d, $condition)
	{
		if(!$id)
		{
			$condition .= " ORDER BY start_time ASC LIMIT 1";
		}
		else 
		{
			$condition .= " AND id =" . $id ;
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "change_plan p WHERE 1 " . $condition;
		$info = $this->db->query_first($sql);
		if($info['id'])
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "change_plan_relation WHERE plan_id=" . $id ." ORDER BY week_num ASC";
			$q = $this->db->query($sql);
			$week_num = array();
			while($r = $this->db->fetch_array($q))
			{
				$week_num[$r['week_num']] = $r['week_num'];
			}
			$info['week_day'] = $week_num;
			$info['week_d'] = $week_d;
		}
		return $info;
	}

	function setSourceInfo($channel2_id, $type, $server_info)
	{
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
		
		if ($channel_id && $type[$i]) //取频道流地址
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
		
		//备播信号
		if ($stream_id && $type[$i])
		{
			$sql = "SELECT id, ch_name, type, audio_only, other_info FROM " . DB_PREFIX . "stream WHERE id IN (" . implode(',', $stream_id) . ")";
			$q = $this->db->query($sql);
			
			$stream = array();
			while ($row = $this->db->fetch_array($q))
			{
				$row['other_info'] = unserialize($row['other_info']);
				$stream[$row['id']] = $row;
			}
		}
		
		if ($file_id) //取文件流地址
		{
			$sql = "SELECT id, fileid, toff FROM " . DB_PREFIX . "backup WHERE id IN(" . implode(',', $file_id) .")";
			$f = $this->db->query($sql);

			$backup = array();
			while ($row = $this->db->fetch_array($f))
			{
				/*
				if ($row['fileid'])
				{
					$ret_list = $this->mLivmms->inputFileListInsert($row['fileid']);

					if (!$ret_list['result'])
					{
						continue;
					}
					
					$row['list_fileid'] = $ret_list['list']['id'];
				}
				*/
				$backup[$row['id']] = $row;
			}
		}
		
		foreach($channel2_id AS $i => $id)
		{
			if ($type[$i] == 2)
			{
				$this->mSourceId[$i]   = $backup[$id]['fileid'];
				$this->mSourceType[$i] = 3;
				$this->mFileToff[$i]   = $backup[$id]['toff'];
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
						$wowzaip = $server_info['dvr_in_host'] . ':' . $server_info['dvr_in_port'];
					}
					else 
					{
						$wowzaip = $server_info['core_in_host'] . ':' . $server_info['core_in_port'];
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
				
				$suffix	 = $this->settings['wowza']['dvr_output']['suffix'];
				
				$this->mSourceId[$i] 	= hg_streamUrl($wowzaip, $channel_info[$id]['code'], $channel_info[$id]['channel_stream'][0]['out_stream_name'] . $suffix, 'm3u8');
				$this->mSourceType[$i] 	= 3;
			}
			else
			{
				if ($channel_info[$id]['live_delay'])
				{
					$this->mSourceId[$i]   = $channel_info[$id]['channel_stream'][0]['delay_stream_id'];
					$this->mSourceType[$i] = 2;
				}
				else 
				{
					$this->mSourceId[$i]   = $channel_info[$id]['other_info']['input'][0]['id'];
					$this->mSourceType[$i] = 1;
				}
				$this->mFileToff[$i] = 0;
			}
		}
		return true;
	}
	
	function get_channel_by_id($channel_id, $field = ' * ')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "channel WHERE id = " . $channel_id;
		$ret = $this->db->query_first($sql);
		return $ret;
	}
}

?>