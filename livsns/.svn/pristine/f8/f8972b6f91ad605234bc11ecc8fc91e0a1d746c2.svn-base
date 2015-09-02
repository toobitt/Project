<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_record_auto.php 6444 2012-04-18 05:18:47Z repheal $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','program_record');//模块标识
class programRecordAutoApi extends cronBase
{
	private $mLive;
	private $live;
	function __construct()
	{
		parent::__construct();
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($this->settings['mms']['record_server']['host'], $this->settings['mms']['record_server']['dir']);
		//$this->curl->mPostContentType('string');
		include_once(ROOT_PATH . 'lib/class/live.class.php');
		$this->live = new live();
		
		$this->mLive = $this->settings['mms']['live_stream_server'];
	}

	function __destruct()
	{
		parent::__destruct();
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
	
	public function tips($str,$pub_time)
	{
		echo $str . "----------------------------" . date("Y-m-d H:i:s",$pub_time) . '<br/>';
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
			$ret = $log_data = array();

			$ret['id'] = $log_data['id'] = $row['id'];
			$ret['channel_id'] = $log_data['channel_id'] = $row['channel_id'];
			$ret['name'] = rawurlencode($row['name']);
			$ret['startTime'] = date('YmdHis',($row['start_time']+$row['record_time'] - 1));
			$row['toff'] = $row['toff'] + 2;
			$program = $row['title'] ? $row['title'] : trim($this->program_plan($ret['channel_id'],$ret['starttime'],$ret['endtime']));
			$ret['title'] = $log_data['title'] = rawurlencode(trim($program ? $program : '精彩节目'));
			
			$log_data['start_time'] = $row['start_time'];
			$log_data['toff'] = $row['toff'];
			$log_data['week_day'] = $row['week_day'];
			$log_data['item'] = $row['item'];
			$log_data['columnid'] = $row['columnid'];
			$log_data['column_name'] = $row['column_name'];
			$log_data['ip'] = $row['ip'];
			
			//$ret['starttime'] = $row['start_time']+$row['record_time'];
			
			//$ret['toff'] = $row['toff'];
			
			//live
			if ($this->mLive)
			{
				$ret['stream'] = hg_streamUrl($this->settings['mms']['_output']['wowzaip'], $row['code'], $row['main_stream_name'].$this->settings['mms']['_output']['suffix']);
			}
			else
			{
				$ret['stream'] = hg_streamUrl($this->settings['mms']['output']['wowzaip'], $row['code'], $row['main_stream_name'].$this->settings['mms']['output']['suffix']);
			}
			
			//$callback = $this->settings['App_mediaserver']['protocol'] . $this->settings['App_mediaserver']['host'] . '/' . $this->settings['App_mediaserver']['dir'] . 'admin/create.php?appid=' . $this->input['appid'] . '&appkey=' . $this->input['appkey'] . '&vod_leixing=3&auth=' . $this->settings['vodapi']['token'] . '&channel_id=' . $row['channel_id'];
			$callback = $this->settings['App_mediaserver']['protocol'] . $this->settings['App_mediaserver']['host'] . '/' . $this->settings['App_mediaserver']['dir'] . 'admin/record_callback_old.php';
			
			$ret['vod_sort_id'] = $row['item'];
			$ret['week_flag'] = $row['week_day'] ? 1 : 0;
			$ret['column_id'] = $row['columnid'] ? $row['columnid'] : 0;
			$ret['audit_auto'] = $row['audit_auto'] ? 2 : 0;
			$ret['exit_status'] = $row['audit_auto'];
			$ret['save_time'] = $row['save_time'];
			//$ret['delay_time'] = $row['live_delay'] * 60;
			$ret['source'] = $row['channel_id'];
			$ret['is_allow'] = $row['is_mark'];
			$ret['force_codec'] = $row['force_codec'];
			
			
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
				/*
				$this->curl->setSubmitType('post');
				$this->curl->setReturnFormat('json');
				$this->curl->initPostData();
				$this->curl->addRequestData('action', 'insert');
				$this->curl->addRequestData('url', $ret['stream']);
				$this->curl->addRequestData('callback', urlencode($callback));
				$this->curl->addRequestData('startTime', $ret['starttime']-1);
				$this->curl->addRequestData('duration', $row['toff']+1);
				*/
				
				$this->curl->setSubmitType('get');
				$this->curl->initPostData();
				$this->curl->addRequestData('action', 'INSERT');
				$this->curl->addRequestData('url', $ret['stream']);
				$this->curl->addRequestData('callback', urlencode($callback));
				//$this->curl->addRequestData('startTime', $ret['starttime']-1);
				$this->curl->addRequestData('duration', $row['toff']);
				$this->curl->addRequestData('uploadFile','0');
				$this->curl->addRequestData('appid',$this->input['appid']);
				$this->curl->addRequestData('appkey',$this->input['appkey']);
				foreach($ret AS $k => $v)
				{
					$this->curl->addRequestData($k,$v);
				}
				$ret = array();
				$record_xml = $this->curl->request('');
				$record_array = xml2Array($record_xml);
			    if(is_array($record_array))
				{
					$ret['id'] = $record_array['record']['id'] ? $record_array['record']['id'] : 0;
					if(!$record_array['result'])
					{
						$ret['errortext'] = '录制超时';//报错	
						$ret['isError'] = 1;
					}
					else
					{
						$ret['errortext'] = '等待录制';
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
				$conid = $this->add_queue($row['id']);
				if(!$ret['isError'])//录制成功
				{
					$sql_update = "UPDATE " . DB_PREFIX . "program_record SET is_out=0,conid=" . $conid . ",is_record=" . $is_record . " WHERE id=" . $row['id'];
					$this->db->query($sql_update);

					$log_data['text'] = $ret['errortext'];
					$log_data['state'] = $ret['isError'] ? 2 : 0;
					$log_id = $this->live->addLogs($log_data);
					$this->update_queue($conid,$log_id);
				}
				else//提交失败
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
						$sql_update = "UPDATE " . DB_PREFIX . "program_record SET conid=0,is_record=0,start_time=" . $start_time . " WHERE id=" . $row['id'];
						$this->db->query($sql_update);
					}
					else
					{
						$sql_update = "UPDATE " . DB_PREFIX . "program_record SET is_out=1,conid=0,is_record=1 WHERE id=" . $row['id'];
						$this->db->query($sql_update);
					}
					$log_data['text'] = $ret['errortext'];
					$log_data['state'] = 2;
					$log_id = $this->live->addLogs($log_data);
					$this->update_queue($conid,$log_id);
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
		$sql = "UPDATE " . DB_PREFIX . "program_queue SET log_id=" . $log_id . " WHERE id=" . $conid;
		$this->db->query($sql);
		return true;
	}
	
	function restart()
	{
		switch(intval($this->input['sort']))
		{
			case 0://重建当前时间之后所有的
				
				$sql = "SELECT * FROM " . DB_PREFIX . "program_record WHERE start_time > " . TIMENOW . " AND conid <> 0";
				//. " AND (start_time+toff)<" . strtotime(date('Y-m-d',TIMENOW) . ' 23:59:59')
				//录制等待中的，并未开始录制，并且是当天，删除录制，重新提交
				$q = $this->db->query($sql);
				$queue_id = $record_id = $space = "";
				while($row = $this->db->fetch_array($q))
				{
					$queue_id .= $space . $row['conid'];
					//$record_id .= $space . $row['id'];
					$space = ',';
				}
				if($queue_id)
				{
					$sql = "SELECT * FROM " . DB_PREFIX . "program_queue WHERE id IN(" . $queue_id . ")";
					$q = $this->db->query($sql);
					include_once(ROOT_PATH . 'lib/class/curl.class.php');
					$obj_curl = new curl($this->settings['mms']['record_server']['host'], $this->settings['mms']['record_server']['dir']);
					while($row = $this->db->fetch_array($q))
					{
						$obj_curl->setSubmitType('get');
						$obj_curl->initPostData();
						$obj_curl->addRequestData('action', 'SELECT');
						$obj_curl->addRequestData('id', $row['conid']);
						$record_xml = $obj_curl->request('');
						$record_array = xml2Array($record_xml);
		        		if($record_array)
		        		{
			        		if($record_array['result'])//表示任务存在
			        		{
			        			if($record_array['record']['status'] == 'waiting')// || $record_array['record']['status'] == 'running'
			        			{
				        			$obj_curl->mPostContentType('string');
									$obj_curl->setSubmitType('get');
									$obj_curl->setReturnFormat('json');
									$obj_curl->initPostData();
									$obj_curl->addRequestData('action', 'DELETE');
									$obj_curl->addRequestData('id', $row['conid']);
									$record_xml = $obj_curl->request('');
									$record_array_delete = xml2Array($record_xml);
									/*
									if($record_array_delete['result'])
									{
									
									}
									*/
			        			}
			        		}
		        		}
						//ing
						$sql = "DELETE FROM " . DB_PREFIX . "program_record_log WHERE id=" . $row['log_id'];
		        		$this->db->query($sql);
						$sql = "DELETE FROM " . DB_PREFIX . "program_queue WHERE id=" . $row['id'];
						$this->db->query($sql);
		        		$update_sql = "UPDATE " . DB_PREFIX . "program_record SET is_record=0,conid=0 WHERE id=" . $row['record_id'];//内容清空，录制清空
						$this->db->query($update_sql);
						echo  $row['record_id'].'-----------------ok<br/>';
					}
				}
			break;
			case 1:
				$sql = "SELECT * FROM " . DB_PREFIX . "program_record WHERE start_time > " . TIMENOW . " AND conid <> 0";
				//. " AND (start_time+toff)<" . strtotime(date('Y-m-d',TIMENOW) . ' 23:59:59')
				//录制等待中的，并未开始录制，并且是当天，删除录制，重新提交
				$q = $this->db->query($sql);
				$queue_id = $record_id = $space = "";
				while($row = $this->db->fetch_array($q))
				{
					//$queue_id .= $space . $row['conid'];
					$record_id .= $space . $row['id'];
					$space = ',';
				}

				$update_sql = "UPDATE " . DB_PREFIX . "program_record SET is_record=0,conid=0 WHERE id IN(" . $record_id . ")";//内容清空，录制清空
				$this->db->query($update_sql);
				echo  $record_id . '-----------------ok<br/>';
			break;
			case 2:
				//往后退一步
			
				$sql = "SELECT * FROM " . DB_PREFIX . "program_record WHERE conid=0 and start_time > " . TIMENOW;
				//. " AND (start_time+toff)<" . strtotime(date('Y-m-d',TIMENOW) . ' 23:59:59')
				//录制等待中的，并未开始录制，并且是当天，删除录制，重新提交
				$q = $this->db->query($sql);
				$queue_id = $record_id = $space = "";
				$record = array();			
				include_once(ROOT_PATH . 'lib/class/curl.class.php');
				$obj_curl = new curl($this->settings['mms']['record_server']['host'], $this->settings['mms']['record_server']['dir']);
				while($row = $this->db->fetch_array($q))
				{
					$obj_curl->setSubmitType('get');
					$obj_curl->initPostData();
					$obj_curl->addRequestData('action', 'SELECT');
					$obj_curl->addRequestData('id', $row['id']);
					$record_xml = $obj_curl->request('');
					$record_array = xml2Array($record_xml);
	        		if($record_array)
	        		{
		        		if($record_array['result'])//表示任务存在
		        		{
		        			if($record_array['record']['status'] == 'waiting')// || $record_array['record']['status'] == 'running'
		        			{
								print_r($row);
								print_r($record_array);
			        			$obj_curl->mPostContentType('string');
								$obj_curl->setSubmitType('get');
								$obj_curl->setReturnFormat('json');
								$obj_curl->initPostData();
								$obj_curl->addRequestData('action', 'DELETE');
								$obj_curl->addRequestData('id', $row['id']);
								$record_xml = $obj_curl->request('');
								$record_array_delete = xml2Array($record_xml);
								print_r($record_array_delete);
								
								if($record_array_delete['result'])
								{
									$row['conid'] ? $row['conid'] : $this->delete_queue($row['conid']);
								}
		        			}
		        		}
	        		}			
	        		else
	        		{//任务假如不存在，直接删除
		        		$this->delete_queue($row['conid']);
	        		}	
					$week_day = unserialize($row['week_day']);
					if (is_array($week_day) && $week_day)
					{
						$week_now = date('N',$row['start_time']);
						$now = date('N');
						if($week_now >= $now)
						{
							$ks = $week_now - $now;
						}
						else
						{
							$ks = $week_now - $now;
						}
						$start_time = ($row['start_time']-$ks*86400) > TIMENOW ? ($row['start_time']-$ks*86400) : $row['start_time'];
						echo $row['id'].'-----------------ok' . date('Y-m-d H:i:s',$start_time).'<br/>';
						$sql_update = "UPDATE " . DB_PREFIX . "program_record SET conid=0,is_record=0,start_time=" . $start_time . " WHERE id=" . $row['id'];
						$this->db->query($sql_update);
						echo $row['id'].'-----------------ok<br/>';
					}
					else
					{
						$sql_update = "UPDATE " . DB_PREFIX . "program_record SET is_out=1,conid=0,is_record=1 WHERE id=" . $row['id'];
						$this->db->query($sql_update);
						echo $row['id'].'-----------------ok<br/>';
					}
				}			
			break;
			default:
			break;
		}		
		//录制
	}

	private function delete_queue($conid)
	{
		$sql = "select * from " . DB_PREFIX . "program_queue where id=" . $conid;
		$f = $this->db->query_first($sql);
		if(!empty($f))
		{
			$sql = "DELETE FROM " . DB_PREFIX . "program_record_log WHERE id=" . $f['log_id'];
			$this->db->query($sql);
			$sql = "DELETE FROM " . DB_PREFIX . "program_queue WHERE id=" . $f['id'];
			$this->db->query($sql);
		}	
	}
	
	function delete()
	{
		$sql = "SELECT id FROM " . DB_PREFIX . "program_record_log WHERE state=2";
		$q = $this->db->query($sql);
		$log_id = $space = "";
		while($row = $this->db->fetch_array($q))
		{
			$log_id .= $space . $row['id'];
			$space = ',';
		}
		echo $log_id ;
		$sql = "DELETE FROM " . DB_PREFIX . "program_record_log WHERE state=2";
		//$q = $this->db->query($sql);
		$sql = "DELETE FROM " . DB_PREFIX . "program_queue WHERE log_id IN (" . $log_id . ")";
		//$q = $this->db->query($sql);
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