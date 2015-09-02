<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_plan.class.php 5568 2011-12-31 09:08:23Z repheal $
***************************************************************************/
class programPlan extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	function show($condition,$data_limit = '')
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "program_plan p WHERE 1 " . $condition . " ORDER BY FROM_UNIXTIME(start_time,'%H:%i') ASC, FROM_UNIXTIME(start_time,'%Y-%m-%d') DESC  " . $data_limit;
		$q = $this->db->query($sql);
		$info = array();
		$plan_id = $space = '';
		while($row = $this->db->fetch_array($q))
		{
			$row['start'] = date("H:i",$row['start_time']);
			$row['end'] = date("H:i",$row['start_time']+$row['toff']);
			$row['start_date'] = date("Y-m-d",$row['start_time']);
			$row['end_date'] = date("Y-m-d",$row['start_time']+$row['toff']);
			$plan_id .= $space .$row['id'];
			$space = ',';
			$info[] = $row;
		}
		
		$relation = $relation_ch = array();
		if($plan_id)
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "program_plan_relation WHERE plan_id IN (" . $plan_id . ")";
			$q = $this->db->query($sql);
			$ch = array('一', '二' , '三' , '四' , '五' , '六' , '日' );
			while($row = $this->db->fetch_array($q))
			{
				$relation[$row['plan_id']][] = $row['week_num'];
				$relation_ch[$row['plan_id']][] = $ch[$row['week_num']-1];
			}
		}
		
		foreach($info as $k => $v)
		{
			$info[$k]['week_day'] = !empty($relation[$v['id']]) ? $relation[$v['id']] : array();
			$info[$k]['week_day_ch'] = $relation_ch[$v['id']] ? (count($relation_ch[$v['id']]) == 7 ? array('每天') : $relation_ch[$v['id']]) : array('单天');
			$start = $v['start_time'];
			$info[$k]['plan_in'] = $info[$k]['plan_out'] = 0;
			while($start < ($v['start_time']+$v['toff']))
			{
				if($relation[$v['id']] && in_array(date('N',$start),$relation[$v['id']]))
				{
					if($start > TIMENOW)
					{
						$info[$k]['plan_in']++;
					}
					else
					{
						$info[$k]['plan_out']++;
					}					
				}
				$start+=86400;
			}
		}
		return $info;
	}
	
	function getPlanByChannel($channel_id)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "program_plan WHERE channel_id=" . $channel_id;
		$plan_id = $space = '';
		$data = array();
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$plan_id .= $space . $row['id'];
			$data[] = $row;
			$space = ',';
		}
		if($plan_id)
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "program_plan_relation WHERE plan_id IN (" . $plan_id . ")";
			$q = $this->db->query($sql);
			$relation = $relation_ch = array();
			$ch = array('一', '二' , '三' , '四' , '五' , '六' , '日' );
			while($row = $this->db->fetch_array($q))
			{
				$relation[$row['plan_id']][] = $row['week_num'];
				$relation_ch[$row['plan_id']][] = $ch[$row['week_num']-1];
			}
			if(!empty($relation))
			{
				foreach($data as $k => $v)
				{
					$data[$k]['start'] = date('H:i:s',$v['start_time']);
					$data[$k]['end'] = date('H:i:s',$v['start_time']+$v['toff']);
					$data[$k]['week_day'] = !empty($relation[$v['id']]) ? $relation[$v['id']] : array();
					$data[$k]['week_day_ch'] = $relation_ch[$v['id']] ? (count($relation_ch[$v['id']]) == 7 ? array('每天') : $relation_ch[$v['id']]) : array('单天');
				}
				return $data;
			}
		}
		else
		{
			return false;
		}
	}

	function create($data = array())
	{
		if(empty($data))
		{
			return false;
		}
		$week_num = $data['week_day'];
		unset($data['week_day']);
		$sql_extra = $space = "";
		foreach($data as $key => $value)
		{
			$sql_extra .= $space . $key . "='" . $value . "'";
			$space = ",";
		}
		if($sql_extra)
		{
			$sql = "INSERT INTO " . DB_PREFIX . "program_plan SET ".$sql_extra;
			$this->db->query($sql);
			$info['id'] = $this->db->insert_id();
			if($info['id'])
			{
				if(!empty($week_num))
				{
					$sql_extra = $space = '';
					foreach($week_num as $k => $v)
					{
						$sql_extra .= $space . '(' . $info['id'] . ',' . $v . ')';
						$space = ',';
					}
					$sql = "INSERT INTO " . DB_PREFIX . "program_plan_relation(plan_id,week_num) value" . $sql_extra;
					$this->db->query($sql);
				}
			}
			return $info;
		}
		return false;
	}

	function update($data = array(),$id = 0)
	{	
		if(empty($data) || empty($id))
		{
			return false;
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "program_plan WHERE 1 AND id=" . $id;
		$f = $this->db->query_first($sql);
		if(empty($f))
		{
			return false;
		}
		$week_num = $data['week_day'];
		unset($data['week_day']);
		$sql_extra = $space = "";
		foreach($data as $key => $value)
		{
			$sql_extra .= $space . $key . "='" . $value . "'";
			$space = ",";
		}
		
		if($sql_extra)
		{
			$sql = "UPDATE " . DB_PREFIX . "program_plan SET " . $sql_extra . " WHERE 1 AND id=" . $id;
			$this->db->query($sql);
			$affect_num = $this->db->affected_rows();
			$sql = "SELECT * FROM " . DB_PREFIX . "program_plan_relation where plan_id=" . $id;
			$q = $this->db->query($sql);
			$i = 0;
			while($row = $this->db->fetch_array($q))
			{
				if(!in_array($week_num,$row['week_num']))
				{
					$affect_num = 1;
				}
				$i++;
			}
			if(!$i && $week_num)
			{
				$affect_num = 1;
			}
			if($affect_num)
			{
				$sql = "UPDATE " . DB_PREFIX . "program_plan SET update_time=" . TIMENOW . " WHERE 1 AND id=" . $id;
				$this->db->query($sql);
				$data['id'] = $id;
				if($data['id'])
				{
					$sql = "DELETE FROM " . DB_PREFIX . "program_plan_relation where plan_id=" . $data['id'];
					$this->db->query($sql);
					if(!empty($week_num))
					{
						$sql_extra = $space = '';
						foreach($week_num as $k => $v)
						{
							$sql_extra .= $space . '(' . $data['id'] . ',' . $v . ')';
							$space = ',';
						}
						$sql = "INSERT INTO " . DB_PREFIX . "program_plan_relation(plan_id,week_num) value" . $sql_extra;
						$this->db->query($sql);
					}
				}
			}
			return $data;
		}
		return false;	
	}
	
	public function check_plan($id)
	{

	}
	

	public function count($condition)
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "program_plan p WHERE 1 " . $condition;
		$r = $this->db->query_first($sql);
		return $r;
	}

	public function delete($id)
	{
		if(empty($id))
		{
			return false;
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "program_plan WHERE id=" . $id;
		$f = $this->db->query_first($sql);
		if($f['id'])
		{
			$sql_ =  "SELECT * FROM " . DB_PREFIX . "program_plan WHERE id = " . $id;
			$pre_data = $this->db->query_first($sql_);
			
			$sql = "DELETE FROM " . DB_PREFIX . "program_plan WHERE id=".$id;
			$r = $this->db->query($sql);
			$sql = "DELETE FROM " . DB_PREFIX . "program_plan_relation WHERE plan_id=".$id;
			$r = $this->db->query($sql);
		}
		return $id;
	}

	public function verify($start,$toff,$channel_id,$id = 0)
	{		
		$end = $start + $toff;
		$condition = '';
		$sql = "SELECT * FROM " . DB_PREFIX . "program_plan WHERE 1 AND channel_id=" . $channel_id;
		if($id)
		{
			$sql .= ' AND id NOT IN(' . $id . ')';
		}
		$q = $this->db->query($sql);
		$id_array = array();
		while($row = $this->db->fetch_array($q))
		{
			if($toff)//有结束
			{				
				if($start > $end)
				{
					continue;
				}
				if(intval($row['toff']))//检索内容中有结束
				{
					if(($row['start_time'] >= $start && $row['start_time'] <= $end) || (($row['start_time']+$row['toff']) >= $start && ($row['start_time']+$row['toff'])<= $end) || ($row['start_time'] == $start && ($row['start_time']+$row['toff']) == $end))
					{
						if(date('H:i',$start) == date('H:i',$row['start_time']))
						{
							$id_array[] = $row['id'];
						}
					}
				}
				else//检索内容无限延长
				{
					if(($start <= $row['start_time'] && $end > $row['start_time']) && $start >= $row['start_time'])
					{
						if(date('H:i',$start) == date('H:i',$row['start_time']))
						{
							$id_array[] = $row['id'];
						}
					}
				}			
			}
			else//无结束
			{
				if(intval($row['toff']))//检索内容中有结束
				{
					if($start <= $row['start_time'] || ($start >= $row['start_time'] && $start < ($row['start_time']+$row['toff'])))
					{
						if(date('H:i',$start) == date('H:i',$row['start_time']))
						{
							$id_array[] = $row['id'];
						}						
					}
				}
				else//检索内容无限延长
				{
					//两者无限，必然会有交集
					if(date('H:i',$start) == date('H:i',$row['start_time']))
					{
						$id_array[] = $row['id'];
					}
				}
			}
		}
		
		$id_array = array_unique($id_array);
		$ids = implode(',',$id_array);
		if(!$ids)
		{
			return false;
		}
		else
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "program_plan_relation WHERE plan_id IN(" . $ids . ")";
			$q = $this->db->query($sql);
			$week_array = array();
			while($r = $this->db->fetch_array($q))
			{
				if($r['plan_id'] != $id)
				{
					$week_array[] = $r['week_num'];
				}
			}
			if(empty($week_array))
			{
				$week_array[] = date('N',$start);
			}
			return $week_array;
		}
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
		
		$sql = "SELECT * FROM " . DB_PREFIX . "program_plan p WHERE 1 " . $condition;
		$info = $this->db->query_first($sql);
		if($info['id'])
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "program_plan_relation WHERE plan_id=" . $id ." ORDER BY week_num ASC";
			$q = $this->db->query($sql);
			$week_num = array();
			while($r = $this->db->fetch_array($q))
			{
				$week_num[$r['week_num']] = $r['week_num'];
			}
			$info['week_day'] = $week_num;
			$info['title'] = $info['program_name'];
			$info['start_date'] = date("Y-m-d",$row['start_time']);
			$info['end_date'] = date("Y-m-d",$row['start_time']+$row['toff']);
		}
		return $info;
	}

	function get_item()
	{
		include_once(ROOT_PATH . 'lib/class/livmedia.class.php');
		$livmedia = new livmedia();
		$sort_name = $livmedia->getAutoItem();
		return $sort_name;
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

	public function get_program_plan($channel_id,$start,$end)
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
			return $program_plan;
		}
		else
		{
			return false;
		}
	}
}
?>