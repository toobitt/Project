<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_screen.class.php 6083 2012-03-13 04:59:52Z repheal $
***************************************************************************/
class programScreen extends BaseFrm
{
	public function __construct()
	{
		parent::__construct();
		require_once(ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($this->settings['mms_api']['host'],$this->settings['mms_api']['dir'],$this->settings['mms_api']['token']);
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	function show($condition)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "program_screen WHERE 1 " . $condition . " ORDER BY create_time ASC";
		$q = $this->db->query($sql);
		$info = array();
		$week_day_arr = array('1' => '一', '2' => '二', '3' => '三', '4' => '四', '5' => '五', '6' => '六', '7' => '日');
		while($row = $this->db->fetch_array($q))
		{
			$row['start'] = date("H:i:s",$row['start_time']);
			$row['end'] = date("H:i:s",$row['start_time']+$row['toff']);
			$row['week_day'] = unserialize($row['week_day']);
			$spac = '';
			if(!empty($row['week_day']))
			{
				if(count($row['week_day']) == 7)
				{
					$row['cycle'] = '每天';
				}
				else
				{
					$spac = '';
					foreach($row['week_day'] as $k => $v)
					{
						$row['cycle'] .= $spac . $week_day_arr[$v];
						$spac = '&nbsp;|&nbsp;';
					}
				}
			}
			else
			{
				$row['cycle'] = date('Y-m-d',$row['start_time']);
			}
			if(!empty($row['week_day']))
			{	$week_day = array();	
				$week_day = $row['week_day'];
				$week_now = date('N',TIMENOW);
				$new_arr = array_flip($week_day);
				if(count($week_day) > ($new_arr[$week_now]+1))
				{
					$ks = $new_arr[$week_now] + 1;
				}
				else
				{
					$ks = 0;
				}
				$week_day = array_flip($new_arr);
				$next_week = ($week_day[$ks] - $week_now)>0?($week_day[$ks] - $week_now):($week_day[$ks] - $week_now + 7);
				$start_time = TIMENOW + ($next_week*86400);
			}
			else
			{
				$start_time = $row['start_time'];
			}
			$row['title'] = $this->program_plan($row['channel_id'],$start_time,$start_time+$row['toff']) ? $this->program_plan($row['channel_id'],$start_time,$start_time+$row['toff']) : '精彩节目';
			$row['new_title'] = $this->program_plan($row['channel_id_back'],$start_time,$start_time+$row['channel_id_back']) ? $this->program_plan($row['channel_id_back'],$start_time,$start_time+$row['toff']) : '精彩节目';
			$info[] = $row;
		}
		return $info;
	}

	function create()
	{	
		if(!trim($this->input['channel_id_back']) || !trim($this->input['channel_id']))
		{
			return false;
		}
		
		$ret = $this->get_back($this->input['channel_id_back']);
		if(empty($ret))
		{
			return false;
		}

		$dates = urldecode($this->input['dates']);
		$info = array(
			'channel_id' => trim($this->input['channel_id']),
			'channel_id_back' => trim($this->input['channel_id_back']),
			'backup_file' => $ret ? $ret : '',
			'week_day' =>  !empty($this->input['week_day']) ? serialize($this->input['week_day']) : '',
			'start_time' => strtotime($dates . " " . urldecode($this->input['start_time'])),
			'toff' => strtotime($dates . " " . urldecode($this->input['end_time'])) - strtotime($dates . " " . urldecode($this->input['start_time'])),
			'create_time' => TIMENOW,
			'update_time' => TIMENOW,
			'ip' => hg_getip(),
		);
		$sql_extra = $space = '';
		$sql = "INSERT INTO " . DB_PREFIX . "program_screen SET ".$sql_extra;
		foreach($info as $k => $v)
		{
			$sql_extra .= $space.$k . "=" . "'" . $v . "'";
			$space = ',';
		}
		$sql .= $sql_extra;
		$this->db->query($sql);
		$info['id'] = $this->db->insert_id();
		if(!$info['id'])
		{
			return false;
		}
		return $info;
	}

	function update()
	{	
		if(!trim($this->input['id']) || !trim($this->input['channel_id_back']) || !trim($this->input['channel_id']))
		{
			return false;
		}
		$ret = $this->get_back($this->input['channel_id_back']);
		if(empty($ret))
		{
			return false;
		}
		
		$dates = urldecode($this->input['dates']);
		$info = array(
			'channel_id' => trim($this->input['channel_id']),
			'channel_id_back' => trim($this->input['channel_id_back']),
			'backup_file' => $ret ? $ret : '',
			'week_day' => !empty($this->input['week_day']) ? serialize($this->input['week_day']) : '',
			'start_time' => strtotime($dates . " " . urldecode($this->input['start_time'])),
			'toff' => strtotime($dates . " " . urldecode($this->input['end_time'])) - strtotime($dates . " " . urldecode($this->input['start_time'])),
			'update_time' => TIMENOW,
		);
		$sql_extra = $space = '';
		$sql = "UPDATE " . DB_PREFIX . "program_screen SET ".$sql_extra;
		foreach($info as $k => $v)
		{
			$sql_extra .= $space.$k . "=" . "'" . $v . "'";
			$space = ',';
		}
		$sql .= $sql_extra . "where id=" . trim($this->input['id']);
		$this->db->query($sql);
		$info['id'] = trim($this->input['id']);
		if(!$info['id'])
		{
			return false;
		}
		return $info;
	}

	public function count($condition)
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "program_screen WHERE 1 ";
		$sql = $sql . $condition;
		$r = $this->db->query_first($sql);
		return $r;
	}

