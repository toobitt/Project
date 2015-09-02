<?php
/***************************************************************************
* $Id: schedule_auto.php 33155 2013-12-30 04:04:23Z develop_tong $
***************************************************************************/
define('MOD_UNIQUEID','schedule_auto');
//define('DEBUG_MODE','schedule_auto');
define('ROOT_DIR', '../../../');
define('CUR_CONF_PATH', '../');
require(ROOT_DIR.'global.php');
require(CUR_CONF_PATH."lib/functions.php");
class scheduleAutoApi extends cronBase
{
	private $mLivemms;
	private $mLive;
	private $mSchedule;
	private $mRecordConfig;
	public function __construct()
	{
		parent::__construct();
		require_once ROOT_PATH . 'lib/class/live.class.php';
		$this->mLive = new live();
		
		require_once CUR_CONF_PATH . 'lib/schedule.class.php';
		$this->mSchedule = new schedule();
		
		require_once CUR_CONF_PATH . 'lib/record_config.class.php';
		$this->mRecordConfig = new recordConfig();
		
		require_once CUR_CONF_PATH . 'lib/livemms.class.php';
		$this->mLivemms = new livemms();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '串联单',	 
			'brief' => '串联单',
			'space' => '30',	//运行时间间隔，单位秒
			'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	public function schedule_auto()
	{
		$dates = date('Y-m-d');
		$TIMENOW = TIMENOW + $this->settings['schedule_time'];
		$ETIMENOW = TIMENOW + 600;
		$sql  = "SELECT * FROM " . DB_PREFIX . "schedule s LEFT JOIN " . DB_PREFIX . "channel_server c ON c.channel_id=s.channel_id";
		//$sql .= " WHERE s.dates = '" . $dates . "'";
		$sql .= " WHERE s.start_time >= " . $TIMENOW;
		$sql .= " AND s.start_time <= " . $ETIMENOW;
		$sql .= " AND s.is_run = 0 ";
		$sql .= " ORDER BY s.start_time ASC ";
		
		$q = $this->db->query($sql);
		
		$schedule = array();
		$lasttype = 0;
		$index_start = 0;
		$last_start_time = 0;
		$last_toff = 0;
		$file_lists =array();
		while ($row = $this->db->fetch_array($q))
		{
			/*/找出连续的文件列表
			if ($row['type'] == 2)
			{
				if ($row['file_id'] < 1)
				{
					continue;
				}
				if ($row['type'] == $lasttype && ($last_start_time + $last_toff) == $row['start_time'] && $row['file_id'] && $row['input_state'])
				{
					$schedule[$row['channel_id']][$index_start][$row['start_time']] = $row;
					$file_lists[$row['channel_id']][$index_start][$row['file_id']] = $row['toff'];
				}
				else
				{
					$index_start = $row['start_time'];
					$schedule[$row['channel_id']][$index_start][$row['start_time']] = $row;
					$file_list[$row['channel_id']][$index_start][] = $row['file_id'];
				}
			}
			else
			{
				$schedule[$row['channel_id']][$row['start_time']] = $row;
			}
			*/
			$schedule[$row['channel_id']][$row['start_time']] = $row;
			$lasttype = $row['type'];
			$last_toff = $row['toff'];
			$last_start_time = $row['start_time'];
		}
		$server_info = $this->settings['server_info'];
		$host 		 = $server_info['host'];
		$input_dir 	 = $server_info['input_dir'];
		if (is_array($schedule))
		{
			foreach ($schedule AS $channle_id => $val)
			{
							echo 'is_array';
				if (is_array($val))
				{
					foreach ($val AS $start_time => $v)
					{
						if ($v['is_run'])
						{
							echo 'is_run';
							continue;
						}
						if ($start_time >= $ETIMENOW)
						{
							echo 'time not arr';
							break;
						}
						//文件列表
						$file_list = $file_lists[$channle_id][$start_time];
						if (is_array($file_list) && count($file_list) > 1)
						{
							foreach ($v AS $stime => $vv)
							{
								echo $vv['change2_name'];
								if ($vv['is_run'])
								{
									echo 'is_run';
									continue;
								}
								if ($stime >= $ETIMENOW)
								{
									echo 'time not arr';
									break;
								}
								$input_id = $vv['file_id'];
								$input_state = 1;
								$source_type = 4;
								$toff = $vv['toff'];
								$output_id = $vv['output_id'];
								$data = array(
									'id'	=> $vv['id'],
									'input_id'		=> $input_id,
									'input_state'		=> $vv['input_state'],
									'error_cause'		=> $ret_input['message'],
								);			
								$data['is_run'] = 1;
							}
							continue;
							$source_type = 3;
							$toff = array_sum($file_list);
							$fileids = implode(',', array_keys($file_list));
							
							$list_data = array(
								'action'	=> 'insert',
								'id'		=> $fileids,
							);
							
							$ret_list = $this->mLivemms->inputListOperate($host, $input_dir, $list_data);
							$data = array(
								'id'	=> $v['id'],
								'input_id'		=> $input_id,
								'input_state'		=> $input_state,
								'error_cause'		=> $ret_list['message'],
							);	
							if($ret_list['result'])
							{
								$input_id = $ret_list['list']['id'];
								$input_state = 1;
								$data['is_run'] = 1;
							}
							else
							{
								$data['is_run'] = 0;
							}
									
						}
						else
						{
							if ($file_list)
							{
								$v = array_pop($v);
							}
							$output_id = $v['output_id'];
							if($v['type'] == 1)
							{
								$input_data = array(
									'action'	=> 'insert',
									'url'		=> $v['url'],
									'type'		=> 0,
								);								
								$ret_input = $this->mLivemms->inputStreamOperate($host, $input_dir, $input_data);										
								if (!$ret_input['result'])
								{
									continue;
								}
								$input_id = $ret_input['input']['id'];
								//启动
								$input_data = array(
									'action'	=> 'start',
									'id'		=> $input_id,
								);
								$ret_input = $this->mLivemms->inputStreamOperate($host, $input_dir, $input_data);	
								$input_state = $ret_input['result'];
								$data = array(
									'id'	=> $v['id'],
									'input_id'		=> $input_id,
									'input_state'		=> $input_state,
									'error_cause'		=> $ret_input['message'],
								);							
								if ($ret_input['result'])
								{
									$data['is_run'] = 1;
								}
								else
								{
									$data['is_run'] = 0;
								}
								$source_type = 1;
								$toff = $v['toff'];
								//print_r($data);
							}
							elseif($v['type'] == 2)
							{
								$input_id = $v['file_id'];
								$input_state = 1;
								$source_type = 4;
								$toff = $v['toff'];
								$data = array(
									'id'	=> $v['id'],
									'input_id'		=> $input_id,
									'input_state'		=> $v['input_state'],
									'error_cause'		=> $ret_input['message'],
								);			
								$data['is_run'] = 1;
							}
							elseif($v['type'] == 3)
							{
								if (!$record_config_info)
								{
									$record_config_info = $this->get_record_config();
								}
								
								if (!$record_config_info)
								{
									continue;
								}

								$url = $v['url'] . $v['start_time_shift'] . '000,' . $v['toff'] . '000.m3u8';
								
								$callback = $this->settings['App_schedule']['protocol'].$this->settings['App_schedule']['host'].'/'.$this->settings['App_schedule']['dir'].'admin/callback.php?a=record_callback&appid=' . intval($this->input['appid']) . '&appkey=' . trim($this->input['appkey']);

								$record_config = $record_config_info[@array_rand($record_config_info, 1)];
					
								$record_host 	= $record_config['record_host'];
								$record_dir 	= $record_config['record_dir'];
								$server_id		= $record_config['id'];
								
								$record_data = array(
									'action'		=> 'TIMESHIFT',
									'id' 			=> $this->settings['record']['prefix'] . $v['id'],
									'uploadFile' 	=> 0,
									'access_token'  => $this->user['token'],
									'channel_id'  	=> $v['change2_id'],
									'url'			=> urlencode($url),
									'callback'		=> urlencode($callback),
								);
								$ret_record = $this->mLivemms->recordOperate($record_host, $record_dir, $record_data);
								if($ret_record['result'])
								{
									$data = array(
										'id'	=> $v['id'],
										'is_run'		=> 1,
										'server_id'		=> $server_id,
									);			
									$this->mSchedule->update($data);
								}
								$input_state = 0;
							}
						}
						if ($input_state)
						{
							$schedule_data = array(
								'action'		=> 'insert',
								'outputId'		=> $output_id,
								'sourceId'		=> $input_id,
								'sourceType'	=> $source_type,
								'startTime'		=> $start_time,
								'duration'		=> $toff,
							);
							print_r($schedule_data);
							$ret_schedule = $this->mLivemms->inputScheduleOperate($host, $input_dir, $schedule_data);
							if ($ret_schedule['result'] && $ret_schedule['schedule']['id'])
							{
								$data['schedule_id'] = $ret_schedule['schedule']['id'];
								$this->mSchedule->update($data);
							}
						}
					}
				}
			}
		}
		$this->addItem($ret_schedule);
		$this->output();
	}
	
	public function test()
	{
		$url = $this->input['url'];
		if (!$url)
		{
			exit;
		}
		$record_config_info = $this->get_record_config();
		$callback = $this->settings['App_schedule']['protocol'].$this->settings['App_schedule']['host'].'/'.$this->settings['App_schedule']['dir'].'admin/callback.php?a=record_callback&appid=' . intval($this->input['appid']) . '&appkey=' . trim($this->input['appkey']);

		$record_config = $record_config_info[@array_rand($record_config_info, 1)];

		$record_host 	= $record_config['record_host'];
		$record_dir 	= $record_config['record_dir'];
		$server_id		= $record_config['id'];
		
		$record_data = array(
			'action'		=> 'TIMESHIFT',
			'id' 			=> $this->settings['record']['prefix'] . $v['id'],
			'uploadFile' 	=> 0,
			'access_token'  => $this->user['token'],
			'channel_id'  	=> $v['change2_id'],
			'url'			=> urlencode($url),
			'callback'		=> urlencode($callback),
		);
		$ret_record = $this->mLivemms->recordOperate($record_host, $record_dir, $record_data);
		print_r($ret_record);
	}
	/**
	 * 取出已审核的服务器配置
	 * 返回通路的服务器配置
	 * Enter description here ...
	 */
	private function get_record_config()
	{
		$sql = "SELECT id, record_protocol, record_host, record_dir, record_port FROM " . DB_PREFIX . "record_config ";
		$sql.= " WHERE status = 1 ORDER BY id DESC ";
		
		$q = $this->db->query($sql);
		
		$record_config = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['record_host'] = $row['record_host'] . ':' . $row['record_port'];
			unset($row['record_port']);
			$record_config[] = $row;
		}
		
		$return = array();
		if (!empty($record_config))
		{
			foreach ($record_config AS $v)
			{
				//筛选已审核且通路服务器
		        $ret_check_server = $this->mRecordConfig->check_server($v['record_host']);
		        if ($ret_check_server)
		        {
		        	$return[] = $v;
		        }
			}
		}
		
		return $return;
	}
}

$out = new scheduleAutoApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'schedule_auto';
}
$out->$action();
?>