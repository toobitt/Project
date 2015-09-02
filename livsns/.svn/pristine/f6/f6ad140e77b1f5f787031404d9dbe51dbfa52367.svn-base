<?php
/*
 * 对来自云平台视频进行下载转码
 * 
 * */
require_once('global.php');
require_once(ROOT_PATH . 'lib/class/material.class.php');
require_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
require_once(ROOT_PATH . 'lib/class/curl.class.php');
require_once(CUR_CONF_PATH . 'lib/transcode.class.php');
require_once(CUR_CONF_PATH . 'lib/mediainfo.class.php');
require_once(CUR_CONF_PATH . 'lib/SnapFromVideo.class.php');
require_once(CUR_CONF_PATH . 'lib/TranscodeRoute.class.php');
require_once(CUR_CONF_PATH . 'lib/mosaic_mode.php');
require_once(CUR_CONF_PATH . 'lib/water_config_mode.php');
define('MOD_UNIQUEID','livmedia');//模块标识
set_time_limit(0);

class yun extends adminBase
{
	private $dir_info = array();//临时存储目录信息
    public function __construct()
    {
       parent::__construct();
       $this->curl = new curl($this->settings['App_mediaserver']['host'], $this->settings['App_mediaserver']['dir'] . 'admin/');
    }
    
