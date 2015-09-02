<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program.class.php 5623 2012-01-12 05:30:22Z repheal $
***************************************************************************/
class program extends BaseFrm
{
	private $channel_id;
	private $save_time;
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	function show($condition,$channel_id,$start_time,$end_time,$save_time=0)
	{
		$this->channel_id = $channel_id;
		$this->save_time = $save_time;
		$sql = "SELECT * FROM " . DB_PREFIX . "program WHERE 1 " . $condition . " ORDER BY dates DESC,start_time ASC";
		$q = $this->db->query($sql);
		$time_range = array();
		$info = $this->getDates($start_time,$end_time);
		while($row = $this->db->fetch_array($q))
		{
			$time_range[$row['dates']]['start'] = $time_range[$row['dates']]['start']?$time_range[$row['dates']]['start']:0;
			$time_range[$row['dates']]['end'] = $time_range[$row['dates']]['end']?$time_range[$row['dates']]['end']:0;
			$row['display'] = 0;
			if((TIMENOW - 3600 * $save_time) <= $row['start_time'] && $row['start_time'] <= TIMENOW)
			{
				$row['display'] = 1;
			}
			if(($row['start_time']+$row['toff']) > TIMENOW  && $row['start_time'] < TIMENOW)
			{
				$row['now_display'] = 1;
			}
			$row['start'] = date("H:i:s",$row['start_time']);
			$row['end'] = date("H:i:s",$row['start_time']+$row['toff']);
			if($row['start_time'] < $time_range[$row['dates']]['start'] && $time_range[$row['dates']]['start'])
			{
				$time_range[$row['dates']]['start'] = $row['start_time'];
			}
			if($row['start_time']+$row['toff'] > $time_range[$row['dates']]['end'] && $time_range[$row['dates']]['end'])
			{
				$time_range[$row['dates']]['end'] = $row['start_time']+$row['toff'];
			}
			
			$time_range[$row['dates']]['start'] = $time_range[$row['dates']]['start'] ? $time_range[$row['dates']]['start'] : $row['start_time'];
			$time_range[$row['dates']]['end'] = $time_range[$row['dates']]['end'] ? $time_range[$row['dates']]['end'] : $row['start_time']+$row['toff'];
			unset($info[$row['dates']]['start'],$info[$row['dates']]['end']);
			$info[$row['dates']][] = $row;
		}

		if(!empty($info))
		{
			$program = array();
			foreach($info as $key => $value)
			{
				unset($value['start'],$value['end']);
				$program_day = array();
				$dates = $key;
				$start = strtotime($dates." 00:00:00");
				$end = strtotime($dates." 23:59:59");
				$com_time = 0;
				$program_plan = $this->getPlan($this->channel_id,$key);
				if(!empty($value))
				{
					foreach($value as $rk => $row)
					{
						if(!$com_time && $row['start_time'] > $start)//头
						{
							$plan = $this->verify_plan($program_plan,$start,$row['start_time']);
							if($plan)
							{
								foreach($plan as $k => $v)
								{
									$program_day[] = $v;
								}
							}
							else
							{
								$day_time = $this->count_day_hours($start,$row['start_time']);
								foreach($day_time as $tk => $tv)
								{
									$program_day[] = $this->getInfo($tv['start'],$tv['end'],$key);
								}
							}
						}

						if($com_time && $com_time != $row['start_time'])//中
						{				
							$plan = $this->verify_plan($program_plan,$com_time,$row['start_time']);
							if($plan)
							{
								foreach($plan as $k => $v)
								{
									$program_day[] = $v;
								}
							}
							else
							{
								$day_time = $this->count_day_hours($com_time,$row['start_time']);
								foreach($day_time as $tk => $tv)
								{
									$program_day[] = $this->getInfo($tv['start'],$tv['end'],$key);
								}
							}
						}
						$row['start'] = date("H:i",$row['start_time']);
						$row['end'] = date("H:i",$row['start_time']+$row['toff']);
						$com_time = $row['start_time']+$row['toff'];
						$program_day[] = $row;
					}
					if($com_time && $com_time < $end)//尾
					{			
						$plan = $this->verify_plan($program_plan,$com_time,$end);
						if($plan)
						{
							foreach($plan as $k => $v)
							{
								$program_day[] = $v;
							}
						}
						else
						{
							$day_time = $this->count_day_hours($com_time,$end);
							foreach($day_time as $tk => $tv)
							{
								$program_day[] = $this->getInfo($tv['start'],$tv['end'],$key);
							}
						}
					}
				}
				else
				{
					$plan = $this->verify_plan($program_plan,$start,$end);
					if($plan)
					{
						foreach($plan as $k => $v)
						{
							$program_day[] = $v;
						}
					}
					else
					{
						$day_time = $this->count_day_hours($start,$end);
						foreach($day_time as $tk => $tv)
						{
							$program_day[] = $this->getInfo($tv['start'],$tv['end'],$key);
						}
					}
				}			
				$program[$key] = $program_day;
			}
		}

		if(!$program)
		{
			$program = $this->copy_program($start_time,$end_time);
		}

		return $program;
	}		

