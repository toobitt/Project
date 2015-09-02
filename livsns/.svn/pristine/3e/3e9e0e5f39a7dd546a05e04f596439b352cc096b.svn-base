<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program.class.php 5623 2012-01-12 05:30:22Z repheal $
***************************************************************************/
class program extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	function show($condition,$channel_id,$start_time,$end_times,$save_time=0, $tf_by_endtime = false)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "program WHERE 1 " . $condition . " ORDER BY dates DESC,start_time ASC";
		$q = $this->db->query($sql);
		$program = array();
		include_once(ROOT_PATH . 'lib/class/live.class.php');
		$newLive = new live();
		$channel_info = $newLive->getChannelById($channel_id, -1);
		if ($tf_by_endtime)
		{
			$dates = date("Y-m-d",$end_times);
		}
		else
		{
			$dates = date("Y-m-d",TIMENOW);
		}
		$start = strtotime(date("Y-m-d",$start_time) . " 00:00:00");
		$end = strtotime(date("Y-m-d",$end_times) . " 23:59:59")+1;
		$channel_info = $channel_info[0];
		$com_time = 0;
		while($row = $this->db->fetch_array($q))
		{
			$start_time = $row['start_time'];
			$end_time = $row['start_time']+$row['toff'];
			$dates = date("Y-m-d",$start_time);
			$start = strtotime(date("Y-m-d",$start_time)." 00:00:00");
			$end = strtotime(date("Y-m-d",$end_time)." 23:59:59");
			$display = $lave_time = $now_play = $zhi_play = 0;
			if(!$com_time && $start_time > $start)//头
			{
				$program[] = $this->getInfo($start,$start_time,$dates,$channel_id);
			}
			$row['start'] = date("H:i",$start_time);
			$row['end'] = date("H:i",$end_time);
			$row['zhi_play'] = $zhi_play;	
			$row['now_play'] = $now_play;	
			$row['display'] = $display;	
			$row['lave_time'] = $lave_time;
			$row['is_program'] = 1;
			if (!$row['theme'])
			{
				$row['theme'] = '精彩节目';
			}
			$row['stime'] = date("H:i",$start_time);			
			$com_time = $end_time;
			$program[] = $row;
		}
		$all_program = $program_plan = $program_plan_tmp = $program_plan_keys = array();
		if($save_time)
		{
		$day = ceil($save_time/24);
		
                if($day > 1)
                {
                        for($i = 0;$i<$day;$i++)
                        {                       
                           $tmp_dates = date('Y-m-d',strtotime($dates . ' 23:59:59') - $i*86400);
			   $tmp  = $this->getPlan($channel_id,$tmp_dates);
			   $program_plan_tmp = array_merge($program_plan_tmp,$tmp);
                        }
                }
		}
		else
		{
		$program_plan_tmp = $this->getPlan($channel_id,$dates);
		}
//hg_pre($program_plan_tmp);exit;
		if(!empty($program_plan_tmp))
		{
			foreach($program_plan_tmp as $k => $v)
			{
				$program_plan[strtotime($v['dates'] . ' ' . date('H:i:s',$v['start_time']))] = $v;
				$program_plan[strtotime($v['dates'] . ' ' . date('H:i:s',$v['start_time']))]['start_time'] = strtotime($v['dates'] . ' ' . date('H:i:s',$v['start_time']));
			}
			$program_plan_keys = array_keys($program_plan);
		}
		if(empty($program))
		{
			$program = $this->copy_program($start,$end,$channel_id);
			if(defined('PROGRAM_DEFAULT') && !PROGRAM_DEFAULT && !empty($program_plan_tmp))//为定义，或者不允许,节目计划存在
			{
				$program = $program_plan_tmp;
				$length_tmp = count($program);
				foreach($program AS $k => $v)
				{
					if($k < ($length_tmp-1))
					{
						$program[$k]['toff'] = strtotime($program[$k+1]['dates'] . ' ' . $program[$k+1]['start']) - strtotime($program[$k]['dates'] . ' ' . $program[$k]['start']);
					}
					else
					{
						$program[$k]['toff'] = strtotime($program[$k]['dates'] . ' 23:59:59') - strtotime($program[$k]['dates'] . ' ' . $program[$k]['start']);
					}
				}
			}
		}