    public function download_and_transcode()
    {
    	//下载
	    $data = $this->input;
	    if(!$data)
	    {
		    return false;
	    }
	    $is_forcecode = intval($data['is_forcecode']);
  		$url = $data['hostwork'].'/'.$data['video_path'].$data['video_filename'];
		
  		//通过url下载视频
  		
  		$fileformat = substr($data['video_filename'], strrpos($data['video_filename'], '.'));
  		
  		
  		$v_videodir 	= create_video_dir();
		$vod_dir_names  = $v_videodir[0];
		$video_dir 		= $v_videodir[1];
		$filename = $vod_dir_names . $fileformat;
  		$video_path = $video_dir;
  		$video_base_path = TARGET_DIR;
  		$video_filename = $filename;
  		
  		$source_hostwork = defined("SOURCE_VIDEO_DOMIAN")?'http://'.ltrim(SOURCE_VIDEO_DOMIAN,'http://'):'';
		$source_base_path = UPLOAD_DIR;
		$source_path = 'yun/'.hg_build_dowload_dir();
		$source_filename = $data['video_filename'];
		
  		$filepath = $source_base_path.$source_path;
  		if(hg_mkdir($filepath))
  		{
  			$file = file_get_contents($url);
  			file_put_contents($filepath . $data['video_filename'], $file);
  		}
		if (!hg_mkdir(TARGET_DIR . $video_dir) || !is_writeable(TARGET_DIR . $video_dir))
		{
			$this->errorOutput(NOWRITE);
		}
		
		$sql = "UPDATE " .DB_PREFIX. "vodinfo SET source_hostwork='".$source_hostwork."',
													  video_base_path = '" .$video_base_path."',
													  source_base_path = '" .$source_base_path."',
													  source_path = '" .$source_path."',
													  video_path = '" .$video_path."',
													  video_filename = '" .$video_filename."',
													  source_filename = '" .$source_filename. "',
													  is_forcecode = '" .$is_forcecode. "',
													  hostwork = 'http://".TARGET_VIDEO_DOMAIN."'
													  WHERE id = '" .$data['id']. "'";
		$this->db->query($sql);
    	if(defined('IS_TRANSCODE') && IS_TRANSCODE)
	    {
			$sql = "UPDATE " .DB_PREFIX. "vodinfo SET status=0 WHERE id = '" .$data['id']. "'";
			$this->db->query($sql);
			$ret = $this->submit_transcode($tr_data,$video_dir,$vod_dir_names,$data['id'],unserialize($data['img_info']));
			if(!$ret)
			{
				$ret['error'] = 'transcode';
			}
	    }
		else
		{
			$target_file = TARGET_DIR . $video_path . $video_filename;
			$ret = copy($filepath . $data['video_filename'], $target_file);
			if(!$ret)
			{
				$ret['error'] = 'download';
			}
		}
		$this->publish_video($data['id'], 'insert');			
  		$this->addItem($ret);
	  	$this->output();  
	}
	private function publish_video($id,$op,$column_id = array())
	{
		$id = intval($id);
		if(empty($id))
		{
			return false;
		}
		if(empty($op))
		{
			return false;
		}
			
		$sql = "select * from " . DB_PREFIX ."vodinfo where id = " . $id;
		$info = $this->db->query_first($sql);
		if(empty($column_id))
		{
			$info['column_id'] = unserialize($info['column_id']);
			if(is_array($info['column_id']))
			{
				$column_id = array_keys($info['column_id']);
				$column_id = implode(',',$column_id);
			}
		}
		else
		{
			$column_id = implode(',',$column_id);
		}

		require_once(ROOT_PATH . 'lib/class/publishplan.class.php');
		$plan = new publishplan();
		$data = array(
			'set_id' 		=> PUBLISH_SET_ID,
			'from_id' 		=> $info['id'],
			'class_id' 		=> 0,
			'column_id' 	=>  $column_id,
			'title' 		=> $info['title'],
			'action_type'	=> $op,
			'publish_time'	=> $info['pub_time'],
			'publish_people'=> $this->user['user_name'],
			'ip'=> hg_getip(),
		);
		$ret = $plan->insert_queue($data);
		return $ret;
	} 
    public function submit_transcode($video,$target,$output_filename,$vid,$img_info)
    {
    	//构建提交转码的参数
	    $vod_config = transcode_config();
	    //$video = $this->input;
		$source_arr = array('source' =>  UPLOAD_DIR . $video['filepath'],'start' => '0','duration' => '','is_water_marked' => '0');
		$this->settings['App_mediaserver']['dir'] = $this->settings['App_mediaserver']['dir'] . 'admin/';
		$data = array(
			"sourceFile" 		=> array($source_arr),
			"id" 				=> $vid,
			"app_id" 			=> APPID,
			"app_key" 			=> APPKEY,
		    "type"				=>"transcode_upload",
		    "targetDir"			=> TARGET_DIR . $target,
		    "output_filename" 	=> $output_filename,
			"config" 			=> $vod_config,
			"callback" 			=> $this->settings['App_mediaserver'],
			"ts_need_preprocess"=> $video['ts_need_preprocess']?'1':'0',
			"mp4_from_sobey"	=> $video['mp4_from_sobey']?'1':'0',
			"force_recodec"		=> $video['force_recodec']?'1':'0',
			"absolute_path"		=> '1',//采用绝对路径
		);
		
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
		
    	//选取转码服务器
    	if($video['server_id'])//指定转码服务器
    	{
    		$tran_server = select_servers_by_id($video['server_id'],$vid);
    	}
    	else 
    	{
    		$tran_server = select_servers($vid);//自动选择转码服务器
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
			$data['sourceFile'][0]['url'] = 'http://' . ltrim(SOURCE_VIDEO_DOMIAN,'http://') . '/' . $source_arr['source'];
		}
		$ret = $trans->addTranscodeTask($data);
	    $return = json_decode($ret,1);
		if($return['return'] == 'fail')
		{
			$sql = " UPDATE " . DB_PREFIX ."vodinfo SET status = -1 WHERE id = {$vid}";
			$this->db->query($sql);
			hg_do_transcode_fail($data,$vid);//将转码失败的信息记录下来
			$error_info = array(
				'return' 	=> 'success',//上传成功
				'transcode' => 'fail',//转码失败
				'id'		=> $vid,
				'app'		=> APP_UNIQUEID,
				'module'	=> MOD_UNIQUEID,
				'img'		=> $img_info,
				'ErrorCode' => '0x0025',
				'ErrorText' => '提交转码失败',
			);
			echo json_encode($error_info);//上传成功,转码失败
		}
		else
		{
			//返回数据
			$video_info = array(
				'id' 		=> $vid,
				'protocol' 	=> 'http://',
				'host' 		=> defined("TARGET_VIDEO_DOMAIN")?ltrim(TARGET_VIDEO_DOMAIN,'http://'):$this->settings['videouploads']['host'],
				'dir'		=> $target, 
				'file_name' => $output_filename,
				'img'		=> $img_info,
				'type'		=> $vod_config['output_format'],
				'app'		=> APP_UNIQUEID,
				'module'	=> MOD_UNIQUEID,
				'return'	=> 'success',
		);

		return $video_info;
			
			
			/*****************************
			//请求用户提供的callback地址,并且携带用户提供的数据
			if($video['callback_url'])
			{
				if($video['callback_data'])
				{
					$callback_data = json_decode(base64_decode($video['callback_data']),1);
					//此处在视频库里面记录电视剧的id
					if($callback_data['tv_play_id'])
					{
						$sql = "UPDATE " . DB_PREFIX ."vodinfo SET tv_play_id = '" . $callback_data['tv_play_id'] . "' WHERE id = '" .$vid. "'";
						$this->db->query($sql);
					}
					$video_info['callback_data'] = $callback_data;
				}
				
				if($callbackReturnData = $this->mediaserverCallback($video['callback_url'],$video_info))
				{
					$callbackReturnData = json_decode($callbackReturnData,1);
					if($callbackReturnData['ErrorCode'])
					{
						$video_info['callback_return'] = $callbackReturnData;
					}
					else
					{
						$video_info['callback_return'] = $callbackReturnData[0];
					}
				}
			}
			
			//可以接受用户传递的callback，这个callback是在转码完成之后回调
			if($video['after_callback_url'])
			{
				//在data目录产生文件记录这个url
				$after_dir = DATA_DIR . 'after_callback_url/';
				if (!hg_mkdir($after_dir) || !is_writeable($after_dir))
				{
					$this->errorOutput(NOWRITE);
				}
				file_put_contents($after_dir . $vid . '.url',$video['after_callback_url']);
			}
			
			$this->addItem($video_info);
			$this->output();
			*****************************/
		}
    
    }
    
