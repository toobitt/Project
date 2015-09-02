<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_record_auto.php 6444 2012-04-18 05:18:47Z repheal $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','program_record');
class programRecordAutoApi extends cronBase
{
	function __construct()
	{
		parent::__construct();
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($this->settings['mms']['record_server']['host'], $this->settings['mms']['record_server']['dir']);
		$this->curl->mPostContentType('string');
		include_once(ROOT_PATH . 'lib/class/logs.class.php');
		$this->logs = new logs();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	public function tips($str,$pub_time)
	{
		echo $str . "----------------------------" . date("Y-m-d H:i:s",$pub_time) . '<br/>';
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
		$sql = "SELECT c.code, c.name,c.save_time,c.main_stream_name,c.stream_state,c.record_time,c.stream_id, pr.*,vs.sort_name
					FROM " . DB_PREFIX . "program_record pr 
					LEFT JOIN " . DB_PREFIX . "channel c 
						ON pr.channel_id=c.id 
					LEFT JOIN " . DB_PREFIX . "vod_sort vs 
						ON pr.item=vs.id
				WHERE is_record=0 and (pr.start_time) > " . strtotime(date('Y-m-d',TIMENOW)) . " and pr.start_time < " . (strtotime(date('Y-m-d',TIMENOW)) + 86399) ;   
		$q = $this->db->query($sql);
		
		while($row = $this->db->fetch_array($q))
		{
			$return = array();
			if(!$row['channel_id'])
			{
				continue;
			}
			$ret = array();
			$ret['record_id'] = $row['id'];
			$ret['channel_id'] = $row['channel_id'];
			$ret['name'] = $row['name'];
			$ret['program'] = $row['title'] ? $row['title'] : trim($this->program_plan($ret['channel_id'],$ret['starttime'],$ret['endtime']));
			$ret['title'] = trim($ret['program'] ? $ret['program'] : '精彩节目');
			$ret['starttime'] = $row['start_time']+$row['record_time'];
			$ret['toff'] = $row['toff'];
			
			$ret['stream'] = hg_streamUrl($this->settings['mms']['output']['wowzaip'], $row['code'], $row['main_stream_name'].$this->settings['mms']['output']['suffix'], '');
			$callback = $this->settings['App_mediaserver']['protocol'] . $this->settings['App_mediaserver']['host'] . '/' . $this->settings['App_mediaserver']['dir'] . 'admin/create.php?appid=' . $this->input['appid'] . '&appkey=' . $this->input['appkey'] . '&vod_leixing=3&auth=' . $this->settings['vodapi']['token'];

			$ret['save_time'] = $row['save_time'];
			$ret['delay_time'] = $row['live_delay'] * 60;
			$ret['source'] = $row['channel_id'];
			$ret['is_allow'] = $row['is_mark'];
			$ret['force_codec'] = $row['force_codec'];
			$ret['sort_name'] = $row['sort_name'];
			$ret['vod_sort_id'] = $row['item'];
			$ret['week_flag'] = $row['week_day'] ? 1 : 0;
			$ret['column_id'] = $row['columnid'] ? $row['columnid'] : 0;
			$ret['audit_auto'] = $row['audit_auto'] ? 2 : 0;

			foreach($ret as $key => $value)
			{
				$callback .= '&' . $key . '=' . $value;
			}
			if($row['columnid'])
			{
				$callback .= '&status=2';
			}
			
			$sql = "select id, ch_name,other_info,s_status from " . DB_PREFIX . "stream where id=" . $row['stream_id'];
			$stream = $this->db->query_first($sql);
			if(!$row['stream_state'])
			{
				$ret = array('errortext' => '视频流未开启！');

			}elseif(!$stream['s_status'])
			{
				$ret = array('errortext' => '视频上游流未开启！');
			}
			else
			{	
				$this->curl->setSubmitType('post');
				$this->curl->setReturnFormat('json');
				$this->curl->initPostData();
				
				$this->curl->addRequestData('action', 'insert');
				$this->curl->addRequestData('url', $ret['stream']);
				$this->curl->addRequestData('callback', urlencode($callback));
				$this->curl->addRequestData('startTime', $ret['starttime']-1);
				$this->curl->addRequestData('duration', $row['toff']+1);
				$ret = array();
				$record_xml = $this->curl->request('');
				$record_array = xml2Array($record_xml);
			    if(is_array($record_array))
				{
					$ret['id'] = $record_array['record']['id'] ? $record_array['record']['id'] : 0;
					if(!$record_array['result'])
					{
						$ret['errortext'] = $record_array['error']['message'];//报错	
						$ret['isError'] = 1;
					}
					else
					{
						$ret['errortext'] = '';
						$ret['isError'] = 0;
					}
				}
				else
				{
					$ret['id'] = 0;
					$ret['errortext'] = '录制失败，无内容！';
					$ret['isError'] = 1;
				}
				$is_record = $ret['id'] ? 1 : 0 ;
				//unset($pre_data['week_day']);
				
				$conid = $this->add_queue($row['id'],$ret['id']);
				if($is_record)//录制成功
				{
					$sql_update = "UPDATE " . DB_PREFIX . "program_record SET is_out=0,conid=" . $conid . ",is_record=" . $is_record . " WHERE id=" . $row['id'];
					$this->db->query($sql_update);
					$sql = "select * from " . DB_PREFIX . "program_record where id=" . $row['id'];
					$pre_data = $this->db->query_first($sql);
					$pre_data['title'] = $pre_data['title'] ? $pre_data['title'] : '精彩节目';
					$log_id = $this->logs->addLogs($ret['errortext'],$pre_data,$ret['isError'],$row['id'],$row['channel_id']);
					$this->update_queue($conid,$log_id);
				}
				else
				{
					if($record_array['error']['message'] && !$row['is_out'])//报错了
					{
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
							$sql_update = "UPDATE " . DB_PREFIX . "program_record SET is_out=1,conid=0,is_record=" . $is_record . ",start_time=" . $start_time . " WHERE id=" . $row['id'];
							$this->db->query($sql_update);
						}
						else
						{
							$sql_update = "UPDATE " . DB_PREFIX . "program_record SET is_out=1,conid=0,is_record=1 WHERE id=" . $row['id'];
							$this->db->query($sql_update);
						}
						
						$sql = "select * from " . DB_PREFIX . "program_record where id=" . $row['id'];
						$pre_data = $this->db->query_first($sql);						
						$pre_data['title'] = $pre_data['title'] ? $pre_data['title'] : '精彩节目';
						$log_id = $this->logs->addLogs($ret['errortext'],$pre_data,$ret['isError'],$row['id'],$row['channel_id']);
						$this->update_queue($conid,$log_id);
					}
					else
					{
						if(TIMENOW < $row['start_time'])
						{
							//什么都不做还没开始录制
						}
						else
						{
							//woza断了，但照理的录制正在进行中，一旦woza开启就error,记录错误，更新记录
							if(!$row['is_out'])
							{
								$sql = "UPDATE " . DB_PREFIX . "program_record SET is_out=1 WHERE id=" . $row['id']; //woza停止，改为超时，
								$this->db->query($sql);
								
								$sql = "select * from " . DB_PREFIX . "program_record where id=" . $row['id'];
								$pre_data = $this->db->query_first($sql);						
								$pre_data['title'] = $pre_data['title'] ? $pre_data['title'] : '精彩节目';
								$log_id = $this->logs->addLogs($ret['errortext'],$pre_data,$ret['isError'],$row['id'],$row['channel_id']);
								$this->update_queue($conid,$log_id);
							}
						}
					}
				}
				
			}
			$str = 'ID-' . $row['id'] . ($ret['errortext'] ? $ret['errortext'] : '录制成功');
			$this->tips($str,TIMENOW);
		}
	}
	
	function add_queue($record_id,$conid=0)
	{
		if(empty($conid))
		{
			$sql = "INSERT INTO " . DB_PREFIX . "program_queue(record_id) VALUES(" . $record_id . ")";
			$this->db->query($sql);
			$id = $this->db->insert_id();
			$sql = "UPDATE " . DB_PREFIX . "program_queue SET conid=" . $record_id . " WHERE id=" . $id;
			$this->db->query($sql);
			return $id;
		}
		else
		{
			$sql = "INSERT INTO " . DB_PREFIX . "program_queue(record_id,conid) VALUES(" . $record_id . "," . $conid . ")";
			$this->db->query($sql);
			return $id = $this->db->insert_id();
		}
	}
	
	function update_queue($conid,$log_id)
	{
		$sql = "UPDATE " . DB_PREFIX . "program_queue SET log_id=" . $log_id . " WHERE record_id=" . $record_id . " AND conid=" . $conid;
		$this->db->query($sql);
		return true;
	}
}

$out = new programRecordAutoApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>