//hg_pre($program);exit;
		foreach($program as $key => $value)
		{
			$value['channel_name'] = $channel_info['name'];
			$value['channel_logo'] = array(
				'rectangle' =>  $channel_info['logo_rectangle'],
				'square' =>  $channel_info['logo_square'],
			);
			$value['channel_id'] = $channel_info['id'];
			$value['toff'] = isset($program[$key+1]) ? ($program[$key+1]['start_time'] - $program[$key]['start_time']) : ($end - $program[$key]['start_time']) ;
            $value['end'] = date("H:i",$program[$key]['start_time']+$value['toff']);
			//频道接管
			$channel_info['starttime'] = $value['start_time'];
			$channel_info['toff'] 	   = $value['toff'];
			$value['m3u8'] = $this->set_m3u8($channel_info);
			$new_unit_program = array();
			if($program_plan_keys && 0)
			{
				foreach($program_plan_keys as $k => $v)
				{
					if($v > $value['start_time'] && $v < ($value['start_time'] + $value['toff']))
					{
						if($v == $value['start_time'])
						{
							if($value['is_program'])
							{
								unset($program_plan[$v],$program_plan_keys[$k]);
							//	continue;
							}
							else
							{
								$value['theme'] = $program_plan[$v]['theme'];
								$value['is_plan'] = 1;
								$value['end'] = date("H:i",$value['start_time']+$value['toff']);
								$channel_info['starttime'] = $value['start_time'];
								$channel_info['toff'] 	   = $value['toff'];
								
								$value['m3u8'] = $this->set_m3u8($channel_info);
								$all_program[date("Y-m-d",$value['start_time'])][$value['start']] = $value;
								unset($program_plan[$v],$program_plan_keys[$k]);
							//	continue;
							}
						}
						else
						{
							$new_unit_program = $value;//是被切的节目单的上班部分的节目
							$new_unit_program['toff'] = $program_plan[$v]['start_time'] - $new_unit_program['start_time'];
							$new_unit_program['start'] = date("H:i",$new_unit_program['start_time']);
							$new_unit_program['end'] = date("H:i",$new_unit_program['start_time']+$new_unit_program['toff']);
							
							$channel_info['starttime'] = $new_unit_program['start_time'];
							$channel_info['toff'] 	   = $new_unit_program['toff'];
							
							$new_unit_program['m3u8'] = $this->set_m3u8($channel_info);
							$all_program[date("Y-m-d",$new_unit_program['start_time'])][$new_unit_program['start']] = $new_unit_program;
							
							//被切的下半部分，即为节目计划
							$value['theme'] = $program_plan[$v]['theme'];
							$value['toff'] = $value['toff'] - $new_unit_program['toff'];
							$value['start_time'] = $program_plan[$v]['start_time'];
							$value['is_plan'] = 1;
							$value['id'] = $program_plan[$v]['start_time'];
							$value['start'] = date("H:i",$value['start_time']);
							$value['end'] = date("H:i",$value['start_time']+$value['toff']);
							
							//频道接管
							$channel_info['starttime'] = $value['start_time'];
							$channel_info['toff'] 	   = $value['toff'];
							
							$value['m3u8'] = $this->set_m3u8($channel_info);
							//
							$all_program[date("Y-m-d",$value['start_time'])][$value['start']] = $value;
							unset($program_plan[$v],$program_plan_keys[$k]);
						//	continue;
						}
					}
					else
					{
						$all_program[date("Y-m-d",$value['start_time'])][$value['start']] = $value;
					}
				}
			}
			else
			{
				$all_program[date("Y-m-d",$value['start_time'])][$value['start']] = $value;
			}
		}
	//	hg_pre($all_program);exit;
		$start_range = $end_range = 0;
		if($save_time && 0)
		{
			if ($tf_by_endtime)
			{
				$end_range = $end_times;
				$start_range = $end_times - 3600*($save_time + 24);
			}
			else
			{
				$end_range = TIMENOW;
				$start_range = TIMENOW - 3600*$save_time;
			}
		}
		$tmp_all_program = array();
		foreach($all_program as $key => $value)
		{
			foreach($value as $k => $v)
			{
				if($start_range && $end_range)
				{
					if($v['start_time'] >= $start_range && ($v['start_time']+$v['toff']) <= $end_range)
					{
						$tmp_all_program[] = $v;
					}
				}
				else
				{
					$tmp_all_program[] = $v;
				}
			}
		}
		$all_program = $tmp_all_program;
	//	hg_pre($all_program);exit;
		//排序
		$tmp_program = $program = array();
		$length = count($all_program);
		for($i = 0,$j = $length; $i < $j; $i++)
		{  
	        for($k = $j-1; $k > $i; $k--) 
	        {
	            if ($all_program[$k]['start_time'] < $all_program[$k-1]['start_time'])
	            {
		            list($all_program[$k-1], $all_program[$k]) = array($all_program[$k], $all_program[$k-1]);  
	            }
	        }
	        
	        if($all_program[$i]['start_time'] < TIMENOW)
			{
				if ($all_program[$i]['start_time'] < (TIMENOW - ($channel_info['time_shift'] * 3600)))
				{
					$all_program[$i]['display'] = 0;
				}
				else
				{
					$all_program[$i]['display'] = 1;
				}
			}
			if($all_program[$i]['start_time'] < TIMENOW && ($all_program[$i]['start_time']+$all_program[$i]['toff']) > TIMENOW)
			{
				$all_program[$i]['zhi_play'] = $is_zhi = empty($is_zhi) ? 1 : 0;
				$all_program[$i]['lave_time'] = $all_program[$i]['start_time']+$all_program[$i]['toff'] - TIMENOW;
				if(!$play_time)
				{
					$all_program[$i]['now_play'] = 1;
				}
			}
			
			if($play_time && $all_program[$i]['start_time'] <= $play_time && ($all_program[$i]['start_time']+$all_program[$i]['toff']) > $play_time)
			{
				$all_program[$i]['now_play'] = 1;
				$all_program[$i]['lave_time'] = 1;
			}
			$all_program[$i]['stime'] = $all_program[$i]['start'];
			$program[] = $all_program[$i];
	    }
	   // hg_pre($program);exit;
		return $program;
	}
	
	public function set_m3u8($channel_info)
	{
		$channel_stream = $channel_info['channel_stream'][0];
		if (!$channel_info['is_sys'] && $channel_stream['timeshift_url'])
		{
			$timeshift_url = $channel_stream['timeshift_url'];
			if (strstr($timeshift_url, 'dvr') && strstr($timeshift_url, '{&#036;starttime}') && strstr($timeshift_url, '{&#036;duration}'))
			{
				$timeshift_url = str_replace('{&#036;starttime}', $channel_info['starttime'] . '000', $timeshift_url);
				$timeshift_url = str_replace('{&#036;duration}', $channel_info['toff'] . '000', $timeshift_url);
			}
			else if (strstr($timeshift_url, '{&#036;starttime}') && strstr($timeshift_url, '{&#036;endtime}'))
			{
				$timeshift_url = str_replace('{&#036;starttime}', $channel_info['starttime'] . '000', $timeshift_url);
				$timeshift_url = str_replace('{&#036;endtime}', ($channel_info['starttime'] + $channel_info['toff']) . '000', $timeshift_url);
			}
			$m3u8 = $timeshift_url;
		}
		else
		{
			if ($channel_stream['live_m3u8'])
			{
				$m3u8 = $channel_stream['live_m3u8'] . '?dvr&starttime=' . $channel_info['starttime']*1000 . '&duration=' . $channel_info['toff']*1000;
			}
			else
			{
				$m3u8 = '';
			}
		}
		return $m3u8;
	}

	private function count_day_hours($start,$end)
	{
		$toff = $end - $start;
		$info = array();
		if($toff <= 3600)
		{
			$info[] = array('start' => $start,'end' => $end);
		}
		else
		{
			$num = ceil($toff/3600);
			for($i=0;$i<$num;$i++)
			{
				$info[] = array('start' => $start+$i*3600,'end' => ($i == ($num-1)) ? $end:($start + ($i+1)*3600));
			}
		}
		return $info;
	}
	
	
	function copy_program($start_time,$end_time,$channel_id)
	{
		$start = strtotime(date("Y-m-d",$start_time) . " 00:00:00");
		$end = strtotime(date("Y-m-d",$end_time) . " 23:59:59");
		$com_time = 0;	
		$program_day = array();
		$day_time = $this->count_day_hours($start,$end);
		foreach($day_time as $tk => $tv)
		{
			$dates = date("Y-m-d",$tv['start']);
			$program_day[] = $this->getInfo($tv['start'],$tv['end'],$dates,$channel_id);
		}
		return $program_day;
	}

	public function create($data)
	{
		$sql = "INSERT INTO " . DB_PREFIX . "program SET ";
		$space = '';
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		
		$this->db->query($sql);
		
		$data['id'] = $this->db->insert_id();
		
		if ($data['id'])
		{
			return $data;
		}
		return false;
	}
	
	public function update($data)
	{
		$sql = "UPDATE " . DB_PREFIX . "program SET ";
		$space = '';
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		
		$sql .= " WHERE id = " . $data['id'];
		
		$this->db->query($sql);
		
		if ($data['id'])
		{
			return $data;
		}
		return false;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "program WHERE 1 " . $condition;
		$r = $this->db->query_first($sql);
		return $r;
	}

	function getDates($start_time = 0,$end_time = 0,$num = 70)
	{
		if(!$end_time)
		{
			return false;
		}
		$start_time = $start_time ? $start_time : TIMENOW;
		$arr_date = array();
		if(date('Y',$start_time) == date('Y',$end_time))
		{
			$differ = date('z',$end_time) - date('z',$start_time);
		}
		else
		{
			$differ = date('z',$end_time) - date('z',$start_time) + (date('L',$start_time)?366:365);
		}
		if(!$differ)
		{
			$arr_date[date('Y-m-d',$start_time)]['start'] = $start_time;
			$arr_date[date('Y-m-d',$start_time)]['end'] = $end_time;
		}
		else
		{
			for($i=0 ;$i <= $differ;$i++)
			{
				$snap_time = $end_time - $i*24*3600;
				$arr_date[date('Y-m-d',$snap_time)]['start'] = strtotime(date('Y-m-d',$snap_time) . " 00:00:00");
				$arr_date[date('Y-m-d',$snap_time)]['end'] = strtotime(date('Y-m-d',$snap_time) . " 23:59:59");
			}
		}

		if(count($arr_date)>$num)
		{
			array_splice($arr_date,($num-1));
		}

		return $arr_date;
	}

	private function getInfo($start,$end,$dates,$channel_id = 0)
	{
		$info = array(
				'id' => hg_rand_num(10),
				'channel_id' => $channel_id,
				'start_time' => $start,	
				'toff' =>  $end-$start,	
				'theme' => '精彩节目',
				'subtopic' => '',
				'type_id' => 1,
				'dates' => $dates,
				'weeks' => date('W',$start),
				'describes' => '',
				'create_time' => TIMENOW,
				'update_time' => TIMENOW,
				'ip' => hg_getip(),
				'is_show' => 1,
				'start' => date("H:i",$start),
				'end' => date("H:i",$end),
				'display' => $display,
				'now_display' => $now_display,
			);
		return $info;
	}
	
	public function getPlan($channel_id,$dates)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "program_plan p LEFT JOIN " . DB_PREFIX . "program_plan_relation r ON r.plan_id=p.id WHERE 1 and p.channel_id=" . $channel_id . " AND r.week_num=" . date("N",strtotime($dates)) . " ORDER BY p.start_time ASC";
		$q = $this->db->query($sql);
		$program_plan = array();
		$start = strtotime($dates . ' 00:00:00');
		$end = strtotime($dates . ' 23:59:59');
		while($r = $this->db->fetch_array($q))
		{
			$start_time = strtotime($dates . " " . date("H:i:s",$r['start_time']));
			$end_time = strtotime($dates . " " . date("H:i:s",$r['start_time'])) + $r['toff'];
			$display = $lave_time = $now_play = $zhi_play = 0;
			if(intval($r['toff']))
			{
				if(($r['start_time'] <= $start && ($r['start_time']+$r['toff']) >= $start) || ($r['start_time'] >= $start && ($r['start_time']+$r['toff']) <= $end) || ($r['start_time'] <= $end && ($r['start_time']+$r['toff']) >= $end))
				{
					$program_plan[] = array(
						'id' => $start_time,	
						'channel_id' => $r['channel_id'],
						'start_time' => $start_time,	
						'toff' =>  $r['toff'],	
						'theme' => $r['program_name'],	
						'subtopic' => '',
						'type_id' => 1,
						'dates' => $dates,
						'weeks' => date('W',$start_time),
						'describes' => '',
						'start' => date("H:i",$start_time),
						'end' => date("H:i",$end_time),		
						'zhi_play' => $zhi_play,
						'now_play' => $now_play,
						'display' => $display,
						'lave_time' => $lave_time,
						'stime' => date("H:i",$start_time),
						'plan' => 1,
					);
				}				
			}
			else
			{
				if($r['start_time'] <= $start || ($r['start_time'] >= $start && $r['start_time'] <= $end))
				{
					$program_plan[] = array(
						'id' => $start_time,	
						'channel_id' => $r['channel_id'],
						'start_time' => $start_time,	
						'toff' =>  $r['toff'],	
						'theme' => $r['program_name'],	
						'subtopic' => '',
						'type_id' => 1,
						'dates' => $dates,
						'weeks' => date('W',$start_time),
						'describes' => '',
						'start' => date("H:i",$start_time),
						'end' => date("H:i",$end_time),		
						'zhi_play' => $zhi_play,
						'now_play' => $now_play,
						'display' => $display,
						'lave_time' => $lave_time,
						'stime' => date("H:i",$start_time),
						'plan' => 1,
					);
				}
			}
		}$count = count($program_plan);
                for($i=0; $i<$count; $i++)
                {
                        for($j=$count-1; $j>$i; $j--)
                        {
                                if ($program_plan[$j]['start_time'] < $program_plan[$j-1]['start_time'])
                                {
                                        $tmp = $program_plan[$j];
                                        $program_plan[$j] = $program_plan[$j-1];
                                        $program_plan[$j-1] = $tmp;
                                }
                        }
                }
		return $program_plan;
	}
	
	public function getGreaterProgram($channel_id,$times)
	{
		if(empty($channel_id) || empty($times))
		{
			return false;
		}
		$channel_ids = explode(',',$channel_id);
		$program = array();
		foreach($channel_ids as $kk => $vv)
		{
			$program[$vv] = $this->show(" AND dates='" . date('Y-m-d',$times) . "' AND channel_id=" . $vv,$vv,strtotime(date('Y-m-d',$times) . ' 00:00:00'),strtotime(date('Y-m-d',$times) . ' 23:59:59'));
		}
		$return = array();
		if(!empty($program))
		{
			foreach($program as $key => $value)
			{
				if(!empty($value))
				{
					foreach($value as $k => $v)
					{
						if($v['start_time'] > $times)
						{
							$v['is_program'] = 1;
							unset($v['display'],$v['now_display']);
							$return[] = $v;
						}
					}
				}
			}
		}
		return $return;
	}
	
	public function getCurrentProgram($channel_id,$times)
	{
		if(empty($channel_id) || empty($times))
		{
			return false;
		}
		$channel_ids = explode(',',$channel_id);
		$program = array();
		foreach($channel_ids as $kk => $vv)
		{
			$program[$vv] = $this->show(" AND dates='" . date('Y-m-d',$times) . "' AND channel_id=" . $vv,$vv,strtotime(date('Y-m-d',$times) . ' 00:00:00'),strtotime(date('Y-m-d',$times) . ' 23:59:59'));
		}
		//hg_pre($program);exit;
		$return = array();
		if(!empty($program))
		{
			foreach($program as $key => $value)
			{
				if(!empty($value))
				{
					foreach($value as $k => $v)
					{
						if($v['start_time'] <= $times && ($v['start_time']+$v['toff']) >= $times)
						{
							$v['is_program'] = 1;
							unset($v['display'],$v['now_display']);
							$return[] = $v;
						}
					}
				}
			}
		}
		return $return;
	}

	public function getCurrentNextProgram($channel_id, $times)
	{
		if(empty($channel_id) || empty($times))
		{
			return false;
		}
		$channel_ids = explode(',',$channel_id);
		$program = array();
		foreach($channel_ids as $kk => $vv)
		{
			$program[$vv] = $this->show(" AND dates='" . date('Y-m-d',$times) . "' AND channel_id=" . $vv,$vv,strtotime(date('Y-m-d',$times) . ' 00:00:00'),strtotime(date('Y-m-d',$times) . ' 23:59:59'));
		}
		$return = array();
		if(!empty($program))
		{
			$i = 0;
			foreach($program as $key => $value)
			{
				if(!empty($value))
				{
					foreach($value as $k => $v)
					{
						if($v['zhi_play'])
						{
							$v['is_program'] = 1;
							//unset($v['display'],$v['now_display']);
							$return[] = $v;	
							if ($value[$k + 1])
							{
								$return[] = $value[$k + 1];
							}
							else
							{
								$return[] = $v;
							}
							break;
						}
					}
				}
			}
		}
		return $return;
	}
	
	public function delete_by_channel_id($channel_id, $dates)
	{
		$sql = "DELETE FROM " . DB_PREFIX . "program WHERE channel_id IN (" . $channel_id . ") AND dates = '" . $dates . "'";
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}
	
	public function get_program_info($channel_id, $dates, $field = ' * ')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "program ";
		$sql.= " WHERE 1 AND channel_id IN (" . $channel_id . ") AND dates = '" . $dates . "'";
		
		$q = $this->db->query($sql);
		
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			if ($row['create_time'])
			{
				$row['create_time'] = date('Y-m-d H:i:s', $row['create_time']);
			}
			
			if ($row['update_time'])
			{
				$row['update_time'] = date('Y-m-d H:i:s', $row['update_time']);
			}
			
			if ($row['start_time'])
			{
				$row['start_time'] = date('Y-m-d H:i:s', $row['start_time']);
			}
			$return[$row['channel_id']][] = $row;
		}
		return $return;
	}
}

?>