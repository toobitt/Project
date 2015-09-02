<?php
/***************************************************************************
* $Id: callback.php 32028 2013-11-28 05:18:11Z tong $
***************************************************************************/
define('MOD_UNIQUEID','callback');
require('global.php');
class callbackApi extends cronBase
{
	private $mLivemms;
	private $mLive;
	private $mSchedule;
	public function __construct()
	{
		parent::__construct();
		require_once ROOT_PATH . 'lib/class/live.class.php';
		$this->mLive = new live();
		
		require_once CUR_CONF_PATH . 'lib/schedule.class.php';
		$this->mSchedule = new schedule();
		
		require_once CUR_CONF_PATH . 'lib/livemms.class.php';
		$this->mLivemms = new livemms();
	}
	

	public function __destruct()
	{
		parent::__destruct();
	}

	public function initcron()
	{
	}
	
	public function backup_callback()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入串联单id');
		}
		
		if (!$this->input['result'])
		{
			$this->errorOutput('备播文件添加失败');
		}

		$schedule = $this->mSchedule->get_schedule_by_id($id);
		
		if (empty($schedule))
		{
			$this->errorOutput('该串联单不存在或已被删除');
		}
		
		
		$server_info = $this->settings['server_info'];
		$host 		 = $server_info['host'];
		$input_dir 	 = $server_info['input_dir'];
		

		if (0 && $schedule['schedule_id'])
		{
			$delete_schedule_id = explode(',', $schedule['schedule_id']);
			
			foreach ($delete_schedule_id AS $v)
			{
				if ($v)
				{
					$schedule_data = array(
						'action'		=> 'delete',
						'id'			=> $v,
					);
					
					$ret_schedule = $this->mLivemms->inputScheduleOperate($host, $input_dir, $schedule_data);
					
					if (!$ret_schedule['result'])
					{
						continue;//$this->errorOutput('媒体库串联单删除失败');
					}
				}
				
			}
		}
		
		$toff 		= intval($schedule['toff']);
		$file_toff  = intval(substr($schedule['file_toff'], 0, -3));
		
		if ($toff && $file_toff)
		{
			$loop_count = ceil($toff / $file_toff);
		}
		
		//剩余时长
		$last_toff = $toff - ($loop_count - 1) * $file_toff;
		
		$schedule_id = array();
		if (0)
		{
			for ($i = 0; $i < $loop_count; $i ++)
			{
				$schedule_data = array(
					'action'		=> 'insert',
					'outputId'		=> $schedule['change_id'],
			//		'sourceId'		=> $list_id,
					'sourceId'		=> $schedule['file_id'],
					'sourceType'	=> $schedule['source_type'],
					'startTime'		=> $schedule['start_time'] + $i * $file_toff,
					'duration'		=> ($i == ($loop_count - 1)) ? $last_toff : $file_toff,
				);
				
				$ret_schedule = $this->mLivemms->inputScheduleOperate($host, $input_dir, $schedule_data);
				
				if (!$ret_schedule['result'])
				{
					continue;//$this->errorOutput('媒体库添加串联单失败');
				}
				$schedule_id[$i] = $ret_schedule['schedule']['id'];
			}
		}
		
		$update_data = array(
			'id'	 	 	=> $id,
	//		'source_id'	 	=> $list_id,
			'source_id'	 	=> $schedule['file_id'],
			'schedule_id'	=> implode(',', $schedule_id),
			'is_success' 	=> $this->input['result'],
			'input_state' 	=> $this->input['result'],
		);
		if ($this->input['result'] && $this->input['source'] == 'timeshift')
		{
			$sql = 'SELECT output_id  FROM ' . DB_PREFIX . 'channel_server WHERE channel_id=' . $schedule['channel_id'];
			$schedule_serv = $this->db->query_first($sql);
			$schedule_data = array(
				'action'		=> 'insert',
				'outputId'		=> $schedule_serv['output_id'],
				'sourceId'		=> $schedule['file_id'],
				'sourceType'	=> 4,
				'startTime'		=> $schedule['start_time'],
				'duration'		=> $schedule['toff'],
			);
			$ret_schedule = $this->mLivemms->inputScheduleOperate($host, $input_dir, $schedule_data);
			if ($ret_schedule['result'] && $ret_schedule['schedule']['id'])
			{
				$update_data['schedule_id'] = $ret_schedule['schedule']['id'];
			}
		}
		
		$return = $this->mSchedule->update($update_data);
		$this->addItem($return);
		$this->output();
	}
	
	public function record_callback()
	{
		//file_put_contents(CACHE_DIR . 'ss.txt', var_export($this->input, 1));
		$record = str_replace('&quot;','"',$this->input['data']);
		$record = json_decode($record,1);
		if (!$record['exit_status'])
		{
			$this->errorOutput('抓取时移文件失败');
		}
		
		$record_protocol 	= $this->settings['record']['protocol'];
		$record_protocol	= $record_protocol ? $record_protocol : 'http://';
		$record_prefix 		= $this->settings['record']['prefix'];
		
		if ($record['id'])
		{
			$id = intval(substr($record['id'], strlen($record_prefix)));
		}
		
		if (!$id)
		{
			$this->errorOutput('未传入串联单id');
		}
		
		$schedule = $this->mSchedule->get_schedule_by_id($id);
		
		if (empty($schedule))
		{
			$this->errorOutput('该串联单不存在或已被删除');
		}
		
		//时移服务器信息
		$record_config = array();
		if ($schedule['server_id'])
		{
			$record_config = $this->get_record_config(intval($schedule['server_id']));
		}
		
		if (empty($record_config))
		{
			$this->errorOutput('服务器配置不存在或已被删除');
		}
		
		$record_host = $record_config['record_output_host'];
	//	$record_dir  = $record_config['record_output_dir'];
		
	
		
		$server_info = $this->settings['server_info'];
		$host 		 = $server_info['host'];
		$input_dir 	 = $server_info['input_dir'];
		
		
	//	$url = $record_protocol . $record_host . '/' . $record_dir . $record['file_path'];
		$url = $record_protocol . $record_host . '/' . $record['file_path'];
		
		$callback = $this->settings['App_schedule']['protocol'] . $this->settings['App_schedule']['host'] . '/' . $this->settings['App_schedule']['dir'] . 'admin/callback.php?a=backup_callback&source=timeshift&id=' . $id . '&appid=' . intval($this->input['appid']) . '&appkey=' . trim($this->input['appkey']);
		
		$file_data = array(
			'action'	=> 'insert',
			'url'		=> $url,
			'callback'	=> urlencode($callback),
		);
		
		$ret_file = $this->mLivemms->inputFileOperate($host, $input_dir, $file_data);
		if (!$ret_file['result'])
		{
			$this->errorOutput('媒体库添加备播文件失败');
		}
		
		$file_id = $ret_file['file']['id'];
		$update_data = array(
			'id'		=> $id,
			'file_id'	=> $file_id,
			'file_toff'	=> $schedule['toff'] . '000',
		);
		$return = $this->mSchedule->update($update_data);
		
		$this->addItem($return);
		$this->output();
	}
	
	function unknow()
	{
		$this->errorOutput('未定义的空方法');
	}
	
	protected function verifyToken()
	{
	}

	private function get_record_config($id)
	{
		if (!$id)
		{
			return array();
		}
		$sql  = "SELECT id, record_protocol, record_output_host, record_output_dir FROM " . DB_PREFIX . "record_config ";
		$sql .= " WHERE id = " . $id;
		$return = $this->db->query_first($sql);
		return $return;
	}
}
$out = new callbackApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>