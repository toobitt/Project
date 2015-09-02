<?php
/*
 * 视频标注接口
 */
require_once('global.php');
define('MOD_UNIQUEID','transcode_manger');//模块标识
require_once(CUR_CONF_PATH . 'lib/transcode.class.php');
require_once(CUR_CONF_PATH . 'lib/TranscodeRoute.class.php');
set_time_limit(0);

class videomark extends adminBase
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

	public function mark()
	{	
		if(!$this->input['type'])
		{
			$this->errorOutput(NOMARKTYPE);
		}
		
		$type = ($this->input['type']);
		if($type == 'transcode_mark' || $type == 'transcode_fast_edit')
		{
			$func = 'videomark';
		}
		else
		{
			$func = "mark_".$type;
		}

		if(!method_exists($this, $func))
		{
			$func = 'unknow';
		}
		$this->$func($type);
	}
	
	//生成多码流视频
	private function mark_transcode_multi_bitrate($type)
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);//任务id
		}
		$vid = intval($this->input['id']);
		$sql 	= "SELECT * FROM ".DB_PREFIX."vodinfo WHERE id = {$vid}";
		$video 	= $this->db->query_first($sql);
		
		//采用转码之后的视频
		$video_source = rtrim($video['video_path'],'/')  . '/' . $video['video_filename'];
		
		//构建target的目录	
		$output_file = array();
		$transcode_configs = get_transcode_configs();
		$clarityUniqueId = array();//记录清晰度标识
		if($transcode_configs)
		{
			foreach($transcode_configs AS $k => $v)
			{
				$target_dir_info = pathinfo(rtrim($video['video_path'],'/'));
				$new_target_dir  = $target_dir_info['dirname'] . '/' . $v['unique_id'] . '_' . $target_dir_info['basename'];
				if (!hg_mkdir($video['video_base_path'] . $new_target_dir) || !is_writeable($video['video_base_path'] . $new_target_dir))
				{
					$this->errorOutput(NOWRITE);
				}
				
				$output_file[] = array(
					'targetDir' 		=> $video['video_base_path'] . $new_target_dir,
					'output_filename' 	=> $v['unique_id'] . '_' . $target_dir_info['filename'],
					'config'			=> $v,
				);
				$clarityUniqueId[] = $v['unique_id'];
			}
		}
		
		//选择转码服务器
		$tran_server = select_servers();
		if(!$tran_server)
		{
			$this->errorOutput(NO_SELECT_TRANSERVER);
		}
		if($tran_server['need_file'])
		{
			/*
			if(defined("TARGET_VIDEO_DOMAIN"))
			{
				$url = 'http://' . ltrim(TARGET_VIDEO_DOMAIN,'http://') . '/' . $video_source;
			}
			else
			{
				$url = $this->settings['videouploads']['protocol'] . $this->settings['videouploads']['host'] . '/' . $video_source;
			}
			*/
			$url = 'http://' . ltrim(rtrim($video['hostwork'],'/'),'http://') . '/' . $video_source;
		}
		else
		{
			$url = '';
		}
		
		$sourceFile = array();
		$sourceFile[] = array(
			'source' 			=> $video['video_base_path'] . $video_source,
			'start' 			=> '0',
			'duration' 			=> '',
			'is_water_marked' 	=> '0',
			'url'				=> $url,
		);
		
		//构建提交转码的数据
		$this->settings['App_mediaserver']['dir'] = $this->settings['App_mediaserver']['dir'] . 'admin/';
		$this->settings['App_mediaserver']['filename'] = 'more_bitrate_callback.php';//设置多码流回调
		$data = array(
		    "sourceFile" 		=> $sourceFile,
		    "id" 				=> $vid . '_more',
		    "app_id" 			=> APPID,
			"app_key" 			=> APPKEY,
		    "type" 				=> $type,
		    "outputFile"		=> $output_file,
		    "callback" 			=> $this->settings['App_mediaserver'],
			"absolute_path"		=> '1',//采用绝对路径
	    );
		$trans = new transcode($tran_server);
		$ret = $trans->addTranscodeTask($data);
		$return = json_decode($ret,1);
		if($return['return'] != 'fail')
		{
			$sql = "UPDATE " .DB_PREFIX. "vodinfo SET clarity = '" .serialize($clarityUniqueId). "' WHERE id = '" .$vid. "'";
			$this->db->query($sql);
		}
		echo $ret;
	}
	
	private function videomark($type)
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);//任务id
		}
		$vid 				= intval($this->input['id']);
		$start 				= $this->input['start'];//对应每个视频片段的开始时间
		$duration 			= $this->input['duration'];//对应每个视频片段的时长
		$source_dir 		= $this->input['source_dir'];//视频片段的原文件目录
		$is_water_marked 	= $this->input['is_water_marked'];//视频片段的是否已经加过水印
		$is_forcecode 		= $this->input['is_forcecode'];//标识视频是否已经经过强制转码
		$video_base_path 	= $this->input['video_base_path'];//视频的基路径
		
		if(!is_array($start))
		{
			foreach(array("start","duration","source_dir") AS $v)
			{
				$$v = array($$v);
			}
		}
		
		if (count($start) != count($duration) || count($start) != count($source_dir))
		{
			$this->errorOutput(NOMATCH);
		}
		
		/****************查询视频配置信息*****************************/
		$vod_config = transcode_config();
		/***************配置一下转码完成之后视频的存放目录****************/
		if($type == 'transcode_mark')
		{
			//判断当前该视频是不是正在转码，如果正在转码就删除该任务
			if($t_server = checkStatusFromAllServers($vid))
			{
				$s_tran = new transcode($t_server);
				$s_tran->stop_transcode_task($vid);
			}
			
			//拆条的时候要判断原视频是否正在转码中，如果正在转码中,暂时不拆条
			$sql = "SELECT * FROM " .DB_PREFIX. "vodinfo WHERE id = '" .$vid. "'";
			$cur_video = $this->db->query_first($sql);
			if($cur_video['original_id'])
			{
				if(checkStatusFromAllServers($cur_video['original_id']))
				{
					$sql = " UPDATE ".DB_PREFIX. "vodinfo SET status = -1 WHERE id =  {$vid}";
					$this->db->query($sql);
					$this->addLogs('拆条有误', '', '','当前拆条的原视频正在转码中,该视频id:' . $vid);
					$this->errorOutput('当前拆条的原视频正在转码中,请稍后对该视频拆条');				
				}
			}
			
			$v_videodir = create_video_dir();
			$vod_dir_names  = $v_videodir[0];
			$target_dir = $v_videodir[1];
			$all_target_dir = TARGET_DIR . $target_dir;
			if (!hg_mkdir($all_target_dir) || !is_writeable($all_target_dir))
			{
				$this->errorOutput(NOWRITE);
			}
			$output_filename = $vod_dir_names.'';
			//为了兼容老版本
			if(defined("TARGET_VIDEO_DOMAIN"))
			{
				$server_host =  ltrim(TARGET_VIDEO_DOMAIN,'http://');
			}
			else 
			{
				$server_host =  $this->settings['videouploads']['host'];
			}
			
			$sql = " UPDATE ".DB_PREFIX. "vodinfo SET video_base_path = '"  .TARGET_DIR.  "', video_path = '".$target_dir."',video_filename = '".$output_filename.".".$vod_config['output_format']."',hostwork = 'http://". $server_host."' WHERE id =  {$vid}";
			$this->db->query($sql);
		}
		else
		{
			//快编的时候由于要覆盖原视频，所以$target_dir目录是原来视频的目录，从数据库查询
			$sql = "SELECT video_base_path,video_path,video_filename,transcode_server FROM ".DB_PREFIX."vodinfo WHERE id = {$vid}";
			$arr = $this->db->query_first($sql);
			
			//检测该视频有没有被拆过条,如果有的话就不允许快编
			if($this->checkVideoIsSplit($vid))
			{
				$this->addLogs('快编有误', '', '','该视频已经有拆条不能快编,该视频id:' . $vid);
				$this->errorOutput('该视频已经有拆条不能快编');		
			}
			
			//判断当前该视频是不是正在转码，如果正在转码就删除该任务
			foreach(array($vid,$vid . '_more') AS $_vid)
			{
				if($t_server = checkStatusFromAllServers($_vid))
				{
					$s_tran = new transcode($t_server);
					$s_tran->stop_transcode_task($_vid);
				}
			}

			$target_dir = $arr['video_path'];
			$filename = explode('.',$arr['video_filename']);
			$output_filename = $filename[0];
			if($this->input['audit_auto'])
			{
				$this->settings['App_mediaserver']['extends'] = ($this->input['audit_auto']);
			}
			$all_target_dir = $arr['video_base_path'] . $target_dir;
		}
		
		//更新当前清晰度，表明当前视频使用哪个转码配置进行转码的
		$sql = " UPDATE ".DB_PREFIX. "vodinfo SET cur_clarity = '" .$vod_config['unique_id']. "',status = 0,app_uniqueid = 'livmedia',mod_uniqueid = 'livmedia' WHERE id =  {$vid}";
		$this->db->query($sql);
		
		//选取转码服务器
		$tran_server = select_servers($vid);
		if(!$tran_server)
		{
			$this->errorOutput(NO_SELECT_TRANSERVER);
		}
		$source_conf = array();
		foreach($source_dir AS $k => $v)
		{
			$source_conf[] = array(
				'source' => $video_base_path[$k] .  $source_dir[$k],
				'start' => $start[$k],
				'duration' => $duration[$k],
				'is_water_marked' => $is_water_marked[$k],
				'is_recodec' => $is_forcecode[$k],
				'url'	=> $tran_server['need_file']?(defined("TARGET_VIDEO_DOMAIN")?'http://' . ltrim(TARGET_VIDEO_DOMAIN,'http://') . '/' . $source_dir[$k]:$this->settings['videouploads']['protocol'] . $this->settings['videouploads']['host'] . '/' . $source_dir[$k]):'',
			);
		}
		
		/**************用curl将视频连同转码参数一并提交过去****************/
		$this->settings['App_mediaserver']['dir'] = $this->settings['App_mediaserver']['dir'] . 'admin/';
		$data = array(
			"sourceFile" 		=> $source_conf,
			"id" 				=> "{$vid}",
			"app_id" 			=> APPID,
			"app_key" 			=> APPKEY,
		    "type"				=>	$type,
		    "targetDir" 		=> $all_target_dir,
		    "output_filename" 	=> $output_filename,
			"config" 			=> $vod_config,
			"callback" 			=> $this->settings['App_mediaserver'],
			"absolute_path"		=> '1',//采用绝对路径
		);
		
		//根据选取到转码服务器是否需要携带文件，选择各自的提交方式
		if($tran_server['need_file'])
		{
			$data['upload_file_in_callback'] = "1";
		}
		
		//头信息
		if($this->settings['metadata'])
		{
			$metadata = $this->settings['metadata'];
			foreach ($metadata AS $k => $v)
			{
				if(!$v)
				{
					unset($metadata[$k]);
				}
				else 
				{
					$metadata[$k] = urlencode($v);
				}
			}
			$data['metadata'] = $metadata;
		}
		
		$trans = new transcode($tran_server);
		$ret = $trans->addTranscodeTask($data);
		$return = json_decode($ret,1);
		if($return['return'] == 'fail')
		{
			$sql = " UPDATE " . DB_PREFIX ."vodinfo SET status = -1 WHERE id = {$vid}";
			$this->db->query($sql);
			hg_do_transcode_fail($data,$vid);//将转码失败的信息记录下来
		}
		echo $ret;
	}
	
	//检测某个视频是否有拆条任务
	private function checkVideoIsSplit($id = '')
	{
		if(!$id)
		{
			return false;
		}
		$sql = "SELECT id FROM " .DB_PREFIX. "vodinfo WHERE original_id = '" .$id. "'";
		$arr = $this->db->query_first($sql);
		if($arr)
		{
			return true;
		}
		return false;
	}

	public function unknow()
	{
		$this->errorOutput(NOFUC);
	}
}

$out = new videomark();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'mark';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();
?>