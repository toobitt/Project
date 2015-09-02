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
		$record = json_decode(html_entity_decode($this->input['data']),1);
		$curl = new curl($this->settings['App_mediaserver']['host'],$this->settings['App_mediaserver']['dir'] . 'admin/');
  	    $curl->setSubmitType('post');
		$curl->initPostData();
		$data = array(
		   'id'					=> $record['id'],
		   'is_time_shift'      => $record['time_shift'] ? 1 : 0,
		   'title' 				=> $record['title'] ? $record['title'] : '精彩节目',
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
		   'force_recodec' 		=> $record['force_codec'],
		   'appid' 				=> $record['appid'],
		   'appkey' 			=> $record['appkey'],
		   'comment'            => $record['comment'],
		   'user_id'            => $record['user_id'],
		   'user_name'          => $record['user_name'],
		);
		
		foreach ($data AS $k => $v)
		{
			$curl->addRequestData($k,$v);
		}

		include_once(ROOT_PATH . 'lib/class/program_record.class.php');
		$obj_record = new programRecord();
		$tmp_ret = array();
		switch($record['exit_status'])
		{
			case 0://出错
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
			break;
			case 1://成功
				$data = array(
					'text' => '录制成功',
					'state' => 1,
					'content_id' => $record['id'],
					'conid' => $record['id'],
				);
				$tmp_ret = $obj_record->updateLogs($data);
				if($tmp_ret)
				{
					$obj_record->update_record_state($record['id']);
				}
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
			$this->addItem(array());
			$this->output();
			break;
			default:
			break;
		}
		$ret = $curl->request('create.php');
		$this->addItem($ret);
		$this->output();
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