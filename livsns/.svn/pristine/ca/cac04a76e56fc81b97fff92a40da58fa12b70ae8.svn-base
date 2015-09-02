<?php
/*
 * 录制完成之后的回调操作
 * 
 */
require('global.php');
define('MOD_UNIQUEID','transcode_manger');//模块标识
require_once(ROOT_PATH . 'lib/class/curl.class.php');
set_time_limit(0);
class record_callback extends adminBase
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function callBack()
	{
		$record = json_decode(html_entity_decode($this->input['data']),1);//striplashes
		$curl = new curl($this->settings['App_mediaserver']['host'],$this->settings['App_mediaserver']['dir'] . 'admin/');
  	    $curl->setSubmitType('post');
		$curl->initPostData();
		$data = array(
		   'id'					=> $record['id'],
		   'is_time_shift'      => $record['time_shift'] ? 1 : 0,
		   'title' 				=> $record['title'] ? json_decode(base64_decode($record['title'])) : '精彩节目',
		   'filepath' 			=> $record['file_path'],
		   'source' 			=> $record['source'],
		   'is_mark' 			=> $record['is_allow'],
		   'vod_sort_id' 		=> $record['vod_sort_id'],
		   'audit_auto' 		=> $record['audit_auto'],
		   'column_id' 			=> $record['column_id'],
		   'channel_id' 		=> $record['channel_id'],
		   'vod_leixing' 		=> 3,
		   'start' 				=> '0',
		   'end' 				=> $record['duration']*1000,
		   'starttime' 			=> $record['start_time'],
		   'create_time'		=> -1,
		   'appid' 				=> $this->input['appid'],
		   'appkey' 			=> $this->input['appkey'],
		   'comment'            => $record['comment'],
		);
		if(!empty($record) && $record['extend'])
		{
			$record['extend'] = json_decode(base64_decode($record['extend']),1);
			foreach($record['extend'] as $k => $v)
			{
				$data[$k] = $v;
			}
		}
		foreach ($data AS $k => $v)
		{
			$curl->addRequestData($k,$v);
		}
		$ret = $curl->request('create.php');
		
		
		include_once(ROOT_PATH . 'lib/class/program_record.class.php');
		$obj_record = new programRecord();
		$tmp_ret = array();
		switch($record['exit_status'])
		{
			case 0://出错
				$shift_data = $data;
				$data = array(
					'text' => '录制失败',
					'state' => 2,
					'content_id' => $record['id'],
					'conid' => $record['id'],
				);
				$tmp_ret = $obj_record->updateLogs($data);
				if($tmp_ret)
				{
					$obj_record->update_record_state($record['id']);
				}
				/*********************如果失败提交到时移****************************/
				$time_shift_data = array(
					 'channel_id' 		=> $shift_data['channel_id'],
					 'start_time' 		=> $shift_data['start_time'],
					 'end_time' 		=> intval($shift_data['start_time'] + $record['duration']),
					 'title' 			=> $shift_data['title'],
					 'is_mark' 			=> $shift_data['is_allow'],
				     'vod_sort_id' 		=> $shift_data['vod_sort_id'],
				     'audit_auto' 		=> $shift_data['audit_auto'],
				     'column_id' 		=> $shift_data['column_id'],
				 	 'force_recodec' 	=> $shift_data['force_codec'],
				);
				$this->submitToTimeShift($time_shift_data);
				/*********************如果失败提交到时移****************************/
				echo json_encode(array('result' => 0));
			break;
			case 1://成功
				$data = array(
					'text' => '录制成功',
					'state' => 1,
					'content_id' => $record['id'],
					'conid' => $record['id'],
				);
				if(!$record['update_state'])
				{
					$tmp_ret = $obj_record->updateLogs($data);
					if($tmp_ret)
					{
						$obj_record->update_record_state($record['id']);
					}
				}
				if($record['extend']['callback_extra'])
				{	
					$callback_extra = json_decode($record['extend']['callback_extra'],true);
					$curl_extra = new curl($this->settings[$callback_extra['app']]['host'],$this->settings[$callback_extra['app']]['dir']);
					$curl_extra->setSubmitType('post');
					$curl_extra->initPostData();
					$curl_extra->addRequestData('vodid',$ret['id']);
					$curl_extra->addRequestData('a',$callback_extra['action']);
					$curl_extra->request($callback_extra['filename']);
				}					
				echo json_encode(array('result' => 1));
			break;
			case 2://停止录制
/*
			$data = array(
				'text' => '停止录制',
				'state' => 2,
				'content_id' => $record['id'],
				'conid' => $record['id'],
			);
			$obj_live->updateLogs($data);
			$obj_live->update_record_state($record['id']);
*/
			echo json_encode(array('result' => -1));
			break;
			default:
			break;
		}
	}
	
	//提交到时移接口
	private function submitToTimeShift($data)
	{
		$curl = new curl($this->settings['App_live_time_shift']['host'],$this->settings['App_live_time_shift']['dir'] . 'admin/');
		$curl->setSubmitType('get');
		$curl->initPostData();
		foreach ($data AS $k => $v)
		{
			$curl->addRequestData($k,$v);
		}
		$curl->request('live_time_shift_update.php');
	}
	
	protected function verifyToken()
	{
	}
}

$out = new record_callback();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'callBack';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>