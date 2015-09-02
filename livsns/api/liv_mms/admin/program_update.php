<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_update.php 5937 2012-02-16 03:08:02Z repheal $
***************************************************************************/
require('global.php');
require_once(ROOT_PATH.'lib/class/curl.class.php');
class programUpdateApi extends BaseFrm
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 更新节目单数据
	 * @param $channel_id 频道ID  		not null
	 * @param $start_time 开始时间  		not null
	 * @param $toff 时长					not null
	 * @param $theme 主题				not null 
	 * @param $subtopic 副主题			null
	 * @param $type_id	节目类型			not null
	 * @param $weeks 所属周				not null
	 * @param $dates 日期					not null
	 * @param $describes 描述				null
	 * return $ret 节目单的信息 
	 */
	function update()
	{
		
		
	}

	function update_day()
	{
		if(!$this->input['channel_id'])
		{
			$this->errorOutput("未传入频道ID");
		}
		
		if(!$this->input['dates'])
		{
			$this->errorOutput("未传入更新日期");
		}
		$sql = "select r.start_time,r.end_time,r.channel_id,r.week_num,p.item from " . DB_PREFIX . "program_record p left join " . DB_PREFIX . "program_record_relation r on p.id = r.record_id where r.channel_id=" . $this->input['channel_id'] . " and r.week_num=".date('N',strtotime($this->input['dates']));
		$q = $this->db->query($sql);
		$record = array();
		while($r = $this->db->fetch_array($q))
		{
			$record[] = $r['start_time'] . '-' . $r['end_time'] . '-' . $r['item'];
		}
		$arr = array(
			'color'=>$this->input['color'],
			'checke'=>$this->input['checke'],
			'start_time'=>$this->input['start_time'],
			'theme'=>$this->input['theme'],
			'end_time'=>$this->input['end_time'],
			'item'=>$this->input['item'],
			'new'=>$this->input['new'],
		);
		foreach($arr as $key => $value)
		{
			if(empty($value))
			{
				unset($arr[$key]);
			}
		}
		$dates = urldecode($this->input['dates']);
		if(empty($arr))
		{
			$program = array();
			$start = strtotime($dates." 00:00:00");
			$end = strtotime($dates." 23:59:59");
			
			$sql = "DELETE FROM " . DB_PREFIX . "program WHERE channel_id = " . $this->input['channel_id'] . " and dates='" . $dates . "'";
			$this->db->query($sql);
			$program[] = $this->getInfo($start,$end,$dates,$this->input['channel_id']);
			$this->addItem($program);
			$this->output();
		}
		$prev_end = 0;
		foreach($arr['start_time'] as $k => $v)
		{
			$start_this = strtotime(urldecode($dates." ".$v));
			$end_this = strtotime(urldecode($dates . " " . $arr['end_time'][$k]));
			if($start_this >= $end_this)
			{
				$this->errorOutput($v . '~' . urldecode($arr['end_time'][$k])."的开始时间大于等于结束时间");
			}
			if($prev_end && $prev_end > $start_this)
			{
				$this->errorOutput($start_this."的上一个节目的时间有误");
			}
			$prev_end = $end_this;
		}
		
		$ids = $spa = '';
		foreach($arr['color'] as $key => $value)
		{
			$pid = $key;
			if($arr['checke'][$pid])
			{
				$info = array(
						'id' => $pid,
						'color' => urldecode($value),
						'start_time' => strtotime(urldecode($dates." ".$arr['start_time'][$key])),
						'theme' => urldecode($arr['theme'][$key]),
						'toff' => strtotime(urldecode($dates." ".$arr['end_time'][$key])) - strtotime(urldecode($dates." ".$arr['start_time'][$key])),
						'item' => urldecode($arr['item'][$key]),
						'new' => urldecode($arr['new'][$key]),
					);
			
				if($info['new'])
				{
					$creates = array(
						'channel_id' => $this->input['channel_id'],
						'start_time' => $info['start_time'],
						'toff' => $info['toff'],
						'theme' => urldecode($arr['theme'][$key]),
						'type_id' => 1,
						'weeks' => date("W",$info['start_time']),
						'dates' => date("Y-m-d",$info['start_time']),
						'create_time' => TIMENOW,
						'update_time' => TIMENOW,
						'ip' => hg_getip(),
						'is_show' => 1
					);
					$sql = "INSERT INTO " . DB_PREFIX . "program SET ";
					$space = "";
					foreach($creates as $k => $v)
					{
						$sql .= $space . $k . "=" . "'" . $v . "'";
						$space = ",";
					}
					$this->db->query($sql);
					$info['id'] = $this->db->insert_id();
					$pid = $info['id'];
				}
				else
				{
					$sql = "UPDATE " . DB_PREFIX . "program SET color='" . $info['color'] . "',start_time=" . $info['start_time'] . ",theme='" . $info['theme'] . "',toff=" . $info['toff'] . " where id=" . $info['id'];
					$this->db->query($sql);
				}
				if($info['item'] > 0)
				{
					$infos = array(
						'channel_id' => $this->input['channel_id'],
						'start_time' => $info['start_time'],
						'toff' => $info['toff'],
						'item' => $info['item'],
						'create_time' => TIMENOW,
						'update_time' => TIMENOW,
						'ip' => hg_getip(),
					);
					$record_verify = date("H:i:s",$info['start_time']) . '-' . date("H:i:s",$info['start_time']+$info['toff']) . '-' . $info['item'];
					if(!in_array($record_verify,$record))
					{
						$createsql = "INSERT INTO " . DB_PREFIX . "program_record SET ";
						$space = "";
						foreach($infos as $k => $v)
						{
							$createsql .= $space . $k . "=" . "'" . $v . "'";
							$space = ",";
						}
						$this->db->query($createsql);
						$record_id = $this->db->insert_id();
						$this->insert_relation($infos['channel_id'],$record_id,$infos['start_time'],$infos['toff'],0);
					}
				}
			}
			$ids .= $spa . $pid;
			$spa = ',';
		}
		
		$sql = "DELETE FROM " . DB_PREFIX . "program WHERE channel_id = " . $this->input['channel_id'] . " AND id NOT IN(" . $ids . ") and dates='" . $dates . "'";
		$this->db->query($sql);
		$condition = " AND channel_id=" . $this->input['channel_id'];
		$condition .= " AND dates='" . $dates . "'";
		//该频道的录播记录
		$sql = "select r.start_time,r.end_time,r.channel_id,r.week_num,p.item from " . DB_PREFIX . "program_record p left join " . DB_PREFIX . "program_record_relation r on p.id = r.record_id where r.channel_id=" . $this->input['channel_id'] . " and r.week_num=".date('N',strtotime($dates));
		$q = $this->db->query($sql);
		$record = array();
		while($r = $this->db->fetch_array($q))
		{
			$record[$r['start_time'] . '-' . $r['end_time']] = $r['item'];
		}
	
		$sql = "select *,FROM_UNIXTIME(start_time, '%Y-%m-%d') as start,FROM_UNIXTIME(start_time, '%U') as week_set from " . DB_PREFIX . "program ";
		$sql .= ' where 1 ' . $condition . ' ORDER BY start_time ASC';
		$q = $this->db->query($sql);
		$program = array();
		$start = strtotime($dates." 00:00:00");
		$end = strtotime($dates." 23:59:59");
		$com_time = 0;
		while($row = $this->db->fetch_array($q))
		{
			if(!$com_time && $row['start_time'] > $start)//头
			{
				$program[] = $this->getInfo($start,$row['start_time'],$dates,$this->input['channel_id']);
			}
			if($com_time && $com_time != $row['start_time'])//中
			{
				$program[] = $this->getInfo($com_time,$row['start_time'],$dates,$this->input['channel_id']); 
			}
			$row['start'] = date("H:i",$row['start_time']);
			$row['end'] = date("H:i",$row['start_time']+$row['toff']);
			$row['item'] = $record[$row['id']]?$record[$row['id']]:0;
			$com_time = $row['start_time']+$row['toff'];
			$record_verify = date("H:i:s",$row['start_time']) . '-' . date("H:i:s",$row['start_time']+$row['toff']);
			$row['item'] = $record[$record_verify]?$record[$record_verify]:0;
			if(($row['start_time']+$row['toff']) <= TIMENOW)
			{
				$row['outdate'] = 1;
			}
			else
			{
				$row['outdate'] = 0;
			}
			$program[] = $row;
		}
		if($com_time && $com_time < $end)//中
		{
			$program[] = $this->getInfo($com_time,$end,$dates,$this->input['channel_id']);
		}
		$this->addItem($program);
		$this->output();
	}

	private function insert_relation($channel_id,$record_id,$start_time,$toff,$week_day)
	{
		$start = date("H:i:s",$start_time);
		$end = date("H:i:s",$start_time+$toff);

		$sql = "DELETE FROM " . DB_PREFIX . "program_record_relation WHERE record_id=" . $record_id;
		$this->db->query($sql);
		if(!$week_day)
		{
			$week_num = date('N',$start_time);

			$sql = "INSERT INTO " . DB_PREFIX . "program_record_relation SET record_id=" . $record_id . " ,channel_id=" . $channel_id . ", start_time='" . $start . "', end_time='" . $end . "', week_num=" . $week_num . ",num=0";
			$this->db->query($sql);
		}
		else
		{
			$week_day = unserialize($week_day);
			foreach($week_day as $k => $v)
			{
				$sql = "INSERT INTO " . DB_PREFIX . "program_record_relation SET record_id=" . $record_id . " ,channel_id=" . $channel_id . ", start_time='" . $start . "', end_time='" . $end . "', week_num=" . $v . ",num=1";
				$this->db->query($sql);
			}
		}
	}

	public function add_dom_day()
	{
		$channel_id = $this->input['channel_id'];
		if(!$channel_id)
		{
			$this->errorOutput("未传入频道ID");
		}

		$times = $this->input['times'];//验证时间
		if(!$times)
		{
			$this->errorOutput("参数不完整");
		}

		$dates = date("Y-m-d",$times);
		$type = $this->input['type'] ? 1:0;//0---表示开始，往上添加 1---表示结束，往下添加
		
		$sql = "select * from " . DB_PREFIX . "program where channel_id=" . $channel_id . " and dates='" . $dates . "'  ORDER BY start_time ASC";
		$q = $this->db->query($sql);
		$start = strtotime($dates." 00:00:00");
		$end = strtotime($dates." 23:59:59");
		$com_time = 0;
		$queue = array();
		while($row = $this->db->fetch_array($q))
		{
			if(!$com_time && $row['start_time'] > $start)//头
			{
				$queue[] = array(
						'start' => $start,
						'end' => $row['start_time'],
						'starts' => date("H:i:s",$start),
						'ends' => date("H:i:s",$row['start_time']),
					);
			}
			if($com_time && $com_time != $row['start_time'])//中
			{
				$queue[] = array(
						'start' => $com_time,
						'end' => $row['start_time'],
						'starts' => date("H:i:s",$com_time),
						'ends' => date("H:i:s",$row['start_time']),
					);
			}
			$com_time = $row['start_time']+$row['toff'];
			$queue[] = array(
						'start' => $row['start_time'],
						'end'=> $row['start_time']+$row['toff'],
						'starts' => date("H:i:s",$row['start_time']),
						'ends' => date("H:i:s",$row['start_time']+$row['toff']),
					);
		}
		if($com_time < $end)//中
		{
			$queue[] = array(
						'start' => $com_time,
						'end' => $end,
						'starts'=>date("H:i:s",$com_time),
						'ends'=>date("H:i:s",$end),
					);
		}
//echo date("Y-m-d H:i:s",$times);
//hg_pre($queue,1);
		$start = $end = $range_start = $range_end = 0;
		foreach($queue as $key => $value)
		{
			if($times > $value['start'] && $times < $value['end'])
			{
				$range_start = $value['start'];
				$range_end = $value['end'];
				if($type)
				{
					$start = $times;
					$end = $value['end'];
				}
				else
				{
					$start = $value['start'];
					$end = $times;
				}
			}
		}
		if(!$start || !$end)
		{
			echo "填写时间在" . $dates . "的" . date("H:i",$range_start) . "~" . date("H:i",$range_end) . "之间";
			$this->errorOutput("填写时间在" . $dates . "的" . date("H:i",$range_start) . "~" . date("H:i",$range_end) . "之间");
		}
		$program[] = $this->getInfo($start,$end,$dates,$channel_id);
		$this->addItem($program);
		$this->output();
	}

	public function check_copy()
	{
		$channel_id = $this->input['channel_id'];
		if(!$channel_id)
		{
			$this->errorOutput("未传入频道ID");
		}
		
		$dates = urldecode($this->input['dates']);//源日期
		if(!$dates)
		{
			$this->errorOutput("未传入更新日期");
		}

		$sql = "select * from " . DB_PREFIX . "program where channel_id=" . $channel_id . " and dates='" . $dates . "'";
		$f = $this->db->query_first($sql);
		
		$tip = array('ret'=>1,'id'=>$this->input['id'],'show_id'=>$this->input['show_id']);
		if(!$f['id'])
		{
			$tip = array('ret'=>0,'id'=>$this->input['id'],'show_id'=>$this->input['show_id']);
		}
		$this->addItem($tip);
		$this->output();
	}

	public function copy_day()
	{
		$channel_id = $this->input['channel_id'];
		if(!$channel_id)
		{
			$this->errorOutput("未传入频道ID");
		}
		
		$dates = urldecode($this->input['dates']);//源日期
		if(!$dates)
		{
			$this->errorOutput("未传入更新日期");
		}

		$copy_dates = urldecode($this->input['copy_dates']);
		if(!$copy_dates)
		{
			$this->errorOutput("未传入更新日期");
		}
		
		$diff = strtotime($copy_dates) - strtotime($dates); //相差时间
		
		$sql = "DELETE FROM  " . DB_PREFIX . "program WHERE channel_id=" . $channel_id ." AND dates='" . $copy_dates . "'";
		$this->db->query($sql);
		
		$sql ="INSERT  INTO  " . DB_PREFIX . "program (channel_id,start_time, toff, theme, subtopic, type_id, dates, weeks, describes, create_time, update_time, ip, is_show) SELECT channel_id,start_time+" . $diff . ", toff, theme, subtopic, type_id, '" . $copy_dates . "', " . date('N',strtotime($copy_dates)) . ", describes, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), ip, is_show FROM " . DB_PREFIX . "program ";
		
		$sql .= "WHERE dates='" . $dates ."' AND channel_id=" . $channel_id;
		$this->db->query($sql);
		$tip = array('ret'=>1);
		$this->addItem($tip);
		$this->output();

	}

	private function getInfo($start,$end,$dates,$channel_id)
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
				'color' => '#DF6564,#FEF2F2',	
				'start' => date("H:i",$start),	
				'end' => date("H:i",$end),	
				'week_set' => date('W',$start),	
				'item' => 0,
				'new' => 1,
			);
		
		if($end <= TIMENOW)
		{
			$info['outdate'] = 1;
		}
		else
		{
			$info['outdate'] = 0;
		}
		return $info;
	}

	function check_day()
	{
		$id = $this->input['id'];
		$channel_id = $this->input['channel_id'];
		if(!$channel_id)
		{
			$this->errorOutput("未传入频道ID");
		}
		$start_time = $this->input['start_time'];
		if(!$start_time)
		{
			$this->errorOutput("未传入开始时间");
		}
		$end_time = $this->input['end_time'];
		if(!$end_time)
		{
			$this->errorOutput("未传入结束时间");
		}

		//该频道的录播记录
		$sql = "SELECT p.id,r.start_time,r.end_time,r.channel_id,r.week_num,p.item FROM " . DB_PREFIX . "program_record p LEFT JOIN " . DB_PREFIX . "program_record_relation r ON p.id = r.record_id WHERE r.channel_id=" . $channel_id . " AND r.week_num=" . date('N',strtotime($dates)) . " AND r.start_time='" . date('H:i:s',$start_time) . "' AND r.end_time='" . date('H:i:s',$end_time) . "'";	
		$f = $this->db->query_first($sql);

		if($f['id'])
		{
			$this->addItem(array('tips'=>1));
		}
		else
		{
			$father = array_keys($this->settings['video_upload_type'],'直播归档');
			$this->setXmlNode('record_item' , 'info');
			$sql = "select id,sort_name from " . DB_PREFIX . "vod_sort WHERE father=" . $father[0];
			$q = $this->db->query($sql);
			$sort_name =  array();
			while($row = $this->db->fetch_array($q))
			{
				$sort_name[$row['id']] = $row['sort_name'];
			}
			$this->addItem($sort_name);
		}
		$this->output();
	}
	
}
$out = new programUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'update';
}
$out->$action();
?>