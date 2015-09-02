<?php
/*
 * 录制完成之后的回调操作
 * 
 */
require('global.php');
define('MOD_UNIQUEID','live_time_shift');//模块标识
require_once(ROOT_PATH . 'lib/class/curl.class.php');
require_once(CUR_CONF_PATH . 'lib/live_time_shift_mode.php');
set_time_limit(0);

class live_time_shift_callback extends adminBase
{
	private $mode;
	public function __construct()
	{
		parent::__construct();
		$this->mode = new live_time_shift_mode();
	}
	
	public function callBack()
	{
		$callback_data = $this->input['data'];
		$shift = json_decode(html_entity_decode($callback_data),1);
		/*****************************根据时移反馈的信息，更新时移日志的状态*******************************/
		$sql = 'SELECT * FROM '.DB_PREFIX.'time_shift_log WHERE id = '.(int)$shift['id'];
		$shift_info = $this->db->query_first($sql);
		if(empty($shift_info))
		{
			$this->errorOutput('时移数据不存在');
		}
		if($shift)
		{
			switch ($shift['exit_status'])
			{
				case 0:$status = 0;break;
				case 1:$status = 1;break;
			}
			//更新时移状态
			$this->mode->update($shift['id'],array('status' => $status));
		}
		
		if(!$status)
		{
			writeErrorLog("时移失败:\n" . var_export($shift,1));
			$this->errorOutput('时移失败');
		}
		/*****************************根据时移反馈的信息，更新时移日志的状态*******************************/
		
		/****************************将时移好的视频提交到mediaserver进行转码*****************************/
		if(!$this->settings['App_mediaserver'])
		{
			$this->errorOutput('未安装mediaserver');
		}

		$curl = new curl($this->settings['App_mediaserver']['host'],$this->settings['App_mediaserver']['dir'] . 'admin/');
  	    $curl->setSubmitType('post');
		$curl->initPostData();
		//构建需要提交的数据
		$data = array(
		   'filepath' 			=> $shift['file_path'],
		   'vod_sort_id' 		=> $shift['vod_sort_id'],
		   'vod_leixing' 		=> 3,
		   'start'				=> '0',
		   'duration'			=> '',
		);
		
		if($shift_info['live_split_callback'])
		{
			$data['app_uniqueid'] = 'live_split_data';
			$data['mod_uniqueid'] = 'live_split_data';
		}
		
		if(!empty($shift) && $shift['extend'])
		{
			$shift['extend'] = json_decode(base64_decode($shift['extend']),1);
			foreach($shift['extend'] as $k => $v)
			{
				$curl->addRequestData($k,$v);
			}
		}
		
		foreach ($data AS $k => $v)
		{
			$curl->addRequestData($k,$v);
		}

		$ret = $curl->request('create.php');		
		/****************************将时移好的视频提交到mediaserver进行转码*****************************/
		
		/****************************提交之后的回调处理************************************************/
		if($ret && $ret[0]['id'])
		{
			//更新时移的视频id
			$this->mode->update($shift['id'],array('video_id' => $ret[0]['id']));
			$shift_info['live_split_callback'] && $this->live_split_callback($shift_info['live_split_callback'],$callback_data, $ret[0]['id']);
		}
		else 
		{
			//时移成功，但是提交mediserver失败
			$this->mode->update($shift['id'],array('status' => 3));			
			$shift_info['live_split_callback'] && $this->live_split_callback($shift_info['live_split_callback'],$callback_data, 0);
			writeErrorLog("时移成功,提交转码失败:\n" . var_export($ret,1));
		}
		$this->addItem($ret);
		$this->output();
	}
	
	public function live_split_callback($live_split_id, $callbackdata , $video_id)
	{
		$curl  = new curl($this->settings['App_video_split']['host'],$this->settings['App_video_split']['dir'] . 'admin/');
	  	$curl->setSubmitType('post');
		$curl->initPostData();
		$curl->addRequestData('access_token', $this->user['token']);
		$curl->addRequestData('live_split_id', $live_split_id);
		$curl->addRequestData('data', $callbackdata);
		$curl->addRequestData('video_id', $video_id);
		$curl->addRequestData('a', 'time_shift_callbck');
		return $curl->request('live_split_update.php');
	}
	
	protected function verifyToken(){}
}

$out = new live_time_shift_callback();
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