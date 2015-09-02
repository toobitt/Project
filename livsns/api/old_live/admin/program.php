<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program.php 7071 2012-06-08 05:22:40Z repheal $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','old_live');
class programApi extends adminReadBase
{
	private $weeks;
	private $years;
	function __construct()
	{
		parent::__construct();
		if($this->mNeedCheckIn && !$this->prms['program'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	private function getDayOfWeek($year,$week,$type = 0)
	{
		if($type)
		{
			return date('Y年m月d日',strtotime('+' . ($week-1) . ' week 2 days',strtotime($year . '-01-01')));
		}
		else
		{
			return date('m月d日',strtotime('+' . $week . ' week 1 days',strtotime($year . '-01-01')));
		}
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
	
	private function getDayInfoOfWeek($year,$week,$day)
	{
		$week = $week-1;
		$info = array('日','一','二','三','四','五','六');
		
		$day = intval($day) + 1;
		
		$week = Date('Y-m-d',strtotime('+' . $week . ' week ' . $day . ' days',strtotime($year . '-01-01')));
		$date = $info[date('w',strtotime($week))];

		return array('w' => $week,'d' => $date);
	} 

	/**
	 * 显示节目单
	 */
	function show()
	{
		
	}

	function getProgramByDay()
	{
		$condition = '';
		
		if(!$this->input['channel_id'])
		{
			$sql = "SELECT id FROM " . DB_PREFIX . "channel WHERE 1 ORDER BY order_id DESC LIMIT 1";
			$f = $this->db->query_first($sql);
			if(!empty($f))
			{
				$this->input['channel_id'] = $f['id'];
			}
			else
			{
				$this->errorOutput('暂未创建频道');
			}
		}
		$condition .= " AND channel_id=" . intval($this->input['channel_id']);
		$dates = $this->input['dates'] ? $this->input['dates'] : date("Y-m-d");
		$condition .= " AND FROM_UNIXTIME(start_time, '%Y-%m-%d')='" . $dates . "'";

		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;

		//该频道的录播记录
		$sql = "select p.id,r.start_time,r.end_time,r.channel_id,r.week_num,p.item from " . DB_PREFIX . "program_record_relation r left join " . DB_PREFIX . "program_record p on p.id = r.record_id where r.channel_id=" .  intval($this->input['channel_id']) . " and r.week_num=".date('N',strtotime($dates));
		$q = $this->db->query($sql);
		$record = array();
		while($r = $this->db->fetch_array($q))
		{
			$record[$r['id']] = $r['item'];
		}
		//所有频道信息
		$sql = "select id,logo_info,name from ". DB_PREFIX . "channel ORDER BY order_id DESC";
		$ch = $this->db->query($sql);
		$channel_info = array();
		while($row = $this->db->fetch_array($ch))
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
		$this->addItem_withkey('channel_info', $channel_info);
		
		$data_limit = " LIMIT " . $offset . " , " . $count;
		$sql = "select *,FROM_UNIXTIME(start_time, '%Y-%m-%d') as start,FROM_UNIXTIME(start_time, '%U') as week_set from " . DB_PREFIX . "program ";
		$sql .= ' where 1 '.$condition.' ORDER BY start_time ASC';
		$q = $this->db->query($sql);
		$this->addItem_withkey('date', $dates);
		$program = array();
		$start = strtotime($dates." 00:00:00");
		$end = strtotime($dates." 23:59:59");
		$com_time = 0;
		$program_plan = $this->getPlan($this->input['channel_id'],$dates);
		while($row = $this->db->fetch_array($q))
		{
			if(!$com_time && $row['start_time'] > $start)//头
			{
				$plan = $this->verify_plan($program_plan,$start,$row['start_time']);
				if($plan)
				{
					foreach($plan as $k => $v)
					{
						$program[] = $v;
					}
				}
				else
				{
					$program[] = $this->getInfo($start,$row['start_time'],$dates);
				}
			}

			if($com_time && $com_time != $row['start_time'])//中
			{				
				$plan = $this->verify_plan($program_plan,$com_time,$row['start_time']);
				if($plan)
				{
					foreach($plan as $k => $v)
					{
						$program[] = $v;
					}
				}
				else
				{
					$program[] = $this->getInfo($com_time,$row['start_time'],$dates); 
				}
			}

			$row['start'] = date("H:i",$row['start_time']);
			$row['end'] = date("H:i",$row['start_time']+$row['toff']);
			$record_verify = $row['record_id'];
			$row['item'] = $record[$record_verify]?$record[$record_verify]:0;
			if($row['start_time'] <= TIMENOW)
			{
				$row['outdate'] = 1;
			}
			else
			{
				$row['outdate'] = 0;
			}
			$com_time = $row['start_time']+$row['toff'];
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
			if(empty($program_plan))
			{
				$program[] = $this->getInfo($start,strtotime($dates." 08:00:00"),$dates,0,1);
				$program[] = $this->getInfo(strtotime($dates." 08:00:00"),$end,$dates);
			}
			else
			{
				$program = array();
				$start = strtotime($dates." 00:00:00");
				$end = strtotime($dates." 23:59:59");
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
			}
		}
	//	hg_pre($program,0);
		$this->addItem_withkey('program', $program);
		$this->output();
	}

	private function getInfo($start,$end,$dates,$new=1,$type=0)
	{
		$info = array(
				'id' => hg_rand_num(10),	
				'channel_id' => $this->input['channel_id'],
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
				'color' => '#DF6564,#FEF2F2',	
				'start' => date("H:i",$start),	
				'end' => date("H:i",$end),	
				'week_set' => date('W',$start),	
				'item' => 0,
				'new' => $new,
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
					'color' => '#537ABF,#E5EEFF',	
					'start' => date("H:i",strtotime($dates . " " . date("H:i:s",$r['start_time']))),	
					'end' => date("H:i",strtotime($dates . " " . date("H:i:s",$r['start_time'])) + $r['toff']),	
					'week_set' => date('W',strtotime($dates . " " . date("H:i:s",$r['start_time']))),	
					'item' => $r['item'],
					'new' => 0,
					'outdate' => (strtotime($dates . " " . date("H:i:s",$r['start_time']))) <= TIMENOW ? 1:0,
					'is_plan' => $r['id'],
				);
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
		$data = array();
		foreach($ret as $k => $v)
		{
			$data[$v['id']] = $v['name'];
		}
		echo json_encode($data);
	}
	//屏蔽类型
	function getTitle()
	{
		$sql = "select id,title from " . DB_PREFIX . "backup WHERE 1";
		$q = $this->db->query($sql);
		$program_screen =  array();
		while($row = $this->db->fetch_array($q))
		{
			$program_screen[$row['id']] = $row['title'];
		}
		echo json_encode($program_screen);
	}
	
	/**
	 * 取单条信息
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
			$condition = ' WHERE id in(' . $id .')';
		}			
		$sql = "SELECT * FROM " . DB_PREFIX . "program " . $condition;		
		$row = $this->db->query_first($sql);
		$this->setXmlNode('program' , 'info');	
		if(is_array($row) && $row)
		{
			$row['create_time'] = date('Y-m-d H:i:s' , $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s' , $row['update_time']);
			//录播			
			$sql = "SELECT p.id,r.start_time,r.end_time,r.channel_id,r.week_num,p.item FROM " . DB_PREFIX . "program_record_relation r LEFT JOIN " . DB_PREFIX . "program_record p ON p.id = r.record_id WHERE r.channel_id=" . $row['channel_id'] . " AND r.week_num=" . date('N',strtotime($row['dates'])) . " AND r.start_time='" . date('H:i:s',$row['start_time']) . "' AND r.end_time='" . date('H:i:s',$row['end_time']) . "'";
			$return = $this->db->query_first($sql);
			if(is_array($return) && $return)
			{
				$row['item'] = $return['item'];
			}
			//屏蔽			
			$sql = "SELECT * FROM " . DB_PREFIX . "program_screen WHERE program_id=". $this->input['id'];
			$return = $this->db->query_first($sql);
			if(is_array($return) && $return)
			{
				$row['program_id_screen'] = $return['program_id'];
				$row['backup_id'] = $return['backup_id'];
			}
			
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
		if(isset($this->input['start_time']) && !empty($this->input['start_time']))
		{
			$this->input['start_time'] = strtotime(urldecode($this->input['start_time']));
		    $a = $this->input['start_time'];
			if(isset($this->input['end_time']) && !empty($this->input['end_time']))
			{
				$this->input['end_time'] = strtotime(urldecode($this->input['end_time']));
				$condition .= 'and create_time between '.$this->input['start_time'].' and '.$this->input['end_time'];
			}
			else
			{
				$condition .= 'and create_time > '.$this->input['start_time'];
			}
		}
		if(!$this->input['start_time'] && isset($this->input['end_time']) && !empty($this->input['end_time']))
		{
			$this->input['end_time'] = strtotime(urldecode($this->input['end_time']));
			$condition .= 'and create_time < '.$this->input['end_time'];
		}
		
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' and concat(theme, subtopic) like \'%'.urldecode($this->input['k']).'%\'';
		}
		if(!($this->input['channel_id']))
		{
	 //		$this->output(OBJECT_NULL);
			$this->input['channel_id'] = 1;
		}
		$condition .= ' and channel_id = ' . $this->input['channel_id'];

		$this->weeks = date("W");
		$this->years = $this->input['years']?$this->input['years']:date("Y");
		if(!isset($this->input['day_list']))
		{
			$day_list = $space = '';
			for($i = 1; $i < 8; $i++)
			{
				$week_info = $this->getDayInfoOfWeek($this->years, $this->weeks ,$i);
				$day_list .= $space."'".$week_info['w']."'";
				$date[$i] = $week_info;
				$space = ',';
			}
		}
		else
		{
			$day_list = urldecode($this->input['day_list']);
		}
		
		$condition .= " AND dates IN(" . $day_list . ") AND is_show=1 ";

		//$condition .= '';
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