	private function verify_plan($plan,$start_time,$end_time)
	{
		$program_plan = array();
		if(!empty($plan))
		{
			foreach($plan as $k => $v)
			{
				if($v['start_time'] >= $start_time && ($v['start_time']+$v['toff'])<= $end_time)
				{
					$program_plan[] = $v;
				}
			}
			if(empty($program_plan))
			{
				return false;
			}
			$program = array();
			$start = $start_time;
			$end = $end_time;
			$dates = date("Y-m-d",$start_time);
			$com_time = 0;
			foreach($program_plan as $k => $v)
			{
				if(!$com_time && $v['start_time'] > $start)//头
				{
					//$program[] = $this->getInfo($start,$v['start_time'],$dates);
					$day_time = $this->count_day_hours($start,$v['start_time']);
					foreach($day_time as $tk => $tv)
					{
						$program[] = $this->getInfo($tv['start'],$tv['end'],$dates);
					}
				}

				if($com_time && $com_time != $v['start_time'])//中
				{
					//$program[] = $this->getInfo($com_time,$v['start_time'],$dates); 
					$day_time = $this->count_day_hours($com_time,$v['start_time']);
					foreach($day_time as $tk => $tv)
					{
						$program[] = $this->getInfo($tv['start'],$tv['end'],$dates);
					}
				}
				
				$v['start'] = date("H:i",$v['start_time']);
				$v['end'] = date("H:i",$v['start_time']+$v['toff']);

				$com_time = $v['start_time']+$v['toff'];
				$program[] = $v;
			}
			if($com_time && $com_time < $end)//中
			{				
				$day_time = $this->count_day_hours($com_time,$end);
				foreach($day_time as $tk => $tv)
				{
					$program[] = $this->getInfo($tv['start'],$tv['end'],$dates);
				}
			//	$program[] = $this->getInfo($com_time,$end,$dates);
			}
			if(empty($program_plan))
			{
				return false;
			}
			return $program;
		}
		else
		{
			return false;
		}
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

	function copy_program($start_time,$end_time)
	{
		$channel_id = $this->channel_id;
		$save_time = $this->save_time;
		$date = $this->getDates($start_time,$end_time);
		$program = array();
		foreach($date as $key => $value)
		{
			$program_plan = $this->getPlan($channel_id,$key);
			$start = strtotime($key . " 00:00:00");
			$end = strtotime($key . " 23:59:59");
			$com_time = 0;	
			$program_day = array();
			foreach($program_plan as $k => $v)
			{
				if(!$com_time && $v['start_time'] > $start) //头
				{
					$day_time = $this->count_day_hours($start,$v['start_time']);
					foreach($day_time as $tk => $tv)
					{
						$program_day[] = $this->getInfo($tv['start'],$tv['end'],$key);
					}
				}

				if($com_time && $com_time < $v['start_time'])//中
				{
					$day_time = $this->count_day_hours($com_time,$v['start_time']);
					foreach($day_time as $tk => $tv)
					{
						$program_day[] = $this->getInfo($tv['start'],$tv['end'],$key);
					}
				}
				$com_time = $v['start_time']+$v['toff'];
				$program_day[] = $v;
			}
			if($com_time && $com_time < $end)//中
			{
				$day_time = $this->count_day_hours($com_time,$end);
				foreach($day_time as $tk => $tv)
				{
					$program_day[] = $this->getInfo($tv['start'],$tv['end'],$key);
				}
			}
			else
			{
				if(!$com_time)
				{
					$day_time = $this->count_day_hours($value['start'],$value['end']);
					foreach($day_time as $tk => $tv)
					{
						$program_day[] = $this->getInfo($tv['start'],$tv['end'],$key);
					}
				}
			}
			$program[$key] = $program_day;
		}
		return $program;
	}

	function create($info)
	{
		
	}

	function update($info,$id)
	{	
		
	}
	
	public function verify($data)
	{
		if(is_array($data))
		{
			foreach($data as $key => $value)
			{
				$sql_extra = $key . "='" . $value . "'";
			}
			$sql = "SELECT * FROM " . DB_PREFIX . "coding WHERE 1 AND " . $sql_extra;
			$r = $this->db->query_first($sql);
			if($r['id'])
			{
				return $r;
			}
		}
		return false;
	}

	public function count($condition)
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "coding WHERE 1 ";
		$sql = $sql . $condition;
		$r = $this->db->query_first($sql);
		return $r;
	}

