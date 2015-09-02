<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program.php 7071 2012-06-08 05:22:40Z repheal $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','program');
class programApi extends adminReadBase
{
	private $weeks;
	private $years;
	function __construct()
	{
		######分类和操作追加######
		$this->mNodes = array(
			'program_node' => '频道列表',
		);
		$this->mPrmsMethods['uploads'] = array( 
			'name' => '上传节目',
			'node' => 1,
			'append' => ''
		);

		######分类和操作追加######
		parent::__construct();
		unset($this->mPrmsMethods['audit'],$this->mPrmsMethods['sort']);
		require_once ROOT_PATH . 'lib/class/live.class.php';
		$this->mNewLive = new live();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	private function getDayInfoOfWeek($year,$week,$day)
	{
		$week = $week-1;
		$info = array('日','一','二','三','四','五','六');
		
		$day = intval($day) + 1;
		
		$week = Date('Y-m-d',strtotime('+' . $week . ' week ' . $day . ' days',strtotime($year . '-01-01')));
		$date = $info[date('w',strtotime($week))];

		return array('w' => $week,'d' => $date);
	}
	
	private function getWeek($today_format = '')
	{
		$today_format = $today_format ? $today_format : strtotime(date('Y-m-d'));
		
		$dates = array();
		for ($i = 0; $i < 7; $i ++)
		{
			$time = $today_format + ($i * 86400);
			$dates[$i] = date('Y-m-d', $time);
		}
		return $dates;
	}
	
	private function getWeekInfo($channel_id, $today_format = '')
	{
		$dates = $this->getWeek($today_format);
		
		$sql  = "SELECT channel_id, dates FROM " . DB_PREFIX . "program ";
		$sql .= " WHERE 1 AND channel_id IN (" . $channel_id . ") AND dates IN ('" . implode("','", $dates) . "')";
		$sql .= " ORDER BY dates ASC ";
		$q = $this->db->query($sql);
		$schedule = array();
		while ($row = $this->db->fetch_array($q))
		{
			$schedule[$row['channel_id']][$row['dates']] = $row['dates'];
		}
		
		$return = array();
		if (!empty($schedule))
		{
			foreach ($schedule AS $k => $v)
			{
				for ($i = 0; $i < 7; $i ++)
				{
					$return[$k]['week'][$i] = $dates[$i];
					$return[$k]['is_schedule'][$i] = $v[$dates[$i]] ? 1 : 0;
				}
			}
		}
		else 
		{
			$channel_id = explode(',', $channel_id);
			foreach ($channel_id AS $k => $v)
			{
				for ($i = 0; $i < 7; $i ++)
				{
					$return[$v]['week'][$i] = $dates[$i];
					$return[$v]['is_schedule'][$i] = 0;
				}
			}
		}
		
		return $return;
	}


	/**
	 * 显示节目单
	 */
	function show()
	{
		$condition = '';
		$channel_id = intval($this->input['channel_id']) ? intval($this->input['channel_id']) : 0;
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		$dates = array();
		if($channel_id)
		{
			$dates = $this->getWeekInfo($channel_id);
		}
		else
		{
			$this->errorOutput(NO_CHANNEL_ID);
		}
		
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$is_action = trim($this->input['a']) == 'count' ? true:false;
			if($this->user['prms']['app_prms'][APP_UNIQUEID]['action'])
			{				
				foreach($this->user['prms']['app_prms'][APP_UNIQUEID]['action'] as $k => $v)
				{
					if($v == $this->input['a'])
					{
						$is_action = true;
					}
				}
			}
			if($is_action && $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'])
			{
				$all_node = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
				$cond = array();
				if(intval($this->input['_id']))
				{
					if(in_array(intval($this->input['_id']),$all_node))
					{
						$cond['channel_id'] = intval($this->input['_id']);
					}
				}
				else
				{
					$cond['channel_id'] = '';
                    $space = '';
                    foreach($all_node as $k => $v)
                    {
                        if($v > 0)
                        {
                            $cond['channel_id'] .= $space . $v;
                            $space = ',';
                        }
                    }
				}
			//	echo $cond['node_id'].'<br/>';exit;	
				$cond['is_stream'] = 0;
				$cond['field'] = 'id, name, code, is_control, is_audio, is_mobile_phone, server_id, logo_rectangle, logo_square, node_id';
				$channel = $this->mNewLive->getChannelInfo($cond);
				$channel_id = array();
				if (!empty($channel))
				{
					foreach ($channel AS $v)
					{
						$channel_id[] = $v['id'];
					}
				}
				if($this->input['channel_id'])
				{
					if(!in_array($this->input['channel_id'], $channel_id))
					{
						$this->errorOutput(NO_PRIVILEGE);
					}
					else
					{
						$channel_condition = intval($this->input['channel_id']);
					}
				}
				else
				{
					$channel_condition = $channel_id ? implode(',', $channel_id) : '';
				}
				$channel_condition = $channel_condition ? $channel_condition : -1;
				if($channel_condition > -1)
				{
					$condition .= ' AND channel_id = ' . $channel_condition;
				}
				else
				{
					$this->errorOutput(NO_PRIVILEGE);
				}
			}
		}
		else
		{
			$channel_condition = intval($this->input['channel_id']) ? intval($this->input['channel_id']) : -1;
		}

		$this->verify_content_prms();
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		$channel_id_info = $channel_id;
		$channel_id = $channel_condition;
		$week = array('日', '一', '二', '三', '四', '五', '六');
		$get_week = $this->getWeek();
		$channel = $this->mNewLive->getChannel();
		$_dates = $short_week = array();
		foreach ($get_week AS $k => $v)
		{
			$short_week[] = $week[date('w', strtotime($v))];
			$_dates[$k] = 0;
		}
		
		$channel_info = array();
		if(!empty($channel))
		{
			foreach($channel as $k => $row)
			{
				$row['is_schedule'] = $dates[$row['id']] ? $dates[$row['id']]['is_schedule'] : $_dates;
				if($this->user['group_type'] > MAX_ADMIN_TYPE)
				{
					if(in_array($row['id'],$channel_id_info))
					{
						if(empty($channel_info))
						{
							$channel_info['default'] = $row;
						}
						$row['logo_info'] = unserialize($row['logo_info']);
						if ($row['logo_info'])
						{
							$row['logo_url'] = hg_material_link($row['logo_info']['host'], $row['logo_info']['dir'], $row['logo_info']['filepath'], $row['logo_info']['filename'],'112x43/');
						}
						unset($row['logo_info']);
						$channel_info[$row['id']] = $row;
					}					
				}
				else
				{
					if(empty($channel_info))
					{
						$channel_info['default'] = $row;
					}
					$row['logo_info'] = unserialize($row['logo_info']);
					if ($row['logo_info'])
					{
						$row['logo_url'] = hg_material_link($row['logo_info']['host'], $row['logo_info']['dir'], $row['logo_info']['filepath'], $row['logo_info']['filename'],'112x43/');
					}
					unset($row['logo_info']);
					$channel_info[$row['id']] = $row;				
				}				
			}
		}
		$this->addItem_withkey('channel_info', $channel_info);
		$this->addItem_withkey('week', $get_week);	
		$this->addItem_withkey('short_week', $short_week);
		
		$dates = $this->input['dates'] ? $this->input['dates'] : date("Y-m-d");
		$condition .= " AND FROM_UNIXTIME(start_time, '%Y-%m-%d')='" . $dates . "'";
		if($channel_condition > -1)
		{
			$condition .= ' AND channel_id = ' . $channel_condition;
		}

		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		
		$data_limit = " LIMIT " . $offset . " , " . $count;
		$sql = "select *,FROM_UNIXTIME(start_time, '%Y-%m-%d') as start,FROM_UNIXTIME(start_time, '%U') as week_set from " . DB_PREFIX . "program ";
		$sql .= ' where 1 '.$condition.' ORDER BY start_time ASC';

		$q = $this->db->query($sql);
		
		$this->addItem_withkey('date', $dates);

		//$program_plan = $this->getPlan($channel_id,$dates);
        $program_template = $this->getTemplate($channel_id, $dates);
        $program_plan = $program_template;
		$all_program = array();
		$key = '';
		while($row = $this->db->fetch_array($q))
		{
			$start_time = $row['start_time'];
			$end_time = $row['start_time']+$row['toff'];
			$display = $lave_time = $now_play = $zhi_play = 0;
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
		//	$com_time = $end_time;
			$program[] = $row;
		}
		
		if(!empty($program))
		{
			foreach($program_plan as $k => $v)
			{
				$program_plan[$k]['start_time'] = strtotime($v['dates'] . ' ' . date('H:i:s',$v['start_time']));
			}
			foreach($program as $key => $value)
			{
				$new_unit_program = array();
				if($program_plan)
				{
					$length_plan = count($program_plan);
					for($i = 0;$i<$length_plan;$i++)
					{
						if($value['start_time'] < $program_plan[$i]['start_time'] && ($value['start_time']+$value['toff']) > $program_plan[$i]['start_time'] )
						{
							if($value['start_time'] == $program_plan[$i]['start_time'])
							{
								$value['theme'] = $program_plan[$i]['theme'];
								$value['is_plan'] = 1;
								$value['end'] = date("H:i",$value['start_time']+$value['toff']);
								$all_program[$value['start']] = $value;
								break;
							}
							else
							{
								$new_unit_program = $value;//是被切的节目单的上班部分精彩节目
								$new_unit_program['toff'] = $program_plan[$i]['start_time'] - $new_unit_program['start_time'];
								$new_unit_program['start'] = date("H:i",$new_unit_program['start_time']);
								$new_unit_program['end'] = date("H:i",$new_unit_program['start_time']+$new_unit_program['toff']);
								$all_program[$new_unit_program['start']] = $new_unit_program;
								//被切的下半部分，即为节目计划
								$value['theme'] = $program_plan[$i]['theme'];
								$value['toff'] = $value['toff'] - $new_unit_program['toff'];
								$value['start_time'] = $program_plan[$i]['start_time'];
								$value['is_plan'] = 1;
								$value['start'] = date("H:i",$value['start_time']);
								$value['end'] = date("H:i",$value['start_time']+$value['toff']);
								$all_program[$value['start']] = $value;
								break;
							}					
						}
						else
						{
							$all_program[$value['start']] = $value;
							continue;
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
		}
		$program_line = array();
		if($program_plan)//假如存在节目单计划需要重新排序
		{
			if(empty($all_program))
			{
				$all_program = $program_plan;					
			}
			$tmp_program = array();
			$length = count($all_program);
			for($i = 0,$j = $length; $i < $j; $i++)
			{  
		        for($k = $j-1; $k > $i; $k--) 
		        {  
		            if ( $all_program[$k]['start_time'] < $all_program[$k-1]['start_time'])
		            {
			            list($all_program[$k-1], $all_program[$k]) = array($all_program[$k], $all_program[$k-1]);  
		            }
		        }
		        $program_line[$i] = $all_program[$i];
		    }			
		}
		else
		{
			$program_line = $all_program;
		}
		$program = array();
		if($program_line)
		{
			$noon = strtotime($dates." 12:00");
			foreach($program_line as $k => $v)
			{
				if($this->user['group_type'] > MAX_ADMIN_TYPE)
				{
					switch($this->user['prms']['default_setting']['show_other_data'])
					{
						case 0://不允许
							if($this->user['user_id'] != $v['user_id'])
							{
								$this->errorOutput(NO_PRIVILEGE);
							}
						break;
						case 1:
							if($this->user['org_id'] != $v['org_id'])
							{
								$this->errorOutput(NO_PRIVILEGE);
							}
						break;
						case 5:
						break;
						default:
						break;
					}					
				}
				if($v['start_time'] < $noon)
				{
					$v['pos'] = hg_get_pos($v['start_time'] - strtotime($dates));
					$v['slider'] = hg_get_slider($v['start_time'] - strtotime($dates));
					$key = 'am';
				}
				else
				{
					$v['pos'] = hg_get_pos($v['start_time'] - strtotime($dates." 12:00"));
					$v['slider'] = hg_get_slider($v['start_time'] - strtotime($dates." 12:00"));
					$key = 'pm';				
				}
				$v['key'] = hg_rand_num(4);
				$program[$key][] = $v;
			}
		}
		$this->addItem_withkey('program', $program);
		$this->output();
	}

	private function getInfo($start,$end,$dates,$new=1,$type=0)
	{
		$toff = $end-$start;
		if($end-$start > 3600)
		{
			$toff = 3600;	
		}
		$info = array(
				'id' => hg_rand_num(10),	
				'channel_id' => $this->input['channel_id'],
				'start_time' => $start,	
				'toff' => $toff,	
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
				'color' => '#DF6564,#FEF2F2',	
				'start' => date("H:i",$start),	
				'end' => date("H:i",$start+$toff),	
				'week_set' => date('W',$start),	
				'item' => 0,
				'new' => $new,
				'user_id' => $this->user['user_id'],
				'user_name' => $this->user['user_name'],
				'org_id' => $this->user['org_id'],
				'appid' => $this->user['appid'],
				'appname' => $this->user['appname'],
			);
		
		if($start <= TIMENOW)
		{
			$info['outdate'] = 1;
		}
		else
		{
			$info['outdate'] = 0;
		}
		if($type)
		{
			$info['space'] = 1;
		}
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
		$week = date("w",strtotime($dates)) == 0 ? 7 : date("w",strtotime($dates));
		$condition = '';
		$sql = "SELECT * FROM " . DB_PREFIX . "program_plan WHERE channel_id=" . $channel_id . $condition;
		$q = $this->db->query($sql);
		$program_plan = $plan = array();
		$plan_id = $space = "";
		while($r = $this->db->fetch_array($q))
		{
			$plan[] = $r;
			$plan_id .= $space . $r['id'];
			$space = ',';
		}
		if($plan_id)
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "program_plan_relation WHERE plan_id IN(" . $plan_id . ")";//and week_num=" . $week;
			$q = $this->db->query($sql);
			$relation = array();
			while($row = $this->db->fetch_array($q))
			{
				$relation[$row['plan_id']][] = $row['week_num'];
			}
			$start = strtotime($dates . ' 00:00:00');
			$end = strtotime($dates . ' 23:59:59');
			foreach($plan as $k => $v)
			{	
				if(!in_array($week,$relation[$v['id']]))
				{
					continue;
				}
				if($v['toff'])
				{
					if(($v['start_time'] <= $start && ($v['start_time']+$v['toff']) >= $start) || ($v['start_time'] >= $start && ($v['start_time']+$v['toff']) <= $end) || ($v['start_time'] <= $end && ($v['start_time']+$v['toff']) >= $end))
					{
						$program_plan[] = array(	
							'channel_id' => $v['channel_id'],
							'start_time' => strtotime($dates . " " . date("H:i:s",$v['start_time'])),	
							'toff' =>  $v['toff'],	
							'theme' => $v['program_name'],	
							'subtopic' => '',	
							'type_id' => 1,	
							'dates' => $dates,	
							'weeks' => date('W',strtotime($dates . " " . date("H:i:s",$v['start_time']))),	
							'describes' => '',	
							'create_time' => TIMENOW,	
							'update_time' => TIMENOW,	
							'ip' => hg_getip(),	
							'is_show' => 1,	
							'color' => '#537ABF,#E5EEFF',	
							'start' => date("H:i",strtotime($dates . " " . date("H:i:s",$v['start_time']))),	
							'end' => date("H:i",strtotime($dates . " " . date("H:i:s",$v['start_time'])) + $v['toff']),	
							'week_set' => date('W',strtotime($dates . " " . date("H:i:s",$v['start_time']))),	
							'item' => $v['item'],
							'new' => 0,
							'outdate' => (strtotime($dates . " " . date("H:i:s",$v['start_time']))) <= TIMENOW ? 1:0,
							'is_plan' => $v['id'],
							'user_id' => $this->user['user_id'],
							'user_name' => $this->user['user_name'],
							'org_id' => $this->user['org_id'],
							'appid' => $v['appid'],
							'appname' => $v['appname'],
						);
					}
				}
				else
				{				
					if($v['start_time'] <= $start || ($v['start_time'] >= $start && $v['start_time'] <= $end))
					{
						$program_plan[] = array(	
							'channel_id' => $v['channel_id'],
							'start_time' => strtotime($dates . " " . date("H:i:s",$v['start_time'])),	
							'toff' =>  $v['toff'],	
							'theme' => $v['program_name'],	
							'subtopic' => '',	
							'type_id' => 1,	
							'dates' => $dates,	
							'weeks' => date('W',strtotime($dates . " " . date("H:i:s",$v['start_time']))),	
							'describes' => '',	
							'create_time' => TIMENOW,	
							'update_time' => TIMENOW,	
							'ip' => hg_getip(),	
							'is_show' => 1,	
							'color' => '#537ABF,#E5EEFF',	
							'start' => date("H:i",strtotime($dates . " " . date("H:i:s",$v['start_time']))),	
							'end' => date("H:i",strtotime($dates . " " . date("H:i:s",$v['start_time'])) + $v['toff']),	
							'week_set' => date('W',strtotime($dates . " " . date("H:i:s",$v['start_time']))),	
							'item' => $v['item'],
							'new' => 0,
							'outdate' => (strtotime($dates . " " . date("H:i:s",$v['start_time']))) <= TIMENOW ? 1:0,
							'is_plan' => $v['id'],
							'user_id' => $this->user['user_id'],
							'user_name' => $this->user['user_name'],
							'org_id' => $this->user['org_id'],
							'appid' => $v['appid'],
							'appname' => $v['appname'],
						);						
					}
				}
			}			
		}
		return $program_plan;
	}

	private function verify_plan($plan,$start_time,$end_time)
	{
		$program_plan = array();
		if(!empty($plan))
		{
			foreach($plan as $k => $v)
			{
				if($v['toff'])
				{
					if($v['start_time'] >= $start_time && ($v['start_time']+$v['toff']) <= $end_time)
					{
						$program_plan[] = $v;
					}
				}
				else
				{
					if($v['start_time'] <= $start_time)
					{
						$program_plan[] = $v;
					}
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
					$program[] = $this->getInfo($start,$v['start_time'],$dates);
				}

				if($com_time && $com_time != $v['start_time'])//中
				{
					$program[] = $this->getInfo($com_time,$v['start_time'],$dates); 
				}
				$v['start'] = date("H:i",$v['start_time']);
				$v['end'] = date("H:i",$v['start_time']+$v['toff']);
				if($v['start_time'] <= TIMENOW)
				{
					$v['outdate'] = 1;
				}
				else
				{
					$v['outdate'] = 0;
				}
				$com_time = $v['start_time']+$v['toff'];
				$program[] = $v;
			}
			if($com_time && $com_time < $end)//中
			{
				$program[] = $this->getInfo($com_time,$end,$dates);
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

	//节目类型
	function getType()
	{
		$this->setXmlNode('program_type' , 'info');
		foreach($this->settings['program_type'] as $key => $value)
		{
			$this->addItem($value);
		}
		$this->output();
	}
	//自动录播栏目
	function getItem()
	{
		include_once(ROOT_PATH . 'lib/class/livmedia.class.php');
		$livmedia = new livmedia();
		$ret = $livmedia->getAutoItem();
		if(!empty($ret))
		{
			foreach($ret as $k => $v)
			{
				$this->addItem($v);
			}
			$this->output();
		}
	}

	/**
	 * 取单条信息
	 */
	function detail()
	{
		$id = intval($this->input['id']) ? intval($this->input['id']) : 0;
		if(!$id)
		{
			$condition = ' ORDER BY id DESC LIMIT 1';
		}
		else
		{
			$condition = ' WHERE id in(' . $id .')';
		}			
		$sql = "SELECT * FROM " . DB_PREFIX . "program " . $condition;		
		$row = $this->db->query_first($sql);
		$id = $row['id'];
		$this->setXmlNode('program' , 'info');	
		if(is_array($row) && $row)
		{
			$row['create_time'] = date('Y-m-d H:i:s' , $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s' , $row['update_time']);
			
			//下一个节目的开始时间，时长
			$sql = "SELECT start_time FROM " . DB_PREFIX . "program WHERE id=" . $id;	//当前节目开始时间
			$time = $this->db->query_first($sql);		
	
			$old_start_time = $time['start_time'];
			
			$sql = "SELECT start_time, toff FROM " . DB_PREFIX . "program WHERE start_time >" . $old_start_time . " ORDER BY start_time ASC";
			$next_time = $this->db->query_first($sql);
			if(is_array($next_time) && $next_time)
			{
				$row['next_start_time'] = $next_time['start_time'];
				$row['next_toff'] = $next_time['toff'];
			}
			$this->addItem($row);
			$this->output();
		}
		else
		{
			$this->errorOutput('节目不存在');	
		} 	
	}
	
	/**
	 * Enter description here ...
	 */
	public function count()
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "program WHERE 1 ";

		//获取查询条件
		$condition = $this->get_condition();				
		$sql = $sql . $condition;
		$r = $this->db->query_first($sql);

		//暂时这样处理
		echo json_encode($r);
	}
	
	/**
	 * 获取条件
	 */
	private function get_condition()
	{
		$condition = '';
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			switch($this->user['prms']['default_setting']['show_other_data'])
			{
				case 0://不允许
					$condition .= ' AND user_id = ' . $this->user['user_id'];
				break;
				case 1:
					$condition .= ' AND org_id = ' . $this->user['org_id'];
				break;
				case 5:
				break;
				default:
				break;
			}
			$is_action = trim($this->input['a']) == 'count' ? true:false;
			if($this->user['prms']['app_prms'][APP_UNIQUEID]['action'])
			{				
				foreach($this->user['prms']['app_prms'][APP_UNIQUEID]['action'] as $k => $v)
				{
					if($v == $this->input['a'])
					{
						$is_action = true;
					}
				}
			}
			if($is_action && $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'])
			{
				$tmp_node = implode(',',$this->user['prms']['app_prms'][APP_UNIQUEID]['nodes']);
				$all_node = $this->mNewLive->getChildNodeByFid($tmp_node);
				$all_node = array_unique(explode(',',implode(',',$all_node)));
				$cond = array();
				if(intval($this->input['_id']))
				{
					if(in_array(intval($this->input['_id']),$all_node))
					{
						$cond['node_id'] = intval($this->input['_id']);
					}
				}
				else
				{
					$cond['node_id'] = implode(',',$all_node);
				}
			//	echo $cond['node_id'].'<br/>';exit;	
				if($cond['node_id'])
				{
					$cond['is_stream'] = 0;
					$cond['field'] = 'id, name, code, is_control, is_audio, is_mobile_phone, server_id, logo_rectangle, logo_square, node_id';
					$channel = $this->mNewLive->getChannelInfo($cond);
					$channel_id = array();
					if (!empty($channel))
					{
						foreach ($channel AS $v)
						{
							$channel_id[] = $v['id'];
						}
					}
					if($this->input['channel_id'])
					{
						if(!in_array($this->input['channel_id'], $channel_id))
						{
							$this->errorOutput(NO_PRIVILEGE);
						}
						else
						{
							$channel_condition = intval($this->input['channel_id']);
						}
					}
					else
					{
						$channel_condition = $channel_id ? implode(',', $channel_id) : '';
					}
				}
				$channel_condition = $channel_condition ? $channel_condition : -1;
				if($channel_condition > -1)
				{
					$condition .= ' AND channel_id = ' . $channel_condition;
				}
				else
				{
					$this->errorOutput(NO_PRIVILEGE);
				}
			}
		}
		else
		{
			if($this->input['channel_id'])
			{
				$condition .= ' AND channel_id = ' . intval($this->input['channel_id']);
			}
		}
		
		if($this->input['dates'])
		{
			$condition .= " AND dates = '" . trim($this->input['dates']) . "' ";
		}
		return $condition;
	}
	
	function index()
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