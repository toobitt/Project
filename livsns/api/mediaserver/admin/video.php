<?php/* * 转码完成之后的回调操作(更新视频信息) *  */require('global.php');define('MOD_UNIQUEID','transcode_manger');//模块标识set_time_limit(0);class video extends adminBase{	private $video_info;	public function __construct()	{		global $_INPUT;		$input = &$_INPUT;		$this->video_info = json_decode(html_entity_decode($input['data']),1);		if($input['app_id'])		{			$input['appid']  = $input['app_id'];		}		else		{			$input['appid']  = $this->video_info['app_id'];		}				if($input['app_key'])		{			$input['appkey'] = $input['app_key'];		}		else		{			$input['appkey'] = $this->video_info['app_key'];		}		parent::__construct();	}		public function __destruct()	{		parent::__destruct();	}		//上传文件转码完成的回调	public function update_video()	{		$video_info = $this->video_info;		if(!$video_info || !is_array($video_info) || !$video_info['id'])		{			$this->errorOutput(NODATA);		}		//更新视频数据			if($video_info['exit_status'] == 1)		{			$trans_status = -1;//转码失败			hg_do_transcode_fail($video_info,$video_info['id']);//将转码失败的信息记录下来		}		else if($video_info['exit_status'] == 2)		{			//$trans_status = -1;//执行stop命令去除了转码			echo json_encode(array('return' => 'success'));			exit;		}		else		{			$trans_status = 1;//转码成功			if($video_info['extends'] && $video_info['extends'] != -1)			{				$trans_status = $video_info['extends'];			}			hg_do_transcode_success($video_info['id']);//转码成功之后删除vod_fail_video中的记录		}		$v_info = explode('x',trim($video_info['video_resolution']));		$video_width  = $v_info[0];		$video_height = $v_info[1];				$data = array(			'duration' 			=> intval((float)$video_info['total_time']*1000),			'trans_use_time'	=> intval((float)$video_info['transcode_time']*1000),			'totalsize' 		=> intval($video_info['file_size']),			'audio'	   			=> $video_info['audio_format'],			'audio_channels' 	=> $video_info['audio_channels']?'Front: L R':'',			'sampling_rate'		=> (intval($video_info['audio_sample_rate'])/1000).' KHz',			'video' 			=> $video_info['video_format'],			'frame_rate' 		=> $video_info['video_frame_rate'],			'aspect' 			=> $video_info['video_aspect_ratio'],			'width'				=> $video_width,			'height'			=> $video_height,			'bitrate'			=> $video_info['bitrate'],			'isfile'			=> 1,			'status'			=> $trans_status,			'is_audio'			=> $video_info['audio_only'],			'is_forcecode'		=> $video_info['is_recodec']?1:0,			'is_water_marked'	=> $video_info['is_water_marked']?1:0,			'update_time'		=> TIMENOW,		);				if($trans_status == 'retain_status')		{			unset($data['status']);		}				$sql = "UPDATE ".DB_PREFIX."vodinfo SET ";		foreach ($data AS $k => $v)		{			$sql .= " {$k} = '{$v}',";		}		$sql = trim($sql,',');		$sql .= " WHERE id = {$video_info['id']} ";		$this->db->query($sql);				//查询出转码后文件的目录		$sql = "SELECT * FROM ".DB_PREFIX."vodinfo WHERE id = {$video_info['id']} ";		$vodinfo = $this->db->query_first($sql);		if($vodinfo['status'] == 2)		{			if(!empty($vodinfo['expand_id']))			{				$op = "update";			}			else 			{				$op = "insert";			}		}		$this->publish_video($video_info['id'], $op);		$target = $vodinfo['video_base_path'] . $vodinfo['video_path'];		$filename = explode('.',$vodinfo['video_filename']);		$video_name = $filename[0];		/*************************此处处理携带文件的情况**********************/		if($_FILES['videofile'])		{				if (!@move_uploaded_file($_FILES['videofile']['tmp_name'], $target . $vodinfo['video_filename']))			{				$this->errorOutput(FAILMOVE);			}		}		/****************************************************************/		if(!defined('NOT_CREATE_ISMV') || !NOT_CREATE_ISMV)		{			$this->create_ism($target,$video_name,$vodinfo['video_filename']);		}				//判断有没有用户需要回调的地质		$after_callback_path = DATA_DIR . 'after_callback_url/' . $video_info['id'] . '.url';		if(file_exists($after_callback_path))		{			$after_callback_url = file_get_contents($after_callback_path);			//回调			if($vodinfo['tv_play_id'])			{				$vodinfo['callback_data'] = array('tv_play_id' => $vodinfo['tv_play_id'],'after_callback' => 1);				$vodinfo['img'] = unserialize($vodinfo['img_info']);				$this->mediaserverCallback($after_callback_url,$vodinfo);			}			else 			{				$data['video_id'] = $video_info['id'];				$this->mediaserverCallback($after_callback_url,$data);			}						//回调完成就删除			@unlink($after_callback_path);		}				echo json_encode(array('return' => 'success'));	}		//用命令生成视频的ism文件	private function create_ism($target,$video_name,$fromvideo)	{		$ismv = $target . $video_name . '.ismv';		$ism  = $target . $video_name . '.ism';		if(file_exists($ismv))		{			@unlink($ismv);		}		if(file_exists($ism))		{			@unlink($ism);		}		$cmd = MP4SPLIT_CMD . $ismv . ' ' . $target . $fromvideo;		$cmd .= "\n" . MP4SPLIT_CMD . $ism . ' ' . $ismv;		if (defined('FFMPED2TS_CMD') && FFMPED2TS_CMD)		{			$ts_dur = intval(TS_DURATION) ? TS_DURATION : 10;			hg_mkdir($target . 'ts/');			$cmd .= "\ncd {$target}\n" . FFMPED2TS_CMD . ' -i ' . $target . $fromvideo . ' -f  segment -segment_time ' . $ts_dur . ' -segment_format mpegts -segment_list playlist.m3u8  -codec copy -bsf:v h264_mp4toannexb -map 0 ts/' . $video_name . '_%d.ts';		}		exec($cmd);	}			private function publish_video($id,$op,$column_id = array())	{		$id = intval($id);		if(empty($id))		{			return false;		}		if(empty($op))		{			return false;		}					$sql = "select * from " . DB_PREFIX ."vodinfo where id = " . $id;		$info = $this->db->query_first($sql);		if(empty($column_id))		{			$info['column_id'] = unserialize($info['column_id']);			if(is_array($info['column_id']))			{				$column_id = array_keys($info['column_id']);				$column_id = implode(',',$column_id);			}		}		else		{			$column_id = implode(',',$column_id);		}		require_once(ROOT_PATH . 'lib/class/publishplan.class.php');		$plan = new publishplan();		$data = array(			'set_id' 		=> PUBLISH_SET_ID,			'from_id' 		=> $info['id'],			'class_id' 		=> 0,			'column_id' 	=>  $column_id,			'title' 		=> $info['title'],			'action_type'	=> $op,			'publish_time'	=> $info['pub_time'],			'publish_people'=> $this->user['user_name'],			'ip'=> hg_getip(),		);		$ret = $plan->insert_queue($data);		return $ret;	}		//上传之后的回调    private function mediaserverCallback($callback_url = '',$data = array())    {    	if(!$callback_url)    	{    		return false;    	}    	    	if(!class_exists('curl'))    	{    		include_once(ROOT_PATH . 'lib/class/curl.class.php');    	}    	    	$curl = new curl();    	return $curl->curl_json($callback_url,$data);    }}$out = new video();if(!method_exists($out, $_INPUT['a'])){	$action = 'update_video';}else {	$action = $_INPUT['a'];}$out->$action(); ?>