	/**
	*	根据频道ID获取频道信息
	*/
	public function getChannelById($channel_id)
	{
		if(!$channel_id)
		{
			return false;
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "channel WHERE id=" . $channel_id;
		$channel = $this->db->query_first($sql);
		if(!$channel)
		{
			return false;
		}
		return $channel;
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

	private function getInfo($start,$end,$dates)
	{
		$save_time = $this->save_time;
		$display = $now_display = 0;
		if((TIMENOW - 3600 * $save_time) <= $start && $start <= TIMENOW)
		{
			$display = 1;
		}

		if($end > TIMENOW  && $start < TIMENOW)
		{
			$now_display = 1;
		}
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
		$save_time = $this->save_time;
		$sql = "SELECT * FROM " . DB_PREFIX . "program_plan p LEFT JOIN " . DB_PREFIX . "program_plan_relation r ON r.plan_id=p.id WHERE 1 and p.channel_id=" . $channel_id . " AND r.week_num=" . date("N",strtotime($dates)) . " ORDER BY p.start_time ASC";
		$q = $this->db->query($sql);
		$program_plan = array();
		while($r = $this->db->fetch_array($q))
		{
			$display = $now_display = 0;
			if((TIMENOW - 3600 * $save_time) <= strtotime($dates . " " . date("H:i:s",$r['start_time'])) && strtotime($dates . " " . date("H:i:s",$r['start_time'])) <= TIMENOW)
			{
				$display = 1;
			}

			if(strtotime($dates . " " . date("H:i:s",$r['start_time']))+$r['toff'] > TIMENOW  && strtotime($dates . " " . date("H:i:s",$r['start_time'])) < TIMENOW)
			{
				$now_display = 1;
			}

			$program_plan[] = array(
					'id' => hg_rand_num(10),	
					'channel_id' => $r['channel_id'],
					'start_time' => strtotime($dates . " " . date("H:i:s",$r['start_time'])),	
					'toff' =>  $r['toff'],	
					'theme' => $r['program_name'],	
					'subtopic' => '',	
					'type_id' => 1,	
					'dates' => $dates,	
					'weeks' => date('W',strtotime($dates . " " . date("H:i:s",$r['start_time']))),	
					'describes' => '',	
					'create_time' => TIMENOW,	
					'update_time' => TIMENOW,	
					'ip' => hg_getip(),	
					'is_show' => 1,	
					'start' => date("H:i",strtotime($dates . " " . date("H:i:s",$r['start_time']))),	
					'end' => date("H:i",strtotime($dates . " " . date("H:i:s",$r['start_time'])) + $r['toff']),	
					'display' => $display,
					'now_display' => $now_display,
					'is_plan' => 1,
				);
		}
		return $program_plan;
	}
}

?>