    public function sync_letv()
    {
    	if ($this->settings['video_file_cloud'] > 1 && !$this->settings['video_cloud'])
		{
			$this->errorOutput("缺少相应的配置");		
		}
		$id = intval($this->input['id']);
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'vodinfo WHERE id = '.$id;
		$vodinfo = $this->db->query_first($sql);
		
		if(!$vodinfo)
		{
			$this->errorOutput("视频不存在或已删除");
		}
		//file_put_contents(CACHE_DIR . 'debug.txt', var_export($vodinfo,1));
		$filepath = '';
		if($this->settings['video_file_cloud'] == 2)
		{
			$filepath = UPLOAD_DIR . $vodinfo['source_path'] . $vodinfo['source_filename'];
		}
    	if($this->settings['video_file_cloud'] == 3)
		{
			$filepath = TARGET_DIR . $vodinfo['video_path'] . $vodinfo['video_filename'];
		}
		
		//file_put_contents(CACHE_DIR . 'debug.txt', var_export($filepath,1));
		if(!is_file($filepath))
		{
			$this->errorOutput("视频不存在或已删除");
		}
    	include(CUR_CONF_PATH . 'lib/cloud/' . $this->settings['video_cloud'] . '.php');
		$cloud = new $this->settings['video_cloud']();
		$uparray = array(
			'name' =>$vodinfo['title'],
			'tmp_name' => $filepath,
			'size' => filesize($filepath),
		);
		$cloud->setFiles($uparray);
		$cloud->setInput($this->input);
		$cloud->setSettings($this->settings);
		$ret = $cloud->upload();
		$videoinfo = $cloud->getVideoInfo();
		$this->input['content_id'] = $videoinfo['content_id'];
		$this->input['extend_data'] = $videoinfo['extend_data'];
		$this->input['notranscode'] = $videoinfo['notranscode'];
		//$this->dir_info['original'] = $original;
		//file_put_contents(CACHE_DIR . 'debug.txt', var_export($videoinfo,1) . $ret);
		if ($ret != 'success')
		{
			$this->errorOutput($ret);
		}
		//记录扩展表数据
    	if($videoinfo['content_id'])
		{
			$extend_data = array(
				'vodinfo_id' => $id,
				'content_id' => $videoinfo['content_id'],
				'extend_data' => $videoinfo['extend_data'],
			);
			$sql = " INSERT INTO ".DB_PREFIX."vod_extend SET ";
			foreach ($extend_data AS $k => $v)
			{
				$sql .= " {$k} = '{$v}',";
			}
			$sql = trim($sql,',');
			$this->db->query($sql);
		}
		//修改数据状态为转码中
		$sql = 'UPDATE ' . DB_PREFIX .'vodinfo SET status = 0 WHERE id = ' . $id;
		$this->db->query($sql);
		$this->addItem('success');
		$this->output();
    }
}

$out = new yun();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'download_and_transcode';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();
?>