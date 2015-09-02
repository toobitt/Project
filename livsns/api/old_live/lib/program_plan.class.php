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
		$this->mDates = "2011-12-05";
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	function show($condition)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "program_plan_relation r left join " . DB_PREFIX . "program_plan p on p.id=r.plan_id WHERE 1 " . $condition . " ORDER BY start_time ASC";
		$q = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($q))
		{
			$row['start'] = strtotime(date("H:i:s",$row['start_time']));
			$row['end'] = strtotime(date("H:i:s",$row['start_time']+$row['toff']));
			$info[] = $row;
		}
		return $info;
	}

	function create()
	{
		$dates = $this->mDates;
		$info = array(
			'channel_id' => trim($this->input['channel_id']),
			'program_name' => rawurldecode($this->input['program_name'])?rawurldecode($this->input['program_name']):'精彩节目',
			'start_time' => strtotime($dates . " " . urldecode($this->input['start_time'])),
			'toff' => strtotime($dates . " " . urldecode($this->input['end_time'])) - strtotime($dates . " " . urldecode($this->input['start_time'])),
			'item' => $this->input['item_source'] ? (trim($this->input['item'])>0 ? trim($this->input['item']) : 0) : 0,
			'create_time' => TIMENOW,
			'update_time' => TIMENOW,
			'ip' => hg_getip(),
		);
		$sql_extra = $space = "";
		foreach($info as $key => $value)
		{
			$sql_extra .= $space . $key . "='" . $value . "'";
			$space = ",";
		}
		if($sql_extra && $this->input['week_day'])
		{
			$sql = "INSERT INTO " . DB_PREFIX . "program_plan SET ".$sql_extra;
			$this->db->query($sql);
			$info['id'] = $this->db->insert_id();
			if($info['id'])
			{
				$week_num = $this->input['week_day'];
				if(!empty($week_num))
				{
					$sql_extra = $space = '';
					foreach($week_num as $k => $v)
					{
						$sql_extra .= $space . '(' . $info['id'] . ',' . $v . ')';
						$space = ',';
					}
					$sql = "INSERT INTO " . DB_PREFIX . "program_plan_relation(plan_id,week_num) value".$sql_extra;
					$this->db->query($sql);
				}
				
				if($info['item'])
				{
					$week_now = date('N',TIMENOW);
					if(in_array($week_now,$week_num))
					{
						$dates = date('Y-m-d',TIMENOW);
						$start_time =  strtotime($dates . ' ' . urldecode($this->input['start_time']));
						if($start_time <= TIMENOW)
						{
							$i = 0;
							$next_day = $next_week_day = 0;
							foreach($week_num as $k => $v)
							{
								if(!$i && $v > $week_now)
								{
									$next_day = $v;
									$i = 1;
								}
								if(!$k)
								{
									$next_week_day = $v;
								}
							}
							if(!$next_day)
							{
								$next_day = $next_week_day;
							}
							$next_week = ($next_day - $week_now)>0?($next_day - $week_now):($next_day - $week_now + 7);
							$dates = date('Y-m-d', (TIMENOW + $next_week*86400));
							$start_time =  strtotime($dates . ' ' . urldecode($this->input['start_time']));	
						}
					}
					else
					{
						$i = 0;
						$next_day = $next_week_day = 0;
						foreach($week_num as $k => $v)
						{
							if(!$i && $v > $week_now)
							{
								$next_day = $v;
								$i = 1;
							}
							if(!$k)
							{
								$next_week_day = $v;
							}
						}
						if(!$next_day)
						{
							$next_day = $next_week_day;
						}
						$next_week = ($next_day - $week_now)>0?($next_day - $week_now):($next_day - $week_now + 7);
						$dates = date('Y-m-d', (TIMENOW + $next_week*86400));
						$start_time =  strtotime($dates . ' ' . urldecode($this->input['start_time']));	
					}
									

					$record = array(
						'title' => $info['program_name'],
						'channel_id' => $info['channel_id'],
						'plan_id' => $info['id'],
						'start_time' => $start_time,
						'toff' => $info['toff'],
						'item' => $info['item'],
						'week_day' => serialize($week_num),
						'create_time' => TIMENOW,
						'update_time' => TIMENOW,
						'ip' => hg_getip(),
						'is_record' => 0,
						'is_out' => 0,
					);
					/*$sql = "SELECT * FROM " . DB_PREFIX . "program_record WHERE channel_id =" . $record['channel_id'] . " AND " . $record['start_time'] . "=start_time and " . ($record['start_time']+$record['toff']) . "=(start_time+toff)";
					$f = $this->db->query_first($sql);					
					if($f['id'])
					{
						//更新节目单计划
						$updatesql = "UPDATE " . DB_PREFIX . "program_record SET plan_id='" . $info['id'] . "', week_day='" . $record['week_day'] . "',item=" . $record['item'] . ",update_time=" . TIMENOW . " WHERE id =" . $f['id'];
						$this->db->query($updatesql);
						$this->insert_relation($infos['channel_id'],$f['id'],$infos['start_time'],$infos['toff'],$record['week_day']);
						$record_id = $f['id'];
					}
					else
					{	
						
					}*/
					
					//创建节目单计划			
					$createsql = "INSERT INTO " . DB_PREFIX . "program_record SET ";
					$space = "";
					foreach($record as $k => $v)
					{
						$createsql .= $space . $k . "=" . "'" . $v . "'";
						$space = ",";
					}
					$this->db->query($createsql);
					$record_id = $this->db->insert_id();
					$this->insert_relation($record['channel_id'],$record_id,$start_time,$info['toff'],$record['week_day']);
					$sql = "UPDATE " . DB_PREFIX . "program_plan SET record_id=" . $record_id . " WHERE id=" . $info['id'];
					$this->db->query($sql);
				}
			}
			return $info;
		}
		return false;
	}

	private function insert_relation($channel_id,$record_id,$start_time,$toff,$week_day)
	{
		$start = date("H:i:s",$start_time);
		$end = date("H:i:s",$start_time+$toff);

		$sql = "DELETE FROM " . DB_PREFIX . "program_record_relation WHERE record_id=" . $record_id ;
		$this->db->query($sql);
		$week_day = unserialize($week_day);
		if(!$week_day || count($week_day) <= 1)
		{
			$week_num = date('N',$start_time);
			$sql = "INSERT INTO " . DB_PREFIX . "program_record_relation SET record_id=" . $record_id . " ,channel_id=" . $channel_id . ", start_time='" . $start . "', end_time='" . $end . "', week_num=" . $week_num . ",num=0";
			$this->db->query($sql);
		}
		else
		{
			foreach($week_day as $k => $v)
			{
				$sql = "INSERT INTO " . DB_PREFIX . "program_record_relation SET record_id=" . $record_id . " ,channel_id=" . $channel_id . ", start_time='" . $start . "', end_time='" . $end . "', week_num=" . $v . ",num=1";
				$this->db->query($sql);
			}
		}
	}

	function update()
	{	
		$dates = $this->mDates;
		$info = array(
			'channel_id' => trim($this->input['channel_id']),
			'program_name' => rawurldecode($this->input['program_name'])?rawurldecode($this->input['program_name']):'精彩节目',
			'start_time' => strtotime($dates . " " . urldecode($this->input['start_time'])),
			'toff' => strtotime($dates . " " . urldecode($this->input['end_time'])) - strtotime($dates . " " . urldecode($this->input['start_time'])),
			'item' => $this->input['item_source'] ? (trim($this->input['item'])>0 ? trim($this->input['item']) : 0) : 0,
			'update_time' => TIMENOW,
			'ip' => hg_getip(),
		);
		$sql_extra = $space = "";
		foreach($info as $key => $value)
		{
			$sql_extra .= $space . $key . "='" . $value . "'";
			$space = ",";
		}
		if($sql_extra && $this->input['week_day'])
		{
			$sql_ =  "SELECT * FROM " . DB_PREFIX . "program_plan WHERE id = " . $this->input['id'];
			$pre_data = $this->db->query_first($sql_);
			
			$sql = "UPDATE " . DB_PREFIX . "program_plan SET " . $sql_extra . " WHERE 1 AND id=" . $this->input['id'];
			$this->db->query($sql);
			$info['id'] =  $this->input['id'];
			if($info['id'])
			{
				$sql = "DELETE FROM " . DB_PREFIX . "program_plan_relation where plan_id=" . $info['id'];
				$this->db->query($sql);
				$week_num = $this->input['week_day'];
				if(!empty($week_num))
				{
					$sql_extra = $space = '';
					foreach($week_num as $k => $v)
					{
						$sql_extra .= $space . '(' . $info['id'] . ',' . $v . ')';
						$space = ',';
					}
					$sql = "INSERT INTO " . DB_PREFIX . "program_plan_relation(plan_id,week_num) value".$sql_extra;
					$this->db->query($sql);
				}
				
				if($info['item'])
				{					
					$week_now = date('N',TIMENOW);
					if(in_array($week_now,$week_num))
					{
						$dates = date('Y-m-d',TIMENOW);
						$start_time =  strtotime($dates . ' ' . urldecode($this->input['start_time']));
						if($start_time <= TIMENOW)
						{
							$i = 0;
							$next_day = $next_week_day = 0;
							foreach($week_num as $k => $v)
							{
								if(!$i && $v > $week_now)
								{
									$next_day = $v;
									$i = 1;
								}
								if(!$k)
								{
									$next_week_day = $v;
								}
							}
							if(!$next_day)
							{
								$next_day = $next_week_day;
							}
							$next_week = ($next_day - $week_now)>0?($next_day - $week_now):($next_day - $week_now + 7);
							$dates = date('Y-m-d', (TIMENOW + $next_week*86400));
							$start_time =  strtotime($dates . ' ' . urldecode($this->input['start_time']));	
						}
					}
					else
					{
						$i = 0;
						$next_day = $next_week_day = 0;
						foreach($week_num as $k => $v)
						{
							if(!$i && $v > $week_now)
							{
								$next_day = $v;
								$i = 1;
							}
							if(!$k)
							{
								$next_week_day = $v;
							}
						}
						if(!$next_day)
						{
							$next_day = $next_week_day;
						}
						$next_week = ($next_day - $week_now)>0?($next_day - $week_now):($next_day - $week_now + 7);
						$dates = date('Y-m-d', (TIMENOW + $next_week*86400));
						$start_time =  strtotime($dates . ' ' . urldecode($this->input['start_time']));	
					}

					$record = array(
						'title' => $info['program_name'],
						'channel_id' => $info['channel_id'],
						'plan_id' => $info['id'],
						'start_time' => $start_time,
						'toff' => $info['toff'],
						'item' => $info['item'],
						'week_day' => serialize($week_num),
						'create_time' => TIMENOW,
						'update_time' => TIMENOW,
						'ip' => hg_getip(),
						'is_record' => 0,
						'is_out' => 0,
					);
					
					$sql = "SELECT * FROM " . DB_PREFIX . "program_record WHERE plan_id=" . $info['id'];
					$f = $this->db->query_first($sql);
					if(!empty($f))
					{
						if($this->input['sys_record'] == 1)//更新录制 || $this->input['sys_record'] == 0
						{
							$updatesql = "UPDATE " . DB_PREFIX . "program_record SET is_record=0,conid=0,title='" . $record['title'] . "' ,channel_id='" . $record['channel_id'] . "',plan_id='" . $info['id'] . "',start_time='" . $record['start_time'] . "', toff='" . $record['toff'] . "', week_day='" . $record['week_day'] . "',item=" . $record['item'] . ",update_time=" . TIMENOW . " WHERE id =" . $f['id'];							
//file_put_contents('../cache/ddd',date("Y-m-d", $record['start_time']));
//file_put_contents('../cache/sss',$updatesql);
							$this->db->query($updatesql);
							$this->insert_relation($info['channel_id'],$f['id'],$info['start_time'],$info['toff'],$record['week_day']);
							$record_id = $f['id'];
							//根据录制中的内容ID，删除正在录制的（包括日志），重新进行录制
							$sql = "SELECT id,log_id,conid FROM " . DB_PREFIX . "program_queue WHERE id=" . $f['conid'];
							$sen = $this->db->query_first($sql);
							if($sen)
							{
								include_once (ROOT_PATH . 'lib/class/curl.class.php');
								$this->curl = new curl($this->settings['mms']['record_server']['host'], $this->settings['mms']['record_server']['dir']);
								$this->curl->mPostContentType('string');
								$this->curl->setSubmitType('get');
								$this->curl->setReturnFormat('json');
								$this->curl->initPostData();
								if(!defined('IS_WOZA') || !IS_WOZA)
								{
									$this->curl->addRequestData('action', 'DELETE');
								}
								else
								{
									$this->curl->addRequestData('action', 'delete');				
								}
								$this->curl->addRequestData('id', $sen['conid']);
								$record_xml = $this->curl->request('');
								$record_array = xml2Array($record_xml);
								if($record_array['result'])
								{									
									$sql = "DELETE FROM " . DB_PREFIX . "program_record_log WHERE id IN (" . $sen['log_id'] . ")";
									$this->db->query($sql);
									$sql = "DELETE FROM " . DB_PREFIX . "program_queue WHERE id=" . $sen['id'];
									$this->db->query($sql);
								}
							}
						}
						/*
						
						else if($this->input['sys_record'] == 2)//新建录制
						{
							$createsql = "INSERT INTO " . DB_PREFIX . "program_record SET ";
							$space = "";
							foreach($record as $k => $v)
							{
								$createsql .= $space . $k . "=" . "'" . $v . "'";
								$space = ",";
							}
							$this->db->query($createsql);
							$record_id = $this->db->insert_id();
							$this->insert_relation($record['channel_id'],$record_id,$start_time,$info['toff'],$record['week_day']);
							
							$sql = "UPDATE " . DB_PREFIX . "program_record SET plan_id=0 WHERE id=" . $f['id'];
							$this->db->query($sql);
						}
						
						*/
					}
					else
					{
						//不为空，就是新的录制
						$createsql = "INSERT INTO " . DB_PREFIX . "program_record SET ";
						$space = "";
						foreach($record as $k => $v)
						{
							$createsql .= $space . $k . "=" . "'" . $v . "'";
							$space = ",";
						}
						$this->db->query($createsql);
						$record_id = $this->db->insert_id();
						$this->insert_relation($record['channel_id'],$record_id,$start_time,$info['toff'],$record['week_day']);
					}
					$sql = "UPDATE " . DB_PREFIX . "program_plan SET record_id=" . $record_id . " WHERE id=" . $info['id'];
					$this->db->query($sql);
				}
				else//不是录制，判断
				{
					$sql = "SELECT * FROM " . DB_PREFIX . "program_record WHERE plan_id=" . $info['id'];
					$f = $this->db->query_first($sql);
					if(!empty($f))//原来的是录制，现在不是了，就必须删除录制
					{
						include_once(ROOT_PATH . 'lib/class/live.class.php');
						$obj_live = new live();
						$obj_live->delete_record($f['id']);
						//原本的计划中的录制ID必须更新为0
						$sql = "UPDATE " . DB_PREFIX . "program_plan SET record_id=0 WHERE id=" . $info['id'];
						$this->db->query($sql);
					}
				}
			}
			$this->addLogs('update' , $pre_data , $info , '' , '');
			return $info;
		}
		return false;		
	}
	
	public function check_plan($id)
	{
		//验证节目计划
		if(empty($id))
		{
			return false;
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "program_record where plan_id=" . $id;
		$f = $this->db->query_first($sql);
		if(empty($f))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	

	public function count($condition)
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "program_plan_relation r left join " . DB_PREFIX . "program_plan p on p.id=r.plan_id WHERE 1 ";
		$sql = $sql . $condition;
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
			if($f['record_id'])
			{
				include_once(ROOT_PATH . 'lib/class/live.class.php');
				$obj_live = new live();
				$obj_live->delete_record($f['record_id']);
			}
			$sql_ =  "SELECT * FROM " . DB_PREFIX . "program_plan WHERE id = " . $id;
			$pre_data = $this->db->query_first($sql_);
			
			$sql = "DELETE FROM " . DB_PREFIX . "program_plan WHERE id=".$id;
			$r = $this->db->query($sql);
			$sql = "DELETE FROM " . DB_PREFIX . "program_plan_relation WHERE plan_id=".$id;
			$r = $this->db->query($sql);
			
			$this->addLogs('delete' , $pre_data , '' , '' , '');
		}
		return $f['channel_id'];
	}

	public function verify($start,$end,$channel_id,$id = 0)
	{
		$start = strtotime($this->mDates . " " . $start);
		$end = strtotime($this->mDates . " " . $end);
		
		if($start >= $end)
		{
			return false;
		}
		$sql = "select *,FROM_UNIXTIME(start_time, '%Y-%m-%d %H:%i:%S') as start_time,FROM_UNIXTIME((start_time+toff), '%Y-%m-%d %H:%i:%S') as end_time from " . DB_PREFIX . "program_plan where channel_id=" . $channel_id." and (start_time >" . $start . " and (start_time) <" . $end ." or (start_time+toff) >" . $start . " and (start_time+toff) <" . $end . " or start_time=" . $start . " and (start_time+toff)=" . $end . " )";
		$q = $this->db->query($sql);
		$id_array = array();
		while($r = $this->db->fetch_array($q))
		{
			$id_array[] = $r['id'];
		}
		$id_array = array_unique($id_array);
		$ids = implode(',',$id_array);

		if(!$ids)
		{
			return false;
		}

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
		return $week_array;
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
}

?>