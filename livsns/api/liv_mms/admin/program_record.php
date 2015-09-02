<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_record.php 7586 2012-07-05 09:40:56Z repheal $
***************************************************************************/
require('global.php');
class programRecordApi extends BaseFrm
{
	private $weeks;
	private $years;
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	private function getDayOfWeeks($year,$week,$type = 0)
	{
		if($type)
		{
			return date('Y.m.d',strtotime('+' . ($week-1) . ' week 2 days',strtotime($year . '-01-01')));
		}
		else
		{
			return date('m.d',strtotime('+' . $week . ' week 1 days',strtotime($year . '-01-01')));
		}
	} 

	private function getPlan($channel_id,$dates)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "program_plan p LEFT JOIN " . DB_PREFIX . "program_plan_relation r ON r.plan_id=p.id WHERE 1 and p.channel_id=" . $channel_id . " AND r.week_num=" . date("N",strtotime($dates)) . " ORDER BY p.start_time ASC";
		$q = $this->db->query($sql);
		$program_plan = array();
		while($r = $this->db->fetch_array($q))
		{
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
					'is_plan' => 1,
				);
		}
		return $program_plan;
	}

	private function program_plan($channel_id,$start,$end)
	{
		if(!$channel_id || !$start || !$end)
		{
			return false;
		}
		$dates = date('Y-m-d',$start);
		$start_time = strtotime($dates . ' 00:00:00');
		$end_time = strtotime($dates . ' 23:59:59');
		
		$program_plan = $this->getPlan($channel_id,$dates);
		$sql = "SELECT * FROM " . DB_PREFIX . "program WHERE channel_id=" . $channel_id . " and dates='" . $dates . "' ORDER BY start_time ASC";
		$q = $this->db->query($sql);
		$program = array();
		$com_time = 0;//取节目的最大时间和最小时间
		while($r = $this->db->fetch_array($q))
		{
			if(!$com_time && $r['start_time'] > $start_time)//头
			{
				$plan = $this->verify_plan($program_plan,$start_time,$r['start_time']);
				if($plan)
				{
					foreach($plan as $k => $v)
					{
						$program[] = $v;
					}
				}
			}
			if($com_time && $com_time != $r['start_time'])//中
			{
				$plan = $this->verify_plan($program_plan,$com_time,$r['start_time']);
				if($plan)
				{
					foreach($plan as $k => $v)
					{
						$program[] = $v;
					}
				}
			}
			$r['start'] = date('H:i:s',$r['start_time']);
			$r['end'] = date('H:i:s',$r['start_time']+$r['toff']);
			$com_time = $r['start_time']+$r['toff'];
			$program[] = $r;
		}

		if($com_time && $com_time < $end_time)//尾
		{
			$plan = $this->verify_plan($program_plan,$com_time,$end_time);
			if($plan)
			{
				foreach($plan as $k => $v)
				{
					$program[] = $v;
				}
			}
		}else
		{
			$plan = $this->verify_plan($program_plan,$com_time,$end_time);
			if($plan)
			{
				foreach($plan as $k => $v)
				{
					$program[] = $v;
				}
			}
		}
		if(!empty($program))
		{
			$str = $space = '';
			foreach($program as $key => $value)
			{
				if($value['start_time'] == $start && ($value['start_time']+$value['toff']) == $end)
				{
					$str =  $value['theme'];
					break;
				}
				else
				{
					if($value['start_time'] >= $start && $value['start_time'] < $end)
					{
						$str .= $space . $value['theme'];
						$space = ',';
					}
					if($value['start_time'] < $start)
					{
						if(($value['start_time']+$value['toff']) > $start)
						{
							$str .= $space . $value['theme'];
							$space = ',';
						}
					}				
				}
			}
			return $str;
		}
		else
		{
			return false;
		}
	}

	private function verify_plan($plan,$start_time,$end_time)
	{
		$program_plan = array();
		if(!empty($plan))
		{
			foreach($plan as $k => $v)
			{
				if($v['start_time'] >= $start_time && ($v['start_time']+$v['toff']) <= $end_time)
				{
					$program_plan[] = $v;
				}
			}
			return $program_plan;
		}
		else
		{
			return false;
		}
	}

	

	/**
	 * 显示录播节目单
	 */
	function show()
	{
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$data_limit = " LIMIT " . $offset . " , " . $count;
		$sql = "select p.*,v.sort_name,c.name as channel from " . DB_PREFIX . "program_record p left join " . DB_PREFIX . "vod_sort v on p.item = v.id left join " . DB_PREFIX . "channel c on c.id=p.channel_id ";
		$sql .= " where 1 " . $condition . " ORDER BY p.is_record ASC,p.start_time ASC " . $data_limit;
		$q = $this->db->query($sql);
		$week_day_arr = array('1' => '一', '2' => '二', '3' => '三', '4' => '四', '5' => '五', '6' => '六', '7' => '日');

		$info = array();
		while($row = $this->db->fetch_array($q))
		{
			$dates = date('Y-m-d', $row['start_time']);
			$channel_id = $row['channel_id'];
			$start_time = $row['start_time'];
			$week_day = unserialize($row['week_day']);
			
			
			$row['start_time'] = date('H:i:s', $row['start_time']);
			$row['end_time'] = date('H:i:s', $row['toff'] + $start_time);

			$mins = floor($row['toff']/60);
			$sen = $row['toff'] - $mins*60;
			$row['toff_decode'] = ($mins?$mins."'":'').($sen?$sen."''":'');

			$row['channel_id'] = $channel_id;
			$row['dates'] = $dates;
			$row['w'] = date('w',strtotime($start_time));

			if(!empty($week_day))
			{
				if(count($week_day) == 7)
				{
					$row['cycle'] = '每天';
				}
				else
				{
					$spac = '';
					foreach(unserialize($row['week_day']) as $k => $v)
					{
						$row['cycle'] .= $spac.$week_day_arr[$v];
						$spac = '&nbsp;|&nbsp;';
					}		
				}
				$row['action'] = $dates;
			}
			else
			{
				$row['cycle'] = date('Y-m-d',$start_time);
				$row['action'] = $row['toff'] + $start_time > TIMENOW ? $dates : ($row['is_out'] ? '已过期' : $row['is_record'] ? '已执行':'未执行');
			}
			$info[] = $row;
		}

		foreach($info as $key => $value)
		{
			$spa = '';
			$start_time = strtotime($value['dates'] . " ". $value['start_time']);
			$end_time = strtotime($value['dates'] . " ". $value['start_time']) + $value['toff'];
			$value['title'] = $value['title'] ? $value['title'] : $this->program_plan($value['channel_id'],$start_time,$end_time);
			$value['title'] = $value['title'] ? $value['title'] : '精彩节目';
		//	hg_pre($value);
			$this->addItem($value);
		}
		$this->output();
	}
	/**
	 * 显示录播节目单
	 */
	function show2()
	{
		$condition = $this->get_condition();
		$channel_id = $this->input['channel_id']?$this->input['channel_id']:1;
//		$weeks = $this->input['weeks']?$this->input['weeks']:date('W');
		$sql = "select p.id,p.theme,p.start_time,p.toff,p.subtopic,p.channel_id,p.type_id,p.weeks,p.dates from " . DB_PREFIX . "program p  where 1 " ;
		$sql .= $condition." ORDER BY p.start_time ASC";
		$q = $this->db->query($sql);
		$space = $program_id = '';
		$program = array();
		while($row = $this->db->fetch_array($q))
		{
			$row['stime'] = date("Y-m-d H:i:s",$row['start_time']);
			$row['etime'] = date("Y-m-d H:i:s",($row['start_time']+$row['toff']));
			$program[$row['id']] = $row;
			$program_id .= $space . $row['id'];
			$space = ",";
		}
		if (!$program_id)
		{
			$program_id = 0;
		}
		
		
		$sql = "SELECT * FROM " . DB_PREFIX . "program_record WHERE 1 and program_id IN(" . $program_id . ") order by start_time asc";
		$query = $this->db->query($sql);
		$record = array();
		while($r = $this->db->fetch_array($query))
		{
			$key = $r['years'] . "-" . $r['months'] . "-" .  $r['days'];
			$key = date('w',strtotime($key));
			$record[$key][] = $program[$r['program_id']];
		}
		
		$program_record = array();
		foreach($record as $key => $value)
		{
			$i = 0;
			$pre_end = 0;
			$total = count($value);
			foreach($value as $k => $v)
			{
				//头部
				if(!$i && $v['start_time'] != strtotime($v['dates']." 00:00:00"))
				{
					$program_record[$key][] = array(
						'program_id' => strtotime($v['dates']." 00:00:00"),
						'theme' => '',
						'start_time' => strtotime($v['dates']." 00:00:00"),
						'end_time' => $v['start_time'],
						'toff' => $v['start_time'] - strtotime($v['dates']." 00:00:00"),
						'type_id' => $v['type_id'],
						'channel_id' => $v['channel_id'],
						'weeks' => $v['weeks'],
						'stime' => date("H:i",strtotime($v['dates']." 00:00:00")),
						'etime' => date("H:i",$v['start_time'])
					);
				}
			
				if($pre_end && $pre_end != $v['start_time'])
				{
					$program_record[$key][] = array(
						'program_id' => $pre_end,
						'theme' => '',
						'start_time' => $pre_end,
						'end_time' => $v['start_time'],
						'toff' => $v['start_time'] - $pre_end,
						'type_id' => $v['type_id'],
						'channel_id' => $v['channel_id'],
						'weeks' => $v['weeks'],
						'stime' => date("H:i",$pre_end),
						'etime' => date("H:i",$v['start_time'])
					);
				}
				
				$program_record[$key][] = array(
						'program_id' => $v['id'],
						'theme' => $v['theme'],
						'start_time' => $v['start_time'],
						'end_time' => $v['start_time']+$v['toff'],
						'toff' => $v['toff'],
						'type_id' => $v['type_id'],
						'channel_id' => $v['channel_id'],
						'weeks' => $v['weeks'],
						'stime' => date("H:i",$v['start_time']),
						'etime' => date("H:i",($v['start_time']+$v['toff']))
				);
				
				//结尾
				if($i == ($total-1) && ($v['start_time']+$v['toff']) < strtotime($v['dates']." 23:59:59"))
				{
					$program_record[$key][] = array(
						'program_id' => ($v['start_time']+$v['toff']),
						'theme' => '',
						'start_time' => ($v['start_time']+$v['toff']),
						'end_time' => strtotime($v['dates']." 23:59:59"),
						'toff' => strtotime($v['dates']." 23:59:59") - ($v['start_time']+$v['toff']),
						'type_id' => $v['type_id'],
						'channel_id' => $v['channel_id'],
						'weeks' => $v['weeks'],
						'stime' => date("H:i",($v['start_time']+$v['toff'])),
						'etime' => date("H:i",strtotime($v['dates']." 23:59:59"))
					);
				}
				
				$pre_end = $v['start_time'] + $v['toff']; 
				
				$i++;
			}
		}	
		$this->addItem_withkey('record', $program_record);
		$this->output();
	}
	
	/**
	 * Enter description here ...
	 */
	public function count()
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "program_record p WHERE 1 ";

		//获取查询条件
		$condition = $this->get_condition();				
		$sql = $sql . $condition;
		$r = $this->db->query_first($sql);

		//暂时这样处理
		echo json_encode($r);
	}
	/**
	 * 获取单条信息
	 */
	public function detail()
	{
		$id = urldecode($this->input['id']);
		if(!$id)
		{
			$condition = " ORDER BY p.id DESC LIMIT 1";
		}
		else 
		{
			$condition = " WHERE p.id IN(" . $id . ")";
		}
		$sql ="SELECT p.* FROM " . DB_PREFIX . "program_record p" . $condition;
		$row = $this->db->query_first($sql);
		$this->setXmlNode('program_record', 'info');
		
		if(is_array($row) && $row)
		{
			$row['create_time'] = date('Y-m-d H:i:s' , $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s' , $row['update_time']);
			$row['end_time'] = date('Y-m-d H:i:s' , ($row['start_time'] + $row['toff']));
			$row['start_time'] = date('Y-m-d H:i:s' , $row['start_time']);
			$row['week_day'] = $row['week_day'] ? unserialize($row['week_day']) : array();	
			$start_time = strtotime($row['dates'] . " ". $row['start_time']);
			$end_time = strtotime($row['dates'] . " ". $row['start_time']) + $row['toff'];
			//$row['title'] = $this->program_plan($row['channel_id'],$start_time,$end_time);			
			$row['title'] = $row['title'] ? $row['title'] : '';		
			$this->addItem($row);
			$this->output();
		}
		else 
		{
			$this->errorOutput('录播节目不存在');
		}
	}
	
	/**
	 * 获取条件
	 */
	private function get_condition()
	{
		$condition = '';

		if($this->input['date_search'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date_search']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  p.start_time > " . $yesterday . " AND p.start_time < " . $today;
					break;
				case 3://今天的数据
					$condition .= " AND  p.start_time > " . $today . " AND p.start_time < " . $tomorrow;
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  p.start_time > " . $last_threeday . " AND p.start_time < " . $tomorrow;
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  p.start_time > " . $last_sevenday . " AND p.start_time < " . $tomorrow;
					break;
				case 'other'://所有时间段
					$start = urldecode($this->input['start_time']) ? strtotime(urldecode($this->input['start_time'])) : 0;
					if($start)
					{
						$condition .= " AND start_time > '" . $start . "'";
					}
					$end = urldecode($this->input['end_time']) ? strtotime(urldecode($this->input['end_time'])) : 0;
					if($end)
					{
						$condition .= " AND start_time < '" . $end . "'";
					}
					break;
				default://所有时间段
					break;
			}
		}
		if($this->input['channel_id']>0)
		{
			$condition .= ' AND p.channel_id=' . $this->input['channel_id'];
		}
		return $condition;
	}
}

$out = new programRecordApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>