	public function delete()
	{
		$id = trim(urldecode($this->input['id']));
		if($id)
		{
			$sql = "DELETE FROM " . DB_PREFIX . "program_screen WHERE id IN (" . $id . ")";
			$this->db->query($sql);
		}
		else
		{
			return false;
		}
		return $id;
	}

	public function audit()
	{
		$id = trim($this->input['id']);
		if(!$id)
		{
			return false;
		}
		$state = ($this->input['state'] ? 1 : 0);
		$sql = "UPDATE " . DB_PREFIX . "program_screen SET state=" . $state . " WHERE id=" . $id;
		$this->db->query($sql);
		return array('id' => $id,'state' => $state);
	}
	
	/**
	 * 获取单条信息
	 */
	public function detail($condition)
	{
		$id = urldecode($this->input['id']);
		if(!$id)
		{
			$condition .= " ORDER BY start_time ASC LIMIT 1";
		}
		else 
		{
			$condition .= " AND id =" . $id ;
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "program_screen WHERE 1 " . $condition;
		$info = $this->db->query_first($sql);
		$info['week_day'] = unserialize($info['week_day']);
		$info['new_title'] = '精彩节目';
		return $info;
	}

	public function get_back($channel_id_back)
	{
		$sql = "select * from " . DB_PREFIX . "channel c left join " . DB_PREFIX . "channel_stream s on c.id=s.channel_id where c.id=" . $channel_id_back;
		$f = $this->db->query_first($sql);
		$url = hg_get_stream_url($this->settings['tvie']['stream_server'], array('channel' => $f['stream_mark'], 'stream_name' => $f ['out_stream_name']));
		return $url;
	}

	public function get_one($channel_id,$dates,$start)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "program_screen WHERE 1 AND state=1 AND channel_id=" . $channel_id;
		$q = $this->db->query($sql);
		$screen = array();
		while($row = $this->db->fetch_array($q))
		{
			$week_day = array();	
			$week_day =  $row['week_day'] ? unserialize($row['week_day']) : array(date("N",$row['start_time']));
			$week_now = date('N',strtotime($dates));
			if($week_day && in_array($week_now,$week_day))
			{
				$row['start_time'] = strtotime($dates . ' ' .date('H:i:s',$row['start_time']));
				$row['start'] = date('Y-m-d H:i:s',$row['start_time']);
				$row['end'] = date('Y-m-d H:i:s',$row['start_time']+$row['toff']);
				$screen[] = $row;
			}
		}

		if(!empty($screen))
		{
			return $screen;
		}
		else
		{
			return false;
		}
	}

	public function verify($channel_id,$start_time,$end_time,$week_day,$id = 0)
	{
		if(empty($week_day))
		{
			$week_day = array(date('N',$start_time));
		}
		$date = date('Y-m-d',$start_time) . " ";
		$start_time = strtotime($date . date('H:i:s',$start_time));
		$end_time = strtotime($date . date('H:i:s',$end_time));
		$sql = "SELECT * FROM " . DB_PREFIX . "program_screen WHERE channel_id=" . $channel_id ;
		if($id)
		{
			$sql .= " AND id NOT IN(" . $id . ")";
		}
		$q = $this->db->query($sql);
		$str = $space = '';
		$screen = array();
		while($row = $this->db->fetch_array($q))
		{
			$row['start_time'] = strtotime($date . date('H:i:s',$row['start_time']));
			$screen[] = $row;
		}
		if(empty($screen))
		{
			return true;
		}
		$week = array();
		foreach($screen as $k => $row)
		{
			if($row['start_time'] == $start_time && ($row['start_time']+$row['toff']) == $end_time)
			{
				$week[$row['id']] = $row['week_day'] ? unserialize($row['week_day']) : array(date('N',$row['start_time']));
				break;
			}
			else
			{
				if($row['start_time'] >= $start_time && $row['start_time'] < $end_time)
				{
					$week[$row['id']] = $row['week_day'] ? unserialize($row['week_day']) : array(date('N',$row['start_time']));
				}

				if($row['start_time'] < $start_time)
				{
					if(($row['start_time']+$row['toff']) > $start_time)
					{
						$week[$row['id']] = $row['week_day'] ? unserialize($row['week_day']) : array(date('N',$row['start_time']));
					}
				}				
			}
		}
		
		if(empty($week))
		{
			return true;
		}
		else
		{	
			foreach($week as $k => $v)
			{
				foreach($week_day as $kk => $vv)
				{
					if(in_array($vv,$v))
					{
						return false;
					}
				}
			}
		}
		return true;
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
}

?>