<?php
/***************************************************************************
* $Id: live.class.php 17481 2013-02-21 09:36:46Z gaoyuan $
***************************************************************************/
define('MOD_UNIQUEID','program_record');//模块标识
class programRecord extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition,$data_limit,$dates='')
	{
		$sql = "select p.* from " . DB_PREFIX . "program_record p ";
		if($dates)
		{
			$sql .= "left join " . DB_PREFIX . "program_record_relation r on r.record_id = p.id ";
		}
		$sql .= " where 1 " . $condition . " ORDER BY p.is_record ASC,p.start_time ASC " . $data_limit;
		$q = $this->db->query($sql);
		$week_day_arr = array('1' => '一', '2' => '二', '3' => '三', '4' => '四', '5' => '五', '6' => '六', '7' => '日');
		
		include_once(ROOT_PATH . 'lib/class/livmedia.class.php');
		$livmedia = new livmedia();
		$sort_name = $livmedia->getAutoItem();

		$conid = $space = "";
		$data = $channel = array();
		while($row = $this->db->fetch_array($q))
		{
			$channel[$row['channel_id']] = $row['channel_id'];
			$conid .= $space . $row['conid'];
			$space = ',';
			$data[] = $row;
		}
		if(!empty($channel))
		{
			$channel_ids = implode(',',$channel);
			$channel = array();
			include_once(ROOT_PATH . 'lib/class/live.class.php');
			$newLive = new live();
			$channel_tmp = $newLive->getChannelById($channel_ids, -1, 1);
			if(!empty($channel_tmp))
			{
				foreach($channel_tmp as $k => $v)
				{
					$channel[$v['id']] = $v;
				}
			}
		}
		
		$log = array();
		if($conid)
		{
			$sql = "SELECT log_id FROM " . DB_PREFIX . "program_queue WHERE id IN(" . $conid . ")";
			$log_id = $space = '';
			$q = $this->db->query($sql);
			while($row = $this->db->fetch_array($q))
			{
				$log_id .= $space . $row['log_id'];
				$space = ',';
			}
			if($log_id)
			{
				$sql = "SELECT a.*,q.conid FROM " . DB_PREFIX . "program_record_log a LEFT JOIN " . DB_PREFIX . "program_queue q ON a.id=q.log_id WHERE a.id IN (" . $log_id . ")";
				$q = $this->db->query($sql);
				$log = array();
				while($row = $this->db->fetch_array($q))
				{
					$log[$row['record_id']] = $row;
				}
			}
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "server_config WHERE 1 AND state=1";
		$q = $this->db->query($sql);
		$server_config = array();
		while($row = $this->db->fetch_array($q))
		{
			$server_config[$row['id']] = $row;
		}
		include_once(ROOT_PATH . 'lib/class/curl.class.php');
		foreach($data as $k => $v)
		{
			if(empty($channel[$v['channel_id']]))
			{
				$channel[$v['channel_id']]['name'] = '频道关闭';
			}
			$v['channel'] = $channel[$v['channel_id']]['name'];
			$v['status'] = $channel[$v['channel_id']]['status']; 
			$dates = date('Y-m-d', $v['start_time']);
			$channel_id = $v['channel_id'];
			$start_time = $v['start_time'];
			$week_day = unserialize($v['week_day']);
			
			$v['start_time'] = date('H:i:s', $start_time);
			$v['end_time'] = date('H:i:s', $v['toff'] + $start_time);
			
			$mins = floor($v['toff']/60);
			$sen = $v['toff'] - $mins*60;
			$v['toff_decode'] = ($mins ? $mins . "'" : '') . ($sen ? $sen . "''" : '');
			$v['dates'] = $dates;
			//$v['w'] = date('w',strtotime($start_time));
			$v['sort_name'] = $sort_name[$v['item']];
			
			$tmp = $log[$v['id']];
			
			if(!empty($week_day))
			{
				if(count($week_day) == 7)
				{
					$v['cycle'] = '每天';
				}
				else
				{
					$spac = '';
					foreach($week_day as $kk => $vv)
					{
						$v['cycle'] .= $spac . $week_day_arr[$vv];
						$spac = '&nbsp;|&nbsp;';
					}
				}
			}
			else
			{
				$v['cycle'] = date('Y-m-d',$start_time);
			}
			
			if($server_config && $server_config[$v['server_id']])
			{	
				if($tmp)
				{
					$obj_curl = new curl($server_config[$v['server_id']]['host'] . ':' . $server_config[$v['server_id']]['port'], $server_config[$v['server_id']]['dir']);
					switch(intval($tmp['state']))
					{
						case 0://录制等待录制
								$obj_curl->setSubmitType('get');
								$obj_curl->initPostData();
								$obj_curl->addRequestData('action', 'SELECT');
								$obj_curl->addRequestData('id', $tmp['conid']);
								$record_xml = $obj_curl->request('');
								$record_array = xml2Array($record_xml);
				        		if(!empty($record_array) && $record_array['result'])//表示任务存在
				        		{
				        			if($record_array['record']['status'] == 'running')
				        			{
				        				$v['action'] = '录制中';
				        			}
				        			if($record_array['record']['status'] == 'waiting')
				        			{
				        				if(($start_time+$v['toff']) <= ($record_array['record']['serverTime'] ? $record_array['record']['serverTime'] : TIMENOW))
				        				{
				        					$v['action'] = '录制超时';
				        				}
				        				else
				        				{
				        					$v['action'] = '等待录制';
				        				}
				        			}
				        		}
				        		else
				        		{			        			
					        		$v['action'] = '录制超时';
					        	}
						break;
						case 1://录制成功
							if($tmp['week_day'])
							{
								$v['action'] = '等待录制';
							}
							else
							{
								$v['action'] = $tmp['text'] ? $tmp['text'] : '录制超时';							
							}
						break;
						case 2://录制失败
							if($tmp['week_day'])
							{
								$v['action'] = '等待录制';
							}
							else
							{
								$v['action'] = $tmp['text'] ? $tmp['text'] : '录制超时';							
							}
						break;
					}
				}
				else//刚添加的计划收录
				{
					if($start_time >= TIMENOW)
					{
						$v['action'] = '等待录制';
					}else if(TIMENOW > $start_time && TIMENOW < ($v['toff'] + $start_time))
					{
						$v['action'] = empty($week_day) ? '录制超时' : '等待录制';
					}else if(($v['toff'] + $start_time) <= TIMENOW)//录制成功
					{
						$v['action'] = empty($week_day) ? '录制超时' : '等待录制'; //假如is_record 存在video 在上传，否则就是录制成功 
					}
				}
			
			}
			else
			{
				$v['action'] = '服务停止';
			}
			
			if($v['is_out'])
			{
				//$row['action'] = '录制超时';
			}
			if(!$v['status'])
			{
				$v['action'] = '频道关闭';
			}
			if(!$v['server_id'])
			{
				$v['action'] = '服务停止';
			}
			$info[] = $v;	
		}
		return $info;
	}
	
	public function getRecordByCondition($condition = '')
	{
		$sql = "select p.id,r.start_time,r.end_time,r.channel_id,r.week_num,p.item from " . DB_PREFIX . "program_record p left join " . DB_PREFIX . "program_record_relation r on p.id = r.record_id where 1 " . $condition;
		$q = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($q))
		{
			$info[] = $row;
		}
		return $info;
	}
	
	
	public function detail($condition='')
	{
		$sql ="SELECT p.* FROM " . DB_PREFIX . "program_record p " . $condition;
		$row = $this->db->query_first($sql);
		if(is_array($row) && $row)
		{
			include_once(ROOT_PATH . 'lib/class/program.class.php');
			$program_plan = new program();
			include_once(ROOT_PATH . 'lib/class/live.class.php');
			$newLive = new live();
			
			$channel = $newLive->getChannelById($row['channel_id']);
			$channel = $channel[0];

			$row['create_time'] = date('Y-m-d H:i:s' , $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s' , $row['update_time']);
			$row['end_time'] = date('Y-m-d H:i:s' , ($row['start_time'] + $row['toff']));
			$row['start_time'] = date('Y-m-d H:i:s' , $row['start_time']);
			$row['week_day'] = $row['week_day'] ? unserialize($row['week_day']) : array();
			$start_time = strtotime($row['dates'] . " ". $row['start_time']);
			$end_time = strtotime($row['dates'] . " ". $row['start_time']) + $row['toff'];
			$row['title'] = $row['title'] ? $row['title'] : (trim($program_plan->get_program_plan($row['channel_id'],$start_time,$end_time)) ? trim($program_plan->get_program_plan($row['channel_id'],$start_time,$end_time)) : '精彩节目');
			$mins = floor($row['toff']/60);
			$sen = $row['toff'] - $mins*60;
			$row['toff_decode'] = ($mins ? $mins . "'" : '') . ($sen ? $sen . "''" : '');
			$row['channel_name'] = $channel['name'];
			return $row;
		}
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "program_record p WHERE 1 " . $condition;
		$r = $this->db->query_first($sql);
		return $r;
	}
	
	public function create($info = array())
	{
		$createsql = "INSERT INTO " . DB_PREFIX . "program_record SET ";
		$space = "";
		foreach($info as $key => $value)
		{
			$createsql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		$this->db->query($createsql);
		$ret = array();
		$ret['id'] = $this->db->insert_id();

		$this->insert_relation($info['channel_id'],$ret['id'],$info['start_time'],$info['toff'],$info['week_day']);
		return $ret;
	}
	
	public function update($id,$data = array())
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "program_record WHERE id=" . $id;
		$f = $this->db->query_first($sql);
		if(empty($f))
		{
			return false;
		}
		
		$columnid = $data['column_id'];
		$column_name = $data['column_name'];

		$start_time = $data['start_time'];
		$end_time = $data['end_time'];

		$toff = $end_time - $start_time;
		if($toff < 0)
		{
			$toff = $toff + 24*3600;
		}

		if(empty($toff))
		{
			$this->errorOutput('录制时长不能为零！');
		}
		
		$is_record = 0;//判断录制状态
		$is_out = 0;//判断是否超时
		if(is_array($data['week_day']) && $data['week_day'])
		{
			$is_record = 0;
			if($start_time <= TIMENOW)//大于当前时间
			{
				$week_now = date('N',TIMENOW);
				$week_num = $data['week_day'];
				if(in_array($week_now,$week_num))
				{
					$dates = date('Y-m-d',TIMENOW);
				}
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
				$start_time =  strtotime($dates . ' ' . date("H:i:s",$data['start_time']));
				$end_time =  strtotime($dates . ' ' . date("H:i:s",$data['end_time']));
			}
			$week_day = serialize($data['week_day']);
		}
		else
		{//单天
			if($start_time > TIMENOW)//大于当前时间
			{
				$is_record = 0;
				$is_out = 0;
			}
			else
			{
				$is_record = 2;
				$is_out = 1;
			}			
		}
		
		$info = array(
			'channel_id' => $data['channel_id'],
			'start_time' => $start_time,
			'title' => $data['title'],
			'toff' => $toff,
			'week_day' => $week_day ? $week_day : '',
			'item' => $data['item'],
			'server_id' => $data['server_id'],
			'columnid' => $columnid,
			'column_name' => $column_name,
			'is_mark' => $data['is_mark'],
			'force_codec' => $data['force_codec'],
			'audit_auto' => $data['audit_auto'],
			'is_out' => $is_out,
			'is_record' => $is_record,
			'conid' => 0,
		);
		
		$sql = "UPDATE " . DB_PREFIX . "program_record SET ";
		$space = "";
		$sql_extra = "";
		foreach($info as $key => $value)
		{
			if(isset($value))
			{
				$sql_extra .= $space . $key . "=" . "'" . $value . "'";
				$space = ",";
			}
		}
		if($sql_extra)
		{
			$sql .= $sql_extra . " WHERE id=" . $id;
			$this->db->query($sql);
			$affect_rows  = $this->db->affected_rows();
			if($affect_rows > 0)
			{
				$info['id'] = $id;
				$info['update_time'] = TIMENOW;
				$sql = "UPDATE " . DB_PREFIX . "program_record SET update_time=" . $info['update_time'] . " WHERE id=" . $info['id'];
				$this->db->query($sql);
			}
		}
		$this->insert_relation($data['channel_id'],$id,$start_time,$toff,serialize($week_day));
		return $info;
	}
	
	private function insert_relation($channel_id,$record_id,$start_time,$toff,$week_day)
	{
		$start = date("H:i:s",$start_time);
		$end = date("H:i:s",$start_time+$toff);

		$sql = "DELETE FROM " . DB_PREFIX . "program_record_relation WHERE record_id=" . $record_id ;
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
			if($week_day)
			{
				foreach($week_day as $k => $v)
				{
					$sql = "INSERT INTO " . DB_PREFIX . "program_record_relation SET record_id=" . $record_id . " ,channel_id=" . $channel_id . ", start_time='" . $start . "', end_time='" . $end . "', week_num=" . $v . ",num=1";
					$this->db->query($sql);
				}
			}
		}
	}
	
	public function delete()
	{
		
	}
	
	public function update_record_state($id)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "program_record where id=" . $id;
		$row = $this->db->query_first($sql);

		$week_day = unserialize($row['week_day']);
		if (is_array($week_day) && $week_day)
		{
			$week_now = date('N',$row['start_time']);
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
			$start_time = $row['start_time']+($next_week*86400);
			$sql_update = "UPDATE " . DB_PREFIX . "program_record SET conid=0,is_record=0,start_time=" . $start_time . " WHERE id=" . $row['id'];
			$this->db->query($sql_update);
		}
		else
		{
			$sql_update = "UPDATE " . DB_PREFIX . "program_record SET is_record=2 WHERE id=" . $row['id'];
			$this->db->query($sql_update);
		}
		return true;
	}
	
	public function check_record($channel_id,$start_time,$end_time)
	{	
		if(empty($channel_id) || empty($start_time) || empty($end_time))
		{
			return false;
		}
		$sql = "SELECT p.id,r.start_time,r.end_time,r.channel_id,r.week_num,p.item FROM " . DB_PREFIX . "program_record p LEFT JOIN " . DB_PREFIX . "program_record_relation r ON p.id = r.record_id WHERE r.channel_id=" . $channel_id . " AND r.week_num=" . date('N',strtotime($start_time)) . " AND r.start_time='" . date('H:i:s',$start_time) . "' AND r.end_time='" . date('H:i:s',$end_time) . "'";	
		$f = $this->db->query_first($sql);
		return $f;	
	}
}
?>