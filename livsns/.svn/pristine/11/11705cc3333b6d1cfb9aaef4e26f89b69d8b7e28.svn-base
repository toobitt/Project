<?php
require_once('global.php');
define('MOD_UNIQUEID','transcode_manger');//模块标识
require_once(CUR_CONF_PATH . 'lib/transcode.class.php');
require_once(CUR_CONF_PATH . 'lib/SnapFromVideo.class.php');
require_once(CUR_CONF_PATH . 'lib/TranscodeRoute.class.php');
require_once(ROOT_PATH . 'lib/class/material.class.php');
set_time_limit(0);

class retranscode extends adminBase
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function retranscode()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}		
		$id = intval($this->input['id']);
		$sql = " SELECT * FROM " . DB_PREFIX . "vodinfo WHERE id = {$id} ";
		$video = $this->db->query_first($sql);
		$output_tmp = explode('.',$video['video_filename']);
		
		//重新转码只针对源视频
		if($video['source_path'])
		{
			$source_path = rtrim($video['source_path'],'/') . '/' . $video['source_filename'];
		}
		else
		{
			$source_path = '';
		}
		
		if(!is_file($video['source_base_path'] . $source_path) || !$source_path)
		{
			$sql = " UPDATE " . DB_PREFIX ."vodinfo SET status = -1 WHERE id = {$id}";
			$this->db->query($sql);
			hg_do_transcode_fail($data,$id);//将转码失败的信息记录下来
			$this->errorOutput(NOFILE);
		}
		
		//判断当前该视频是不是正在转码，如果正在转码就删除该任务
		if($t_server = checkStatusFromAllServers($id))
		{
			$s_tran = new transcode($t_server);
			$s_tran->stop_transcode_task($id);
		}

		$vod_config = transcode_config();//查询出视频转码的配置信息
		if($this->input['audit_auto'])//如果是自动收录,回调增加一个参数
		{
			$this->settings['App_mediaserver']['extends'] = $this->input['audit_auto'];
		}
		//构建提交的转码配置	
		if(defined('SOBEY_SORTIDS') && SOBEY_SORTIDS)
		{
			if (in_array($video['vod_sort_id'], explode(',', SOBEY_SORTIDS)))
			{
				$this->input['mp4_from_sobey'] = 1;
			}
		}
		$this->settings['App_mediaserver']['dir'] = $this->settings['App_mediaserver']['dir'] . 'admin/';
		$data = array(
			"sourceFile" => array(
				array('source' => $video['source_base_path'] . $source_path,'start' => '0','duration' => '','is_water_marked' => '0'),
			),
			"id" 				=> "{$id}",
			"app_id" 			=> APPID,
			"app_key" 			=> APPKEY,
		    "type"				=>"transcode_upload",
		    "targetDir"			=> $video['video_base_path'] . $video['video_path'],
		    "output_filename" 	=> $output_tmp[0],
			"config" 			=> $vod_config,
			"force_recodec" 	=> $this->input['force_recodec']?'1':'',
			"mp4_from_sobey" 	=> $this->input['mp4_from_sobey']?'1':'',
			"callback" 			=> $this->settings['App_mediaserver'],
			"absolute_path"		=> '1',//采用绝对路径
		);
		
		//如果没传保持原来状态就将状态变成转码中
		if(!$this->input['retain_status'])
		{
			$sql = "UPDATE ".DB_PREFIX."vodinfo SET status = 0  WHERE id = {$id}";
			$this->db->query($sql);
		}
		
		//如果是制定强制转码的话就要调取该视频第一次提交转码的时候的马赛克和水印配置
		if(file_exists(UPLOAD_DIR . 'water/' . $id . '.json'))
		{
			$_param = file_get_contents(UPLOAD_DIR . 'water/' . $id . '.json');
			$_param = json_decode($_param,1);
			if ($data['force_recodec'])
			{
				if($_param['mosaic'])
				{
					$data['config']['mosaic'] = $_param['mosaic'];
				}
				
				if ($_param['water'])
				{
					$data['config']['water_mark'] 	= $_param['water']['water_mark'];
					$data['config']['water_mark_x'] = $_param['water']['water_mark_x'];
					$data['config']['water_mark_y'] = $_param['water']['water_mark_y'];
				}
			}
			
			if($_param['metadata'])
			{
				$data['metadata'] = $_param['metadata'];
			}
		}

		//选取转码服务器(此处强制转码与普通转码在选取服务器的时候有所不同)
		if($data['force_recodec'])
		{
			$tran_server = select_assign_servers(true);
		}
		else 
		{
			$tran_server = select_servers($id);
		}

		if(!$tran_server)
		{
			$this->errorOutput(NO_SELECT_TRANSERVER);
		}
		$trans = new transcode($tran_server);
		//根据选取到转码服务器是否需要携带文件，选择各自的提交方式
		if($tran_server['need_file'])
		{
			$data['upload_file_in_callback'] = "1";
			//$data['sourceFile'][0]['url'] = 'http://' . ltrim(SOURCE_VIDEO_DOMIAN,'http://') . '/' . $source_path;
			$data['sourceFile'][0]['url'] = 'http://' . ltrim(rtrim($video['source_hostwork'],'/'),'http://') . '/' . $source_path;
		}
		$ret = $trans->addTranscodeTask($data);
		$return = json_decode($ret,1);
		if($return['return'] == 'fail')
		{
			$sql = " UPDATE " . DB_PREFIX ."vodinfo SET status = -1 WHERE id = {$id}";
			$this->db->query($sql);
			hg_do_transcode_fail($data,$id);//将转码失败的信息记录下来
		}
		
		//此处是处理强制转码之后删除json文件
		if(file_exists(UPLOAD_DIR . 'water/' . $id . '.json') && $data['force_recodec'])
		{
			@unlink(UPLOAD_DIR . 'water/' . $id . '.json');
		}
		echo $ret;
	}
}

$out = new retranscode();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'retranscode';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();
?>