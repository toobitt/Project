<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program.php 6082 2012-03-13 03:16:40Z repheal $
***************************************************************************/
define('MOD_UNIQUEID','program');
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_PATH."global.php");
require(CUR_CONF_PATH."lib/functions.php");
class programApi extends outerReadBase
{
	private $channel_id;
	private $play_time;
	private $m;
	private $mProgramShield;
	function __construct()
	{
		$this->mNeedCheckIn = false;
		parent::__construct();
		include_once CUR_CONF_PATH . 'lib/program_shield.class.php';
		$this->mProgramShield = new programShield();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	function show()
	{
		$this->channel_id = $this->input['channel_id'];
		
		$sql = "SELECT t1.*, t2.core_in_host, t2.core_out_port, t2.is_dvr_output, t2.dvr_in_host, t2.dvr_out_port  FROM " . DB_PREFIX . "channel t1 ";
		$sql.= " LEFT JOIN " . DB_PREFIX . "server_config t2 ON t2.id = t1.server_id ";

		if($this->channel_id == 'latest')
		{
			$sql.= " WHERE 1  AND t1.stream_state ";
			$sql.= " ORDER BY t1.id DESC LIMIT 1";
		}
		else
		{
			$sql.= " WHERE t1.id = " . intval($this->channel_id);
		}
		
		$channel_info = $this->db->query_first($sql);
		$this->channel_id  = intval($channel_info['id']);
		if(!$this->channel_id)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$play_time = $this->input['play_time']?$this->input['play_time']:0; //是当前要播放的时间，我不想改了
		$info = array();
		if(!$channel_info)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		
		if ($channel_info['logo_info'])
		{
			$channel_info['logo_info'] = @unserialize($channel_info['logo_info']);
			$channel_info['logo']['rectangle']['host'] = $channel_info['logo_info']['host'];
			$channel_info['logo']['rectangle']['dir'] = $channel_info['logo_info']['dir'];
			$channel_info['logo']['rectangle']['filepath'] = $channel_info['logo_info']['filepath'];
			$channel_info['logo']['rectangle']['filename'] = $channel_info['logo_info']['filename'];
		}
		
		if ($channel_info['logo_mobile_info'])
		{
			$channel_info['logo_mobile_info'] = @unserialize($channel_info['logo_mobile_info']);
			$channel_info['logo']['square']['host'] = $channel_info['logo_mobile_info']['host'];
			$channel_info['logo']['square']['dir'] = $channel_info['logo_mobile_info']['dir'];
			$channel_info['logo']['square']['filepath'] = $channel_info['logo_mobile_info']['filepath'];
			$channel_info['logo']['square']['filename'] = $channel_info['logo_mobile_info']['filename'];
		}
		
		if ($channel_info['core_in_host'])
		{
			if ($channel_info['is_dvr_output'])
			{
				$wowzaip = $channel_info['dvr_in_host'] . ':' . $channel_info['dvr_out_port'];
			}
			else 
			{
				$wowzaip = $channel_info['core_in_host'] . ':' . $channel_info['core_out_port'];
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
		
		$channel_info['m3u8'] = hg_streamUrl($wowzaip, $channel_info['code'], $channel_info['main_stream_name'] . $suffix, 'm3u8', '', 'dvr');
		
		if (!isset($this->input['zone']))
		{
			$dates = $this->input['dates'] ? urldecode($this->input['dates']) : date('Y-m-d');
			if($play_time)
			{
				$dates = date('Y-m-d', $play_time);
			}
		}
		else
		{
			$zone = intval($this->input['zone']);
			if ($zone)
			{
				$dates = date('Y-m-d', strtotime($zone . ' day'));
			}
			else
			{
				$dates = date('Y-m-d');
			}
		}
		$this->play_time = $play_time;
		$sql = "select id,channel_id,start_time,toff,theme,subtopic,type_id,dates,weeks,describes from " . DB_PREFIX . "program where channel_id=" . $this->channel_id . " AND dates='" . $dates . "' ORDER BY start_time ASC ";
		$q = $this->db->query($sql);
		$this->m = 0;
		$program_plan = $this->getPlan($dates);
		$program = array();
		$start = strtotime($dates." 00:00:00");
		$end = strtotime($dates." 23:59:59");
		$com_time = 0;

		while($row = $this->db->fetch_array($q))
		{
			$start_time = $row['start_time'];
			$end_time = $row['start_time']+$row['toff'];
			$display = $lave_time = $now_play = $zhi_play = 0;
			if(!$com_time && $start_time > $start)//头
			{
				$plan = $this->verify_plan($program_plan,$start,$start_time);
				if($plan)
				{
					foreach($plan as $k => $v)
					{
						$program[] = $v;
					}
				}
				else
				{
					$program[] = $this->getInfo($start,$start_time,$dates); 
				}
			}

			if($com_time && $com_time != $start_time)//中
			{
				$plan = $this->verify_plan($program_plan,$com_time,$start_time);
				if($plan)
				{
					foreach($plan as $k => $v)
					{
						$program[] = $v;
					}
				}
				else
				{
					$program[] = $this->getInfo($com_time,$start_time,$dates); 
				}
			}

			if($start_time < TIMENOW)
			{
				$display = 1;
			}
			if($start_time < TIMENOW && $end_time > TIMENOW)
			{
				$zhi_play = 1;
				$lave_time = $end_time - TIMENOW;
				if(!$this->m &&!$play_time)
				{
					$now_play = $this->m = 1;
				}
			}

			if($play_time && $start_time <= $play_time && $end_time > $play_time)
			{
				$now_play = 1;
				$lave_time = 0;
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
		if($com_time && $com_time < $end)//中
		{
			$plan = $this->verify_plan($program_plan,$com_time,$end);
			if($plan)
			{
				foreach($plan as $k => $v)
				{
					$program[] = $v;
				}
			}
			else
			{
				$program[] = $this->getInfo($com_time,$end,$dates); 
			}
		}

		if(empty($program))
		{
			$this->m = 0;
			$program = $this->copy_program($start,$end);
		}
/*
		include(CUR_CONF_PATH . 'lib/program_screen.class.php');
		$this->screen = new programScreen();
		$cond = " AND channel_id=" . $this->channel_id . " AND date='" . $dates . "'";
		$screen = $this->screen->show($cond);
		if(!empty($program) && !empty($screen)) //处理屏蔽节目
		{		
			foreach($program as $k => $v)
			{
				if(!$v['new'])
				{
					$start_time = $v['start_time'];
					$end_time = $v['start_time'] + $v['toff'];
					foreach($screen as $key => $value)
					{
						if($value['start_time'] == $start_time && ($value['start_time']+$value['toff']) == $end_time)
						{
							$program[$k]['screen_id'] = $value['id'];
							return false;
						}
						else
						{
							if($value['start_time'] >= $start_time && $value['start_time'] < $end_time)
							{
								$program[$k]['screen_id'] = $value['id'];
							}

							if($value['start_time'] < $start_time)
							{
								if(($value['start_time']+$value['toff']) > $start_time)
								{
									$program[$k]['screen_id'] = $value['id'];
								}
							}				
						}
					}					
				}
			}
		}
*/
		//屏蔽节目
		$orderby = " ORDER BY start_time ASC ";
		$shield = $this->mProgramShield->show($this->channel_id, $dates, '', $orderby);
		
		foreach($program as $key => $value)
		{
			$value['is_shield'] = 0;
			$value['end_time'] = $value['start_time'] + $value['toff'];
			if ($value['start_time'] < (TIMENOW - ($channel_info['save_time'] * 3600)))
			{
				$value['display'] = 0;
			}
			else 
			{
				//屏蔽
				if (!empty($shield))
				{
					foreach ($shield AS $v)
					{
						$v['end_time'] = $v['start_time'] + $v['toff'];
						if (($v['end_time'] >= $value['end_time'] && $v['start_time'] < $value['end_time']) || ($v['start_time'] < $value['end_time'] && $v['start_time'] >= $value['start_time']) || ($value['start_time'] >= $v['start_time'] && $value['start_time'] < $v['end_time']))
						{
							$value['is_shield'] = 1;
						}
					}
				}
			}
			$value['channel_name'] = $channel_info['name'];
			$value['channel_logo'] = $channel_info['logo'];
			$value['channel_id'] = $channel_info['id'];
			$value['m3u8'] = $channel_info['m3u8'] . '&starttime=' . $value['start_time'] . '000';
			$value['livem3u8'] = $channel_info['m3u8'];
			//if (!$value['now_play'])
			{
				$value['m3u8'] .= '&duration=' . $value['toff'] . '000';
			}
			$this->addItem($value);	
		}
		$this->output();
	}

	function copy_program($start_time,$end_time)
	{
		//$channel_id = $this->channel_id;
		$dates = date("Y-m-d",$start_time);
		$program_plan = $this->getPlan($dates);

		$start = strtotime($dates . " 00:00:00");
		$end = strtotime($dates . " 23:59:59");
		$com_time = 0;	
		$program_day = array();
		if(!empty($program_plan))
		{
			foreach($program_plan as $k => $v)
			{
				if(!$com_time && $v['start_time'] > $start) //头
				{
					$day_time = $this->count_day_hours($start,$v['start_time']);
					foreach($day_time as $tk => $tv)
					{
						$program_day[] = $this->getInfo($tv['start'],$tv['end'],$dates);
					}
				}

				if($com_time && $com_time < $v['start_time'])//中
				{
					$day_time = $this->count_day_hours($com_time,$v['start_time']);
					foreach($day_time as $tk => $tv)
					{
						$program_day[] = $this->getInfo($tv['start'],$tv['end'],$dates);
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
					$program_day[] = $this->getInfo($tv['start'],$tv['end'],$dates);
				}
			}
		}
		else
		{
			$day_time = $this->count_day_hours($start,$end);
			foreach($day_time as $tk => $tv)
			{
				$program_day[] = $this->getInfo($tv['start'],$tv['end'],$dates);
			}
		}
		return $program_day;
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

	private function getInfo($start_time,$end_time,$dates)
	{
		$play_time = $this->play_time;
		$display = $lave_time = $now_play = $zhi_play = 0;
		if($start_time < TIMENOW)
		{
			$display = 1;
		}
		if($start_time < TIMENOW && $end_time > TIMENOW)
		{
			$zhi_play = 1;
			$lave_time = $end_time - TIMENOW;
			if(!$this->m &&!$play_time)
			{
				$now_play = $this->m = 1;
			}
		}

		if($play_time && $start_time <= $play_time && $end_time > $play_time)
		{
			$now_play = 1;
			$lave_time = 0;
		}
		
		$info = array(
			'id' => $start_time,
			'channel_id' => $this->channel_id,
			'start_time' => $start_time,	
			'toff' =>  $end_time-$start_time,	
			'theme' => '精彩节目',
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
		);

		return $info;
	}

	private function getPlan($dates)
	{
		$save_time = $this->save_time;
		$play_time = $this->play_time;
		$sql = "SELECT * FROM " . DB_PREFIX . "program_plan p LEFT JOIN " . DB_PREFIX . "program_plan_relation r ON r.plan_id=p.id WHERE 1 and p.channel_id=" . $this->channel_id . " AND r.week_num=" . date("N",strtotime($dates)) . " ORDER BY p.start_time ASC";
		$q = $this->db->query($sql);
		$program_plan = array();
		while($r = $this->db->fetch_array($q))
		{			
			$start_time = strtotime($dates . " " . date("H:i:s",$r['start_time']));
			$end_time = strtotime($dates . " " . date("H:i:s",$r['start_time'])) + $r['toff'];
			$display = $lave_time = $now_play = $zhi_play = 0;
			if($start_time < TIMENOW)
			{
				$display = 1;
			}
			if($start_time < TIMENOW && $end_time > TIMENOW)
			{
				$zhi_play = 1;
				$lave_time = $end_time - TIMENOW;
				if(!$this->m &&!$play_time)
				{
					$now_play = $this->m = 1;
				}
			}

			if($play_time && $start_time <= $play_time && $end_time > $play_time)
			{
				$now_play = 1;
				$lave_time = 0;
			}

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
		return $program_plan;
	}
	public function count()
	{
		
	}
	public function detail()
	{
		
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