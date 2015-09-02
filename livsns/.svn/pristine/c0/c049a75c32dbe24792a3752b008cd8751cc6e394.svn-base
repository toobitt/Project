<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_record_auto.php 6444 2012-04-18 05:18:47Z repheal $
***************************************************************************/
define('ROOT_DIR', '../../../');
define('CUR_CONF_PATH', '../');
define('MOD_UNIQUEID','program_record_do');//模块标识
require_once(CUR_CONF_PATH . 'lib/functions.php');
require_once(ROOT_DIR.'global.php');
class programRecordAutoApi extends cronBase
{
	private $mLive;
	private $live;
	private $curl;
	function __construct()
	{
		parent::__construct();
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		//$this->curl->mPostContentType('string');
		include_once(ROOT_PATH . 'lib/class/program_record.class.php');
		$this->record = new programRecord();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '自动收录',	 
			'brief' => '收录直播视频',
			'space' => '10',	//运行时间间隔，单位秒
			'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
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
		$sql = "SELECT * FROM " . DB_PREFIX . "server_config WHERE 1 AND state=1";
		$q = $this->db->query($sql);
		$server_config = array();
		while($row = $this->db->fetch_array($q))
		{
			$server_config[$row['id']] = $row;
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "program_record WHERE is_record=0 and start_time < " . (strtotime(date('Y-m-d',TIMENOW))+86399);
		$record_info = $channel_info = $item_info = array();
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$channel_info[$row['channel_id']] = $row['channel_id'];
			$item_info[$row['item']] = $row['item'];
			$record_info[] = $row;
		}
		if(empty($record_info))
        {
            return '';
        }
        $channel = array();
		if(!empty($channel_info))
		{
			$channel_ids = implode(',',$channel_info);
			include_once(ROOT_PATH . 'lib/class/live.class.php');
			$newLive = new live();
			$channel_tmp = $newLive->getChannelInfoById(array('id' => $channel_ids,'is_stream' => 1, 'live' => 1, 'is_sys' => -1, 'fetch_live' => 1));
			foreach($channel_tmp as $k => $v)
			{
				$channel[$v['id']] = $v;
			}
            if(empty($channel))
            {
            	return '';
            }
		}
		if(!empty($item_info))
		{
			include_once(ROOT_PATH . 'lib/class/livmedia.class.php');
			$livmedia = new livmedia();
			$item_info = $livmedia->getAutoItem();
		}
		include_once(ROOT_PATH . 'lib/class/program.class.php');
		$program_plan = new program();
		foreach($record_info as $k => $row)
		{
			if(empty($server_config[$row['server_id']]))
			{
				continue;
			}
			$check_server = $this->checkServer($server_config[$row['server_id']]['host'] . ':' . $server_config[$row['server_id']]['port'] . $server_config[$row['server_id']]['dir']);
			if(!$check_server)
			{
				continue;
			}
			$channel_tmp = $channel[$row['channel_id']];
			$row['code'] = $channel_tmp['code'];
			$row['name'] = $channel_tmp['name'];
			$row['is_audio'] = $channel_tmp['is_audio'];
			$row['save_time'] = $channel_tmp['time_shift'];
			$row['main_stream_name'] = $channel_tmp['main_stream_name'];
			$row['stream_state'] = $channel_tmp['status'];
			$row['record_time'] = $channel_tmp['record_time'];
			$row['stream_id'] = $channel_tmp['stream_id'];
			$row['sort_name'] = $row['item'] > 0 ? $item_info[$row['item']]['name'] : '';
			
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
			$program = $row['title'] ? $row['title'] : trim($program_plan->get_program_plan($ret['channel_id'],$ret['starttime'],$ret['endtime']));
			$ret['title'] = base64_encode(json_encode(trim($program ? $program : '精彩节目')));
			$log_data['title'] = rawurlencode(trim($program ? $program : '精彩节目'));
			
			$log_data['start_time'] = $row['start_time'];
			$log_data['toff'] = $row['toff'];
			$log_data['week_day'] = $row['week_day'];
			$log_data['item'] = $row['item'];
			$log_data['columnid'] = $row['columnid'];
			$log_data['column_name'] = $row['column_name'];
			$log_data['ip'] = $row['ip'];
			
			if(empty($channel_tmp['record_stream']))
			{
				continue;
			}
			$ret['stream'] = $channel_tmp['record_uri'] ? $channel_tmp['record_uri'] : $channel_tmp['record_stream'][0]['output_url_rtmp'];

			$callback = $this->settings['App_program_record']['protocol'] . $this->settings['App_program_record']['host'] . '/' . $this->settings['App_program_record']['dir'] . 'admin/record_callback.php';
			
			$ret['vod_sort_id'] = $row['item'];
			$ret['week_flag'] = $row['week_day'] ? 1 : 0;
			$ret['column_id'] = $row['columnid'] ? $row['columnid'] : 0;
			$ret['audit_auto'] = $row['audit_auto'] ? 2 : 0;
			$ret['exit_status'] = $row['audit_auto'];
			$ret['save_time'] = $row['save_time'];
			//$ret['delay_time'] = $row['live_delay'] * 60;
			//$ret['source'] = $row['channel_id'];
			$ret['is_allow'] = $row['is_mark'];
			$ret['is_audio'] = $row['is_audio'];
			$ret['extend'] = base64_encode(json_encode(array('_user_id' => $row['user_id'],'_user_name' => $row['user_name'],'org_id' => $row['org_id'],'force_codec'=>$row['force_codec'],'source' => $row['name'],'callback_extra' => $row['callback_extra'])));
			if(!$row['stream_state'])
			{
				$ret = array('errortext' => '视频流未开启！');
				$str = 'ID-' . $row['id'] . ($ret['errortext'] ? $ret['errortext'] : '录制成功');
				$this->tips($str,TIMENOW);
			}
			else
			{	
				switch($row['is_record'])
				{
					case  0://录制未进行提交
						$this->curl = new curl($server_config[$row['server_id']]['host'] . ':' .		
						$server_config[$row['server_id']]['port'], $server_config[$row['server_id']]['dir']);
						$this->curl->setSubmitType('get');
						$this->curl->initPostData();
						$this->curl->addRequestData('action', 'INSERT');
						$this->curl->addRequestData('url', $ret['stream']);
						$this->curl->addRequestData('callback', $callback);//urlencode
						//$this->curl->addRequestData('startTime', $ret['starttime']-1);
						$this->curl->addRequestData('duration', $row['toff']);
						$this->curl->addRequestData('uploadFile','0');
						$this->curl->addRequestData('app','live');
						$this->curl->addRequestData('streamname',$channel_tmp['code'].'_'.$channel_tmp['main_stream_name']);
						$this->curl->addRequestData('html',1);
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
								$ret['errortext'] = '录制超时,重复提交';//报错	
								$ret['isError'] = 1;
								$ret['id'] = -1;
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
							$log_id = $this->record->addLogs($log_data);
							$this->update_queue($conid,$log_id);
						}
						else//提交失败
						{
							$week_day = unserialize($row['week_day']);
							$select_curl = new curl($server_config[$row['server_id']]['host'] . ':' .		
							$server_config[$row['server_id']]['port'], $server_config[$row['server_id']]['dir']);
							$select_curl->setSubmitType('get');
							$select_curl->initPostData();
							$select_curl->addRequestData('action', 'SELECT');
							$select_curl->addRequestData('id',$row['id']);
							$select_record = $this->curl->request('');
							$select_array = xml2Array($select_record);
							if(is_array($select_array))
							{
								if(!$select_array['result'])//不存在说明是提交时过期
								{
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
								}
								else//任务已存在说明是重复提交,暂不增加任何操作
								{
									
								}
							}
							$log_data['text'] = $ret['errortext'];
							$log_data['state'] = 2;
							$log_id = $this->record->addLogs($log_data);
							$this->update_queue($conid,$log_id);
						}
						$str = 'ID-' . $row['id'] . ($ret['errortext'] ? $ret['errortext'] : '录制成功');
						$this->tips($str,TIMENOW);
						break;
					case 1://正在录制，录制超时,只处理周期性的录制超时
						if (($row['start_time'] + $row['toff']) < (TIMENOW-120))
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
								$str = 'ID-' . $row['id'] . '录制超时处理成功';
								$this->tips($str,TIMENOW);
							}
						}
						break;
				}				
			}			
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
	
	private function checkServer($url)
	{
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 2);
        curl_exec($ch);
		$head_info = curl_getinfo($ch);
        curl_close($ch);
		if ($head_info['http_code'] != 200)
		{
			return false;
		}
		return true;
	}
	
	function restart()
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "server_config WHERE 1 AND state=1";
		$q = $this->db->query($sql);
		$server_config = array();
		while($row = $this->db->fetch_array($q))
		{
			$server_config[$row['id']] = $row;
		}
		switch(intval($this->input['sort']))
		{
			case 0://重建当前时间之后所有的
				$sql = "SELECT * FROM " . DB_PREFIX . "program_record WHERE start_time > " . TIMENOW . " AND conid <> 0";
				//. " AND (start_time+toff)<" . strtotime(date('Y-m-d',TIMENOW) . ' 23:59:59')
				//录制等待中的，并未开始录制，并且是当天，删除录制，重新提交
				$q = $this->db->query($sql);
				$queue_id = $record_id = $space = "";
				$tmp_server = array();
				while($row = $this->db->fetch_array($q))
				{
					$queue_id .= $space . $row['conid'];
					//$record_id .= $space . $row['id'];
					$tmp_server[$row['id']] = $row['server_id'];
					$space = ',';
				}
				if($queue_id)
				{
					$sql = "SELECT * FROM " . DB_PREFIX . "program_queue WHERE id IN(" . $queue_id . ")";
					$q = $this->db->query($sql);
					include_once(ROOT_PATH . 'lib/class/curl.class.php');
					while($row = $this->db->fetch_array($q))
					{
						$obj_curl = new curl($server_config[$tmp_server[$row['record_id']]]['host'] . ':' . $server_config[$tmp_server[$row['record_id']]]['port'], $server_config[$tmp_server[$row['record_id']]]['dir']);
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
			
				$sql = "SELECT * FROM " . DB_PREFIX . "program_record WHERE 1";//conid=0 and start_time > " . TIMENOW;
				//. " AND (start_time+toff)<" . strtotime(date('Y-m-d',TIMENOW) . ' 23:59:59')
				//录制等待中的，并未开始录制，并且是当天，删除录制，重新提交
				$q = $this->db->query($sql);
				$queue_id = $record_id = $space = "";
				$record = array();			
				include_once(ROOT_PATH . 'lib/class/curl.class.php');
				while($row = $this->db->fetch_array($q))
				{
					$obj_curl = new curl($server_config[$row['server_id']]['host'] . ':'. $server_config[$row['server_id']]['port'], $server_config[$row['server_id']]['dir']);
					if($this->input['today'])
					{
						$row['start_time'] = strtotime(date('Y-m-d',TIMENOW).' ' . date("H:i:s",$row['start_time']));
					}
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
		//echo $log_id ;
		$sql = "DELETE FROM " . DB_PREFIX . "program_record_log WHERE state=2";
		$this->db->query($sql);
		$sql = "DELETE FROM " . DB_PREFIX . "program_queue WHERE log_id IN (" . $log_id . ")";
		$this->db->query($sql);
	}

	function tmp_func()
	{
		$sql = "SELECT a.id, a.title, a.conid, a.is_record,max(q.id) as qid , MAX( q.log_id ) , FROM_UNIXTIME( a.start_time,  '%Y-%m-%d %H:%i:%s' ) 
FROM m2o_program_queue q
LEFT JOIN m2o_program_record a ON a.id = q.record_id
WHERE a.conid =0
AND a.start_time > UNIX_TIMESTAMP( NOW( ) ) 
AND FROM_UNIXTIME( a.start_time,  '%Y-%m-%d' ) =  '2014-12-18'
GROUP BY a.id";
	//	$sql = "";
	//	$sql =  "SELECT a.id, a.title, a.conid, a.is_record,max(q.id) as qid , MAX( q.log_id ) , FROM_UNIXTIME( a.start_time,  '%Y-%m-%d %H:%i:%s' ) FROM m2o_program_queue q LEFT JOIN m2o_program_record a ON a.id = q.record_id WHERE a.id IN (136, 227, 308, 586, 679, 709 ) GROUP BY a.id";

		$q = $this->db->query($sql);
		$info = array();
		$id = $space = '';
		while($row = $this->db->fetch_array($q))
		{
			$id .= $space .$row['id'];
			$space = ',';
			$info[$row['id']] = $row['qid'];
		echo	$sqlc = "update m2o_program_record set conid=" . $row['qid'] . ",is_record=1 where id=" . $row['id'];
echo "<br/>";
		//	$this->db->query($sqlc);
		}
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