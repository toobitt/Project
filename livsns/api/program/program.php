<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program.php 6082 2012-03-13 03:16:40Z repheal $
***************************************************************************/
define('ROOT_DIR', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_DIR . "global.php");
require(CUR_CONF_PATH."lib/functions.php");
define('MOD_UNIQUEID','program');
class programApi extends outerReadBase
{
	private $channel_id;
	private $play_time;
	private $m;
	function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/program.class.php');
		$this->obj = new program();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		$condition = '';
		$this->channel_id = $channel_id = intval($this->input['channel_id']) ? intval($this->input['channel_id']) : 0;
		if(!$channel_id)
		{
			$this->verify_content_prms($nodes);
		}
		include_once(ROOT_PATH . 'lib/class/live.class.php');
		$newLive = new live();
		$channel_info = $newLive->getChannelById($channel_id, -1);
		$channel_info = $channel_info[0];
		
		$play_time = $this->input['play_time']?$this->input['play_time']:0; //是当前要播放的时间，我不想改了
		$condition .= " AND channel_id=" . $channel_id;
		if (!isset($this->input['zone']))
		{
			$dates = urldecode($this->input['dates']) ? urldecode($this->input['dates']) : date("Y-m-d",time());
			if($play_time)
			{
				$dates = date("Y-m-d",$play_time);
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
		$condition .= " AND FROM_UNIXTIME(start_time, '%Y-%m-%d')='" . $dates . "'";

		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		
		$data_limit = " LIMIT " . $offset . " , " . $count;
		$this->play_time = $play_time;
		$sql = "select *,FROM_UNIXTIME(start_time, '%Y-%m-%d') as start,FROM_UNIXTIME(start_time, '%U') as week_set from " . DB_PREFIX . "program ";
		$sql .= ' where 1 '.$condition.' ORDER BY start_time ASC';
		$q = $this->db->query($sql);
		
		$program = array();
		$start = strtotime($dates." 00:00:00");
		$end = strtotime($dates." 23:59:59")+1;
		$com_time = 0;
		while($row = $this->db->fetch_array($q))
		{
			$start_time = $row['start_time'];
			$end_time = $row['start_time']+$row['toff'];
			$display = $lave_time = $now_play = $zhi_play = 0;
			if(!$com_time && $start_time > $start)//头
			{
				$program[] = $this->getInfo($start,$start_time,$dates);
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
		$all_program = $program_plan = $program_plan_keys = array();
		$program_template = $this->getTemplate($channel_id, $dates);
		$program_plan_tmp = $program_template;
		//$program_plan_tmp = $this->getPlan($channel_id,$dates);
		if(!empty($program_plan_tmp))
		{
			foreach($program_plan_tmp as $k => $v)
			{
				$program_plan[strtotime($v['dates'] . ' ' . date('H:i:s',$v['start_time']))] = $v;
				$program_plan[strtotime($v['dates'] . ' ' . date('H:i:s',$v['start_time']))]['start_time'] = strtotime($v['dates'] . ' ' . date('H:i:s',$v['start_time']));
			}
			$program_plan_keys = array_keys($program_plan);
		}
		$null_program_plan = 0;
		if(empty($program))
		{
			if(defined('PROGRAM_DEFAULT') && !PROGRAM_DEFAULT && !empty($program_plan_tmp))//未定义，或者不允许,节目计划存在
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
						$program[$k]['toff'] = strtotime($program[$k]['dates'] . ' 23:59:59')+1 - strtotime($program[$k]['dates'] . ' ' . $program[$k]['start']);
					}
					
					//频道接管
					$program[$k]['channel_name'] = $channel_info['name'];
					$program[$k]['channel_logo'] = array(
						'rectangle' =>  $channel_info['logo_rectangle'],
						'square' =>  $channel_info['logo_square'],
					);
					$program[$k]['channel_id'] = $channel_info['id'];
					$channel_info['starttime'] = $program[$k]['start_time'];
					$channel_info['toff'] 	   = $program[$k]['toff'];
					$program[$k]['start'] = date("H:i",$program[$k]['start_time']);
					$program[$k]['end'] = date("H:i",$program[$k]['start_time']+$program[$k]['toff']);
					$program[$k]['m3u8'] = $this->set_m3u8($channel_info);
				}
				$null_program_plan = 1;
			}
			else
			{
				$program = $this->copy_program($start,$end);
			}
		}
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
			$value['channel_stream'] = $this->set_channel_stream($channel_info);
			$new_unit_program = array();
			if($program_plan_keys && $null_program_plan)
			{
				foreach($program_plan_keys as $k => $v)
				{
					if($v >= $value['start_time'] && $v < ($value['start_time'] + $value['toff']))
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
								$value['channel_stream'] = $this->set_channel_stream($channel_info);
								$all_program[$value['start']] = $value;
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
							$new_unit_program['channel_stream'] = $this->set_channel_stream($channel_info);	
							$all_program[$new_unit_program['start']] = $new_unit_program;
							
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
							$value['channel_stream'] = $this->set_channel_stream($channel_info);	
							//
							$all_program[$value['start']] = $value;
							unset($program_plan[$v],$program_plan_keys[$k]);
						//	continue;
						}
					}
					else
					{
						$all_program[$value['start']] = $value;
					}
				}
			}
			else
			{
				$all_program[$value['start']] = $value;
			}
		}
		$tmp_all_program = array();
		foreach($all_program as $k => $v)
		{
			$tmp_all_program[] = $v;
		}
		$all_program = $tmp_all_program;
		//hg_pre($all_program);exit;
		//排序
		$tmp_program = $program = array();
		
		$length = count($all_program);
		
		//获取频道屏蔽时间段
		$curl = new curl($this->settings['App_live']['host'], $this->settings['App_live']['dir']);
		$curl->setSubmitType('post');		
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('channel_code', $channel_info['code']);
		$curl->addRequestData('dates', $dates);
		$curl->addRequestData('a', 'get_shield_zone');
		$shield_time_zone = $curl->request('program_shield.php');
		//获取结束

		for($i = 0,$j = $length; $i < $j; $i++)
		{  
	        for($k = $j-1; $k > $i; $k--) 
	        {
	            if ($all_program[$k]['start_time'] < $all_program[$k-1]['start_time'])
	            {
		            list($all_program[$k-1], $all_program[$k]) = array($all_program[$k], $all_program[$k-1]);  
	            }
	        }
	        
			if($all_program[$i]['start_time'] < TIMENOW && ($all_program[$i]['start_time']+$all_program[$i]['toff']) > TIMENOW)
			{
				$all_program[$i]['zhi_play'] = $is_zhi = empty($is_zhi) ? 1 : 0;
				$all_program[$i]['lave_time'] = $all_program[$i]['start_time']+$all_program[$i]['toff'] - TIMENOW;
				if(!$play_time)
				{
					$all_program[$i]['now_play'] = 1;
					$all_program[$i]['display'] = 1;
				}
			}
			
			if($play_time && $all_program[$i]['start_time'] <= $play_time && ($all_program[$i]['start_time']+$all_program[$i]['toff']) > $play_time)
			{
				$all_program[$i]['now_play'] = 1;
				$all_program[$i]['lave_time'] = 1;
				$all_program[$i]['display'] = 1;
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
					if ($shield_time_zone)
					{	
						$stime = $all_program[$i]['start_time'];
						$etime = $all_program[$i]['start_time'] + $all_program[$i]['toff'];
						foreach ($shield_time_zone AS $zone)
						{
							if ($zone['start_time'] <= $stime && $stime<= $zone['end_time'] || $zone['start_time'] <= $etime && $etime <= $zone['end_time'])
							{
								$all_program[$i]['display'] = 0;
								break;
							}
						}
					}
				}
			}
			$all_program[$i]['stime'] = $all_program[$i]['start'];
	        //hg_pre($all_program[$i]);
			$this->addItem($all_program[$i]);
	    }
		$this->output();
	}
	private function set_channel_stream($channel_info)
	{
			$ch_stream = array();
			if ($channel_info['channel_stream'])
			{
				foreach ($channel_info['channel_stream'] AS $k => $stream)
				{
					$temp = $channel_info;
					$temp['channel_stream'][0]['live_m3u8'] = $temp['channel_stream'][$k]['m3u8'];
					$m3u8 = $this->set_m3u8($temp);
					$ch_stream[] = array(
						'name' => $stream['name'],
						'stream_name' => $stream['stream_name'],
						'm3u8' => $m3u8,
						'bitrate' => $stream['bitrate']
					);
					
				}
			}
			return $ch_stream;
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
				if ($channel_info['server_type'] == 'nginx')
				{
					$m3u8url = parse_url($channel_stream['live_m3u8']);
					$m3u8url['path'] = str_replace(array('playlist.m3u8', 'live.m3u8'), $channel_info['starttime']*1000 . ',' .$channel_info['toff']*1000 . '.m3u8' ,$m3u8url['path']);
					$sign = hg_sign_uri($m3u8url['path'], $this->settings['live_expire'], $this->settings['sign_type']);
					$m3u8 = $m3u8url['scheme'] . '://' . $m3u8url['host'] . $m3u8url['path'] . $sign[0];
				}
				else
				{
					$m3u8url = parse_url($channel_stream['live_m3u8']);
					$m3u8 = $m3u8url['scheme'] . '://' . $m3u8url['host'] . $m3u8url['path'] . '?dvr&starttime=' . $channel_info['starttime']*1000 . '&duration=' . $channel_info['toff']*1000;
				}
			}
			else
			{
				$m3u8 = '';
			}
		}
		return $m3u8;
	}
	
	function copy_program($start_time,$end_time)
	{
		$dates = date("Y-m-d",$start_time);
		$start = strtotime($dates . " 00:00:00");
		$end = strtotime($dates . " 23:59:59")+1;
		$com_time = 0;	
		$program_day = array();
		$day_time = $this->count_day_hours($start,$end);
		foreach($day_time as $tk => $tv)
		{
			$program_day[] = $this->getInfo($tv['start'],$tv['end'],$dates);
		}
		return $program_day;
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
			'zhi_play' => 0,
			'now_play' => 0,
			'display' => 0,
			'lave_time' => $lave_time,
			'stime' => date("H:i",$start_time),
		);

		return $info;
	}
	
    //关联节目模板详细信息
    private function getTemplate($channel_id, $dates)  {
        $sql = 'SELECT * FROM '.DB_PREFIX.'program_template_relation WHERE channel_id=' . $channel_id . ' AND date = \''.$dates.'\'';
        $proTemRelation = $this->db->query_first($sql);
        if (empty($proTemRelation) || !$proTemRelation['template_id']) {
            return array();
        }
        if (!class_exists('programTemplate')) {
            include (CUR_CONF_PATH . 'lib/program_template.class.php');   
        }
        $objProgramTemplate = new programTemplate();
        $arTemplate = $objProgramTemplate->getOneById($proTemRelation['template_id']);
        $programTemplate = array(); 
        if (is_array($arTemplate['data']) && count($arTemplate['data']) > 0) {
            foreach ($arTemplate['data'] as $k => $v) {
                $v['start_time'] = strtotime($dates . ' ' . $v['start']);
                $program = array(    
                            'channel_id' => $channel_id,
                            'start_time' => $v['start_time'],   
                            'toff' =>  $v['toff'],  
                            'theme' => $v['theme'],  
                            'subtopic' => '',   
                            'type_id' => 1, 
                            'dates' => $dates,  
                            'weeks' => date('W',$v['start_time']),  
                            'describes' => '',  
                            'create_time' => TIMENOW,   
                            'update_time' => TIMENOW,   
                            'ip' => hg_getip(), 
                            'is_show' => 1, 
                            'color' => '#537ABF,#E5EEFF',   
                            'start' => $v['start'],    
                            'end' => date("H:i",strtotime($v['start_time'] + $v['toff'])), 
                            'week_set' => date('W',strtotime($v['start_time'])),   
                            'item' => $v['item'],
                            'new' => 0,
                            'outdate' => $v['start_time'] <= TIMENOW ? 1:0,
                            'is_plan' => 1,
                            'user_id' => $this->user['user_id'],
                            'user_name' => $this->user['user_name'],
                            'org_id' => $this->user['org_id'],
                            'appid' => $v['appid'],
                            'appname' => $v['appname'],
                );
                $programTemplate[] = $program;                
            }
        }
        return $programTemplate;
    }	

	private function getPlan($channel_id,$dates)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "program_plan p LEFT JOIN " . DB_PREFIX . "program_plan_relation r ON r.plan_id=p.id WHERE 1 and p.channel_id=" . $channel_id . " AND r.week_num=" . date("N",strtotime($dates)) . " ORDER BY p.start_time ASC";
		$q = $this->db->query($sql);
		$program_plan = array();
		$start = strtotime($dates . ' 00:00:00');
		$end = strtotime($dates . ' 23:59:59')+1;
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
		}
		$count = count($program_plan);
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
	
	public function detail()
	{
	
	}
	
	public function getGreaterProgram()
	{
		$now_time = TIMENOW;
		$channel_id = trim($this->input['channel_id']);
		if(empty($channel_id))
		{
			$this->errorOutput('缺少频道ID');	
		}
		$ret = $this->obj->getGreaterProgram($channel_id,$now_time);
		if(!empty($ret))
		{
			$this->addItem($ret);
			$this->output();
		}		
	}
	
	public function getCurrentProgram()
	{
		$now_time = TIMENOW;
		$channel_id = trim($this->input['channel_id']);
		$ret = $this->obj->getCurrentProgram($channel_id,$now_time);
		$this->addItem($ret);
		$this->output();
	}
	
	public function getCurrentNextProgram()
	{
		$now_time = TIMENOW;
		$channel_id = trim($this->input['channel_id']);
		$ret = $this->obj->getCurrentNextProgram($channel_id, $now_time);
		if (0 && is_array($ret))
		{
			foreach ($ret AS $v)
			{
				$this->addItem($v);
			}
		}
		$this->addItem($ret);
		$this->output();
	}
	
	public function getTimeshift()
	{
		$channel_id = intval($this->input['channel_id'] ? $this->input['channel_id'] : '');
		if(empty($channel_id))
		{
			$this->errorOutput('缺少频道ID');	
		}
		include_once(ROOT_PATH . 'lib/class/live.class.php');
		$newLive = new live();
		$channel = $newLive->getChannelById($channel_id, -1);
		if(empty($channel))
		{
			$this->errorOutput("此频道不存在或者已被删除！");
		}
		$channel = $channel[0];
		$channel_name = $channel['name'];
		$save_time = $channel['time_shift'];
		if (!$this->input['dates'])
		{
			$this->input['dates'] = date("Y-m-d");
		}
		$end_time = strtotime($this->input['dates']." 23:59:59")+1;
		$start_time = strtotime(date("Y-m-d",($end_time - 3600 * $save_time))." 00:00:00");
		//$end_time = strtotime(date("Y-m-d",TIMENOW)." 24:00:00");
		$condition = " AND channel_id=" . $channel_id . " AND  start_time>'" . $start_time ."' AND start_time<" . $end_time ."";
		$info = $this->obj->show($condition,$channel_id,$start_time,$end_time,$save_time, true);
		$date_type = array(
			'00:00~09:00' => array(
				'start'=> '00:00:00',
				'end'=> '08:59:59'
					),	
			'09:00~13:00' => array(
				'start'=> '09:00:00',
				'end'=> '12:59:59'
					),	
			'13:00~19:00' => array(
				'start'=> '13:00:00',
				'end'=> '18:59:59'
					),	
			'19:00~24:00' => array(
				'start'=> '19:00:00',
				'end'=> '23:59:59'
					),	
		);
		//串联单时移
		$today = date('Y-m-d',TIMENOW);
		$stime = trim($this->input['stime']);
		
		if ($dates > $today)
		{
			$day_offset = (strtotime($dates) - strtotime($today))/86400;
		}
		$i = $day_offset;
		if(!empty($info))
		{
			$tmp_info = $info;
			$info = array();
			foreach($tmp_info as $k => $v)
			{
				$info[$v['dates']][] = $v;
			}
			$program = array();
			foreach($info as $ks => $vs)
			{
				$day_program = array();
				$v['dates'] = '';
				foreach($vs as $k => $v)
				{
					if ($dates > $today)
					{
						$_dates = date('Y-m-d', strtotime($today) + 86400 * $i);
					}
					
					$v['starttime'] =  date("Y-m-d H:i:s",$v['start_time']);
					$v['endtime'] =  date("Y-m-d H:i:s",($v['start_time']+$v['toff']));
					$v['start'] = date("H:i",$v['start_time']);
					$v['channel_name'] = $channel_name;
					//补齐频道id
					$v['channel_id'] = $v['channel_id'] ? $v['channel_id'] : $channel_id;
					foreach($date_type as $key => $value)
					{
						$start = strtotime($v['dates'] . " " . $value['start']);
						$end = $value['end'] == '23:59:59' ? (strtotime($v['dates'] . " " . $value['end']) + 1) : strtotime($v['dates'] . " " . $value['end']);
						if($v['start_time'] >= $start && $v['start_time'] < $end)
						{
							if ($dates > $today)
							{
								$v['starttime'] = $_dates . ' ' . date('H:i:s', $v['start_time']);
								$v['endtime'] = $_dates . ' ' . date('H:i:s', ($v['start_time'] + $v['toff']));
								$v['dates'] = $_dates;
								$v['start_time'] = strtotime($v['starttime']);
								$v['weeks'] = date('W', $v['start_time']);
								if (strtotime($_dates . ' ' . $stime) < ($v['start_time']+$v['toff']) && $i == $day_offset)
								{
									$v['display'] = 0;
								}
							}
							$day_program[$key][] = $v;
						}
					}
				}
				if($day_program)
				{
					$program[$v['dates']] = $day_program;
				}
				
				$i --;
			}
			krsort($program);
			$this->addItem($program);
			$this->output();
		}
		else
		{
			$this->errorOutput('noprogram');
		}		
	}
	
	public function getTimeshiftNew()
	{
		$channel_id = intval($this->input['channel_id'] ? $this->input['channel_id'] : '');
		if(empty($channel_id))
		{
			$this->errorOutput('缺少频道ID');	
		}
		include_once(ROOT_PATH . 'lib/class/live.class.php');
		$newLive = new live();
		$channel = $newLive->getChannelById($channel_id);
		if(empty($channel))
		{
			$this->errorOutput("此频道不存在或者已被删除！");
		}
		$channel = $channel[0];
		$channel_name = $channel['name'];
		$save_time = $channel['time_shift'];

		$start_time = strtotime(date("Y-m-d",(TIMENOW - 3600 * $save_time))." 00:00:00");
		$end_time = strtotime(date("Y-m-d",TIMENOW)." 23:59:59")+1;
		$condition = " AND channel_id=" . $channel_id . " AND  start_time>'" . $start_time ."' AND start_time<" . $end_time ."";
		$info = $this->obj->show($condition,$channel_id,$start_time,$end_time,$save_time);
		$this->addItem($info);
		$this->output();	
	}
	
	public function count()
	{
		
	}
	
	public function get_program_info()
	{
		$channel_id = trim($this->input['channel_id']);
		$dates		= trim($this->input['dates']);
		$field		= $this->input['field'] ? $this->input['field'] : '*';
		
		if (!$channel_id)
		{
			$this->errorOutput('未传入频道id');
		}
		
		if (!$dates)
		{
			$this->errorOutput('未传入日期');
		}
		
		$return = $this->obj->get_program_info($channel_id, $dates, $field);
		if (!empty($return))
		{
			foreach ($return AS $v)
			{
				$this->addItem($v);
			}
		}
		
		$this->output();
	}
	
	/**
	 * 检测是否存在节目单
	 * Enter description here ...
	 */
	public function check_program_exists()
	{
		$channel_id = trim($this->input['channel_id']);
		$dates		= trim($this->input['dates']);
		
		if (!$channel_id)
		{
			$this->errorOutput('未传入频道id');
		}
		
		if (!$dates)
		{
			$this->errorOutput('未传入日期');
		}
		
		$sql = "SELECT id FROM " . DB_PREFIX . "program ";
		$sql.= " WHERE 1 AND channel_id = " . $channel_id . " AND dates = '" . $dates . "' LIMIT 1 ";
		
		$return = $this->db->query_first($sql);
		
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